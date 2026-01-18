<?php

declare(strict_types=1);

namespace core\Database;

use Scratchy\InputType;

class DatabaseColumn
{
    public function __construct(
        public string             $name,
        public DatabaseColumnType $type,
        public InputType          $input = InputType::none,
        public bool               $isPrimaryKey = false,
        public bool               $autoIncrement = false,
        public bool               $nullable = false,
        public bool               $unique = false,
        public mixed              $default = null,
        public ?string            $foreignTable = null,
        public ?string            $foreignColumn = null,
        public ?string            $onDelete = null,
        public ?string            $onUpdate = null,
        public bool               $isLabel = false,
    )
    {
    }

    public function isLabel(): bool
    {
        return $this->isLabel;
    }

    public function columnName(): string
    {
        $s = preg_replace('/(?<!^)[A-Z]/', '_$0', $this->name);
        return strtolower($s ?? $this->name);
    }
}