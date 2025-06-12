<?php 

    class View{

        public $products;
        public $mensaje; // Variable para almacenar el mensaje flash
        function __construct(){
            $this->mensaje = flash::get('mensaje'); // Obtener el mensaje flash
        }

        function render($nombre, $datos = []) {
            extract($datos); // Convierte ['mensaje' => ...] en $mensaje
            require 'views/' . $nombre . '.php';
        }
    }
?>