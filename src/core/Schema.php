<?php

declare(strict_types=1);

namespace core;

use BackedEnum;
use InvalidArgumentException;
use PDO;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use Throwable;

final class Schema
{
    /**
     * @throws ReflectionException
     * returns SchemaComparator[]
     */
    public static function getDifference(): array
    {
        $differences = [];
        $models = self::getModels();
        foreach ($models as $model) {
            $columns = $model::define();
            $table = $model::table();
            $difference = Schema::compare($model::pdo(), $table, $columns);
            if ($difference->hasChanges()) {
                $differences[] = $difference;
            }
        }

        return $differences;
    }

    /**
     * @throws Throwable
     */
    public static function buildDatabase(): bool
    {
        $models = self::getModels();
        $sqlToExecute = [];
        foreach ($models as $model) {
            $columns = $model::define();
            $table = $model::table();

            $schemaComparator = Schema::compare($model::pdo(), $table, $columns);
            if ($schemaComparator->hasChanges()) {
                $sqlToExecute += $schemaComparator->sql;
            }
        }

        if (count($sqlToExecute) > 0) {
            Database::transaction($sqlToExecute);
        }

        return true;
    }

    /**
     * @throws ReflectionException
     */
    private static function getModels(): array
    {
        $models = [];

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(__DIR__ . '/../model')
        );

