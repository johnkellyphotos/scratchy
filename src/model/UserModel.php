<?php

namespace model;

use core\DatabaseColumn;
use core\DatabaseColumnType;
use core\Model;

final class UserModel extends Model
{
    public ?int $id = null;
    public ?string $username = null;
    public ?string $password = null;
    public ?string $created = null;
    public ?string $lastSignIn = null;

    public static function define(): array
    {
        // return an array of type DatabaseColumn for each property you want in the database.
        return [
            new DatabaseColumn(
                name: 'id',
                type: DatabaseColumnType::INT,
                isPrimaryKey: true,
                autoIncrement: true
            ),
            new DatabaseColumn(
                name: 'username',
                type: DatabaseColumnType::VARCHAR_64,
                unique: true
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
                name: 'last_sign_in',
                type: DatabaseColumnType::DATETIME,
                nullable: true,
            ),
        ];
    }
}
