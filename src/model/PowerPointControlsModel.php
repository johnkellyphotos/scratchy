<?php

namespace model;

use core\Database\DatabaseColumn;
use core\Database\DatabaseColumnType;
use core\Database\Model;
use Scratchy\InputType;

final class PowerPointControlsModel extends Model
{
    // should be lowerCamelCase of column name
    public ?string $name = null;
    public bool $previous = false;
    public bool $next = false;
    public ?string $message = null;
    public bool $start = false;
    public bool $pause = false;
    public bool $restart = false;

    public static function define(): array
    {
        return [
            new DatabaseColumn(
                name: 'name',
                type: DatabaseColumnType::VARCHAR_64,
                input: InputType::text,
                unique: true,
                isLabel: true,
            ),
            new DatabaseColumn(
                name: 'previous',
                type: DatabaseColumnType::BOOL,
                input: InputType::text,
                default: 0,
            ),
            new DatabaseColumn(
                name: 'next',
                type: DatabaseColumnType::BOOL,
                input: InputType::text,
                default: 0,
            ),
            new DatabaseColumn(
                name: 'message',
                type: DatabaseColumnType::VARCHAR_512,
                input: InputType::text,
                nullable: true,
            ),
            new DatabaseColumn(
                name: 'start',
                type: DatabaseColumnType::BOOL,
                input: InputType::text,
                default: 0,
            ),
            new DatabaseColumn(
                name: 'pause',
                type: DatabaseColumnType::BOOL,
                input: InputType::text,
                default: 0,
            ),
            new DatabaseColumn(
                name: 'restart',
                type: DatabaseColumnType::BOOL,
                input: InputType::text,
                default: 0,
            ),
        ];
    }
}
