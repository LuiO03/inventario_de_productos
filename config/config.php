<?php

    // ==============================
    // ENTORNO Y BASE URL
    // ==============================

    // Detecta si el entorno es local (localhost)
    $isLocalhost = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);

    // Define la carpeta raíz del proyecto (ajústalo si está en otro directorio)
    $baseFolder = '/inventario_de_productos/';

    // Define la URL base para recursos como CSS, JS, imágenes
    define('BASE_URL', 'http://localhost/inventario_de_productos/');
    //define('BASE_URL', ($isLocalhost ? 'http://localhost' : 'https://' . $_SERVER['SERVER_NAME']) . $baseFolder);

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
    define('APP_LOGO', BASE_URL.'public/images/logos/logo.png'); // Ruta del logo (relativa a la raíz del proyecto)

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
