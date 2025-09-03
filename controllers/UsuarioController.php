<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
require_once 'models/RolModel.php';
class UsuarioController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Usuario');
    }

    // ==================== EXPORTAR PDF ====================
    public function exportarPdf()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ids'])) {
            $idsArray = array_filter(array_map('intval', explode(',', $_POST['ids'])));
            $usuarios = !empty($idsArray) ? $this->model->getByIds($idsArray) : [];
            if (empty($usuarios)) {
                $this->redirigirConMensaje('usuario/index', 'warning', 'Nada seleccionado', 'No se seleccionaron usuarios.');
                return;
            }
        } else {
            $usuarios = $this->model->getAll();
        }

        ob_start();
        include './views/templates/pdfs/usuario-pdf.php';
        $html = ob_get_clean();

        $mpdf = new Mpdf([
            'default_font' => 'dejavusans',
            'tempDir' => __DIR__ . '/../tmp'
        ]);

        $fechaHora = date('Y-m-d_H-i-s');
        $nombreArchivo = (!empty($idsArray))
            ? "usuarios_seleccionados_$fechaHora.pdf"
            : "usuarios_$fechaHora.pdf";

        $mpdf->WriteHTML($html);
        $mpdf->Output($nombreArchivo, 'I');
    }

    // ==================== EXPORTAR EXCEL ====================
    public function exportarExcel()
    {
        if (isset($_POST['ids']) && !empty($_POST['ids'])) {
            $idsArray = explode(',', $_POST['ids']);
            $usuarios = $this->model->getByIds($idsArray);
            $titulo = 'Lista de Usuarios Seleccionados';
            $nombreArchivoBase = 'usuarios_seleccionados';
        } else {
            $usuarios = $this->model->getAll();
            $titulo = 'Lista de Usuarios';
            $nombreArchivoBase = 'usuarios';
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Usuarios');

        // ===== TÍTULO =====
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', $titulo);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ===== ENCABEZADOS =====
        $encabezados = ['ID', 'Nombre', 'Apellido', 'Correo', 'Teléfono', 'Rol', 'Estado', 'Creado'];
        $col = 'A';
        foreach ($encabezados as $header) {
            $sheet->setCellValue($col . '2', $header);
            $col++;
        }
        $sheet->getStyle('A2:H2')->getFont()->setBold(true);
        $sheet->getStyle('A2:H2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ===== DATOS =====
        $fila = 3;
        foreach ($usuarios as $u) {
            $sheet->setCellValue("A{$fila}", $u->getId());
            $sheet->setCellValue("B{$fila}", $u->getNombre());
            $sheet->setCellValue("C{$fila}", $u->getApellido());
            $sheet->setCellValue("D{$fila}", $u->getCorreo());
            $sheet->setCellValue("E{$fila}", $u->getTelefono() ?? 'Sin teléfono');
            $sheet->setCellValue("F{$fila}", $u->getNombreRol());
            $sheet->setCellValue("G{$fila}", $u->getEstado() ? 'Activo' : 'Inactivo');
            $sheet->setCellValue("H{$fila}", FechaHelper::formatoCorto($u->getCreatedAt()));
            $fila++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
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
        $GLOBALS['pageTitle'] = 'Lista de Usuarios';
        $usuarios = $this->model->getAll();
        foreach ($usuarios as $u) {
            $u->imagen_url = verificarImagen('images/usuarios', $u->getImagen());
        }
        // Cargar roles
        $rolModel = new RolModel();
        $roles = $rolModel->getAll();

        $this->view->render('usuario/index', [
            'mensaje' => $this->view->mensaje,
            'usuarios' => $usuarios,
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $GLOBALS['pageTitle'] = 'Agregar Usuario';
        $this->view->render('usuario/create', [
            'mensaje' => $this->view->mensaje,
            'old' => FlashHelper::getOld()
        ]);
    }

    public function store()
    {
        protegerContraCSRF();
        if (!$this->validarDatosUsuario($errores)) {
            FlashHelper::setOld($_POST);
            FlashHelper::setValidate($errores);
            header('Location: ' . BASE_URL . 'usuario/create');
            exit;
        }

        $nombreArchivo = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('user_') . '.' . strtolower($extension);
            $ruta = 'public/images/usuarios/';
            if (!file_exists($ruta)) mkdir($ruta, 0777, true);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta . $nombreArchivo);
        }

        $usuario = new Usuario(
            id: 0,
            nombre: TextoHelper::formatearNombre($_POST['nombre']),
            apellido: TextoHelper::formatearNombre($_POST['apellido']),
            correo: $_POST['correo'],
            contrasena: password_hash($_POST['contrasena'], PASSWORD_BCRYPT),
            telefono: $_POST['telefono'] ?? null,
            direccion: $_POST['direccion'] ?? null,
            rolId: (int)$_POST['rol_id'],
            imagen: $nombreArchivo,
            estado: isset($_POST['estado']) ? 1 : 0,
            creadoPor: $_SESSION['usuario_id'] ?? null
        );

        if ($this->model->create($usuario)) {
            $this->redirigirConMensaje('usuario/index', 'success', 'Registro exitoso', 'Usuario creado correctamente.');
        } else {
            $this->redirigirConMensaje('usuario/create', 'danger', 'Error', 'No se pudo registrar el usuario.');
        }
    }

    public function edit($id)
    {
        $usuario = $this->model->getById((int)$id);
        if (!$usuario) {
            $this->redirigirConMensaje('usuario/index', 'warning', 'No encontrado', 'Usuario no encontrado.');
        }

        if (!verificarImagen('images/usuarios', $usuario->getImagen())) {
            $usuario->setImagen(null);
        }

        $GLOBALS['pageTitle'] = 'Editar Usuario';
        $this->view->render('usuario/edit', [
            'usuario' => $usuario,
            'mensaje' => $this->view->mensaje
        ]);
    }

    public function update($id)
    {
        protegerContraCSRF();
        if (!$this->validarDatosUsuario($errores, true)) {
            FlashHelper::setValidate($errores);
            header('Location: ' . BASE_URL . 'usuario/edit/' . urlencode($id));
            exit;
        }

        $usuario = $this->model->getById((int)$id);
        if (!$usuario) {
            $this->redirigirConMensaje('usuario/index', 'danger', 'No encontrado', 'El usuario no existe.');
        }

        $usuario->setNombre(TextoHelper::formatearNombre($_POST['nombre']));
        $usuario->setApellido(TextoHelper::formatearNombre($_POST['apellido']));
        $usuario->setCorreo($_POST['correo']);
        if (!empty($_POST['contrasena'])) {
            $usuario->setContrasena(password_hash($_POST['contrasena'], PASSWORD_BCRYPT));
        }
        $usuario->setTelefono($_POST['telefono'] ?? null);
        $usuario->setDireccion($_POST['direccion'] ?? null);
        $usuario->setRolId((int)$_POST['rol_id']);
        $usuario->setEstado(isset($_POST['estado']) ? 1 : 0);
        $usuario->setModificadoPor($_SESSION['usuario_id'] ?? null);

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoArchivo = uniqid('user_') . '.' . strtolower($extension);
            $ruta = 'public/images/usuarios/';
            if (!file_exists($ruta)) mkdir($ruta, 0777, true);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta . $nuevoArchivo);
            if ($usuario->getImagen() && file_exists($ruta . $usuario->getImagen())) {
                unlink($ruta . $usuario->getImagen());
            }
            $usuario->setImagen($nuevoArchivo);
        }

        if ($this->model->update($usuario)) {
            $this->redirigirConMensaje('usuario/index', 'success', 'Actualizado', 'Usuario actualizado correctamente.');
        } else {
            $this->redirigirConMensaje('usuario/index', 'danger', 'Error', 'No se pudo actualizar.');
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($id)) {
            protegerContraCSRF();

            $usuario = $this->model->getById((int)$id);
            if ($usuario && $usuario->getImagen()) {
                $ruta = 'public/images/usuarios/' . $usuario->getImagen();
                if (file_exists($ruta)) unlink($ruta);
            }

            $this->model->delete((int)$id);
            $this->redirigirConMensaje('usuario/index', 'success', 'Eliminado', 'Usuario eliminado correctamente.');
        } else {
            $this->redirigirConMensaje('usuario/index', 'danger', 'Error', 'Solicitud inválida.');
        }
    }

    public function show($id)
    {
        $usuario = $this->model->getById((int)$id);
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            if ($usuario) {
                echo json_encode([
                    'id' => '#' . $usuario->getId(),
                    'nombre' => $usuario->getNombre(),
                    'apellido' => $usuario->getApellido(),
                    'correo' => $usuario->getCorreo(),
                    'telefono' => $usuario->getTelefono(),
                    'direccion' => $usuario->getDireccion(),
                    'dni' => $usuario->getDni(),
                    'rol' => $usuario->getNombreRol(),
                    'estado' => $usuario->getEstado(),
                    'imagen' => $usuario->getImagen(),
                    'imagen_url' => verificarImagen('images/usuarios', $usuario->getImagen()),
                    'creado_por' => $usuario->nombreCreador ?? 'Desconocido',
                    'modificado_por' => $usuario->nombreModificador ?? 'Desconocido',
                    'created_at' => FechaHelper::formatoCorto($usuario->getCreatedAt()),
                    'updated_at' => FechaHelper::formatoCorto($usuario->getUpdatedAt())
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'No encontrado']);
            }
        } else {
            $this->view->render('usuario/show', ['usuario' => $usuario]);
        }
    }

    public function toggleEstado($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
            $data = json_decode(file_get_contents('php://input'), true);
            $estado = isset($data['estado']) ? (int)$data['estado'] : null;
            $usuarioSesion = $_SESSION['usuario_id'] ?? null;
            if ($estado !== null && $usuarioSesion) {
                echo json_encode(['success' => $this->model->actualizarEstado($id, $estado, $usuarioSesion)]);
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
                $this->redirigirConMensaje('usuario/index', 'warning', 'Nada seleccionado', 'No se seleccionaron usuarios.');
            }

            foreach ($idsArray as $id) {
                $usuario = $this->model->getById($id);
                if ($usuario && $usuario->getImagen()) {
                    $ruta = 'public/images/usuarios/' . $usuario->getImagen();
                    if (file_exists($ruta)) unlink($ruta);
                }
            }

            $eliminados = $this->model->deleteMultiple($idsArray);
            $this->redirigirConMensaje('usuario/index', 'success', 'Eliminación múltiple', "Se eliminaron correctamente $eliminados usuarios.");
        } else {
            $this->redirigirConMensaje('usuario/index', 'danger', 'Acceso denegado', 'Solicitud no válida.');
        }
    }

    // ==================== VALIDACIONES ====================
    private function validarDatosUsuario(&$errores, $esEdicion = false): bool
    {
        $errores = [];

        if ($e = Validador::campoObligatorio($_POST['nombre'] ?? '', 'Nombre')) $errores[] = $e;
        if ($e = Validador::campoObligatorio($_POST['apellido'] ?? '', 'Apellido')) $errores[] = $e;
        if ($e = Validador::email($_POST['correo'] ?? '', 'Correo')) $errores[] = $e;
        if (!$esEdicion || !empty($_POST['contrasena'])) {
            if ($e = Validador::limitarTexto($_POST['contrasena'] ?? '', 'Contraseña', 6, 50)) $errores[] = $e;
        }
        if ($e = Validador::telefono($_POST['telefono'] ?? '', 'Teléfono')) $errores[] = $e;
        if ($e = Validador::limitarTexto($_POST['direccion'] ?? '', 'Dirección', 0, 255)) $errores[] = $e;
        if ($e = Validador::entero($_POST['rol_id'] ?? '', 'Rol')) $errores[] = $e;
        if ($e = Validador::booleano($_POST['estado'] ?? 0, 'Estado')) $errores[] = $e;
        if ($e = Validador::imagen($_FILES['imagen'] ?? [])) $errores[] = $e;

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
}
