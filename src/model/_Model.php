<?php

namespace model;

use core\Database\Model;

class _Model
{
    public static function get(string $modelName): Model
    {
        $nameSpacedClass = str_contains($modelName, '\\')
            ? $modelName
            : __NAMESPACE__ . '\\' . $modelName;
        return new $nameSpacedClass();
    }

    public static function displayNameFromModelName(string $modelName): string
    {
        $nameSpacedClass = str_contains($modelName, '\\')
            ? $modelName
            : __NAMESPACE__ . '\\' . $modelName;

        return [
            UserModel::class => 'user',
        ][$nameSpacedClass] ?? '';
    }
}
