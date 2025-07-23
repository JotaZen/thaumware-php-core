<?php

namespace Thaumware\Core\Helpers\Text;

use Thaumware\Core\Domain\Facades\UuidFacade;

class TextHelper
{
    public static function toTitleCase($text)
    {
        return ucwords($text);
    }

    public static function toLowerCase($text)
    {
        return strtolower($text);
    }

    public static function toUpperCase($text)
    {
        return strtoupper($text);
    }

    public static function toCamelCase($text)
    {
        $text = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $text)));
        return lcfirst($text);
    }

    public static function toSnakeCase($text)
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($text)));
    }

    public static function toKebabCase($text)
    {
        return strtolower(preg_replace('/[A-Z]/', '-$0', lcfirst($text)));
    }

    public static function truncate($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }

    public static function uuid(): string
    {
        return UuidFacade::generate();
    }

    public static function uuidObject()
    {
        return UuidFacade::generateUuid();
    }

    public static function isValidUuid(string $uuid): bool
    {
        return UuidFacade::isValid($uuid);
    }

    public static function generateUuidV1(): string
    {
        return UuidFacade::generateV1();
    }

    public static function slugify(string $text): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
    }


}