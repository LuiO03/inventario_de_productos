<?php

    class ProductoController extends Controller {

        public function __construct() {
            parent::__construct();
        }
        /* 
         public function index() {
             $products = $this->model->getAll(); // Método en ProductModel
             $this->view->products = $products; // Pasar los productos a la vista
             $this->view->title = 'Lista de Productos'; // Título de la página
             $this->view->render('producto/index');
         }
         */

        public function index() {
            $this->view->title = 'Lista de Productos'; // Título de la página
            
            $this->view->render('producto/index');
        }

        public function crearProducto() {
            $this->model->create(); // Método en ProductModel
            $this->view->title = 'Crear Producto'; // Título de la página
            $this->view->render('producto/crear');
        }
    }
?>