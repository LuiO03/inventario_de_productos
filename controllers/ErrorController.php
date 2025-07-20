<?php 

    class ErrorController extends Controller {
        public $mensajeError; // Variable para almacenar el mensaje de error

        function __construct() {
            parent::__construct();// esto llama al constructor de la clase padre Controller
        }
        public function index() {
            $this->view->render('error');
        }
    }
?>