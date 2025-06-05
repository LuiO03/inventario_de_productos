<?php 

    class View{


        public $title;
        function __construct(){
        }

        function render($nombre, $mensaje = []) {
            extract($mensaje); // Convierte ['mensaje' => ...] en $mensaje
            require 'views/' . $nombre . '.php';
        }
    }
?>