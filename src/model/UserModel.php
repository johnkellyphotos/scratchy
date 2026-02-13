<?php

namespace model;

use core\Database\DatabaseColumn;
use core\Database\DatabaseColumnType;
use core\Database\Model;
use Scratchy\InputType;

class UserModel extends Model
{
    public ?string $username = null;
    public ?string $password = null;
    public ?string $birthday = null;
    public ?string $created = null;
    public ?string $lastSignIn = null;

    public static function define(): array
    {
        return [
            new DatabaseColumn('username', DatabaseColumnType::VARCHAR_64, InputType::text, unique: true, isLabel: true),
            new DatabaseColumn('password', DatabaseColumnType::VARCHAR_256, InputType::password),
            new DatabaseColumn('birthday', DatabaseColumnType::DATE, InputType::date, nullable: true),
            new DatabaseColumn('created', DatabaseColumnType::DATETIME, InputType::none),
            new DatabaseColumn('lastSignIn', DatabaseColumnType::DATETIME, InputType::none, nullable: true),
        ];
    }
}
