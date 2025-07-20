<?php

class Validador
{
    // =========================
    // VALIDACIONES DE CAMPOS
    // =========================

    public static function campoObligatorio($valor, $nombreCampo, $mensaje = null)
    {
        if (trim($valor) === '') {
            return $mensaje ?? "El campo <strong>$nombreCampo</strong> es obligatorio.";
        }
        return null;
    }

    public static function limitarTexto($valor, $nombreCampo, $min, $max, $mensaje = null)
    {
        $longitud = mb_strlen(trim($valor));
        if ($longitud < $min || $longitud > $max) {
            return $mensaje ?? "El campo <strong>$nombreCampo</strong> debe tener entre $min y $max caracteres.";
        }
        return null;
    }

    public static function longitudExacta($valor, $nombreCampo, $longitud)
    {
        if (mb_strlen(trim($valor)) !== $longitud) {
            return "El campo <strong>$nombreCampo</strong> debe tener exactamente $longitud caracteres.";
        }
        return null;
    }

    public static function numero($valor, $nombreCampo)
    {
        if (!is_numeric($valor)) {
            return "El campo <strong>$nombreCampo</strong> debe ser un número válido.";
        }
        return null;
    }

    public static function rangoNumero($valor, $nombreCampo, $min, $max)
    {
        if (!is_numeric($valor) || $valor < $min || $valor > $max) {
            return "El campo <strong>$nombreCampo</strong> debe estar entre $min y $max.";
        }
        return null;
    }

    public static function soloLetras($valor, $nombreCampo)
    {
        if (!preg_match('/^[\p{L}\s]+$/u', $valor)) {
            return "El campo <strong>$nombreCampo</strong> solo debe contener letras.";
        }
        return null;
    }

    public static function soloNumeros($valor, $nombreCampo)
    {
        if (!ctype_digit($valor)) {
            return "El campo <strong>$nombreCampo</strong> solo debe contener números enteros.";
        }
        return null;
    }

    public static function email($valor, $nombreCampo)
    {
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            return "El campo <strong>$nombreCampo</strong> debe ser un correo válido.";
        }
        return null;
    }

    public static function booleano($valor, $nombreCampo)
    {
        if (!in_array($valor, [0, 1, '0', '1'], true)) {
            return "El campo <strong>$nombreCampo</strong> es inválido.";
        }
        return null;
    }

    public static function coincidencia($valor1, $valor2, $nombreCampo = 'Los campos')
    {
        if ($valor1 !== $valor2) {
            return "$nombreCampo no coinciden.";
        }
        return null;
    }

    public static function fechaPosterior($fecha, $compararCon, $nombreCampo)
    {
        if (strtotime($fecha) <= strtotime($compararCon)) {
            return "La fecha de <strong>$nombreCampo</strong> debe ser posterior a la actual.";
        }
        return null;
    }

    // =========================
    // VALIDACIONES DE ARCHIVOS
    // =========================

    public static function imagen($archivo, $maxMB = 3, $formatosPermitidos = ['jpg', 'jpeg', 'png', 'webp'])
    {
        if (!isset($archivo) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // No se subió nada
        }

        $peso = $archivo['size'];
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if ($peso > ($maxMB * 1024 * 1024)) {
            return "La imagen no debe superar los $maxMB MB.";
        }

        if (!in_array($ext, $formatosPermitidos)) {
            return "Formato no permitido. Solo se permiten: " . implode(', ', $formatosPermitidos) . ".";
        }

        return null;
    }

    public static function noSoloNumeros($texto, $campo): ?string
    {
        if (preg_match('/^\d+$/', $texto)) {
            return "El campo <strong>{$campo}</strong> no puede contener solo números.";
        }
        return null;
    }

    // =========================
    // UTILITARIOS
    // =========================

    public static function emailValido($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
