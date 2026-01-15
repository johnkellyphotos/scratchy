<?php

namespace core;

use PDO;
abstract class Model extends Database
{
    private static bool $initialized = false;
    protected static string $table;
    protected static string $primaryKey = 'id';

    protected ?int $id = null;

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
            foreach (get_object_vars($model) as $column => $value) {
                $model->{$column} = $result[$column];
            }
            $modelList[] = $model;
        }
        return $modelList;
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
            foreach (get_object_vars($model) as $column => $value) {
                $model->{$column} = $result[$column];
            }
            return $model;
        }

        return null;
    }

    public function create(): string
    {
        self::initiate();

        $columns = [];
        $placeHolders = [];
        $data = [];

        foreach (get_object_vars($this) as $column => $value) {
            if ($value === null) {
                continue;
            }

            $columns[] = $column;
            $placeHolders[] = ':' . $column;
            $data[':' . $column] = $value;
        }

        $columnSql = implode(', ', $columns);
        $placeHolderSql = implode(', ', $placeHolders);

        $table = static::$table;

        $sql = "INSERT INTO $table ($columnSql) VALUES ($placeHolderSql)";
        $id = self::insert($sql, $data);
        $this->id = $id;
        return $id;
    }
}
