<?php
class CategoriaController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Categoria');
    }

    // Validaciones de datos de formulario
    private function validarDatosCategoria(&$nombre, &$descripcion, &$estado, &$errores): bool
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado = isset($_POST['estado']) && $_POST['estado'] === '1' ? 1 : 0;

        $errores = [];

        if ($e = Validador::campoObligatorio($nombre, 'Nombre')) $errores[] = $e;
        if ($e = Validador::texto($nombre, 'Nombre', 3, 50)) $errores[] = $e;
        if ($e = Validador::texto($descripcion, 'Descripción', 0, 255)) $errores[] = $e;
        if ($e = Validador::booleano($estado, 'Estado')) $errores[] = $e;
        if ($e = Validador::imagen($_FILES['imagen'] ?? [])) $errores[] = $e;

        return empty($errores);
    }

    private function obtenerCategoriaPorIdOSlug(string $valor): ?Categoria
    {
        if (ctype_digit($valor)) {
            return $this->model->getById((int)$valor);
        } else {
            return $this->model->findBySlug($valor);
        }
    }
    private function redirigirConMensaje($ruta, $tipo, $header, $mensaje)
    {
        Flash::set('mensaje', [
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

        if (!$this->validarDatosCategoria($nombre, $descripcion, $estado, $errores)) {
            Flash::setValidate($errores);
            header('Location: ' . BASE_URL . 'categoria/create');
            exit;
        }

        if ($this->model->findByNombre($nombre)) {
            $this->redirigirConMensaje('categoria/create', 'warning', 'Duplicado', 'Ya existe una categoría con el nombre <strong>"' . htmlspecialchars($nombre) . '"</strong>.');
        }

        // Generar slug
        $slug = generarSlug($nombre);
        if ($this->model->findBySlug($slug)) {
            $slug .= '-' . uniqid();
        }

        // Imagen
        $nombreArchivo = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('cat_') . '.' . strtolower($extension);

            $rutaCarpeta = 'public/images/categorias/';
            if (!file_exists($rutaCarpeta)) mkdir($rutaCarpeta, 0777, true);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCarpeta . $nombreArchivo);
        }

        $categoria = new Categoria(
            0,
            $nombre,
            $descripcion,
            $estado,
            $nombreArchivo,
            isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null,
            null,
            $slug
        );
        $categoria->setSlug($slug);

        if ($this->model->create($categoria)) {
            $this->redirigirConMensaje('categoria/index', 'success', 'Registro exitoso', 'Categoría <strong>"' . htmlspecialchars($nombre) . '"</strong> creada correctamente.');
        } else {
            $this->redirigirConMensaje('categoria/create', 'danger', 'Error', 'No se pudo registrar la categoría.');
        }
    }

    public function edit($slugOrId)
    {
        $categoria = $this->obtenerCategoriaPorIdOSlug($slugOrId);
        if (!$categoria) {
            $this->redirigirConMensaje('categoria/index', 'warning', 'No encontrada', 'Categoría no encontrada.');
        }

        if ($categoria->getImagen() && !file_exists('public/images/categorias/' . $categoria->getImagen())) {
            $categoria->setImagen(null);
        }

        $GLOBALS['pageTitle'] = 'Editar Categoría';
        $this->view->render('categoria/edit', [
            'categoria' => $categoria,
            'mensaje' => $this->view->mensaje
        ]);
    }


    public function update($slugOrId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigirConMensaje('categoria/index', 'danger', 'Acceso denegado', 'Solicitud inválida.');
        }

        protegerContraCSRF();

        if (!$this->validarDatosCategoria($nombre, $descripcion, $estado, $errores)) {
            Flash::setValidate($errores);
            header('Location: ' . BASE_URL . 'categoria/edit/' . urlencode($slugOrId));
            exit;
        }

        $categoria = $this->obtenerCategoriaPorIdOSlug($slugOrId);
        if (!$categoria) {
            $this->redirigirConMensaje('categoria/index', 'danger', 'No encontrada', 'La categoría no existe.');
        }

        // Validar duplicados
        $existe = $this->model->findByNombre($nombre);
        if ($existe && $existe->getId() != $categoria->getId()) {
            $this->redirigirConMensaje('categoria/edit/' . urlencode($slugOrId), 'warning', 'Duplicado', 'El nombre <strong>"' . htmlspecialchars($nombre) . '"</strong> ya existe.');
        }

        $categoria->setNombre($nombre);
        $categoria->setDescripcion($descripcion);
        $categoria->setEstado($estado);
        $categoria->setModificadoPor(isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null);

        // Slug actualizado si cambió el nombre
        $nuevoSlug = generarSlug($nombre);
        $existeSlug = $this->model->findBySlug($nuevoSlug);
        if ($existeSlug && $existeSlug->getId() !== $categoria->getId()) {
            $nuevoSlug .= '-' . uniqid();
        }
        $categoria->setSlug($nuevoSlug);

        // Procesar imagen (sin cambios)
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoArchivo = uniqid('cat_') . '.' . strtolower($extension);
            $ruta = 'public/images/categorias/';
            if (!file_exists($ruta)) mkdir($ruta, 0777, true);

            move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta . $nuevoArchivo);

            if ($categoria->getImagen()) {
                $anterior = $ruta . $categoria->getImagen();
                if (file_exists($anterior)) unlink($anterior);
            }

            $categoria->setImagen($nuevoArchivo);
        }

        if ($this->model->update($categoria)) {
            $this->redirigirConMensaje('categoria/index', 'success', 'Actualizado', 'Categoría <strong>"' . htmlspecialchars($nombre) . '"</strong> actualizada correctamente.');
        } else {
            $this->redirigirConMensaje('categoria/index', 'danger', 'Error', 'No se pudo actualizar.');
        }
    }


    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($id)) {
            protegerContraCSRF();
            $this->loadModel('Producto', 'productoModel');

            $cantidad = $this->productoModel->contarPorCategoria((int)$id);

            if ($cantidad > 0) {
                $this->redirigirConMensaje('categoria/index', 'warning', 'No se puede eliminar', "Tiene $cantidad producto(s) asociado(s).");
            } else {
                $categoria = $this->model->getById($id);
                if ($categoria && $categoria->getImagen()) {
                    $ruta = 'public/images/categorias/' . $categoria->getImagen();
                    if (file_exists($ruta)) unlink($ruta);
                }

                $this->model->delete((int)$id);
                $this->redirigirConMensaje('categoria/index', 'success', 'Eliminada', 'Categoría eliminada correctamente.');
            }
        } else {
            $this->redirigirConMensaje('categoria/index', 'danger', 'Error', 'Solicitud inválida.');
        }
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
                echo json_encode(['error' => 'No encontrada']);
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
