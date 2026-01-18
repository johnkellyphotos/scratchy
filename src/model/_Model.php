<?php

namespace model;

use core\Database\Model;

class _Model
{
    public static function get(string $modelName): Model
    {
        $nameSpacedClass = __NAMESPACE__ . '\\' . $modelName;
        return new $nameSpacedClass();
    }

    public static function displayNameFromModelName(string $modelName): string
    {
        return [
            UserModel::class => 'user',
        ][$modelName] ?? '';
    }
}