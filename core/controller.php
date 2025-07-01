<?php
// Se incluyen los archivos base para la vista y el modelo
require_once 'core/view.php';
require_once 'core/model.php';

// Clase base Controller que usarán todos los controladores del sistema
class Controller
{

    // Propiedades protegidas accesibles desde las clases hijas
    protected $view;           // Instancia de la clase View para manejar vistas
    protected $model;          // Modelo principal (por defecto)
    protected $productoModel;  // Modelo adicional opcional (por ejemplo: productos)

    // Constructor base: se inicializa la vista
    function __construct()
    {
        $this->view = new View(); // Crea la vista principal para el controlador
        // Simular que el primer usuario está logueado
        if (!isset($_SESSION['usuario_id'])) {
            $this->simularUsuario(); // Cargar un usuario por defecto
        }
    }

    /**
     * Carga un modelo dinámicamente según el nombre proporcionado.
     * 
     * @param string $model Nombre base del modelo (sin 'Model')
     * @param string $alias Propiedad donde se guardará el modelo instanciado (por defecto 'model')
     * @return object|null Retorna la instancia del modelo cargado o null si falla
     */
    public function loadModel($model, $alias = 'model')
    {
        // Construye la ruta al archivo del modelo
        $archivoModelo = 'models/' . $model . 'Model.php';

        // Verifica si el archivo del modelo existe
        if (file_exists($archivoModelo)) {
            require_once $archivoModelo; // Incluye el archivo del modelo
            $nombreClase = $model . 'Model'; // Ej: 'ProductoModel'

            // Si la clase existe, se instancia y se asigna dinámicamente a la propiedad $alias
            if (class_exists($nombreClase)) {
                $this->{$alias} = new $nombreClase(); // Asigna a $this->model o $this->productoModel, etc.
                return $this->{$alias}; // Devuelve la instancia del modelo
            }
        }

        // Si el archivo o la clase no existen, se retorna null
        return null;
    }
    private function simularUsuario()
    {
        require_once 'models/UsuarioModel.php'; // Asegúrate de tener este modelo

        $usuarioModel = new UsuarioModel(); // Instanciar el modelo
        $usuario = $usuarioModel->obtenerPrimero(); // Método que debes definir

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_usuario'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
            $_SESSION['rol'] = $usuario['rol_id'];
            // Puedes agregar más campos si necesitas
        }
    }
}
