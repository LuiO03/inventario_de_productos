<?php

require_once 'core/model.php';
require_once 'entities/Marca.php';

class MarcaModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create(Marca $marca): int|false
    {
        try {
            $sql = "INSERT INTO marcas (nombre, descripcion, estado, creado_por, imagen, slug)
                    VALUES (:nombre, :descripcion, :estado, :creado_por, :imagen, :slug)";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $marca->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':descripcion', $marca->getDescripcion(), PDO::PARAM_STR);
            $query->bindValue(':estado', $marca->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':imagen', $marca->getImagen(), PDO::PARAM_STR);
            $query->bindValue(':slug', $marca->getSlug(), PDO::PARAM_STR);
            $query->bindValue(':creado_por', $marca->getCreadoPor(), PDO::PARAM_INT);

            $query->execute();
            return $this->PDO->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear la marca: " . $e->getMessage());
            return false;
        }
    }

    public function update(Marca $marca): bool
    {
        try {
            $sql = "UPDATE marcas 
                    SET nombre = :nombre, descripcion = :descripcion, estado = :estado,
                        modificado_por = :modificado_por, imagen = :imagen, slug = :slug
                    WHERE id = :id";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $marca->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':descripcion', $marca->getDescripcion(), PDO::PARAM_STR);
            $query->bindValue(':estado', $marca->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':imagen', $marca->getImagen(), PDO::PARAM_STR);
            $query->bindValue(':slug', $marca->getSlug(), PDO::PARAM_STR);
            $query->bindValue(':modificado_por', $marca->getModificadoPor(), PDO::PARAM_INT);
            $query->bindValue(':id', $marca->getId(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar la marca: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM marcas WHERE id = :id";
            $query = $this->PDO->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar la marca: " . $e->getMessage());
            return false;
        }
    }

    public function getAll(): array
    {
        try {
            $sql = "SELECT * FROM marcas ORDER BY id DESC";
            $query = $this->PDO->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $marcas = [];
            foreach ($result as $row) {
                $marcas[] = Marca::fromArray($row);
            }
            return $marcas;
        } catch (PDOException $e) {
            error_log("Error al obtener las marcas: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id): ?Marca
    {
        try {
            $sql = "SELECT 
                        m.*, 
                        CONCAT(u1.nombre, ' ', u1.apellido) AS nombre_creador,
                        CONCAT(u2.nombre, ' ', u2.apellido) AS nombre_modificador
                    FROM marcas m
                    LEFT JOIN usuarios u1 ON m.creado_por = u1.id
                    LEFT JOIN usuarios u2 ON m.modificado_por = u2.id
                    WHERE m.id = :id";

            $query = $this->PDO->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $marca = Marca::fromArray($row);
                $marca->nombreCreador = $row['nombre_creador'] ?? null;
                $marca->nombreModificador = $row['nombre_modificador'] ?? null;
                return $marca;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Error al obtener marca por ID: " . $e->getMessage());
            return null;
        }
    }

    public function findByNombre(string $nombre): ?Marca
    {
        try {
            $sql = "SELECT * FROM marcas WHERE nombre = :nombre";
            $query = $this->PDO->prepare($sql);
            $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row ? Marca::fromArray($row) : null;
        } catch (PDOException $e) {
            error_log("Error al buscar la marca: " . $e->getMessage());
            return null;
        }
    }

    public function findBySlug(string $slug): ?Marca
    {
        try {
            $sql = "SELECT * FROM marcas WHERE slug = :slug LIMIT 1";
            $query = $this->PDO->prepare($sql);
            $query->bindParam(':slug', $slug, PDO::PARAM_STR);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row ? Marca::fromArray($row) : null;
        } catch (PDOException $e) {
            error_log("Error al buscar por slug: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarEstado($id, $estado, $modificadoPor): bool
    {
        $sql = "UPDATE marcas 
                SET estado = :estado, modificado_por = :modificado_por, updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->PDO->prepare($sql);
        return $stmt->execute([
            'estado' => $estado,
            'modificado_por' => $modificadoPor,
            'id' => $id
        ]);
    }

    public function contar(): int
    {
        $sql = "SELECT COUNT(*) as total FROM marcas";
        $stmt = $this->PDO->query($sql);
        return (int)$stmt->fetch()['total'];
    }
}
