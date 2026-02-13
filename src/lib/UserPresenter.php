<?php

namespace lib;

use model\UserModel;
use lib\DateTimeFormat;

class UserPresenter
{
    public ?int $id = null;
    public ?string $first_name = null;
    public ?string $last_name = null;
    public ?string $username = null;
    public ?string $password = null;
    public ?string $birthday = null;
    public ?string $created = null;
    public ?string $lastSignIn = null;

    public static function from(UserModel $user): self
    {
        $p = new self();
        $p->id = $user->id;
        $p->first_name = $user->first_name;
        $p->last_name = $user->last_name;
        $p->username = $user->username;
        $p->password = $user->password;
        $p->birthday = Format::date($user->birthday, DateTimeFormat::DATE_SHORT);
        $p->created = Format::datetime($user->created, DateTimeFormat::DATETIME_SHORT_TZ);
        $p->lastSignIn = Format::datetime($user->lastSignIn, DateTimeFormat::DATETIME_SHORT_TZ);
        return $p;
    }

    /**
     * @param UserModel[] $users
     * @return self[]
     */
    public static function list(array $users): array
    {
        $out = [];
        foreach ($users as $user) {
            $out[] = self::from($user);
        }
        return $out;
    }
}
