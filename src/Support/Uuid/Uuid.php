<?php

namespace Thaumware\Support\Uuid;

/**
 * Helper para generación y validación de UUIDs
 * 
 * Uso:
 *   use Thaumware\Core\Support\Uuid;
 *   $uuid = Uuid::v4();
 *   $isValid = Uuid::isValid($uuid);
 */
class Uuid
{
    /**
     * Genera un UUID v4 (aleatorio)
     */
    public static function v4(): string
    {
        $data = random_bytes(16);

        // Versión 4
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

        // Variante RFC 4122
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Alias de v4()
     */
    public static function generate(
        string $version = 'v4'
    ): string {
        return match ($version) {
            'v4' => self::v4(),
            'v5' => self::v5(Uuid::NAMESPACE_DNS, 'example.com'),
            'v3' => self::v3(Uuid::NAMESPACE_DNS, 'example.com'),
            default => throw new \InvalidArgumentException('Versión no soportada')
        };
    }

    /**
     * UUID v5 (basado en namespace y nombre, SHA-1)
     */
    public static function v5(string $namespace, string $name): string
    {
        if (!self::isValid($namespace)) {
            throw new \InvalidArgumentException('Namespace UUID inválido');
        }

        $nhex = str_replace(['-', '{', '}'], '', $namespace);
        $nstr = '';
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        $hash = sha1($nstr . $name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            substr($hash, 20, 12)
        );
    }

    /**
     * UUID v3 (basado en namespace y nombre, MD5)
     */
    public static function v3(string $namespace, string $name): string
    {
        if (!self::isValid($namespace)) {
            throw new \InvalidArgumentException('Namespace UUID inválido');
        }

        $nhex = str_replace(['-', '{', '}'], '', $namespace);
        $nstr = '';
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        $hash = md5($nstr . $name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            substr($hash, 20, 12)
        );
    }

    /**
     * Valida formato UUID
     */
    public static function isValid(string $uuid): bool
    {
        return (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid
        );
    }

    /**
     * Convierte a binario (16 bytes)
     */
    public static function toBinary(string $uuid): string
    {
        if (!self::isValid($uuid)) {
            throw new \InvalidArgumentException('UUID inválido');
        }

        return pack('H*', str_replace('-', '', $uuid));
    }

    /**
     * Convierte desde binario
     */
    public static function fromBinary(string $binary): string
    {
        if (strlen($binary) !== 16) {
            throw new \InvalidArgumentException('UUID binario debe ser 16 bytes');
        }

        $hex = unpack('H*', $binary)[1];
        return sprintf(
            '%08s-%04s-%04s-%04s-%12s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }

    /**
     * Sin guiones
     */
    public static function toCompact(string $uuid): string
    {
        if (!self::isValid($uuid)) {
            throw new \InvalidArgumentException('UUID inválido');
        }

        return str_replace('-', '', $uuid);
    }

    /**
     * Desde formato compacto
     */
    public static function fromCompact(string $compact): string
    {
        if (!preg_match('/^[0-9a-f]{32}$/i', $compact)) {
            throw new \InvalidArgumentException('UUID compacto inválido');
        }

        return sprintf(
            '%08s-%04s-%04s-%04s-%12s',
            substr($compact, 0, 8),
            substr($compact, 8, 4),
            substr($compact, 12, 4),
            substr($compact, 16, 4),
            substr($compact, 20, 12)
        );
    }

    /**
     * Obtiene versión (1-5)
     */
    public static function version(string $uuid): ?int
    {
        if (!self::isValid($uuid)) {
            return null;
        }

        $parts = explode('-', $uuid);
        return (int) hexdec(substr($parts[2], 0, 1));
    }

    // Namespaces predefinidos (RFC 4122)
    public const NAMESPACE_DNS = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_URL = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_OID = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_X500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';
}
