<?php 

    require_once 'core/model.php';
    require_once 'entities/Producto.php';
    class ProductoModel extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function create(Producto $producto) {
            try {
                $sql = "INSERT INTO productos (nombre, precio, stock) VALUES (:nombre, :precio, :stock)";
                $query = $this->PDO->prepare($sql);
                //bindParam para variables que puedan cambiarse por referencia ($nombre, $tock, etc)
                //bindValue para usar directamente los valores devueltos por métodos o expresiones
                $query->bindValue(':nombre', $producto->getNombre(), PDO::PARAM_STR);
                $query->bindValue(':precio', $producto->getPrecio(), PDO::PARAM_INT);
                $query->bindValue(':stock', $producto->getStock(), PDO::PARAM_INT);
                $query->execute();

                return $this->PDO->lastInsertId();
            } catch (PDOException $e) {
                error_log("Error al crear el producto: " . $e->getMessage());
                return false;
            }
        }

        public function update(Producto $producto): bool {
            try {
                $sql = "UPDATE productos SET nombre = :nombre, precio = :precio, stock = :stock WHERE id = :id";
                $query = $this->PDO->prepare($sql);
                $query->bindValue(':nombre', $producto->getNombre(), PDO::PARAM_STR);
                $query->bindValue(':precio', $producto->getPrecio(), PDO::PARAM_INT);
                $query->bindValue(':stock', $producto->getStock(), PDO::PARAM_INT);
                $query->bindValue(':id', $producto->getId(), PDO::PARAM_INT);
                return $query->execute();
            } catch (PDOException $e) {
                error_log("Error al actualizar el producto: " . $e->getMessage());
                return false;
            }
        }

        public function delete(int $id): bool {
            try {
                $sql = "DELETE FROM productos WHERE id = :id";
                $query = $this->PDO->prepare($sql);
                $query->bindValue(':id', $id, PDO::PARAM_INT);
                return $query->execute();
            } catch (PDOException $e) {
                error_log("Error al eliminar el producto: " . $e->getMessage());
                return false;
            }
        }

        public function getAll(): array {
            try {
                $sql = "SELECT * FROM productos ORDER BY id DESC";
                $query = $this->PDO->prepare( $sql );
                $query->execute();
                $result = $query->fetchAll(PDO::FETCH_ASSOC);

                $productos = [];
                foreach ($result as $row) {
                    $productos[] = new Producto(
                        (int)$row['id'],
                        $row['nombre'],
                        (float)$row['precio'],
                        (int)$row['stock']
                    );
                }
                return $productos;
            } catch (PDOException $e) {
                error_log("Error al obtener los productos: " . $e->getMessage());
                return [];
            }
        }

        public function getById($id): ?Producto {
            try {
                $sql = "SELECT * FROM productos WHERE id = :id";
                $query = $this->PDO->prepare( $sql );
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->execute();
                $row = $query->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $producto = new Producto(
                        (int)$row['id'],
                        $row['nombre'],
                        (int)$row['precio'],
                        (int)$row['stock']
                    );
                    return $producto;
                }
                return null;
            } catch (PDOException $e) {
                error_log("Error al obtener el producto: " . $e->getMessage());
                return null;
            }
        }

        public function findByNombre($nombre): ?Producto {
            try {
                $sql = "SELECT * FROM productos WHERE nombre = :nombre";
                $query = $this->PDO->prepare( $sql );
                $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $query->execute();
                $row = $query->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $producto = new Producto(
                        (int)$row['id'],
                        $row['nombre'],
                        (int)$row['precio'],
                        (int)$row['stock']
                    );
                    return $producto;
                }

                return null;
            } catch (PDOException $e) {
                error_log("Error al buscar el producto: " . $e->getMessage());
                return null;
            }
        }
    }
?>