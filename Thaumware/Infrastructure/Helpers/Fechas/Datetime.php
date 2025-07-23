<?php

namespace Thaumware\Core\Helpers\Fechas;
class DateTime
{
    public static function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    public static function nowImmutable(): \DateTimeImmutable
    {
        return self::now();
    }

    public static function createFromFormat(string $format, string $time): \DateTimeImmutable
    {
        $dateTime = \DateTimeImmutable::createFromFormat($format, $time);
        if ($dateTime === false) {
            throw new \InvalidArgumentException("Invalid date format: $time");
        }
        return $dateTime;
    }
}