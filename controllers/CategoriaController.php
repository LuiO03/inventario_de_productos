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
        $estado = isset($_POST['estado']) ? true : false;

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

        // Validar si ya existe
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

        $categoria = new Categoria(
            0,
            $nombre,
            $descripcion,
            $estado,
            $_SESSION['usuario_id'] ?? null // creado_por
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

        // Cargar modelo de productos sin reemplazar el modelo actual
        $this->loadModel('Producto', 'productoModel');

        // Contar productos relacionados
        $cantidad = $this->productoModel->contarPorCategoria((int)$id);

        if ($cantidad > 0) {
            flash::set('mensaje', [
                'type' => 'warning',
                'header' => 'No se puede eliminar',
                'message' => "No puedes eliminar esta categoría porque tiene <strong>$cantidad producto(s)</strong> asociado(s)."
            ]);
        } else {
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
        $categoria = $this->model->getById($id); // Devuelve objeto Categoria

        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            if ($categoria) {
                echo json_encode([
                    'id' => $categoria->getId(),
                    'nombre' => $categoria->getNombre(),
                    'descripcion' => $categoria->getDescripcion(),
                    'estado' => $categoria->getEstado(),
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
            // Renderizar vista normal si no es petición AJAX
            $this->view->render('categoria/show', ['categoria' => $categoria]);
        }
    }
}
