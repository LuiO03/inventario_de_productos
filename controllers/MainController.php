<?php
    class MainController extends Controller {


        public function __construct() {
            parent::__construct(); // Solo inicializa el controlador, sin renderizar vistas
        }

        public function index() {
            $mensaje = Flash::get('mensaje'); // Recupera el mensaje flash
            $this->view->render('main/index', ['mensaje' => $mensaje]);// Renderiza la vista principal
        }
        // Método de ejemplo que imprime un saludo

        public function saludo() {
            flash::set('mensaje', [
                'type' => 'success',
                'message' => 'Hola, bienvenido al sistema de inventario'
            ]);
            header('Location: ' . BASE_URL . 'main'); // Redirige para que no se repita el mensaje si recargas
        }

        public function error() {
            flash::set('mensaje', [
                'type' => 'danger',
                'message' => 'Hubo un error al procesar la solicitud'
            ]);
            header('Location: ' . BASE_URL . 'main');
            exit;
        }
    }
?>
