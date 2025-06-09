<?php
    require_once 'controllers/ErrorController.php'; // Cargar el controlador de error por defecto
    class Router {

        function __construct() {
            // Obtener la URL de la petición o establecer 'main' por defecto
            $url = isset($_GET['url']) ? $_GET['url'] : 'main';
            
            $url = rtrim($url, '/'); // Eliminar la barra al final si existe
            $url = explode('/', $url); // Separar los elementos de la URL

            // Construir el nombre del controlador. Ejemplo: 'main' → 'MainController'
            $controllerName = ucfirst($url[0]) . 'Controller';
            $archivoController = 'controllers/' . $controllerName . '.php'; // Ruta al archivo del controlador

            if (file_exists($archivoController)) {
                require_once $archivoController; // Incluir el archivo del controlador
                $controller = new $controllerName(); // Crear instancia del controlador
                //$controller->loadModel($url[0]); // Cargar el modelo asociado al controlador

                // Obtener el método a ejecutar (por defecto: 'index')
                $method = isset($url[1]) ? $url[1] : 'index';

                // Verificar si el método existe dentro del controlador
                if (method_exists($controller, $method)) {
                    $controller->{$method}(); // Llamar al método del controlador
                } else {
                    // Si el método no existe, puedes mostrar un error personalizado
                    $error = new ErrorController(); // Instanciar controlador de errores
                }

            } else {
                // Si el archivo del controlador no existe, se carga el controlador de error
                $error = new ErrorController(); // Instanciar controlador de errores
            }
        }

    }
?>
