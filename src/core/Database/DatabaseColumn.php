<?php

declare(strict_types=1);

namespace core\Database;

final class DatabaseColumn
{
    public function __construct(
        public string             $name,
        public DatabaseColumnType $type,
        public bool               $isPrimaryKey = false,
        public bool               $autoIncrement = false,
        public bool               $nullable = false,
        public bool               $unique = false,
        public mixed              $default = null,
        public ?string            $foreignTable = null,
        public ?string            $foreignColumn = null,
        public ?string            $onDelete = null,
        public ?string            $onUpdate = null
    )
    {
    }
}