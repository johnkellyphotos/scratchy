<?php

namespace model;

use core\Database\DatabaseColumn;
use core\Database\DatabaseColumnType;
use core\Database\Model;

final class UserModel extends Model
{
    // should be lowerCamelCase of column name
    public ?string $username = null;
    public ?string $password = null;
    public ?string $created = null;
    public ?string $lastSignIn = null;

    public static function define(): array
    {
        // return an array of type DatabaseColumn for each property you want in the database.
        // `id` and `label` is required for every table, and will be added automatically if not specified here.
        return [
            new DatabaseColumn(
                name: 'username',
                type: DatabaseColumnType::VARCHAR_64,
                unique: true,
                isLabel: true,
            ),
            new DatabaseColumn(
                name: 'password',
                type: DatabaseColumnType::VARCHAR_256
            ),
            new DatabaseColumn(
                name: 'created',
                type: DatabaseColumnType::DATETIME,
                default: 'CURRENT_TIMESTAMP'
            ),
            new DatabaseColumn(
                name: 'lastSignIn',
                type: DatabaseColumnType::DATETIME,
                nullable: true,
            ),
        ];
    }
}
