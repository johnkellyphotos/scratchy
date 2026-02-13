<?php

namespace core\Database;

use Exception;
use PDO;
use Scratchy\InputType;

abstract class Model extends Database
{
    private static bool $initialized = false;
    protected static string $table;
    protected static string $primaryKey = 'id';
    private static array $columnLabel = [];
    private static array $columnDataType = [];

    public readonly ?int $id;
    public ?string $label = null;

    private static array $dataBaseColumns = [];

    public function __construct(?int $id = null)
    {

    }

    private static function initiate(): void
    {
        if (self::$initialized) {
            return;
        }
        self::$table = self::getTableName();
        parent::pdo();
    }

    public static function pdo(): PDO
    {
        self::initiate();
        return self::$pdo;
    }

    public static function table(): string
    {
        self::initiate();
        return self::$table;
    }

    public static function define(): array
    {
        return [];
    }

    public static function getTableName(): string
    {
        $class = static::class;

        $class = strrchr($class, '\\') !== false
            ? substr(strrchr($class, '\\'), 1)
            : $class;

        $class = preg_replace('/Model$/', '', $class);

        return strtolower(
            preg_replace('/(?<!^)[A-Z]/', '_$0', $class)
        );
    }

    public static function getInputTypeForColumn(string $name): InputType
    {
        $model = static::class;
        if (isset(self::$dataBaseColumns[$model][$name])) {
            return self::$dataBaseColumns[$model][$name];
        }

        $databaseColumns = $model::define();
        foreach ($databaseColumns as $column) {
            if ($column->name === $name) {
                self::$dataBaseColumns[$model][$name] = $column->input;
            }
        }
        return self::$dataBaseColumns[$model][$name] ?? InputType::none;
    }

    public static function findAll(?string $where = null, array $parameters = []): array
    {
        self::initiate();
        $table = static::$table;
        if ($where) {
            $results = self::all("SELECT * FROM $table WHERE $where;", $parameters);
        } else {
            $results = self::all("SELECT * FROM $table;");
        }

        $modelList = [];
        foreach ($results as $result) {
            $modelName = static::class;
            $model = new $modelName();
            $columns = ['id' => null, ...get_object_vars($model)];
            foreach ($columns as $column => $value) {
                $model->{$column} = $result[$column];
            }
            $modelList[] = $model;
        }
        return $modelList;
    }

    public function label(): string
    {
        return c($this->label);
    }

    public static function fromId(int $id): ?static
    {
        self::initiate();
        $table = static::$table;

        $result = self::one("SELECT * FROM $table WHERE id=?;", [$id]);

        if ($result) {
            $modelName = static::class;
            $model = new $modelName();
            $columns = ['id' => null, ...get_object_vars($model)];
            foreach ($columns as $column => $value) {
                $model->{$column} = $result[$column];
            }
            return $model;
        }

        return null;
    }

    public static function findOne(?string $where = null, array $parameters = []): ?static
    {
        self::initiate();
        $table = static::$table;
        if ($where) {
            $result = self::one("SELECT * FROM $table WHERE $where;", $parameters);
        } else {
            $result = self::one("SELECT * FROM $table;");
        }

        if ($result) {
            $modelName = static::class;
            $model = new $modelName();
            $columns = ['id' => null, ...get_object_vars($model)];
            foreach ($columns as $column => $value) {
                $model->{$column} = $result[$column];
            }
            return $model;
        }

        return null;
    }

    public static function castToDataType(string $columnName, mixed $value): string|int|float
    {
        if ($columnName === 'label') {
            return (string)$value;
        }
        $model = static::class;
        if (!isset(self::$columnDataType[$model][$columnName])) {
            $columns = $model::define();
            foreach ($columns as $column) {
                if ($column->name === $columnName) {
                    self::$columnDataType[$model] = [];
                    self::$columnDataType[$model][$columnName] = $column->type;
                    break;
                }
            }
        }

        return match (self::$columnDataType[$model][$columnName]) {
            DatabaseColumnType::BOOL,
            DatabaseColumnType::BIGINT,
            DatabaseColumnType::INT,
            => (int)$value,
            DatabaseColumnType::FLOAT,
            DatabaseColumnType::DOUBLE,
            => (float)$value,
            default => (string)$value,
        };
    }

    public static function getFieldUsedAsLabel(): string
    {
        $model = static::class;
        if (isset(self::$columnLabel[$model])) {
            return self::$columnLabel[$model];
        }

        $columns = $model::define();
        foreach ($columns as $column) {
            if ($column->isLabel()) {
                self::$columnLabel[$model] = $column->name;
            }
        }

        self::$columnLabel[$model] ??= 'id';

        return self::$columnLabel[$model];
    }

    /**
     * @throws Exception
     */
    public function remove(): bool
    {
        self::initiate();

        if (empty($this->id)) {
            throw new Exception('Record does not have ID field set so may not be deleted.');
        }

        $table = static::$table;

        return self::delete("DELETE FROM $table WHERE id=? LIMIT 1;", [$this->id]) > 0;
    }


    public function save(): bool
    {
        self::initiate();

        $idToUpdate = $this->id;

        // ensure label exists in object, but do not update label column in save()
        $fieldToUseAsLabel = self::getFieldUsedAsLabel();
        $this->label = $this->{$fieldToUseAsLabel} ?? null;

        $sets = [];
        $data = [':id' => $idToUpdate];

        foreach (get_object_vars($this) as $column => $value) {
            if ($value === null) {
                continue;
            }

            if ($column === 'id') {
                continue;
            }

            $sets[] = "$column = :$column";
            $data[":$column"] = self::castToDataType($column, $value);
        }

        if (count($sets) === 0) {
            return true;
        }

        $table = static::$table;
        $setSql = implode(', ', $sets);

        return self::update("UPDATE $table SET $setSql WHERE id = :id LIMIT 1", $data);
    }

    public function create(): string
    {
        self::initiate();

        $columns = [];
        $placeHolders = [];
        $data = [];

        if (!isset($this->label)) {
            $fieldToUseAsLabel = self::getFieldUsedAsLabel();
            $this->label = $this->{$fieldToUseAsLabel} ?? null;
        }

        $needToUpdateId = false;
        $columnList = ['id' => null, ...get_object_vars($this)];
        foreach ($columnList as $column => $value) {
            if ($value === null) {
                if ($column === 'id' && $this->label === null) {
                    $needToUpdateId = true;
                }
                continue;
            }

            $columns[] = $column;
            $placeHolders[] = ':' . $column;
            $data[':' . $column] = self::castToDataType($column, $value);
        }

        $columnSql = implode(', ', $columns);
        $placeHolderSql = implode(', ', $placeHolders);

        $table = static::$table;

        $sql = "INSERT INTO $table ($columnSql) VALUES ($placeHolderSql)";
        $id = self::insert($sql, $data);
        $this->id = $id;

        if ($needToUpdateId) {
            $this->label = $id;
            self::update("Update $table SET label = :label WHERE id = :id LIMIT 1", ['label' => $this->label, 'id' => $id]);
        }

        return $id;
    }
}
