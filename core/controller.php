<?php 
    require_once 'core/view.php';
    require_once 'core/model.php';
    
    class Controller{
        protected $view;
        protected $model;
        
        function __construct(){
            //echo "<p>Controlador Principal </p>";
            $this->view = new View(); // Instancia de la clase View
        }

        public function loadModel($model) {
            $archivoModelo = 'models/' . $model . 'Model.php';

            if (file_exists($archivoModelo)) {
                require_once $archivoModelo;
                $nombreModelo = $model . 'Model';

                if (class_exists($nombreModelo)) {
                    $this->model = new $nombreModelo();
                    return $this->model;
                }
                return null;
            }
        }
    }
?>