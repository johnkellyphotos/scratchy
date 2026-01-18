<?php

namespace model;

use core\Database\Model;
use Exception;

class _Model
{
    public static function get(string $modelName): Model
    {
        $nameSpacedClass = __NAMESPACE__ . '\\' . $modelName;
        return new $nameSpacedClass();
    }

    /**
     * @throws Exception
     */
    public static function displayNameFromModelName(string $modelName): string
    {
        return [
            UserModel::class => 'user',
        ][$modelName] ?? throw new Exception("Model $modelName does not have a display name set.");
    }
}