<?php

namespace Thaumware\Core\Helpers\Validacion;

use App\Repositories\base\solicitudes\validacion\InAditionalValidator;

/**
 * Clase para manejar los resultados de las validaciones
 * Recibe un array de datos, un booleano que indica si es valido y un array de mensajes
 */
class Validacion implements InValidacion
{
    private bool $valido;
    private array $mensajes;

    private array $data;
    private array $files;
    private $existente = null;
    private bool $saltarValidaciones = false;

    private array $additionalValidators = [];

    public function __construct(array $data = [], bool $valido = true, array $mensajes = [], array $files = [])
    {
        $this->data = $data;
        $this->valido = $valido;
        $this->mensajes = $mensajes;
    }
    public function setSaltarValidaciones(): self
    {
        $this->saltarValidaciones = true;
        return $this;
    }

    public function esValido(): bool
    {
        return $this->valido;
    }

    /**
     * Construye un mensaje con los mensajes de error
     */
    public function getMensaje($separador = ', '): string
    {
        return implode($separador, $this->mensajes);
    }

    /**
     * Obtiene datos de la validación por clave
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
     * Sobreescribe los datos de la validación
     */
    public function setDataOverride($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     *  Obtiene los datos de la validación según las claves
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
     * Lista los datos de la validación
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
     * Establece un valor en los datos de la validación
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

    public function merge(self $validacion): self
    {
        $this->data = array_merge($this->data, $validacion->getData());
        return $this;
    }

    /**
     * Permite agregar mensajes a la validación
     */
    public function agregarMensaje($mensaje): self
    {
        $this->mensajes[] = $mensaje;
        return $this;
    }

    /**
     * Permite agregar datos a la validación
     */
    public function agregarData($key, $value): self
    {
        if (!$this->saltarValidaciones) {
            $this->data[$key] = $value;
        }
        return $this;
    }

    public function agregarDataArray(array $data): void
    {
        if ($this->saltarValidaciones) {
            return;
        }
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Invalida la validación y agrega un mensaje
     */
    public function invalidar(
        string $mensaje = 'Error',
        bool $critico = false
    ): self {
        $this->valido = false;
        $this->agregarMensaje($mensaje);

        if ($critico) {
            $this->setSaltarValidaciones();
        }
        return $this;
    }


    /**
     * Para la edición, permite establecer un objeto existente
     */
    public function setExistente(mixed $existente): self
    {
        $this->existente = $existente;
        return $this;
    }

    /**
     * Obtiene el objeto existente
     */
    public function getExistente(): object|null
    {
        return $this->existente;
    }

    public function noExisteKey($key, $mensaje = 'Campo requerido'): bool
    {
        if (!isset($this->data[$key])) {
            $this->invalidar($mensaje);
            return true;
        }
        return false;
    }


    public function requerir($key, $mensaje = 'Campo requerido', $esCritico = false): void
    {
        if ($this->saltarValidaciones) {
            return;
        }
        if (!isset($this->data[$key]) || empty($this->data[$key])) {
            $this->invalidar($mensaje);

            if ($esCritico) {
                $this->setSaltarValidaciones();
            }
        }
    }


    public function objetoAEditar($objetoAEditar, $mensajeError = 'No Encontrado', $esCritico = true): bool
    {
        if ($this->saltarValidaciones) {
            return false;
        }
        if (!$objetoAEditar) {
            $this->invalidar($mensajeError);

            if ($esCritico) {
                $this->setSaltarValidaciones();
            }
            return false;
        } else {
            $this->setExistente($objetoAEditar);
            return true;
        }
    }

    public function invalidarSi($condicion, $mensaje = 'Error', $esCritico = true): void
    {
        if ($this->saltarValidaciones) {
            return;
        }
        if ($condicion) {
            $this->invalidar($mensaje);

            if ($esCritico) {
                $this->setSaltarValidaciones();
            }
        }
    }

    public function isSet($key): bool
    {
        return isset($this->data[$key]);
    }


    public function formatData(string $key, callable $formatear, $nullificar = true, $formatNull = false): void
    {
        if ($this->saltarValidaciones) {
            return;
        }
        if (isset($this->data[$key])) {
            $this->data[$key] = ($this->data[$key] === null && !$formatNull) ? null : $formatear($this->data[$key]);
        } elseif ($nullificar) {
            $this->data[$key] = null;
        }
    }


    public function formatMultipleData(array $toFormatArray): void
    {
        foreach ($toFormatArray as $toFormat) {
            $this->formatData(
                $toFormat['key'],
                $toFormat['format'],
                $toFormat['nullificar'] ?? null,
                $toFormat['formatNull'] ?? null
            );
        }
    }




    public function setValidators(array $validator)
    {
        $this->additionalValidators = $validator;
    }

    public function getValidators(): array
    {
        return $this->additionalValidators;
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