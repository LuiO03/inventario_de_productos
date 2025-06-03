<?php 

    class View{

        public $mensaje;
        function __construct(){
        }
        function render($nombre){
            require 'views/'.$nombre.'.php';// hace require para trae el enlace de la vista especificada
        }
    }
?>