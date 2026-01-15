<?php

namespace core;

enum DatabaseColumnType: string
{
    case INT = 'INT';
    case BIGINT = 'BIGINT';
    case TEXT = 'TEXT';
    case BOOL = 'TINYINT(1)';
    case DATETIME = 'DATETIME';
    case DATE = 'DATE';
    case FLOAT = 'FLOAT';
    case DOUBLE = 'DOUBLE';
    case JSON = 'JSON';

    case VARCHAR_64 = 'VARCHAR(64)';
    case VARCHAR_256 = 'VARCHAR(256)';
    case VARCHAR_512 = 'VARCHAR(512)';

    case DECIMAL_10_2 = 'DECIMAL(10,2)';
    case DECIMAL_12_4 = 'DECIMAL(12,4)';
}