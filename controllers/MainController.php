<?php
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
            'cliente'    => 'ClienteModel',
            'usuario'    => 'UsuarioModel',
            'pedido'     => 'PedidoModel',
            'servicio'   => 'ServicioModel',
            'blog'       => 'BlogModel',
            'mensaje'    => 'MensajeModel'
        ];

        $totales = [];

        foreach ($entidades as $key => $modelo) {
            $rutaModelo = "models/$modelo.php";

            // Verifica si el archivo del modelo existe
            if (file_exists($rutaModelo)) {
                require_once $rutaModelo;
                if (class_exists($modelo)) {
                    $instancia = new $modelo();
                    if (method_exists($instancia, 'contar')) {
                        $totales["total" . ucfirst($key) . "s"] = $instancia->contar();
                    } else {
                        $totales["total" . ucfirst($key) . "s"] = 0; // No tiene método contar
                    }
                }
            } else {
                $totales["total" . ucfirst($key) . "s"] = 0; // Modelo no existe aún
            }
        }

        // Agrega el mensaje a los datos
        $totales['mensaje'] = $this->view->mensaje ?? null;

        // Renderizar con los datos dinámicos
        $this->view->render('main/index', $totales);
    }
}
