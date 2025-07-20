<?php 
    class AyudaController extends Controller{
        function __construct(){
            parent::__construct();
        }

        public function index() {
            $this->view->mensaje = FlashHelper::get('mensaje'); // Recupera el mensaje flash
            $this->view->render('ayuda/index');           // Renderiza la vista principal
        }
    }
?>