<?php

namespace Thaumware\Core\Helpers\Validation;

use App\Repositories\base\requests\validation\InAdditionalValidator;

/**
 * Class to handle the state and data of validation flows.
 * Receives a data array, a boolean indicating validity, and an array of messages.
 */
class ValidationContext implements InValidation
{
    private bool $valid;
    private array $messages;

    private array $data;
    private array $files;
    private $existing = null;
    private bool $skipValidations = false;

    public function __construct(array $data = [], bool $valid = true, array $messages = [], array $files = [])
    {
        $this->data = $data;
        $this->valid = $valid;
        $this->messages = $messages;
    }

    public function setSkipValidations(): self
    {
        $this->skipValidations = true;
        return $this;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Builds a message from the error messages
     */
    public function getMessage($separator = ', '): string
    {
        return implode($separator, $this->messages);
    }

    /**
     * Gets validation data by key
     */
    public function getData(string|null $key = null): mixed
    {
        if ($key) {
            return $this->data[$key] ?? null;
        }
        return $this->data;
    }

    public function flushData(): void
    {
        $this->data = [];
    }

    public function filterDataByKeys(array $keys): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->data[$key] ?? null;
        }
        $this->setDataOverride($data);
        return $data;
    }

    /**
     * Overrides the validation data
     */
    public function setDataOverride($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Gets validation data by key with default
     */
    public function getKey(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
    public function get($key, $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Lists only the specified keys from validation data
     */
    public function getDataOnly(array $keys): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->data[$key] ?? null;
        }
        return $data;
    }

    /**
     * Sets a value in the validation data
     */
    public function setDataKey(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }
    public function set(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function appendToArrayKey($key, $value): self
    {
        if (!isset($this->data[$key])) {
            $this->data[$key] = [];
        }
        $this->data[$key][] = $value;
        return $this;
    }

    public function merge(self $validation): self
    {
        $this->data = array_merge($this->data, $validation->getData());
        return $this;
    }

    /**
     * Adds a message to the validation
     */
    public function addMessage($message): self
    {
        $this->messages[] = $message;
        return $this;
    }

    /**
     * Adds data to the validation
     */
    public function addData($key, $value): self
    {
        if (!$this->skipValidations) {
            $this->data[$key] = $value;
        }
        return $this;
    }

    public function addDataArray(array $data): void
    {
        if ($this->skipValidations) {
            return;
        }
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Invalidates the validation and adds a message
     */
    public function invalidate(
        string $message = 'Error',
        bool $critical = false
    ): self {
        $this->valid = false;
        $this->addMessage($message);

        if ($critical) {
            $this->setSkipValidations();
        }
        return $this;
    }

    /**
     * For editing, allows setting an existing object
     */
    public function setExisting(mixed $existing): self
    {
        $this->existing = $existing;
        return $this;
    }

    /**
     * Gets the existing object
     */
    public function getExisting(): object|null
    {
        return $this->existing;
    }

    public function notExistsKey($key, $message = 'Required field'): bool
    {
        if (!isset($this->data[$key])) {
            $this->invalidate($message);
            return true;
        }
        return false;
    }

    public function require($key, $message = 'Required field', $isCritical = false): void
    {
        if ($this->skipValidations) {
            return;
        }
        if (!isset($this->data[$key]) || empty($this->data[$key])) {
            $this->invalidate($message);

            if ($isCritical) {
                $this->setSkipValidations();
            }
        }
    }

    public function objectToEdit($objectToEdit, $errorMessage = 'Not Found', $isCritical = true): bool
    {
        if ($this->skipValidations) {
            return false;
        }
        if (!$objectToEdit) {
            $this->invalidate($errorMessage);

            if ($isCritical) {
                $this->setSkipValidations();
            }
            return false;
        } else {
            $this->setExisting($objectToEdit);
            return true;
        }
    }

    public function invalidateIf($condition, $message = 'Error', $isCritical = true): void
    {
        if ($this->skipValidations) {
            return;
        }
        if ($condition) {
            $this->invalidate($message);

            if ($isCritical) {
                $this->setSkipValidations();
            }
        }
    }

    public function isSet($key): bool
    {
        return isset($this->data[$key]);
    }

    public function formatData(string $key, callable $format, $nullify = true, $formatNull = false): void
    {
        if ($this->skipValidations) {
            return;
        }
        if (isset($this->data[$key])) {
            $this->data[$key] = ($this->data[$key] === null && !$formatNull) ? null : $format($this->data[$key]);
        } elseif ($nullify) {
            $this->data[$key] = null;
        }
    }

    public function formatMultipleData(array $toFormatArray): void
    {
        foreach ($toFormatArray as $toFormat) {
            $this->formatData(
                $toFormat['key'],
                $toFormat['format'],
                $toFormat['nullify'] ?? null,
                $toFormat['formatNull'] ?? null
            );
        }
    }


    public function getFiles($key = null): mixed
    {
        if ($key) {
            return $this->files[$key] ?? null;
        }
        return $this->files;
    }

    public function addFile($key, $file)
    {
        $this->files[$key] = $file;
    }

    public function setIsFile($key)
    {
        $this->addFile($key, $this->get($key)[0]);
        $this->setDataKey($key, null);
    }
}