<?php
    require_once 'controllers/ErrorController.php'; // Cargar el controlador de error por defecto
    class Router {

        function __construct() {
            // Initialization code can go here
            //echo "<p>nueva app</p>";
            //$url = isset($_GET['url']) ? $_GET['url'] : null; Obtener la URL de la petición
            $url = isset($_GET['url']) ? $_GET['url'] : 'maincontroller'; // Obtener la URL de la petición
            $url = rtrim($url, '/');
            $url = explode('/', $url);
            //var_dump($url); esto es para ver el contenido de la variable $url
            $archivoController='controllers/'.$url[0].'.php';
            /*
            if(empty($url[0])){
                require_once 'controllers/mainController.php'; // si no hay controlador, se carga el controlador 
                $url[0] = 'mainController'; // se asigna el controlador principal a la variable $url[0]
                $controller = new MainController(); // se crea una instancia del controlador principal
                return;
            }
            */

            if(file_exists($archivoController)){
                require_once $archivoController; // esto carga el archivo del controlador, ejemplo: controllers/productController.php
                $controller= new $url[0];

                if (isset($url[1])) {
                    $metodo=$url[1]; // si existe el segundo elemento de la url, se llama al método del controlador
                    // Verificar si el método existe en el controlador
                    if (method_exists($controller, $metodo)) {
                        $controller->{$metodo}();
                    } else {
                        
                    }
                }
            } else {
                $controller = new ErrorController(); // si no existe el controlador, se carga el controlador de error
            }

        }

    }
?>

