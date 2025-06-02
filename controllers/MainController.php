<?php
class MainController extends Controller {

    public function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre
        $this->view->traerVista('main/index');
    }

    // Método que carga la vista principal
    public function index() {
        $this->view->traerVista('main/index');
    }

    // Método de ejemplo que imprime un saludo
    public function saludo() {
        echo "<h1>Hola, bienvenido al controlador principal (MainController)!</h1>";
    }
}
?>
