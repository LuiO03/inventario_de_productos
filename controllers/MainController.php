<?php
require_once "models/UsuarioModel.php";
class MainController extends Controller
{


    public function __construct()
    {
        parent::__construct(); // Solo inicializa el controlador, sin renderizar vistas
    }

    public function index()
    {
        $entidades = [
            'producto'   => 'ProductoModel',
            'categoria'  => 'CategoriaModel',
            'marca'      => 'MarcaModel',
            'cliente'    => 'ClienteModel',
            'usuario'    => 'UsuarioModel',
            'rol'        => 'RolModel',
            'pedido'     => 'PedidoModel',
            'servicio'   => 'ServicioModel',
            'blog'       => 'BlogModel',
            'mensaje'    => 'MensajeModel'
        ];

        $totales = [];

        foreach ($entidades as $key => $modelo) {
            $rutaModelo = "models/$modelo.php";

            if (file_exists($rutaModelo)) {
                require_once $rutaModelo;
                if (class_exists($modelo)) {
                    $instancia = new $modelo();
                    if (method_exists($instancia, 'count')) {
                        $totales["total" . ucfirst($key) . "s"] = $instancia->count();
                    } else {
                        $totales["total" . ucfirst($key) . "s"] = 0;
                    }
                }
            } else {
                $totales["total" . ucfirst($key) . "s"] = 0;
            }
        }

        // 🔹 Aquí obtenemos los usuarios activos como colaboradores
        $usuarioModel = new UsuarioModel();
        $totales['colaboradores'] = $usuarioModel->getAll(); 

        $totales['mensaje'] = $this->view->mensaje ?? null;

        $this->view->render('main/index', $totales);
    }

}
