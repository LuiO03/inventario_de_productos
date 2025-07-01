<?php

class FechaHelper
{
    public static function formatoCorto(string $fecha): string
    {
        $dt = new DateTime($fecha);
        return $dt->format('d/m/Y H:i');
    }

    public static function formatoLargo(string|\DateTime|null $fecha): string
{
    if (empty($fecha)) return '-';

    try {
        $dt = $fecha instanceof \DateTime ? $fecha : new \DateTime($fecha);

        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];

        $dia = $dt->format('d');
        $mes = $meses[(int)$dt->format('m')];
        $anio = $dt->format('Y');
        $hora = $dt->format('h:i A');

        return "$dia de $mes de $anio, $hora";
    } catch (\Exception $e) {
        return '-';
    }
}

    public static function formatoRelativo(string $fecha): string
    {
        $ahora = new DateTime();
        $dt = new DateTime($fecha);
        $diferencia = $ahora->diff($dt);

        if ($diferencia->invert === 0) return 'en el futuro';

        if ($diferencia->y > 0) {
            return "hace {$diferencia->y} año(s)";
        } elseif ($diferencia->m > 0) {
            return "hace {$diferencia->m} mes(es)";
        } elseif ($diferencia->d > 0) {
            return "hace {$diferencia->d} día(s)";
        } elseif ($diferencia->h > 0) {
            return "hace {$diferencia->h} hora(s)";
        } elseif ($diferencia->i > 0) {
            return "hace {$diferencia->i} minuto(s)";
        } else {
            return "hace unos segundos";
        }
    }

    
}
