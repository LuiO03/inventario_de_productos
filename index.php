<?php
    // Configuración de la aplicación

    // Este archivo es el punto de entrada de la aplicación(entry point).
    // Aquí se cargan las configuraciones, controladores, modelos y vistas necesarias
    // session_start();
    
    // Punto de entrada de la aplicación
    session_start();

    // Composer autoload (¡agregalo aquí!)
    require_once 'vendor/autoload.php';

    // Configuraciones y helpers
    require_once 'config/config.php';
    require_once 'helpers/FechaHelper.php';
    require_once 'helpers/LayoutHelper.php';
    require_once 'helpers/ValidationHelper.php';
    require_once 'helpers/FlashHelper.php';
    require_once 'helpers/TextoHelper.php';

    // Core MVC
    require_once 'core/csrf.php';
    require_once 'core/controller.php';
    require_once 'core/model.php';
    require_once 'core/view.php';
    require_once 'core/router.php';

    // Iniciar la app
    $app = new Router();
?>