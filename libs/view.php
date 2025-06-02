<?php 

    class View{

        public $mensaje;
        function __construct(){
        }
        function traerVista($nombre){
            require 'views/'.$nombre.'.php';// hace require para trae el enlace de la vista especificada
        }
    }
?>