<?php

    class ProductoController extends Controller {
        

        protected $model;
        public function __construct() {
            parent::__construct();
            $this->loadModel('Producto');
        }

        public function index() {
            
            $title = $this->view->title = 'Lista de Productos'; // Título de la página
            $productos = $this->model->getAll(); // Obtener todos los productos
            $this->view->render('producto/index', [
                'mensaje' => $this->view->mensaje,
                'productos' => $productos,
                'title' => $title
            ]);
        }

        public function create() {
            $title = $this->view->title = 'Agregar Producto'; // Título de la página
            $this->view->render('producto/create', ['mensaje' => $this->view->mensaje, 'title'=> $title]); // Renderizar la vista de creación de producto
        }

        public function store() {
            $nombre = trim($_POST['nombre'] ?? '');
            $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);
            $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);

            if (empty($nombre) || $precio === false || $stock === false) {
                flash::set('mensaje', [
                    'type' => 'danger',
                    'header'=> 'Atención',
                    'message' => 'Por favor, completa todos los campos correctamente.'
                ]);
                header('Location: ' . BASE_URL . 'producto/create');
                exit;
            }

            // Validar si ya existe un producto con el mismo nombre
            $existe = $this->model->findByNombre($nombre);
            if ($existe) {
                flash::set('mensaje', [
                    'type' => 'warning',
                    'header'=> 'Producto ya existente',
                    'message' => 'El producto'.$nombre.' ya está registrado.'
                ]);
                header('Location: ' . BASE_URL . 'producto/create');
                exit;
            }

            $id = $this->model->create($nombre, $precio, $stock);
            if ($id) {
                flash::set('mensaje', [
                    'type' => 'success',
                    'message' => 'Producto creado exitosamente.'
                ]);
            } else {
                flash::set('mensaje', [
                    'type' => 'error',
                    'message' => 'Error al crear el producto.'
                ]);
            }

            header('Location: ' . BASE_URL . 'producto/index');
            exit;
        }

        
    }
?>