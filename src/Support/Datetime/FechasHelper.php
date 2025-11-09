<?php

namespace Thaumware\Support\Datetime;

use DateTime;
use DateTimeImmutable;

class FechasHelper
{
    public static $meses = [
        0 => '-',
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];
    public static $dateTimeFormat = 'Y-m-d\TH:i:s';
    public static function fechaHoraActual(int $offset = 0, $format = null)
    {
        return new DateTimeImmutable(
            date(
                $format ? $format :
                self::$dateTimeFormat,
                strtotime("+$offset sec")
            )
        );
    }
    public static function now()
    {
        return self::fechaHoraActual();
    }

    public static function fechaActual()
    {
        return date('Y-m-d');
    }

    public static function horaActual()
    {
        return date('H:i:s');
    }

    public static function fechaHaceNHoras(int $n, $fecha = null)
    {
        if ($fecha) {
            return date(self::$dateTimeFormat, strtotime("-$n hours", strtotime($fecha)));
        }
        return date(self::$dateTimeFormat, strtotime("-$n hours"));
    }
    public static function fechaHaceNMinutos(int $n, $fecha = null)
    {
        if ($fecha) {
            return date(self::$dateTimeFormat, strtotime("-$n minutes", strtotime($fecha)));
        }
        return date(self::$dateTimeFormat, strtotime("-$n minutes"));
    }
    public static function fechaDespuesDeNHoras(int $n, $fecha = null)
    {
        if ($fecha) {
            return date(self::$dateTimeFormat, strtotime("+$n hours", strtotime($fecha)));
        }
        return date(self::$dateTimeFormat, strtotime("+$n hours"));
    }

    public static function fechaDespuesDeNDias(int $n, $fecha = null)
    {
        if ($fecha) {
            return date(self::$dateTimeFormat, strtotime("+$n days", strtotime($fecha)));
        }
        return date(self::$dateTimeFormat, strtotime("+$n days"));
    }

    public static function fechaHaceUnAno($fecha = null)
    {
        if ($fecha) {
            return date(self::$dateTimeFormat, strtotime('-1 year', strtotime($fecha)));
        }
        return date(self::$dateTimeFormat, strtotime('-1 year'));
    }

    public static function diaHoraActual()
    {
        return date('d H:i');
    }

    public static function fechaHoraActualFormato($formato)
    {
        return date($formato);
    }

    public static function esteMes()
    {
        $mes = date('m');
        return self::$meses[$mes];
    }

    public static function obtenerMesDeFecha($fecha)
    {
        $mes = (int) date('m', strtotime($fecha));
        return array_values(self::$meses)[$mes];
    }

    public static function obtenerAnioDeFecha($fecha)
    {
        $anio = date('Y', strtotime($fecha));
        return $anio;
    }

    public static function formatoFechaHora($fecha, $formato = null, $hora = null)
    {
        return $fecha
            ? date($formato ? $formato : self::$dateTimeFormat, strtotime($fecha . ($hora ? ' ' . $hora : '')))
            : null;
    }

    public static function diaHoraFecha($fecha)
    {
        return date('d (H_i)', strtotime($fecha));
    }

    public static function diaFecha($fecha)
    {
        return date('d', strtotime($fecha));
    }

    public static function horaFecha($fecha)
    {
        return date('H', strtotime($fecha));
    }

    public static function minutosFecha($fecha)
    {
        return date('i', strtotime($fecha));
    }

    public static function fechaTextoSinAnio($fecha)
    {
        $mes = self::obtenerMesDeFecha($fecha);
        $dia = self::diaFecha($fecha);
        return $mes . ' ' . $dia . ', ' . self::horaFecha($fecha) . 'H ' . self::minutosFecha($fecha) . 'm';
    }

