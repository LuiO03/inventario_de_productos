<?php
class Flash
{

    public static function set($key, $message, $type = 'success')
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (is_array($message) && isset($message['message'], $message['type'])) {
            $_SESSION['flash'][$key] = $message;
        } else {
            $_SESSION['flash'][$key] = [
                'message' => $message,
                'type' => $type
            ];
        }
    }

    public static function get($key)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    public static function has($key)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['flash'][$key]);
    }

    // ✅ Nuevo: para validaciones menores
    public static function setValidate($mensajes, $tipo = 'danger')
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!is_array($mensajes)) {
            $mensajes = [$mensajes]; // Convertir a array si es texto simple
        }

        $_SESSION['flash_validate'] = [
            'type' => $tipo,
            'mensajes' => $mensajes
        ];
    }

    public static function getValidate()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['flash_validate'])) {
            $msg = $_SESSION['flash_validate'];
            unset($_SESSION['flash_validate']);
            return $msg;
        }
        return null;
    }

    public static function hasValidate()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['flash_validate']);
    }
}
