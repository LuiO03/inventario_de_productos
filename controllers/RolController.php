<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

require_once 'models/PermisoModel.php';

class RolController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Rol');
    }

    // ==================== EXPORTAR PDF ====================
    public function exportarPdf()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ids'])) {
            $idsArray = array_filter(array_map('intval', explode(',', $_POST['ids'])));
            $roles = !empty($idsArray) ? $this->model->getByIds($idsArray) : [];
            if (empty($roles)) {
                $this->redirigirConMensaje('rol/index', 'warning', 'Nada seleccionado', 'No se seleccionaron roles.');
                return;
            }
        } else {
            $roles = $this->model->getAll();
        }

        ob_start();
        include './views/templates/pdfs/rol-pdf.php';
        $html = ob_get_clean();

        $mpdf = new Mpdf([
            'default_font' => 'dejavusans',
            'tempDir' => __DIR__ . '/../tmp'
        ]);

        $fechaHora = date('Y-m-d_H-i-s');
        $nombreArchivo = (!empty($idsArray))
            ? "roles_seleccionados_$fechaHora.pdf"
            : "roles_$fechaHora.pdf";

        $mpdf->WriteHTML($html);
        $mpdf->Output($nombreArchivo, 'I');
    }

    // ==================== EXPORTAR EXCEL ====================
    public function exportarExcel()
    {
        if (isset($_POST['ids']) && !empty($_POST['ids'])) {
            $idsArray = explode(',', $_POST['ids']);
            $roles = $this->model->getByIds($idsArray);
            $titulo = 'Lista de Roles Seleccionados';
            $nombreArchivoBase = 'roles_seleccionados';
        } else {
            $roles = $this->model->getAll();
            $titulo = 'Lista de Roles';
            $nombreArchivoBase = 'roles';
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Roles');

        // ===== TÍTULO =====
        $sheet->mergeCells('A1:E1');
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

        $sheet->getStyle('A2:F2')->getFont()->setBold(true);
        $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ===== DATOS =====
        $fila = 3;
        foreach ($roles as $rol) {
            $sheet->setCellValue("A{$fila}", $rol->getId());
            $sheet->setCellValue("B{$fila}", $rol->getNombre());
            $sheet->setCellValue("C{$fila}", $rol->getDescripcion() ?? '---');
            $sheet->setCellValue("D{$fila}", $rol->getEstado() ? 'Activo' : 'Inactivo');
            $sheet->setCellValue("E{$fila}", FechaHelper::formatoCorto($rol->getCreatedAt()));
            $sheet->setCellValue("F{$fila}", FechaHelper::formatoCorto($rol->getUpdatedAt()));
            $fila++;
        }

        foreach (range('A', 'F') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->setSelectedCell('A1');

        $fechaHora = date('Y-m-d_H-i-s');
        $nombreArchivo = "{$nombreArchivoBase}_$fechaHora.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ==================== CRUD ====================
    public function index()
    {
        $GLOBALS['pageTitle'] = 'Lista de Roles';
        $roles = $this->model->getAll();
        $this->view->render('rol/index', [
            'mensaje' => $this->view->mensaje,
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $GLOBALS['pageTitle'] = 'Agregar Rol';
        $this->view->render('rol/create', [
            'mensaje' => $this->view->mensaje,
            'old' => FlashHelper::getOld()
        ]);
    }

    public function store()
    {
        protegerContraCSRF();
        if (!$this->validarDatosRol($errores)) {
            FlashHelper::setOld($_POST);
            FlashHelper::setValidate($errores);
            header('Location: ' . BASE_URL . 'rol/create');
            exit;
        }

        $rol = new Rol(
            id: 0,
            nombre: TextoHelper::formatearNombre($_POST['nombre']),
            descripcion: $_POST['descripcion'] ?? null,
            estado: isset($_POST['estado']) && $_POST['estado'] === '1' ? 1 : 0,
            creadoPor: $_SESSION['usuario_id'] ?? null
        );

        if ($this->model->create($rol)) {
            $this->redirigirConMensaje('rol/index', 'success', 'Registro exitoso', 'Rol creado correctamente.');
        } else {
            $this->redirigirConMensaje('rol/create', 'danger', 'Error', 'No se pudo registrar el rol.');
        }
    }

    public function edit($id)
    {
        $rol = $this->model->getById((int)$id);
        if (!$rol) $this->redirigirConMensaje('rol/index', 'warning', 'No encontrado', 'Rol no encontrado.');

        $GLOBALS['pageTitle'] = 'Editar Rol';
        $this->view->render('rol/edit', [
            'rol' => $rol,
            'mensaje' => $this->view->mensaje
        ]);
    }

    public function update($id)
    {
        protegerContraCSRF();
        if (!$this->validarDatosRol($errores, true)) {
            FlashHelper::setValidate($errores);
            header('Location: ' . BASE_URL . 'rol/edit/' . urlencode($id));
            exit;
        }

        $rol = $this->model->getById((int)$id);
        if (!$rol) $this->redirigirConMensaje('rol/index', 'danger', 'No encontrado', 'El rol no existe.');

        $rol->setNombre(TextoHelper::formatearNombre($_POST['nombre']));
        $rol->setDescripcion($_POST['descripcion'] ?? null);
        $rol->setEstado(isset($_POST['estado']) && $_POST['estado'] === '1' ? 1 : 0);
        $rol->setModificadoPor($_SESSION['usuario_id'] ?? null);

        if ($this->model->update($rol)) {
            $this->redirigirConMensaje('rol/index', 'success', 'Actualizado', 'Rol actualizado correctamente.');
        } else {
            $this->redirigirConMensaje('rol/index', 'danger', 'Error', 'No se pudo actualizar.');
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($id)) {
            protegerContraCSRF();
            $this->model->delete((int)$id);
            $this->redirigirConMensaje('rol/index', 'success', 'Eliminado', 'Rol eliminado correctamente.');
        } else {
            $this->redirigirConMensaje('rol/index', 'danger', 'Error', 'Solicitud inválida.');
        }
    }

    public function toggleEstado($id)
    {
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
        ) {
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
                $this->redirigirConMensaje('rol/index', 'warning', 'Nada seleccionado', 'No se seleccionaron roles.');
            }

            $eliminados = $this->model->deleteMultiple($idsArray);
            $this->redirigirConMensaje('rol/index', 'success', 'Eliminación múltiple', "Se eliminaron correctamente $eliminados roles.");
        } else {
            $this->redirigirConMensaje('rol/index', 'danger', 'Acceso denegado', 'Solicitud no válida.');
        }
    }

    // ==================== VALIDACIONES ====================
    private function validarDatosRol(&$errores, $esEdicion = false): bool
    {
        $errores = [];
        if ($e = Validador::campoObligatorio($_POST['nombre'] ?? '', 'Nombre')) $errores[] = $e;
        if ($e = Validador::limitarTexto($_POST['descripcion'] ?? '', 'Descripción', 0, 255)) $errores[] = $e;
        if ($e = Validador::booleano($_POST['estado'] ?? null, 'Estado')) $errores[] = $e;
        return empty($errores);
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

    public function permisos($id)
    {
        $permisoModel = new PermisoModel();
        $rol = $this->model->getById((int)$id);
        if (!$rol) $this->redirigirConMensaje('rol/index', 'warning', 'No encontrado', 'Rol no encontrado.');

        $permisos = $permisoModel->getAllWithEntidad();
        $permisosAsignados = $permisoModel->getByRolId($rol->getId());

        $GLOBALS['pageTitle'] = 'Permisos del Rol';
        $this->view->render('rol/permisos', [
            'rol' => $rol,
            'permisos' => $permisos,
            'permisosAsignados' => $permisosAsignados
        ]);
    }

    public function guardarPermisos($id)
    {
        protegerContraCSRF();
        $permisoModel = new PermisoModel();
        $permisosSeleccionados = $_POST['permisos'] ?? [];
        $permisoModel->actualizarPermisosDeRol((int)$id, $permisosSeleccionados);

        $this->redirigirConMensaje(
            'rol/index',
            'success',
            'Permisos actualizados',
            'Los permisos del rol se guardaron correctamente.'
        );
    }

    public function show($id)
    {
        $rol = $this->model->getById((int)$id);
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            if ($rol) {
                echo json_encode([
                    'id' => '#' . $rol->getId(),
                    'nombre' => $rol->getNombre(),
                    'descripcion' => $rol->getDescripcion(),
                    'estado' => $rol->getEstado(),
                    'usuarios_count' => $rol->getUsuariosCount(),
                    'creado_por' => $rol->nombreCreador ?? 'Desconocido',
                    'modificado_por' => $rol->nombreModificador ?? 'Desconocido',
                    'created_at' => FechaHelper::formatoCorto($rol->getCreatedAt()),
                    'updated_at' => FechaHelper::formatoCorto($rol->getUpdatedAt())]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'No encontrado']);
            }
        } else {
            $this->view->render('rol/show', ['rol' => $rol]);
        }
    }
}