        foreach ($it as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            require_once $file->getPathname();
        }

        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, Model::class)) {
                $ref = new ReflectionClass($class);
                if (!$ref->isAbstract()) {
                    $models[] = $class; // ← class string
                }
            }
        }

        return $models;
    }

    public static function compare(PDO $pdo, string $table, array $columns): SchemaComparator
    {
        $dbName = (string)$pdo->query("SELECT DATABASE()")->fetchColumn();

        $exists = self::tableExists($pdo, $dbName, $table);

        $wantCols = [];
        foreach ($columns as $c) {
            if (!$c instanceof DatabaseColumn) {
                throw new InvalidArgumentException("columns must be DatabaseColumn[]");
            }
            $wantCols[$c->name] = $c;
        }

        $schemaComparator = new SchemaComparator($table);

        if (!$exists) {
            $schemaComparator->missingColumns = array_keys($wantCols);
            $schemaComparator->sql[] = self::createTableSql($table, $columns);
            return $schemaComparator;
        }

        $haveCols = self::readColumns($pdo, $dbName, $table);
        $haveIdx = self::readIndexes($pdo, $dbName, $table);

        foreach ($wantCols as $name => $col) {
            if (!isset($haveCols[$name])) {
                $schemaComparator->missingColumns[] = $name;
                $schemaComparator->sql[] = "ALTER TABLE `$table` ADD COLUMN " . self::columnSql($col) . ";";
                continue;
            }

            $diffs = self::diffColumn($col, $haveCols[$name]);
            if ($diffs) {
                $schemaComparator->changedColumns[$name] = $diffs;
                $schemaComparator->sql[] = "ALTER TABLE `$table` MODIFY COLUMN " . self::columnSql($col) . ";";
            }
        }

        foreach ($haveCols as $name => $_) {
            if (!isset($wantCols[$name])) {
                $schemaComparator->extraColumns[] = $name;
                $schemaComparator->sql[] = "ALTER TABLE `$table` DROP COLUMN `$name`;";
            }
        }

        foreach ($columns as $col) {
            if ($col->isPrimaryKey) {
                $pkOk = ($haveIdx['PRIMARY'] ?? null) === [$col->name];
                if (!$pkOk) {
                    $schemaComparator->missingIndexes[] = "PRIMARY($col->name)";
                    $schemaComparator->sql[] = "ALTER TABLE `$table` DROP PRIMARY KEY, ADD PRIMARY KEY (`$col->name`);";
                }
            }

            if ($col->unique) {
                $hasUnique = self::hasUniqueOnSingleColumn($haveIdx, $col->name);
                if (!$hasUnique) {
                    $idxName = "uniq_{$table}_$col->name";
                    $schemaComparator->missingIndexes[] = "UNIQUE($col->name)";
                    $schemaComparator->sql[] = "ALTER TABLE `$table` ADD UNIQUE KEY `$idxName` (`$col->name`);";
                }
            }
        }

        return $schemaComparator;
    }

    public static function createTableSql(string $table, array $columns): string
    {
        $defs = [];
        $pks = [];
        $uniques = [];

        foreach ($columns as $col) {
            $defs[] = self::columnSql($col);

            if ($col->isPrimaryKey) {
                $pks[] = "PRIMARY KEY (`$col->name`)";
            }

            if ($col->unique) {
                $idxName = "uniq_{$table}_$col->name";
                $uniques[] = "UNIQUE KEY `$idxName` (`$col->name`)";
            }
        }

        $all = array_merge($defs, $pks, $uniques);

        return "CREATE TABLE IF NOT EXISTS `$table` (\n  " .
            implode(",\n  ", $all) .
            "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    }

    private static function tableExists(PDO $pdo, string $dbName, string $table): bool
    {
        $stmt = $pdo->prepare(
            "SELECT 1
             FROM information_schema.TABLES
             WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :t
             LIMIT 1"
        );
        $stmt->execute(['db' => $dbName, 't' => $table]);
        return (bool)$stmt->fetchColumn();
    }

    private static function readColumns(PDO $pdo, string $dbName, string $table): array
    {
        $stmt = $pdo->prepare(
            "SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA, COLUMN_KEY
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :t"
        );
        $stmt->execute(['db' => $dbName, 't' => $table]);

        $cols = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cols[$r['COLUMN_NAME']] = [
                'column_type' => strtolower($r['COLUMN_TYPE']),
                'nullable' => ($r['IS_NULLABLE'] === 'YES'),
                'default' => $r['COLUMN_DEFAULT'],
                'extra' => strtolower((string)$r['EXTRA']),
                'column_key' => (string)$r['COLUMN_KEY'],
            ];
        }
        return $cols;
    }

    private static function readIndexes(PDO $pdo, string $dbName, string $table): array
    {
        $stmt = $pdo->prepare(
            "SELECT INDEX_NAME, SEQ_IN_INDEX, COLUMN_NAME, NON_UNIQUE
             FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :t
             ORDER BY INDEX_NAME, SEQ_IN_INDEX"
        );
        $stmt->execute(['db' => $dbName, 't' => $table]);

        $idx = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = $r['INDEX_NAME'];
            if (!isset($idx[$name])) {
                $idx[$name] = [];
            }
            $idx[$name][] = $r['COLUMN_NAME'];
        }

        return $idx;
    }

    private static function hasUniqueOnSingleColumn(array $indexes, string $col): bool
    {
        foreach ($indexes as $name => $cols) {
            if ($name === 'PRIMARY') {
                continue;
            }
            if (count($cols) === 1 && $cols[0] === $col) {
                return true;
            }
        }
        return false;
    }

    private static function diffColumn(DatabaseColumn $want, array $have): array
    {
        $diffs = [];

        $wantType = strtolower(self::typeSql($want));
        $haveType = $have['column_type'];

        if ($wantType !== $haveType) {
            $diffs['type'] = ['want' => $wantType, 'have' => $haveType];
        }

        if ($want->nullable !== (bool)$have['nullable']) {
            $diffs['nullable'] = ['want' => $want->nullable, 'have' => $have['nullable']];
        }

        $wantAuto = $want->autoIncrement;
        $haveAuto = str_contains($have['extra'], 'auto_increment');
        if ($wantAuto !== $haveAuto) {
            $diffs['auto_increment'] = ['want' => $wantAuto, 'have' => $haveAuto];
        }

        $wantDefault = $want->default;
        $haveDefault = $have['default'];

        if (is_string($wantDefault) && strtoupper($wantDefault) === 'CURRENT_TIMESTAMP') {
            if ($haveDefault === null) {
                $diffs['default'] = ['want' => 'CURRENT_TIMESTAMP', 'have' => null];
            }
        } else {
            if ($wantDefault !== $haveDefault) {
                $diffs['default'] = ['want' => $wantDefault, 'have' => $haveDefault];
            }
        }

        return $diffs;
    }

    private static function columnSql(DatabaseColumn $c): string
    {
        $sql = "`$c->name` " . self::typeSql($c);

        if (!$c->nullable) {
            $sql .= " NOT NULL";
        } else {
            $sql .= " NULL";
        }

        if ($c->autoIncrement) {
            $sql .= " AUTO_INCREMENT";
        }

        if ($c->default !== null) {
            if (is_string($c->default) && strtoupper($c->default) === 'CURRENT_TIMESTAMP') {
                $sql .= " DEFAULT CURRENT_TIMESTAMP";
            } else {
                $sql .= " DEFAULT " . self::sqlLiteral($c->default);
            }
        }

        return $sql;
    }

    private static function typeSql(DatabaseColumn $c): string
    {
        $t = $c->type;

        if ($t instanceof BackedEnum) {
            return (string)$t->value;
        }

        if (is_object($t) && method_exists($t, 'sql')) {
            return (string)$t->sql();
        }

        return $t->value;
    }

    public static function sqlLiteral(mixed $v): string
    {
        if (is_int($v) || is_float($v)) {
            return (string)$v;
        }

        if (is_bool($v)) {
            return $v ? "1" : "0";
        }

        if ($v === null) {
            return "NULL";
        }

        $s = (string)$v;
        $s = str_replace("\\", "\\\\", $s);
        $s = str_replace("'", "''", $s);
        return "'" . $s . "'";
    }
}
