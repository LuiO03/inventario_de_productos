<?php 

    require_once 'core/model.php';
    class ProductoModel extends Model{
        public function __construct(){
            parent::__construct();
        }

        public function create($nombre, $precio, $stock){
            $query=$this->PDO->prepare("INSERT INTO productos (nombre, precio, stock) VALUES (:nombre, :precio, :stock)");
            $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $query->bindParam(':precio',$precio , PDO::PARAM_INT);
            $query->bindParam(':stock',$stock , PDO::PARAM_INT);
            try {
                if ($query->execute()) {
                    return $this->PDO->lastInsertId();
                } else {
                    return false;
                }
            }
            catch (PDOException $e) {
                error_log("Error al crear el producto: " . $e->getMessage());
                return false;
            }
        }

        public function getAll(){
            try {
                $query = $this->PDO->prepare("SELECT * FROM productos");
                $query->execute();
                return $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Manejo de errores
                echo "Error al obtener los productos: " . $e->getMessage();
            }
        }

        public function getById($id){
            try {
                $query = $this->PDO->prepare("SELECT * FROM productos WHERE id = :id");
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->execute();
                return $query->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Manejo de errores
                echo "Error al obtener el producto: " . $e->getMessage();
            }
        }
        
    }
?>