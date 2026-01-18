<?php

declare(strict_types=1);

namespace core\Database;

class DatabaseColumnId extends DatabaseColumn
{
    public function __construct()
    {
        parent::__construct(name: 'id', type: DatabaseColumnType::INT, isPrimaryKey: true, autoIncrement: true);
    }
}