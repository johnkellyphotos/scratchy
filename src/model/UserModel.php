<?php

namespace model;

use core\Database\DatabaseColumn;
use core\Database\DatabaseColumnType;
use core\Database\Model;
use Scratchy\InputType;

class UserModel extends Model
{
    public ?string $first_name = null;
    public ?string $last_name = null;
    public ?string $email = null;
    public ?string $password = null;

    public static function define(): array
    {
        return [
            new DatabaseColumn('first_name', DatabaseColumnType::VARCHAR_64, InputType::text),
            new DatabaseColumn('last_name', DatabaseColumnType::VARCHAR_64, InputType::text),
            new DatabaseColumn('email', DatabaseColumnType::VARCHAR_256, InputType::text, unique: true, isLabel: true),
            new DatabaseColumn('password', DatabaseColumnType::VARCHAR_256, InputType::password),
        ];
    }
}
