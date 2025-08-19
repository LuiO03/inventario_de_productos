<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
class CategoriaController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Categoria');
    }

    public function exportarPdf()
    {
        // Si vienen IDs por POST, filtramos
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ids'])) {
            $idsArray = array_filter(array_map('intval', explode(',', $_POST['ids'])));
            if (!empty($idsArray)) {
                $categorias = $this->model->getByIds($idsArray);
            } else {
                $this->redirigirConMensaje('categoria/index', 'warning', 'Nada seleccionado', 'No se seleccionaron categorías para exportar.');
                return;
            }
        } else {
            // Si no hay POST o no hay IDs => exportar todo
            $categorias = $this->model->getAll();
        }

        // Generar HTML de la vista
        ob_start();
        include './views/templates/pdfs/categoria-pdf.php';
        $html = ob_get_clean();

        // Crear PDF con mPDF
        $mpdf = new Mpdf([
            'default_font' => 'dejavusans',
            'tempDir' => __DIR__ . '/../../tmp'
        ]);

        // Nombre del archivo (según si es todo o seleccionados)
        $fechaHora = date('Y-m-d_H-i-s');
        $nombreArchivo = (isset($idsArray) && !empty($idsArray))
            ? "categorias_seleccionadas_$fechaHora.pdf"
            : "categorias_$fechaHora.pdf";

        $mpdf->WriteHTML($html);
        $mpdf->Output($nombreArchivo, 'I'); // 'I' mostrar en navegador, 'D' descargar
    }

    public function exportarExcel()
    {
        // Verificar si hay IDs seleccionados
        if (isset($_POST['ids']) && !empty($_POST['ids'])) {
            $idsArray = explode(',', $_POST['ids']);
            $categorias = $this->model->getByIds($idsArray);
            $titulo = 'Lista de Categorías Seleccionadas';
            $nombreArchivoBase = 'categorias_seleccionadas';
        } else {
            $categorias = $this->model->getAll();
            $titulo = 'Lista de Categorías';
            $nombreArchivoBase = 'categorias';
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Categorías');

        // ===== TÍTULO =====
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', $titulo);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ===== ENCABEZADOS =====
        $encabezados = ['ID', 'Nombre', 'Descripción', 'Estado', 'Creado', 'Actualizado'];
        $col = 'A';
        foreach ($encabezados as $header) {
            $sheet->setCellValue($col . '2', $header);
            $col++;
        }

        // Estilo negrita y centrado para encabezados
        $sheet->getStyle('A2:F2')->getFont()->setBold(true);
        $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ===== LLENAR DATOS =====
        $fila = 3;
        foreach ($categorias as $categoria) {
            $sheet->setCellValue("A{$fila}", $categoria->getId());
            $sheet->setCellValue("B{$fila}", $categoria->getNombre());
            $sheet->setCellValue("C{$fila}", $categoria->getDescripcion());
            $sheet->setCellValue("D{$fila}", $categoria->getEstado() ? 'Activo' : 'Inactivo');
            $sheet->setCellValue("E{$fila}", FechaHelper::formatoCorto($categoria->getCreatedAt()));
            $sheet->setCellValue("F{$fila}", FechaHelper::formatoCorto($categoria->getUpdatedAt()));
            $fila++;
        }

        // Autoajustar columnas
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Descargar archivo
        $fechaHora = date('Y-m-d_H-i-s');
        $nombreArchivo = "{$nombreArchivoBase}_$fechaHora.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Validaciones de datos de formulario
    private function validarDatosCategoria(&$nombre, &$descripcion, &$estado, &$parentId, &$errores): bool
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado = isset($_POST['estado']) && $_POST['estado'] === '1' ? 1 : 0;
        $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

        $errores = [];

        if ($e = Validador::campoObligatorio($nombre, 'Nombre')) $errores[] = $e;
        if ($e = Validador::limitarTexto($nombre, 'Nombre', 3, 50)) $errores[] = $e;
        if ($e = Validador::limitarTexto($descripcion, 'Descripción', 0, 255)) $errores[] = $e;
        if ($e = Validador::noSoloNumeros($descripcion, 'Descripción')) $errores[] = $e;
        if ($e = Validador::booleano($estado, 'Estado')) $errores[] = $e;
        if ($e = Validador::noSoloNumeros($nombre, 'Nombre')) $errores[] = $e;
        if ($e = Validador::imagen($_FILES['imagen'] ?? [])) $errores[] = $e;

        // Validar que parent_id no sea igual a sí mismo o inválido (opcional)
        if ($parentId !== null && !is_numeric($parentId)) $errores[] = "La categoría padre es inválida.";

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
        $GLOBALS['pageTitle'] = 'Lista de Categorías';
        $categorias = $this->model->getAll();

        // Añadir imagen_url verificada a cada objeto categoría
        foreach ($categorias as $cat) {
            $cat->imagen_url = verificarImagen('images/categorias', $cat->getImagen());
        }

        $this->view->render('categoria/index', [
            'mensaje' => $this->view->mensaje,
            'categorias' => $categorias
        ]);
    }

    public function arbol()
    {
        $GLOBALS['pageTitle'] = 'Arbol de Categorías';
        $categorias = $this->model->getAll(); // Asegúrate de traer: id, nombre, parent_id, estado
        $agrupadas = [];

        foreach ($categorias as $cat) {
            $agrupadas[$cat->getParentId()][] = $cat;
        }

        // Pasar a la vista
        $this->view->render('categoria/arbol', ['agrupadas' => $agrupadas]);
    }

    public function create($parentSlugOrId = null)
    {
        $GLOBALS['pageTitle'] = 'Agregar Categoría';
        $categorias = $this->model->getAll(); // obtener todas para el select

        $old = FlashHelper::getOld();
        $parentId = $old['parent_id'] ?? null;
        $nombrePadre = '';

        if ($parentSlugOrId) {
            $categoriaPadre = $this->obtenerCategoriaPorIdOSlug($parentSlugOrId);
            if ($categoriaPadre) {
                $parentId = $categoriaPadre->getId();
                $nombrePadre = $categoriaPadre->getNombre();
            }
        }
        $nombrePadre = '';

        if ($parentId) {
            $categoriaPadre = $this->model->getById((int)$parentId);
            if ($categoriaPadre) {
                $nombrePadre = $categoriaPadre->getNombre();
            }
        }

        $this->view->render('categoria/create', [
            'mensaje' => $this->view->mensaje,
            'old' => $old,
            'categorias' => $categorias,
            'parentId' => $parentId,
            'nombrePadre' => $nombrePadre
        ]);
    }

    public function store()
    {
        protegerContraCSRF();

        if (!$this->validarDatosCategoria($nombre, $descripcion, $estado, $parentId, $errores)) {
            FlashHelper::setOld($_POST);
            FlashHelper::setValidate($errores);
            header('Location: ' . BASE_URL . 'categoria/create');
            exit;
        }

        // Formatear antes de guardar
        $nombre = TextoHelper::formatearNombre($nombre);
        $descripcion = TextoHelper::primeraLetraMayuscula($descripcion);

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
            id: 0,
            nombre: $nombre,
            descripcion: $descripcion,
            estado: $estado,
            imagen: $nombreArchivo,
            slug: $slug,
            creadoPor: isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null,
            modificadoPor: null
        );
        $categoria->setSlug($slug);
        $categoria->setParentId($parentId);

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

        if (!verificarImagen('images/categorias', $categoria->getImagen())) {
            $categoria->setImagen(null);
        }

        $categorias = $this->model->getAllExcept($slugOrId); // Para el select
        $subcategorias = $this->model->getByParentId($categoria->getId()); // 👈 OBTENER SUBCATEGORÍAS

        $GLOBALS['pageTitle'] = 'Editar Categoría';
        $this->view->render('categoria/edit', [
            'categoria' => $categoria,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias, // 👈 PASAR A LA VISTA
            'mensaje' => $this->view->mensaje
        ]);
    }
    public function update($slugOrId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigirConMensaje('categoria/index', 'danger', 'Acceso denegado', 'Solicitud inválida.');
        }

        protegerContraCSRF();

        if (!$this->validarDatosCategoria($nombre, $descripcion, $estado, $parentId, $errores)) {
            FlashHelper::setValidate($errores);
            header('Location: ' . BASE_URL . 'categoria/edit/' . urlencode($slugOrId));
            exit;
        }

        $nombre = TextoHelper::formatearNombre($nombre);
        $descripcion = TextoHelper::primeraLetraMayuscula($descripcion);

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
        $categoria->setParentId($parentId);
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
                $subcategorias = $this->model->getByParentId($id);
                $categoriaPadre = $categoria->getParentId() ? $this->model->getById($categoria->getParentId()) : null;

                echo json_encode([
                    'id' => '#'. $categoria->getId(),
                    'nombre' => $categoria->getNombre(),
                    'descripcion' => $categoria->getDescripcion(),
                    'estado' => $categoria->getEstado(),
                    'imagen' => $categoria->getImagen(),
                    'imagen_url' => verificarImagen('images/categorias', $categoria->getImagen()),
                    'creado_por' => $categoria->nombreCreador ?? 'Desconocido',
                    'modificado_por' => $categoria->nombreModificador ?? 'Desconocido',
                    'created_at' => FechaHelper::formatoCorto($categoria->getCreatedAt()),
                    'updated_at' => FechaHelper::formatoCorto($categoria->getUpdatedAt()),
                    'categoria_padre' => $categoriaPadre ? [
                        'id' => $categoriaPadre->getId(),
                        'nombre' => $categoriaPadre->getNombre(),
                        'estado' => $categoriaPadre->getEstado()
                    ] : null,
                    'subcategorias' => array_map(fn($sub) => [
                        'id' => $sub->getId(),
                        'nombre' => $sub->getNombre(),
                        'estado' => $sub->getEstado()
                    ], $subcategorias)
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
            $usuarioId = $_SESSION['usuario_id'] ?? null;

            if ($nuevoEstado !== null && $usuarioId) {
                $actualizado = $this->model->actualizarEstado($id, $nuevoEstado, $usuarioId);
                echo json_encode(['success' => $actualizado]);
                exit;
            }
        }

        echo json_encode(['success' => false]);
        exit;
    }

    public function deleteMultiple()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            protegerContraCSRF();

            $ids = $_POST['ids'] ?? '';
            $idsArray = array_filter(array_map('intval', explode(',', $ids)));

            if (empty($idsArray)) {
                $this->redirigirConMensaje('categoria/index', 'warning', 'Nada seleccionado', 'No se seleccionaron categorias para eliminar.');
            }

            // Eliminar imágenes asociadas
            foreach ($idsArray as $id) {
                $categoria = $this->model->getById($id);
                if ($categoria && $categoria->getImagen()) {
                    $ruta = 'public/images/categorias/' . $categoria->getImagen();
                    if (file_exists($ruta)) unlink($ruta);
                }
            }

            $eliminadas = $this->model->deleteMultiple($idsArray);

            $this->redirigirConMensaje('categoria/index', 'success', 'Eliminación múltiple', "Se eliminaron correctamente $eliminadas categorias.");
        } else {
            $this->redirigirConMensaje('categoria/index', 'danger', 'Acceso denegado', 'Solicitud no válida.');
        }
    }
}
