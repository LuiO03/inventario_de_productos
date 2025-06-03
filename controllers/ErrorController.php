<?php 

    class ErrorController extends Controller {

        function __construct() {
            parent::__construct();// esto llama al constructor de la clase padre Controller
            $this->view->mensaje="hola que tal";
            $this->view->render('error');
        }

    }
?>