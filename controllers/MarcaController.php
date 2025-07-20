<?php
class MarcaController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Marca');
    }

    private function validarDatosMarca(&$nombre, &$descripcion, &$estado, &$errores): bool
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado = isset($_POST['estado']) && $_POST['estado'] === '1' ? 1 : 0;

        $errores = [];

        if ($e = Validador::campoObligatorio($nombre, 'Nombre')) $errores[] = $e;
        if ($e = Validador::limitarTexto($nombre, 'Nombre', 3, 50)) $errores[] = $e;
        if ($e = Validador::limitarTexto($descripcion, 'Descripción', 0, 255)) $errores[] = $e;
        if ($e = Validador::noSoloNumeros($descripcion, 'Descripción')) $errores[] = $e;
        if ($e = Validador::booleano($estado, 'Estado')) $errores[] = $e;
        if ($e = Validador::noSoloNumeros($nombre, 'Nombre')) $errores[] = $e;
        if ($e = Validador::imagen($_FILES['imagen'] ?? [])) $errores[] = $e;

        return empty($errores);
    }

    private function obtenerMarcaPorIdOSlug(string $valor): ?Marca
    {
        return ctype_digit($valor) ? $this->model->getById((int)$valor) : $this->model->findBySlug($valor);
    }

    private function redirigirConMensaje($ruta, $tipo, $header, $mensaje)
    {
        FlashHelper::set('mensaje', [
            'type' => $tipo,
            'header' => $header,
            'message' => $mensaje
        ]);
        header('Location: ' . BASE_URL . $ruta);
        exit;
    }

    public function index()
    {
        $GLOBALS['pageTitle'] = 'Lista de Marcas';
        $marcas = $this->model->getAll();
        $this->view->render('marca/index', [
            'mensaje' => $this->view->mensaje,
            'marcas' => $marcas
        ]);
    }

    public function create()
    {
        $GLOBALS['pageTitle'] = 'Agregar Marca';
        $this->view->render('marca/create', [
            'mensaje' => $this->view->mensaje,
            'old' => FlashHelper::getOld()
        ]);
    }

    public function store()
    {
        protegerContraCSRF();

        if (!$this->validarDatosMarca($nombre, $descripcion, $estado, $errores)) {
            FlashHelper::setOld($_POST);
            FlashHelper::setValidate($errores);
            header('Location: ' . BASE_URL . 'marca/create');
            exit;
        }

        $nombre = TextoHelper::formatearNombre($nombre);
        $descripcion = TextoHelper::primeraLetraMayuscula($descripcion);

        if ($this->model->findByNombre($nombre)) {
            $this->redirigirConMensaje('marca/create', 'warning', 'Duplicado', 'Ya existe una marca con el nombre <strong>"' . htmlspecialchars($nombre) . '"</strong>.');
        }

        $slug = generarSlug($nombre);
        if ($this->model->findBySlug($slug)) {
            $slug .= '-' . uniqid();
        }

        $nombreArchivo = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('marca_') . '.' . strtolower($extension);
            $rutaCarpeta = 'public/images/marcas/';
            if (!file_exists($rutaCarpeta)) mkdir($rutaCarpeta, 0777, true);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCarpeta . $nombreArchivo);
        }

        $marca = new Marca(
            id: 0,
            nombre: $nombre,
            descripcion: $descripcion,
            estado: $estado,
            imagen: $nombreArchivo,
            slug: $slug,
            creadoPor: $_SESSION['usuario_id'] ?? null
        );

        if ($this->model->create($marca)) {
            $this->redirigirConMensaje('marca/index', 'success', 'Registro exitoso', 'Marca <strong>"' . htmlspecialchars($nombre) . '"</strong> creada correctamente.');
        } else {
            $this->redirigirConMensaje('marca/create', 'danger', 'Error', 'No se pudo registrar la marca.');
        }
    }

    public function edit($slugOrId)
    {
        $marca = $this->obtenerMarcaPorIdOSlug($slugOrId);
        if (!$marca) {
            $this->redirigirConMensaje('marca/index', 'warning', 'No encontrada', 'Marca no encontrada.');
        }

        if ($marca->getImagen() && !file_exists('public/images/marcas/' . $marca->getImagen())) {
            $marca->setImagen(null);
        }

        $GLOBALS['pageTitle'] = 'Editar Marca';
        $this->view->render('marca/edit', [
            'marca' => $marca,
            'mensaje' => $this->view->mensaje
        ]);
    }

    public function update($slugOrId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigirConMensaje('marca/index', 'danger', 'Acceso denegado', 'Solicitud inválida.');
        }

        protegerContraCSRF();

        if (!$this->validarDatosMarca($nombre, $descripcion, $estado, $errores)) {
            FlashHelper::setValidate($errores);
            header('Location: ' . BASE_URL . 'marca/edit/' . urlencode($slugOrId));
            exit;
        }

        $nombre = TextoHelper::formatearNombre($nombre);
        $descripcion = TextoHelper::primeraLetraMayuscula($descripcion);

        $marca = $this->obtenerMarcaPorIdOSlug($slugOrId);
        if (!$marca) {
            $this->redirigirConMensaje('marca/index', 'danger', 'No encontrada', 'La marca no existe.');
        }

        $existe = $this->model->findByNombre($nombre);
        if ($existe && $existe->getId() != $marca->getId()) {
            $this->redirigirConMensaje('marca/edit/' . urlencode($slugOrId), 'warning', 'Duplicado', 'El nombre <strong>"' . htmlspecialchars($nombre) . '"</strong> ya existe.');
        }

        $marca->setNombre($nombre);
        $marca->setDescripcion($descripcion);
        $marca->setEstado($estado);
        $marca->setModificadoPor($_SESSION['usuario_id'] ?? null);

        $nuevoSlug = generarSlug($nombre);
        if ($this->model->findBySlug($nuevoSlug) && $nuevoSlug != $marca->getSlug()) {
            $nuevoSlug .= '-' . uniqid();
        }
        $marca->setSlug($nuevoSlug);

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoArchivo = uniqid('marca_') . '.' . strtolower($extension);
            $ruta = 'public/images/marcas/';
            if (!file_exists($ruta)) mkdir($ruta, 0777, true);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta . $nuevoArchivo);

            if ($marca->getImagen() && file_exists($ruta . $marca->getImagen())) {
                unlink($ruta . $marca->getImagen());
            }

            $marca->setImagen($nuevoArchivo);
        }

        if ($this->model->update($marca)) {
            $this->redirigirConMensaje('marca/index', 'success', 'Actualizado', 'Marca  <strong>"' . htmlspecialchars($nombre) . '"</strong> actualizada correctamente.');
        } else {
            $this->redirigirConMensaje('marca/index', 'danger', 'Error', 'No se pudo actualizar.');
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($id)) {
            protegerContraCSRF();

            $marca = $this->model->getById($id);
            if ($marca && $marca->getImagen()) {
                $ruta = 'public/images/marcas/' . $marca->getImagen();
                if (file_exists($ruta)) unlink($ruta);
            }

            $this->model->delete((int)$id);
            $this->redirigirConMensaje('marca/index', 'success', 'Eliminada', 'Marca eliminada correctamente.');
        } else {
            $this->redirigirConMensaje('marca/index', 'danger', 'Error', 'Solicitud inválida.');
        }
    }

    public function show($id)
    {
        $marca = $this->model->getById($id);
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            if ($marca) {
                echo json_encode([
                    'id' => $marca->getId(),
                    'nombre' => $marca->getNombre(),
                    'descripcion' => $marca->getDescripcion(),
                    'estado' => $marca->getEstado(),
                    'imagen' => $marca->getImagen(),
                    'creado_por' => $marca->nombreCreador ?? 'Desconocido',
                    'modificado_por' => $marca->nombreModificador ?? 'Desconocido',
                    'created_at' => FechaHelper::formatoCorto($marca->getCreatedAt()),
                    'updated_at' => FechaHelper::formatoCorto($marca->getUpdatedAt())
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'No encontrada']);
            }
        } else {
            $this->view->render('marca/show', ['marca' => $marca]);
        }
    }

    public function toggleEstado($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
            $data = json_decode(file_get_contents('php://input'), true);
            $estado = isset($data['estado']) ? (int)$data['estado'] : null;
            $usuario = $_SESSION['usuario_id'] ?? null;
            if ($estado !== null && $usuario) {
                echo json_encode(['success' => $this->model->actualizarEstado($id, $estado, $usuario)]);
                exit;
            }
        }
        echo json_encode(['success' => false]);
        exit;
    }
}
