<?php 

    class ErrorController extends Controller {

        function __construct() {
            parent::__construct();// esto llama al constructor de la clase padre Controller
            $this->view->render('error');
        }

    }
?>