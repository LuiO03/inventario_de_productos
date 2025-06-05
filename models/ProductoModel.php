<?php 

    require_once 'core/model.php';
    class ProductoModel extends Model{
        public function __construct(){
            parent::__construct();
        }

        public function create(){
            echo " <p>Insertar producto</p>";
        }

        public function getAll(){
            try {
                $query = $this->db->prepare("SELECT * FROM productos");
                $query->execute();
                return $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Manejo de errores
                echo "Error al obtener los productos: " . $e->getMessage();
            }
        }
        
    }
?>