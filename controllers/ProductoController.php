<?php

    class ProductoController extends Controller {
        

        protected $model;
        public function __construct() {
            parent::__construct();
            $this->loadModel('Producto');
        }

        private function validarDatosProducto(&$nombre, &$precio, &$stock) {
            $nombre = trim($_POST['nombre'] ?? '');
            $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);
            $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);

            return !(empty($nombre) || $precio === false || $stock === false);
        }

        private function redirigirConMensaje($ruta, $tipo, $header, $mensaje) {
            flash::set('mensaje', [
                'type' => $tipo,
                'header' => $header,
                'message' => $mensaje
            ]);
            header('Location: ' . BASE_URL . $ruta);
            exit;
        }

        public function index() {
            
            $GLOBALS['pageTitle'] = 'Lista de Productos'; // Título de la página
            $productos = $this->model->getAll(); // Obtener todos los productos
            $this->view->render('producto/index', [
                'mensaje' => $this->view->mensaje,
                'productos' => $productos
            ]);
        }

        public function create() {
            $GLOBALS['pageTitle'] = 'Agregar Producto';
            $this->view->render('producto/create', [
                'mensaje' => $this->view->mensaje
            ]);
        }

        public function store() {
            if (!$this->validarDatosProducto($nombre, $precio, $stock)) {
                $this->redirigirConMensaje(
                    'producto/create', 
                    'danger',
                    'Atención',
                    'Por favor, completa todos los campos correctamente.'
                    );
                exit;
            }

            // Validar si ya existe un producto con el mismo nombre
            $existe = $this->model->findByNombre($nombre);
            if ($existe) {
                flash::set('mensaje', [
                    'type' => 'warning',
                    'header'=> 'Producto ya existente',
                    'message' => 'El producto <strong>"'.htmlspecialchars($nombre).'"</strong> ya está registrado.'
                ]);
                header('Location: ' . BASE_URL . 'producto/create');
                exit;
            }

            // Crear el objeto Producto
            $producto = new Producto(1,$nombre, $precio, $stock);

            // Pasar el objeto al modelo
            $id = $this->model->create($producto);
            if ($id) {
                flash::set('mensaje', [
                    'type' => 'success',
                    'header'=> 'Producto agregado',
                    'message' => 'El producto <strong>"'.htmlspecialchars($nombre).'"</strong> se ha creado exitosamente.'
                ]);
            } else {
                flash::set('mensaje', [
                    'type' => 'danger',
                    'header'=> 'Error',
                    'message' => 'Error al crear el producto.'
                ]);
            }

            header('Location: ' . BASE_URL . 'producto/index');
            exit;
        }

        public function edit($id) {
            if (!is_numeric($id)) {
                flash::set('mensaje', [
                    'type' => 'danger',
                    'header' => 'ID Inválido',
                    'message' => 'El ID proporcionado no es válido.'
                ]);
                header('Location: ' . BASE_URL . 'producto/index');
                exit;
            }

            $producto = $this->model->getById($id);

            if (!$producto) {
                flash::set('mensaje', [
                    'type' => 'warning',
                    'header' => 'No encontrado',
                    'message' => 'No se encontró el producto con ID: ' . htmlspecialchars($id)
                ]);
                header('Location: ' . BASE_URL . 'producto/index');
                exit;
            }

            $GLOBALS['pageTitle'] = 'Editar Producto '.$id;
            $this->view->render('producto/edit', [
                'producto' => $producto,
                'mensaje' => $this->view->mensaje
            ]);
        }

        public function update($id) {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !is_numeric($id)) {
                flash::set('mensaje', [
                    'type' => 'danger',
                    'header' => 'Acceso no permitido',
                    'message' => 'Solicitud inválida o ID incorrecto.'
                ]);
                header('Location: ' . BASE_URL . 'producto/index');
                exit;
            }

            if (!$this->validarDatosProducto($nombre, $precio, $stock)) {
                flash::set('mensaje', [
                    'type' => 'danger',
                    'header'=> 'Atención',
                    'message' => 'Por favor, completa todos los campos correctamente.'
                ]);
                header('Location: ' . BASE_URL . 'producto/edit/' . $id);
                exit;
            }

            // Validar si existe otro producto con el mismo nombre
            $existe = $this->model->findByNombre($nombre);
            if ($existe && $existe->getId() != $id) {
                flash::set('mensaje', [
                    'type' => 'warning',
                    'header'=> 'Nombre duplicado',
                    'message' => 'Ya existe otro producto llamado <strong>"' . htmlspecialchars($nombre) . '"</strong>.'
                ]);
                header('Location: ' . BASE_URL . 'producto/edit/' . $id);
                exit;
            }

            // Crear el objeto Producto con ID
            $producto = new Producto($id, $nombre, $precio, $stock);

            // Actualizar en base de datos
            $actualizado = $this->model->update($producto);

            if ($actualizado) {
                flash::set('mensaje', [
                    'type' => 'success',
                    'header'=> 'Actualización exitosa',
                    'message' => 'El producto <strong>"' . htmlspecialchars($nombre) . '"</strong> (ID: ' . $id . ') se ha actualizado correctamente.'
                ]);
            } else {
                flash::set('mensaje', [
                    'type' => 'danger',
                    'header'=> 'Error',
                    'message' => 'Error al actualizar el producto.'
                ]);
            }

            header('Location: ' . BASE_URL . 'producto/index');
            exit;
        }

        public function delete($id){
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($id)) {
                $this->model->delete((int)$id);
                
                flash::set('mensaje', [
                    'type' => 'success',
                    'header' => 'Eliminación Exitosa',
                    'message' => 'Producto eliminado correctamente.'
                ]);
            } else {
                flash::set('mensaje', [
                    'type' => 'danger',
                    'header' => 'Error',
                    'message' => 'Acceso no permitido o ID inválido.'
                ]);
            }

            header('Location: ' . BASE_URL . 'producto/index');
            exit;
        }
        public function show($id)
        {
            $producto = $this->model->getById($id); // o findById según tu modelo

            if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                if ($producto) {
                    echo json_encode([
                        'id' => $producto->getId(),
                        'nombre' => $producto->getNombre(),
                        'precio' => $producto->getPrecio(),
                        'stock' => $producto->getStock()
                    ]);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Producto no encontrado']);
                }
            } else {
                if (!$producto) {
                    $this->redirigirConMensaje('producto/index', 'warning', 'No encontrado', 'Producto no encontrado.');
                }
                $this->view->render('producto/show', ['producto' => $producto]);
            }
        }
    }
?>