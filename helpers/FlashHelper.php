<?php
    class Flash {

        // Establece un mensaje flash
        public static function set($key, $message, $type = 'success') {
            if (session_status() === PHP_SESSION_NONE) session_start();

            // Si el mensaje ya es un array con 'message' y 'type', lo usamos directamente
            if (is_array($message) && isset($message['message'], $message['type'])) {
                $_SESSION['flash'][$key] = $message;
            } else {
                // Si no, lo envolvemos como mensaje tipo texto con tipo por defecto
                $_SESSION['flash'][$key] = [
                    'message' => $message,
                    'type' => $type
                ];
            }
        }

        // Obtiene y elimina el mensaje flash
        public static function get($key) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (isset($_SESSION['flash'][$key])) {
                $message = $_SESSION['flash'][$key];
                unset($_SESSION['flash'][$key]); // lo elimina para que sea temporal
                return $message;
            }
            return null;
        }

        // Verifica si existe un mensaje flash
        public static function has($key) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            return isset($_SESSION['flash'][$key]);
        }
    }
?>
