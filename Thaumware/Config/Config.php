<?php

namespace Thaumware\Core\Config;

use Illuminate\Support\Facades\DB;

class Config implements ConfigProviderInterface
{
    protected static ?self $instance = null;
    protected string $mode;
    protected string $table = 'settings';
    protected array $cache = [];

    private function __construct(?string $mode = null)
    {
        $this->mode = $mode ?? config('app.config_mode', 'env');
    }

    public static function getInstance(?string $mode = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($mode);
        }
        return self::$instance;
    }

    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        if ($this->mode === 'db') {
            $value = DB::table($this->table)->where('key', $key)->value('value');
            $this->cache[$key] = $value ?? $default;
            return $this->cache[$key];
        }

        $value = config($key, $default);
        $this->cache[$key] = $value;
        return $value;
    }

    public function set(string $key, $value): void
    {
        if ($this->mode === 'db') {
            DB::table($this->table)->updateOrInsert(['key' => $key], ['value' => $value]);
            $this->cache[$key] = $value;
        } else {
            throw new \Exception('No se puede modificar la configuración de archivos en modo env');
        }
    }

    public function mode(): string
    {
        return $this->mode;
    }

    public function clearCache(): void
    {
        $this->cache = [];
    }

    public function getTable(): string
    {
        return $this->table;
    }
}