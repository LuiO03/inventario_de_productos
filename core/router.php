<?php
require_once 'controllers/ErrorController.php';

class Router {

    public function __construct() {
        // Obtener la URL de la petición o establecer 'main' por defecto
        $url = isset($_GET['url']) ? $_GET['url'] : 'main';
        $url = rtrim($url, '/'); // Eliminar la barra al final si existe
        $urlParts = explode('/', $url); // Separar por '/'

        // Obtener nombre del controlador
        $controllerName = ucfirst($urlParts[0]) . 'Controller';
        $archivoController = 'controllers/' . $controllerName . '.php';

        // Verificar si existe el archivo del controlador
        if (file_exists($archivoController)) {
            require_once $archivoController;
            $controller = new $controllerName();

            // Obtener método, si no se proporciona usar 'index'
            $method = isset($urlParts[1]) ? $urlParts[1] : 'index';

            // Obtener parámetros si existen
            $params = array_slice($urlParts, 2);

            // Verificar si el método existe en el controlador
            if (method_exists($controller, $method)) {
                // Llamar al método con o sin parámetros
                call_user_func_array([$controller, $method], $params);
            } else {
                // Si no existe el método, mostrar error
                $error = new ErrorController();
            }

        } else {
            // Si no existe el controlador, mostrar error
            $error = new ErrorController();
        }
    }
}
