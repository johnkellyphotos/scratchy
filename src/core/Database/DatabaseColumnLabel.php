<?php

declare(strict_types=1);

namespace core\Database;

class DatabaseColumnLabel extends DatabaseColumn
{
    public function __construct()
    {
        parent::__construct(name: 'label', type: DatabaseColumnType::VARCHAR_64, nullable: true);
    }
}