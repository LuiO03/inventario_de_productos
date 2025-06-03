<?php 
    require_once 'core/View.php';
    class Controller{
        protected $view;

        function __construct(){
            //echo "<p>Controlador Principal </p>";
            $this->view = new View(); // Instancia de la clase View
        }
    }
?>