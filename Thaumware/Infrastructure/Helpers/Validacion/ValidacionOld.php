<?php

namespace Thaumware\Core\Helpers\Validacion;

/**
 * Clase de apoyo para la validación de datos

 */
class ValidacionOld
{

    public static function validar($validador, array $data, $edicion = false): Validacion
    {
        return $validador->validar($data, $edicion);
    }

    public static function repuestaJson(Validacion $resultado, $objeto = "data"): string
    {
        return response()->json([
            'valido' => $resultado->esValido(),
            'mensaje' => $resultado->getMensaje(),
            $objeto => $resultado->getData()
        ], $resultado->esValido() ? 200 : 400);
    }
}