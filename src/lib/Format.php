<?php

namespace lib;

use DateTimeImmutable;
use DateTimeZone;
use Throwable;

class Format
{
    public static function date(
        ?string $value,
        DateTimeFormat $format = DateTimeFormat::DATE_SHORT,
        string|DateTimeZone|null $timezone = null,
        bool $convertTimezone = true
    ): string
    {
        $dt = self::parseDateTime($value);
        $dt = self::applyTimezone($dt, $timezone, $convertTimezone);
        return $dt ? $dt->format($format->value) : '';
    }

    public static function datetime(
        ?string $value,
        DateTimeFormat $format = DateTimeFormat::DATETIME_SHORT,
        string|DateTimeZone|null $timezone = null,
        bool $convertTimezone = true
    ): string
    {
        $dt = self::parseDateTime($value);
        $dt = self::applyTimezone($dt, $timezone, $convertTimezone);
        return $dt ? $dt->format($format->value) : '';
    }

    private static function parseDateTime(?string $value): ?DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }
        try {
            return new DateTimeImmutable($value);
        } catch (Throwable) {
            return null;
        }
    }

    private static function applyTimezone(
        ?DateTimeImmutable $dt,
        string|DateTimeZone|null $timezone,
        bool $convertTimezone
    ): ?DateTimeImmutable {
        if (!$dt || !$convertTimezone) {
            return $dt;
        }
        $tz = $timezone instanceof DateTimeZone
            ? $timezone
            : new DateTimeZone($timezone ?: date_default_timezone_get());

        return $dt->setTimezone($tz);
    }
}
