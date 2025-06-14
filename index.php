<?php
    // Configuración de la aplicación

    // Este archivo es el punto de entrada de la aplicación(entry point).
    // Aquí se cargan las configuraciones, controladores, modelos y vistas necesarias
    // session_start();
    session_start(); // Muy importante
    require_once 'config/config.php';
    require_once 'helpers/helper.php';
    require_once 'helpers/flash.php';
    require_once 'helpers/fecha.php';
    require_once 'core/csrf.php';
    require_once 'core/controller.php';
    require_once 'core/model.php';
    require_once 'core/view.php';
    require_once 'core/router.php';

    // Iniciar la aplicación: cargar router y procesar la petición
    $app = new Router();
?>