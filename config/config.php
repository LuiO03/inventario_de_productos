<?php
    // ==============================
    // ENTORNO Y BASE URL
    // ==============================

    // Detecta si el entorno es local
    $isLocalhost = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);

    // Carpeta donde está tu proyecto
    $baseFolder = '/inventario_de_productos/';

    // Protocolo (http para localhost, https para producción)
    $protocol = $isLocalhost ? 'http' : 'https';

    // Dominio (localhost o dominio real)
    $host = $_SERVER['HTTP_HOST']; // Más flexible que SERVER_NAME

    // BASE_URL completa
    define('BASE_URL', $protocol . '://' . $host . $baseFolder);

    // ==============================
    // CONFIGURACIÓN GENERAL
    // ==============================

    define('APP_NAME', 'Inventario de Productos');
    define('APP_VERSION', '1.0.0');
    define('APP_AUTHOR', 'LuiOsorio');
    define('APP_COPYRIGHT', '© LuiOsorio 2025 - Todos los Derechos Reservados - Huancayo');
    define('APP_DESCRIPTION', 'Aplicación para gestionar un inventario de productos de manera eficiente y sencilla.');
    define('APP_KEYWORDS', 'inventario, productos, gestión, administración, PHP, MySQL'); // Palabras clave para SEO
    define('APP_LICENSE', 'MIT'); // Licencia de uso
    define('APP_LOGO', BASE_URL . 'public/images/logos/logo.png'); // Ruta del logo (relativa a la raíz del proyecto)

    // ==============================
    // ZONA HORARIA Y CONFIGURACIONES
    // ==============================
    date_default_timezone_set('America/Lima'); // Ajustar según ubicación
    ini_set('default_charset', 'UTF-8');
    mb_internal_encoding("UTF-8");

    // ==============================
    // CONEXIÓN A BASE DE DATOS
    // ==============================
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'inv_productos');      // ⚠️ Reemplaza por el nombre real de tu BD
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');
    define('DB_PORT', '3306');                 // Puerto MySQL por defecto
    define('DB_COLLATE', 'utf8mb4_unicode_ci');
    define('DB_PREFIX', 'inv_');               // Prefijo de tablas

    /*
    define('DB_HOST', 'sql310.infinityfree.com');
    define('DB_NAME', 'if0_39085781_inventario');
    define('DB_USER', 'if0_39085781');
    define('DB_PASS', '0afolwKzE0gI4V');
    */
?>