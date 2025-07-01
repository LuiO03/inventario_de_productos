<?php

require_once 'core/model.php';
require_once 'entities/Categoria.php';

class CategoriaModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create(Categoria $categoria): int|false
    {
        try {
            $sql = "INSERT INTO categorias (nombre, descripcion, estado, creado_por)
                    VALUES (:nombre, :descripcion, :estado, :creado_por)";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $categoria->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':descripcion', $categoria->getDescripcion(), PDO::PARAM_STR);
            $query->bindValue(':estado', $categoria->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':creado_por', $categoria->getCreadoPor(), PDO::PARAM_INT);

            $query->execute();
            return $this->PDO->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear la categoría: " . $e->getMessage());
            return false;
        }
    }

    public function update(Categoria $categoria): bool
    {
        try {
            $sql = "UPDATE categorias 
                    SET nombre = :nombre, descripcion = :descripcion, estado = :estado, modificado_por = :modificado_por
                    WHERE id = :id";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $categoria->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':descripcion', $categoria->getDescripcion(), PDO::PARAM_STR);
            $query->bindValue(':estado', $categoria->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':modificado_por', $categoria->getModificadoPor(), PDO::PARAM_INT);
            $query->bindValue(':id', $categoria->getId(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar la categoría: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM categorias WHERE id = :id";
            $query = $this->PDO->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar la categoría: " . $e->getMessage());
            return false;
        }
    }

    public function getAll(): array
    {
        try {
            $sql = "SELECT * FROM categorias ORDER BY id DESC";
            $query = $this->PDO->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $categorias = [];
            foreach ($result as $row) {
                $categorias[] = new Categoria(
                    (int)$row['id'],
                    $row['nombre'],
                    $row['descripcion'],
                    (bool)$row['estado'],
                    isset($row['creado_por']) ? (int)$row['creado_por'] : null,
                    isset($row['modificado_por']) ? (int)$row['modificado_por'] : null,
                    $row['created_at'],
                    $row['updated_at']
                );
            }
            return $categorias;
        } catch (PDOException $e) {
            error_log("Error al obtener las categorías: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id): ?Categoria
    {
        try {
            $sql = "SELECT 
            c.*,
            CONCAT(u1.nombre, ' ', u1.apellido) AS nombre_creador,
            CONCAT(u2.nombre, ' ', u2.apellido) AS nombre_modificador
            FROM categorias c
            LEFT JOIN usuarios u1 ON c.creado_por = u1.id
            LEFT JOIN usuarios u2 ON c.modificado_por = u2.id
            WHERE c.id = :id";

            $query = $this->PDO->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $categoria = new Categoria(
                    (int)$row['id'],
                    $row['nombre'],
                    $row['descripcion'],
                    (bool)$row['estado'],
                    $row['creado_por'] ? (int)$row['creado_por'] : null,
                    $row['modificado_por'] ? (int)$row['modificado_por'] : null,
                    $row['created_at'],
                    $row['updated_at']
                );

                // Extra: guardamos también los nombres de los usuarios si los quieres mostrar
                $categoria->nombreCreador = $row['nombre_creador'] ?? null;
                $categoria->nombreModificador = $row['nombre_modificador'] ?? null;

                return $categoria;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Error al obtener categoría por ID: " . $e->getMessage());
            return null;
        }
    }


    public function findByNombre(string $nombre): ?Categoria
    {
        try {
            $sql = "SELECT * FROM categorias WHERE nombre = :nombre";
            $query = $this->PDO->prepare($sql);
            $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $query->execute();

            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new Categoria(
                    (int)$row['id'],
                    $row['nombre'],
                    $row['descripcion'],
                    (bool)$row['estado'],
                    isset($row['creado_por']) ? (int)$row['creado_por'] : null,
                    isset($row['modificado_por']) ? (int)$row['modificado_por'] : null,
                    $row['created_at'],
                    $row['updated_at']
                );
            }

            return null;
        } catch (PDOException $e) {
            error_log("Error al buscar la categoría: " . $e->getMessage());
            return null;
        }
    }
}
