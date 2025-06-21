<?php
require_once 'controllers/ErrorController.php';

/**
 * Clase Router encargada de analizar la URL, cargar el controlador correspondiente,
 * ejecutar el método solicitado y pasarle los parámetros si los hay.
 */
class Router {
    // Propiedades estáticas accesibles globalmente (útiles para helpers, breadcrumb, etc.)
    public static $controller = 'main'; // Controlador por defecto
    public static $method = 'index';    // Método por defecto
    public static $params = [];         // Parámetros adicionales

    public function __construct() {
        // Obtener la URL desde el parámetro GET o usar 'main' por defecto
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'main';

        // Dividir la URL por '/' para separar controlador, método y parámetros
        $urlParts = explode('/', $url);

        // Establecer el nombre del controlador y método
        self::$controller = $urlParts[0] ?? 'main';
        self::$method = $urlParts[1] ?? 'index';

        // Obtener los parámetros (si hay más segmentos en la URL)
        self::$params = array_slice($urlParts, 2);

        // Construir el nombre de la clase controladora (por convención)
        $controllerName = ucfirst(self::$controller) . 'Controller';
        $archivoController = 'controllers/' . $controllerName . '.php';

        // Verificar si el archivo del controlador existe
        if (file_exists($archivoController)) {
            require_once $archivoController;
            $controller = new $controllerName();

            // Verificar si el método existe dentro del controlador
            if (method_exists($controller, self::$method)) {
                // Llamar al método con los parámetros si existen
                call_user_func_array([$controller, self::$method], self::$params);
            } else {
                // Método no encontrado
                $error = new ErrorController();
            }
        } else {
            // Controlador no encontrado
            $error = new ErrorController();
        }
    }
}