    public static function esMayor(
        string|null $fecha1 = null,
        string|null $fecha2 = null,
        bool $incluirIgual = false
    ) {
        if (!$fecha1 || !$fecha2) {
            return false;
        }
        if ($incluirIgual) {
            return strtotime($fecha1) >= strtotime($fecha2);
        }
        return strtotime($fecha1) > strtotime($fecha2);
    }

    public static function haceSemanas($semanas = 1)
    {
        return date('Y-m-d', strtotime('-' . $semanas . ' weeks'));
    }


    /**
     * horasNoValidas = [
     * ['hora_inicio' => '08:00:00', 'hora_termino' => '12:00:00'],
     * ]
     */
    public static function esHoraValida($fechaHora, $horasNoValidas)
    {
        $fechaHora = self::formatoFechaHora($fechaHora, 'Y-m-d H:i:s');

        foreach ($horasNoValidas as $rango) {
            $horaInicio = self::formatoFechaHora($fechaHora, 'Y-m-d') . ' ' . $rango['hora_inicio'];
            $horaTermino = self::formatoFechaHora($fechaHora, 'Y-m-d') . ' ' . $rango['hora_termino'];
            if (self::esMayor($fechaHora, $horaInicio, true) && self::esMayor($horaTermino, $fechaHora, true)) {
                return false;
            }
        }
        return true;
    }

    public static function restarHorasAFecha(string $fecha, int $horas)
    {
        return date(self::$dateTimeFormat, strtotime("-$horas hour", strtotime($fecha)));
    }

    public static function sumarHorasAFecha(string $fecha, int $horas)
    {
        return date(self::$dateTimeFormat, strtotime("+$horas hour", strtotime($fecha)));
    }

    public static function restarHorasAHora(string $hora, int $horas)
    {
        return date('H:i:s', strtotime("-$horas hour", strtotime($hora)));
    }

    public static function sumarHorasAHora(string $hora, int $horas)
    {
        return date('H:i:s', strtotime("+$horas hour", strtotime($hora)));
    }

    public static function volverUndia(string $fecha, $formato = 'Y-m-d')
    {
        return date($formato, strtotime("-1 day", strtotime($fecha)));
    }

    public static function obtenerNumeroDia(string $fecha)
    {
        return ((int) date('N', strtotime($fecha))) - 1;
    }

    public static function dentroDeNMeses($meses = 1, $fecha = null, $formato = 'Y-m-d')
    {
        return date($formato, strtotime('+' . $meses . ' months', strtotime($fecha ? $fecha : self::fechaActual())));
    }

    public static function obtenerDiaSemana($fecha)
    {
        $dia = date('N', strtotime($fecha));
        return (int) $dia;
    }

    public static function diferenciaHoras($fecha1, $fecha2)
    {
        $fecha1 = new DateTime(self::formatoFechaHora($fecha1));
        $fecha2 = new DateTime(self::formatoFechaHora($fecha2));
        $diferencia = $fecha1->diff($fecha2);
        return $diferencia->days * 24 + $diferencia->h + $diferencia->i / 60 + $diferencia->s / 3600;
    }

    public static function diferenciaMinutos($fecha1, $fecha2)
    {
        $fecha1 = new DateTime(self::formatoFechaHora($fecha1));
        $fecha2 = new DateTime(self::formatoFechaHora($fecha2));
        $diferencia = $fecha1->diff($fecha2);
        return $diferencia->days * 24 * 60 + $diferencia->h * 60 + $diferencia->i + $diferencia->s / 60;
    }



    public static function primerDiaDelMes($fecha = null, $hora = '00:00:00')
    {
        $fecha = $fecha ? strtotime($fecha) : time();
        return self::formatoFechaHora(date("Y-m-01 $hora", $fecha));
    }

    public static function ultimoDiaDelMes($fecha = null, $hora = '23:59:59')
    {
        $fecha = $fecha ? strtotime($fecha) : time();
        return self::formatoFechaHora(date("Y-m-t $hora", $fecha));
    }
}
