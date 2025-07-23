<?php

namespace Thaumware\Core\Domain\Uuid\Traits;

use Thaumware\Core\Domain\Facades\UuidFacade;
use Thaumware\Core\Helpers\Text\TextHelper;

trait HasUuid
{
    /**
     * @var string|null
     */
    protected $uuid;

    /**
     * Get the UUID
     *
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * Set the UUID
     *
     * @param string|null $uuid
     * @return self
     */
    public function setUuid(?string $uuid, bool $autogenerate = true): self
    {
        if ($autogenerate && empty($uuid)) {
            $this->generateUuid();
            return $this;
        }
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Generate a new UUID v4
     *
     * @return self
     */
    public function generateUuid(): self
    {
        $this->uuid = UuidFacade::generate();
        return $this;
    }

    /**
     * Check if UUID is set
     *
     * @return bool
     */
    public function hasUuid(): bool
    {
        return !empty($this->uuid);
    }
}
