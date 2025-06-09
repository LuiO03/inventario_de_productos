<?php 

    class View{


        public $title;
        public $products;
        public $mensaje; // Variable para almacenar el mensaje flash
        function __construct(){
            $this->mensaje = flash::get('mensaje'); // Obtener el mensaje flash
        }

        function render($nombre, $mensaje = []) {
            extract($mensaje); // Convierte ['mensaje' => ...] en $mensaje
            require 'views/' . $nombre . '.php';
        }
    }
?>