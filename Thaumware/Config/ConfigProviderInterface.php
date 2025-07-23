<?php

namespace Thaumware\Core\Config;

interface ConfigProviderInterface
{
    public function get(string $key, $default = null);
    public function set(string $key, $value): void;
    public function mode(): string;
}