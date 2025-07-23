<?php

namespace Thaumware\Core\Helpers\Responses;

use App\Shared\Helpers\Validacion\Validacion;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiResponse
{
    public static function errorIf($condition, $message = "Error", $status = 400, $extraData = [])
    {
        if ($condition) {
            throw new HttpResponseException(response()->json(array_merge([
                "message" => $message
            ], $extraData), $status));
        }
    }

    public static function success($data = [], $status = 200)
    {
        return response()->json($data, $status);
    }

    public static function error($message = "Error", $status = 400, $extraData = [])
    {
        return response()->json(array_merge([
            "message" => $message
        ], $extraData), $status);
    }

    public static function multipleErrorIf($conditions, $status = 400)
    {
        foreach ($conditions as $condition) {
            if ($condition['condition']) {
                throw new HttpResponseException(response()->json([
                    "message" => $condition['message']
                ], $status));
            }
        }
    }

    public static function respuestaValidacion(Validacion $res, $respuesta = [])
    {
        if (!$res->esValido()) {
            return self::error($res->getMensaje());
        }
        return self::success($respuesta);

    }
}