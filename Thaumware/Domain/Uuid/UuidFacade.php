<?php

namespace Thaumware\Domain\Uuid;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidInterface;

/**
 * UUID Facade
 * 
 * Provides a simple interface for UUID operations
 */
class UuidFacade
{
    /**
     * Generate a new UUID v4
     *
     * @return string
     */
    public static function generate(): string
    {
        if (class_exists('Ramsey\Uuid\Uuid')) {
            return RamseyUuid::uuid4()->toString();
        }
        // Implementación propia de UUID v4
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Generate a new UUID v4 and return as UuidInterface
     *
     * @return UuidInterface
     */
    public static function generateUuid()
    {
        if (class_exists('Ramsey\Uuid\Uuid')) {
            return RamseyUuid::uuid4();
        }
        // Si no está ramsey/uuid, retorna el string UUID v4
        return self::generate();
    }

    /**
     * Validate if a string is a valid UUID
     *
     * @param string $uuid
     * @return bool
     */
    public static function isValid(string $uuid): bool
    {
        if (class_exists('Ramsey\Uuid\Uuid')) {
            return RamseyUuid::isValid($uuid);
        }
        // Validación simple de formato UUID v4
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid);
    }

    /**
     * Create UUID from string
     *
     * @param string $uuid
     * @return UuidInterface
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $uuid)
    {
        if (class_exists('Ramsey\Uuid\Uuid')) {
            return RamseyUuid::fromString($uuid);
        }
        // Si no está ramsey/uuid, retorna el string si es válido
        if (self::isValid($uuid)) {
            return $uuid;
        }
        throw new \InvalidArgumentException('Invalid UUID string');
    }

    /**
     * Generate a UUID v1 (time-based)
     *
     * @return string
     */
    public static function generateV1(): string
    {
        if (class_exists('Ramsey\Uuid\Uuid')) {
            return RamseyUuid::uuid1()->toString();
        }
        throw new \RuntimeException('UUID v1 requires ramsey/uuid');
    }

    /**
     * Generate a UUID v3 (name-based using MD5)
     *
     * @param UuidInterface|string $namespace
     * @param string $name
     * @return string
     */
    public static function generateV3($namespace, string $name): string
    {
        if (class_exists('Ramsey\Uuid\Uuid')) {
            return RamseyUuid::uuid3($namespace, $name)->toString();
        }
        throw new \RuntimeException('UUID v3 requires ramsey/uuid');
    }

    /**
     * Generate a UUID v5 (name-based using SHA-1)
     *
     * @param UuidInterface|string $namespace
     * @param string $name
     * @return string
     */
    public static function generateV5($namespace, string $name): string
    {
        if (class_exists('Ramsey\Uuid\Uuid')) {
            return RamseyUuid::uuid5($namespace, $name)->toString();
        }
        throw new \RuntimeException('UUID v5 requires ramsey/uuid');
    }

    /**
     * Generate a nil UUID (all zeros)
     *
     * @return string
     */
    public static function nil(): string
    {
        if (class_exists('Ramsey\Uuid\Uuid')) {
            return RamseyUuid::NIL;
        }
        // Nil UUID: 00000000-0000-0000-0000-000000000000
        return '00000000-0000-0000-0000-000000000000';
    }
}
