<?php

declare(strict_types=1);

namespace core\Database;

use PDO;
use PDOStatement;
use Throwable;

abstract class Database
{
    protected static ?PDO $pdo = null;

    protected static function pdo(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3307';
        $name = getenv('DB_NAME') ?: 'app_db';
        $user = getenv('DB_USER') ?: 'app_user';
        $pass = getenv('DB_PASS') ?: 'app_pass';

        $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";

        self::$pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return self::$pdo;
    }

    protected static function statement(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected static function one(string $sql, array $params = []): ?array
    {
        $stmt = self::statement($sql, $params);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return $row;
    }

    protected static function all(string $sql, array $params = []): array
    {
        return self::statement($sql, $params)->fetchAll();
    }

    protected static function exec(string $sql, array $params = []): int
    {
        return self::statement($sql, $params)->rowCount();
    }

    protected static function delete(string $sql, array $params = []): int
    {
        return self::exec($sql, $params);
    }

    protected static function insert(string $sql, array $params = []): string
    {
        self::statement($sql, $params);
        return self::pdo()->lastInsertId();
    }

    protected static function update(string $sql, array $params = []): bool
    {
        self::statement($sql, $params);
        return true;
    }

    /**
     * @throws Throwable
     */
    public static function transaction(array $listOfQueries): void
    {
        $pdo = self::pdo();
        $pdo->beginTransaction();

        try {
            foreach ($listOfQueries as $query) {
                $pdo->exec($query);
            }

            if ($pdo->inTransaction()) {
                $pdo->commit();
            }
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
