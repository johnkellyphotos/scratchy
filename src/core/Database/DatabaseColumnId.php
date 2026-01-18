<?php

declare(strict_types=1);

namespace core\Database;

class DatabaseColumnId extends DatabaseColumn
{
    public function __construct()
    {
        parent::__construct('id', DatabaseColumnType::INT, true, true);
    }
}