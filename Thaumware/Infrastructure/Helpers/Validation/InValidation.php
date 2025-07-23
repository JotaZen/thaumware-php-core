<?php

namespace Thaumware\Core\Helpers\Validation;

interface InValidation
{
    public function isValid(): bool;

    public function getMessage(string $separator = ', '): string;

    public function getData(string|null $key = null): mixed;

    public function getDataOnly(array $keys): array;
    public function get(string $key, mixed $default = null): mixed;
    public function getKey(string $key, mixed $default = null): mixed;

    public function setSkipValidations(): self;

    public function set(string $key, mixed $value): self;
    public function setDataKey(string $key, mixed $value): self;

    public function appendToArrayKey(string $key, mixed $value): self;

    public function getFiles(?string $key = null): mixed;

    public function getExisting(): mixed;
    public function setExisting(mixed $existing): self;

    public function addMessage(string $message): self;

    public function invalidate(string $message, bool $skipValidations = false): self;

    public function notExistsKey(string $key, string $message = 'Required field'): bool;
}