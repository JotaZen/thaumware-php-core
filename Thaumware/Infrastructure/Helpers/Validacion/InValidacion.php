<?php

namespace Thaumware\Core\Helpers\Validacion;

interface InValidacion
{
    public function esValido(): bool;

    public function getMensaje($separador): string;

    public function getData(string|null $key): mixed;

    public function getDataOnly(array $keys): array;
    public function get(string $key, mixed $default = null): mixed;
    public function getKey(string $key, mixed $default): mixed;

    public function setSaltarValidaciones(): self;

    public function set(string $key, mixed $value): self;
    public function setDataKey(string $key, mixed $value): self;

    public function appendToArrayKey(string $key, mixed $value): self;

    public function getFiles(): mixed;

    public function getExistente(): mixed;
    public function setExistente(mixed $existente): self;

    public function agregarMensaje(string $mensaje): self;

    public function invalidar(string $mensaje, bool $saltarValidaciones): self;

    public function noExisteKey(string $key, string $mensaje): bool;
}