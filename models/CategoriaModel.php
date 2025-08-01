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
            $sql = "INSERT INTO categorias (nombre, descripcion, estado, creado_por, imagen, slug, parent_id)
                    VALUES (:nombre, :descripcion, :estado, :creado_por, :imagen, :slug, :parent_id)";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $categoria->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':descripcion', $categoria->getDescripcion(), PDO::PARAM_STR);
            $query->bindValue(':estado', $categoria->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':imagen', $categoria->getImagen(), PDO::PARAM_STR);
            $query->bindValue(':slug', $categoria->getSlug(), PDO::PARAM_STR);
            $query->bindValue(':creado_por', $categoria->getCreadoPor(), PDO::PARAM_INT);
            $query->bindValue(':parent_id', $categoria->getParentId(), PDO::PARAM_INT);

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
                    SET nombre = :nombre, descripcion = :descripcion, estado = :estado,
                        modificado_por = :modificado_por, imagen = :imagen, slug = :slug,
                        parent_id = :parent_id
                    WHERE id = :id";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $categoria->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':descripcion', $categoria->getDescripcion(), PDO::PARAM_STR);
            $query->bindValue(':estado', $categoria->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':imagen', $categoria->getImagen(), PDO::PARAM_STR);
            $query->bindValue(':slug', $categoria->getSlug(), PDO::PARAM_STR);
            $query->bindValue(':modificado_por', $categoria->getModificadoPor(), PDO::PARAM_INT);
            $query->bindValue(':parent_id', $categoria->getParentId(), PDO::PARAM_INT);
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
            $sql = "SELECT * FROM categorias ORDER BY parent_id ASC, nombre ASC";
            $query = $this->PDO->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $categorias = [];
            foreach ($result as $row) {
                $categorias[] = Categoria::fromArray($row);
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
                $categoria = Categoria::fromArray($row);
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
            return $row ? Categoria::fromArray($row) : null;
        } catch (PDOException $e) {
            error_log("Error al buscar la categoría: " . $e->getMessage());
            return null;
        }
    }

    public function findBySlug(string $slug): ?Categoria
    {
        try {
            $sql = "SELECT * FROM categorias WHERE slug = :slug LIMIT 1";
            $query = $this->PDO->prepare($sql);
            $query->bindParam(':slug', $slug, PDO::PARAM_STR);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row ? Categoria::fromArray($row) : null;
        } catch (PDOException $e) {
            error_log("Error al buscar por slug: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarEstado($id, $estado, $modificadoPor): bool
    {
        $sql = "UPDATE categorias 
                SET estado = :estado, modificado_por = :modificado_por, updated_at = NOW()
                WHERE id = :id";
        $query = $this->PDO->prepare($sql);
        return $query->execute([
            'estado' => $estado,
            'modificado_por' => $modificadoPor,
            'id' => $id
        ]);
    }

    public function contar(): int
    {
        $sql = "SELECT COUNT(*) as total FROM categorias";
        $query = $this->PDO->query($sql);
        return (int)$query->fetch()['total'];
    }

    // ✅ OPCIONAL: Obtener subcategorías por categoría padre
    public function getByParentId(int $parentId): array
    {
        try {
            $sql = "SELECT * FROM categorias WHERE parent_id = :parent_id";
            $query = $this->PDO->prepare($sql);
            $query->bindParam(':parent_id', $parentId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($row) => Categoria::fromArray($row), $result);
        } catch (PDOException $e) {
            error_log("Error al obtener subcategorías: " . $e->getMessage());
            return [];
        }
    }

    public function getAllExcept($slugOrId): array
    {
        try {
            // Intentar resolver ID real desde slug o directamente usar ID
            if (is_numeric($slugOrId)) {
                $id = (int)$slugOrId;
            } else {
                $sql = "SELECT id FROM categorias WHERE slug = :slug LIMIT 1";
                $stmt = $this->PDO->prepare($sql);
                $stmt->bindParam(':slug', $slugOrId, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $row ? (int)$row['id'] : 0;
            }

            $sql = "SELECT * FROM categorias WHERE id != :id";
            $query = $this->PDO->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($row) => Categoria::fromArray($row), $result);
        } catch (PDOException $e) {
            error_log("Error al obtener categorías excluyendo una: " . $e->getMessage());
            return [];
        }
    }
}
