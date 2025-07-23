<?php

namespace Thaumware\Core\Uuid;

use Thaumware\Domain\Uuid\UuidFacade;

class Uuid
{
    /**
     * Genera un UUID v4
     */
    public static function generate(): string
    {
        return UuidFacade::generate();
    }

    /**
     * Valida un UUID
     */
    public static function isValid(string $uuid): bool
    {
        return UuidFacade::isValid($uuid);
    }

    /**
     * Genera un UUID v1
     */
    public static function generateV1(): string
    {
        return UuidFacade::generateV1();
    }

    /**
     * Genera un UUID v3
     */
    public static function generateV3($namespace, string $name): string
    {
        return UuidFacade::generateV3($namespace, $name);
    }

    /**
     * Genera un UUID v5
     */
    public static function generateV5($namespace, string $name): string
    {
        return UuidFacade::generateV5($namespace, $name);
    }

    /**
     * Genera un UUID nil
     */
    public static function nil(): string
    {
        return UuidFacade::nil();
    }
}
