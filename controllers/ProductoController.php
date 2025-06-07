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
            $mensaje = Flash::get('mensaje');
            $this->view->title = 'Lista de Productos'; // Título de la página
            $this->view->render('producto/index', ['mensaje' => $mensaje]);
        }

        public function create() {
            $this->view->title = 'Agregar Producto'; // Título de la página
            $this->view->render('producto/create'); // Renderizar la vista de creación de producto
        }

        public function store() {
            $nombre = trim($_POST['nombre'] ?? '');
            $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);
            $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
            $id = $this->model->create($nombre, $precio, $stock); // Método en ProductModel para crear un producto
            if ($id !== false) {
                flash::set('mensaje', [
                    'type' => 'success',
                    'message' => 'Producto agregado correctamente con ID: ' . $id
                ]);
                header('Location: ' . BASE_URL . 'producto/index');
                exit;
            } else {
                $this->view->render('producto/create');
                exit;
            }
        }
        /* 
        public function edit(){
            $id = $_GET['id'] ?? null; // Obtener el ID del producto desde la URL
            if ($id) {
                $product = $this->model->getById($id); // Obtener el producto por ID
                if ($product) {
                    $this->view->product = $product; // Pasar el producto a la vista
                    $this->view->title = 'Editar Producto'; // Título de la página
                    $this->view->render('producto/edit'); // Renderizar la vista de edición de producto
                } else {
                    echo "<p>Producto no encontrado</p>";
                }
            } else {
                echo "<p>ID de producto no proporcionado</p>";
            }
        }*/
        
    }
?>