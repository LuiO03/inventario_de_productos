<?php
    if (session_status() === PHP_SESSION_NONE) session_start();

    // Generación del token CSRF
    function generarTokenCSRF() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    // Validación del token CSRF
    function validarTokenCSRF($tokenRecibido) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $tokenRecibido);
    }

    function protegerContraCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                FlashHelper::set('mensaje', [
                    'type' => 'danger',
                    'header' => 'Error de seguridad',
                    'message' => 'Token CSRF inválido o ausente.'
                ]);
                header('Location: ' . BASE_URL . 'producto/index');
                exit;
            }
        }
    }
?>