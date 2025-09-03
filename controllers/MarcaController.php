<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
class MarcaController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Marca');
    }

    public function exportarPdf()
    {
        // Si vienen IDs por POST, filtramos
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ids'])) {
            $idsArray = array_filter(array_map('intval', explode(',', $_POST['ids'])));
            if (!empty($idsArray)) {
                $marcas = $this->model->getByIds($idsArray);
            } else {
                $this->redirigirConMensaje('marca/index', 'warning', 'Nada seleccionado', 'No se seleccionaron marcas para exportar.');
                return;
            }
        } else {
            // Si no hay POST o no hay IDs => exportar todo
            $marcas = $this->model->getAll();
        }

        // Generar HTML de la vista
        ob_start();
        include './views/templates/pdfs/marca-pdf.php';
        $html = ob_get_clean();

        // Crear PDF con mPDF
        $mpdf = new Mpdf([
            'default_font' => 'dejavusans',
            'tempDir' => __DIR__ . '/../tmp'
        ]);

        // Nombre del archivo (según si es todo o seleccionados)
        $fechaHora = date('Y-m-d_H-i-s');
        $nombreArchivo = (isset($idsArray) && !empty($idsArray))
            ? "marcas_seleccionadas_$fechaHora.pdf"
            : "marcas_$fechaHora.pdf";

        $mpdf->WriteHTML($html);
        $mpdf->Output($nombreArchivo, 'I'); // 'I' mostrar en navegador, 'D' descargar
    }

    public function exportarExcel()
    {
        // Verificar si hay IDs seleccionados
        if (isset($_POST['ids']) && !empty($_POST['ids'])) {
            $idsArray = explode(',', $_POST['ids']);
            $marcas = $this->model->getByIds($idsArray);
            $titulo = 'Lista de Marcas Seleccionadas';
            $nombreArchivoBase = 'marcas_seleccionadas';
        } else {
            $marcas = $this->model->getAll();
            $titulo = 'Lista de Marcas';
            $nombreArchivoBase = 'marcas';
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Marcas');

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
        foreach ($marcas as $marca) {
            $sheet->setCellValue("A{$fila}", $marca->getId());
            $sheet->setCellValue("B{$fila}", $marca->getNombre());
            $sheet->setCellValue("C{$fila}", $marca->getDescripcion());
            $sheet->setCellValue("D{$fila}", $marca->getEstado() ? 'Activo' : 'Inactivo');
            $sheet->setCellValue("E{$fila}", FechaHelper::formatoCorto($marca->getCreatedAt()));
            $sheet->setCellValue("F{$fila}", FechaHelper::formatoCorto($marca->getUpdatedAt()));
            $fila++;
        }

        // Autoajustar columnas
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        // Establecer celda activa
        $sheet->setSelectedCell('A1');
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
        // Añadir imagen_url verificada a cada objeto marca
        foreach ($marcas as $marca) {
            $marca->imagen_url = verificarImagen('images/marcas', $marca->getImagen());
        }
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

        if (!verificarImagen('images/marcas', $marca->getImagen())) {
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
                    'id' => '#'. $marca->getId(),
                    'slug' => $marca->getSlug(),
                    'nombre' => $marca->getNombre(),
                    'descripcion' => $marca->getDescripcion(),
                    'estado' => $marca->getEstado(),
                    'imagen' => $marca->getImagen(),
                    'imagen_url' => verificarImagen('images/marcas', $marca->getImagen()),
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
    public function deleteMultiple()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            protegerContraCSRF();

            $ids = $_POST['ids'] ?? '';
            $idsArray = array_filter(array_map('intval', explode(',', $ids)));

            if (empty($idsArray)) {
                $this->redirigirConMensaje('marca/index', 'warning', 'Nada seleccionado', 'No se seleccionaron marcas para eliminar.');
            }

            // Eliminar imágenes asociadas
            foreach ($idsArray as $id) {
                $marca = $this->model->getById($id);
                if ($marca && $marca->getImagen()) {
                    $ruta = 'public/images/marcas/' . $marca->getImagen();
                    if (file_exists($ruta)) unlink($ruta);
                }
            }

            $eliminadas = $this->model->deleteMultiple($idsArray);

            $this->redirigirConMensaje('marca/index', 'success', 'Eliminación múltiple', "Se eliminaron correctamente $eliminadas marcas.");
        } else {
            $this->redirigirConMensaje('marca/index', 'danger', 'Acceso denegado', 'Solicitud no válida.');
        }
    }
}
