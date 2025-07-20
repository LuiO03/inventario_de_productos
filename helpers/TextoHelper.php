<?php

class TextoHelper
{
    /**
     * Poner en mayúscula cada palabra
     */
    public static function formatearNombre(string $texto): string
    {
        return ucwords(mb_strtolower(trim($texto)));
    }

    /**
     * Normalizar texto completo a minúsculas
     */
    public static function minusculas(string $texto): string
    {
        return mb_strtolower(trim($texto));
    }

    /**
     * Capitalizar solo la primera letra del texto
     */
    public static function primeraLetraMayuscula(string $texto): string
    {
        return mb_strtoupper(mb_substr($texto, 0, 1)) . mb_substr($texto, 1);
    }

    // =========================
    // ESCAPE DE SALIDA
    // =========================

    public static function escape($valor)
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }

    public static function limpiarCadena($cadena)
    {
        $string = trim(preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $cadena));
        $string = stripslashes($string);
        $string = strip_tags($string);

        $peligros = [
            "/<script\b[^>]*>(.*?)<\/script>/is",
            "/(SELECT|DELETE|INSERT|DROP|COUNT|LIKE)\s+/i",
            "/OR\s+['\"´]?[a-z0-9]+['\"´]?\s*=\s*['\"´]?[a-z0-9]+['\"´]?/i",
            "/--/", "/\^/", "/\[/", "/\]/", "/==/"
        ];

        return preg_replace($peligros, "", $string);
    }

    public static function limpiarTildes($cadena)
    {
        return str_replace(
            ['Á','À','Â','Ä','á','à','ä','â','ª','É','È','Ê','Ë','é','è','ë','ê',
            'Í','Ì','Ï','Î','í','ì','ï','î','Ó','Ò','Ö','Ô','ó','ò','ö','ô',
            'Ú','Ù','Û','Ü','ú','ù','ü','û','Ñ','ñ','Ç','ç',',','.',';',':'],
            ['A','A','A','A','a','a','a','a','a','E','E','E','E','e','e','e','e',
            'I','I','I','I','i','i','i','i','O','O','O','O','o','o','o','o',
            'U','U','U','U','u','u','u','u','N','n','C','c','','','',''],
            $cadena
        );
    }
}
