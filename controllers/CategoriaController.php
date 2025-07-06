<?php
class CategoriaController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Categoria');
    }

    private function validarDatosCategoria(&$nombre, &$descripcion, &$estado): bool
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado = isset($_POST['estado']) && $_POST['estado'] === '1' ? 1 : 0;

        return !empty($nombre);
    }

    private function redirigirConMensaje($ruta, $tipo, $header, $mensaje)
    {
        flash::set('mensaje', [
            'type' => $tipo,
            'header' => $header,
            'message' => $mensaje
        ]);
        header('Location: ' . BASE_URL . $ruta);
        exit;
    }

    public function index()
    {
        $GLOBALS['pageTitle'] = 'Lista de Categorías';
        $categorias = $this->model->getAll();

        $this->view->render('categoria/index', [
            'mensaje' => $this->view->mensaje,
            'categorias' => $categorias
        ]);
    }

    public function create()
    {
        $GLOBALS['pageTitle'] = 'Agregar Categoría';
        $this->view->render('categoria/create', [
            'mensaje' => $this->view->mensaje
        ]);
    }

    public function store()
    {
        protegerContraCSRF();
        if (!$this->validarDatosCategoria($nombre, $descripcion, $estado)) {
            $this->redirigirConMensaje('categoria/create', 'danger', 'Atención', 'Por favor, completa todos los campos obligatorios.');
        }

        $existe = $this->model->findByNombre($nombre);
        if ($existe) {
            flash::set('mensaje', [
                'type' => 'warning',
                'header' => 'Categoría existente',
                'message' => 'La categoría <strong>"' . htmlspecialchars($nombre) . '"</strong> ya está registrada.'
            ]);
            header('Location: ' . BASE_URL . 'categoria/create');
            exit;
        }

        // Procesar imagen si se subió
        $nombreArchivo = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreOriginal = basename($_FILES['imagen']['name']);
            $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('cat_', true) . '.' . strtolower($extension);

            $rutaCarpeta = 'public/images/categorias/';
            if (!file_exists($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0777, true);
            }

            $rutaDestino = $rutaCarpeta . $nombreArchivo;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
        }

        $categoria = new Categoria(
            0,
            $nombre,
            $descripcion,
            $estado,
            $_SESSION['usuario_id'] ?? null,
            null,
            null,
            null,
            $nombreArchivo // Imagen
        );

        $id = $this->model->create($categoria);
        if ($id) {
            flash::set('mensaje', [
                'type' => 'success',
                'header' => 'Categoría creada',
                'message' => 'La categoría <strong>"' . htmlspecialchars($nombre) . '"</strong> se ha creado correctamente.'
            ]);
        } else {
            flash::set('mensaje', [
                'type' => 'danger',
                'header' => 'Error',
                'message' => 'Error al crear la categoría.'
            ]);
        }

        header('Location: ' . BASE_URL . 'categoria/index');
        exit;
    }


    public function edit($id)
    {
        if (!is_numeric($id)) {
            $this->redirigirConMensaje('categoria/index', 'danger', 'ID inválido', 'El ID proporcionado no es válido.');
        }

        $categoria = $this->model->getById($id);
        if (!$categoria) {
            $this->redirigirConMensaje('categoria/index', 'warning', 'No encontrada', 'Categoría no encontrada.');
        }

        // ✅ Verificar existencia física de la imagen
        if ($categoria && $categoria->getImagen()) {
            $ruta = 'public/images/categorias/' . $categoria->getImagen();
            if (!file_exists($ruta)) {
                $categoria->setImagen(null);
            }
        }

        $GLOBALS['pageTitle'] = 'Editar Categoría ' . $id;
        $this->view->render('categoria/edit', [
            'categoria' => $categoria,
            'mensaje' => $this->view->mensaje
        ]);
    }


    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !is_numeric($id)) {
            $this->redirigirConMensaje('categoria/index', 'danger', 'Acceso denegado', 'Solicitud inválida o ID incorrecto.');
        }

        protegerContraCSRF();

        if (!$this->validarDatosCategoria($nombre, $descripcion, $estado)) {
            $this->redirigirConMensaje('categoria/edit/' . $id, 'danger', 'Atención', 'Completa todos los campos correctamente.');
        }

        $existe = $this->model->findByNombre($nombre);
        if ($existe && $existe->getId() != $id) {
            $this->redirigirConMensaje('categoria/edit/' . $id, 'warning', 'Duplicado', 'Ya existe otra categoría con el nombre <strong>' . htmlspecialchars($nombre) . '</strong>.');
        }

        $categoria = $this->model->getById($id);
        if (!$categoria) {
            $this->redirigirConMensaje('categoria/index', 'danger', 'Error', 'La categoría no existe.');
        }

        $categoria->setNombre($nombre);
        $categoria->setDescripcion($descripcion);
        $categoria->setEstado($estado);
        $categoria->setModificadoPor($_SESSION['usuario_id'] ?? null);

        // Procesar imagen nueva si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreOriginal = basename($_FILES['imagen']['name']);
            $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('cat_', true) . '.' . strtolower($extension);

            $rutaCarpeta = 'public/images/categorias/';
            if (!file_exists($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0777, true);
            }

            $rutaDestino = $rutaCarpeta . $nombreArchivo;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);

            // Eliminar imagen anterior si existe
            if ($categoria->getImagen()) {
                $anterior = $rutaCarpeta . $categoria->getImagen();
                if (file_exists($anterior)) {
                    unlink($anterior);
                }
            }

            $categoria->setImagen($nombreArchivo);
        }

        $actualizado = $this->model->update($categoria);

        if ($actualizado) {
            flash::set('mensaje', [
                'type' => 'success',
                'header' => 'Actualización exitosa',
                'message' => 'La categoría <strong>"' . htmlspecialchars($nombre) . '"</strong> se ha actualizado correctamente.'
            ]);
        } else {
            flash::set('mensaje', [
                'type' => 'danger',
                'header' => 'Error',
                'message' => 'No se pudo actualizar la categoría.'
            ]);
        }

        header('Location: ' . BASE_URL . 'categoria/index');
        exit;
    }


    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($id)) {
            protegerContraCSRF();

            $this->loadModel('Producto', 'productoModel');

            $cantidad = $this->productoModel->contarPorCategoria((int)$id);

            if ($cantidad > 0) {
                flash::set('mensaje', [
                    'type' => 'warning',
                    'header' => 'No se puede eliminar',
                    'message' => "No puedes eliminar esta categoría porque tiene <strong>$cantidad producto(s)</strong> asociado(s)."
                ]);
            } else {
                $categoria = $this->model->getById($id);
                if ($categoria && $categoria->getImagen()) {
                    $ruta = 'public/images/categorias/' . $categoria->getImagen();
                    if (file_exists($ruta)) unlink($ruta); // Elimina la imagen del servidor
                }

                $this->model->delete((int)$id);
                flash::set('mensaje', [
                    'type' => 'success',
                    'header' => 'Eliminación exitosa',
                    'message' => 'Categoría eliminada correctamente.'
                ]);
            }
        } else {
            flash::set('mensaje', [
                'type' => 'danger',
                'header' => 'Error',
                'message' => 'Acceso no permitido o ID inválido.'
            ]);
        }

        header('Location: ' . BASE_URL . 'categoria/index');
        exit;
    }

    public function show($id)
    {
        $categoria = $this->model->getById($id);

        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            if ($categoria) {
                echo json_encode([
                    'id' => $categoria->getId(),
                    'nombre' => $categoria->getNombre(),
                    'descripcion' => $categoria->getDescripcion(),
                    'estado' => $categoria->getEstado(),
                    'imagen' => $categoria->getImagen(),
                    'creado_por' => $categoria->nombreCreador ?? 'Desconocido',
                    'modificado_por' => $categoria->nombreModificador ?? 'Desconocido',
                    'created_at' => FechaHelper::formatoCorto($categoria->getCreatedAt()),
                    'updated_at' => FechaHelper::formatoCorto($categoria->getUpdatedAt())
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Categoría no encontrada']);
            }
        } else {
            $this->view->render('categoria/show', ['categoria' => $categoria]);
        }
    }

    public function toggleEstado($id)
    {
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            $data = json_decode(file_get_contents('php://input'), true);

            $nuevoEstado = isset($data['estado']) ? (int)$data['estado'] : null;

            if ($nuevoEstado !== null) {
                $this->loadModel('Categoria');
                $actualizado = $this->model->actualizarEstado($id, $nuevoEstado);

                echo json_encode(['success' => $actualizado]);
                exit;
            }
        }

        echo json_encode(['success' => false]);
        exit;
    }
}
