<?php 

    class View{


        public $title;
        public $products;
        function __construct(){
        }

        function render($nombre, $mensaje = []) {
            extract($mensaje); // Convierte ['mensaje' => ...] en $mensaje
            require 'views/' . $nombre . '.php';
        }
    }
?>