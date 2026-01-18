<?php

namespace model;

use core\Database\DatabaseColumn;
use core\Database\DatabaseColumnType;
use core\Database\Model;
use Scratchy\InputType;

final class UserActivityModel extends Model
{
    // should be lowerCamelCase of column name
    public ?int $userId = null;
    public ?string $title = null;
    public ?string $notes = null;
    public ?string $timestamp = null;

    public static function define(): array
    {
        // return an array of type DatabaseColumn for each property you want in the database.
        // `id` and `label` is required for every table, and will be added automatically if not specified here.
        return [
            new DatabaseColumn(
                name: 'userId',
                type: DatabaseColumnType::INT,
                input: InputType::text,
                foreignTable: 'user',
                foreignColumn: 'id',
            ),
            new DatabaseColumn(
                name: 'title',
                type: DatabaseColumnType::VARCHAR_64,
                input: InputType::text,
                isLabel: true,
            ),
            new DatabaseColumn(
                name: 'notes',
                type: DatabaseColumnType::TEXT,
                input: InputType::textarea,
                nullable: true,
            ),
            new DatabaseColumn(
                name: 'timestamp',
                type: DatabaseColumnType::DATETIME,
                input: InputType::none,
                default: 'CURRENT_TIMESTAMP'
            ),
        ];
    }
}
