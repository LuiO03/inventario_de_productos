<?php

require_once 'core/model.php';
require_once 'entities/Rol.php';

class RolModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Crear un nuevo rol
    public function create(Rol $rol): int|false
    {
        try {
            $sql = "INSERT INTO roles (nombre, descripcion, estado, creado_por) 
                    VALUES (:nombre, :descripcion, :estado, :creado_por)";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $rol->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':descripcion', $rol->getDescripcion(), PDO::PARAM_STR);
            $query->bindValue(':estado', $rol->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':creado_por', $rol->getCreadoPor(), PDO::PARAM_INT);

            $query->execute();
            return $this->PDO->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear rol: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar rol
    public function update(Rol $rol): bool
    {
        try {
            $sql = "UPDATE roles 
                    SET nombre = :nombre, descripcion = :descripcion, estado = :estado, 
                        modificado_por = :modificado_por
                    WHERE id = :id";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $rol->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':descripcion', $rol->getDescripcion(), PDO::PARAM_STR);
            $query->bindValue(':estado', $rol->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':modificado_por', $rol->getModificadoPor(), PDO::PARAM_INT);
            $query->bindValue(':id', $rol->getId(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar rol: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar solo el estado
    public function actualizarEstado(int $id, bool $estado, ?int $modificadoPor = null): bool
    {
        try {
            $sql = "UPDATE roles 
                    SET estado = :estado, modificado_por = :modificado_por, updated_at = NOW()
                    WHERE id = :id";
            $stmt = $this->PDO->prepare($sql);
            return $stmt->execute([
                ':estado' => $estado ? 1 : 0,
                ':modificado_por' => $modificadoPor,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar estado del rol: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar rol
    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM roles WHERE id = :id";
            $query = $this->PDO->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar rol: " . $e->getMessage());
            return false;
        }
    }

    // Obtener todos los roles
    public function getAll(): array
    {
        try {
            $sql = "SELECT r.*, 
                        c.nombre AS nombre_creador,
                        m.nombre AS nombre_modificador,
                        COUNT(u.id) AS usuarios_count
                    FROM roles r
                    LEFT JOIN usuarios c ON r.creado_por = c.id
                    LEFT JOIN usuarios m ON r.modificado_por = m.id
                    LEFT JOIN usuarios u ON u.rol_id = r.id
                    GROUP BY r.id";

            $query = $this->PDO->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $roles = [];
            foreach ($result as $row) {
                $rol = Rol::fromArray($row);
                $rol->setNombreCreador($row['nombre_creador'] ?? null);
                $rol->setNombreModificador($row['nombre_modificador'] ?? null);
                $rol->setUsuariosCount((int)$row['usuarios_count']); // asignamos aquí
                $roles[] = $rol;
            }
            return $roles;
        } catch (PDOException $e) {
            error_log("Error al obtener roles: " . $e->getMessage());
            return [];
        }
    }

    // Obtener un rol por ID
    public function getById(int $id): ?Rol
    {
        try {
            $sql = "SELECT r.*, 
                    CONCAT(c.nombre, ' ', c.apellido) AS nombre_creador,
                    CONCAT(m.nombre, ' ', m.apellido) AS nombre_modificador,
                    COUNT(u.id) AS usuarios_count
                FROM roles r
                LEFT JOIN usuarios c ON r.creado_por = c.id
                LEFT JOIN usuarios m ON r.modificado_por = m.id
                LEFT JOIN usuarios u ON u.rol_id = r.id
                WHERE r.id = :id
                GROUP BY r.id";

            $query = $this->PDO->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $rol = Rol::fromArray($row);
                $rol->nombreCreador = $row['nombre_creador'] ?? null;
                $rol->nombreModificador = $row['nombre_modificador'] ?? null;
                $rol->setUsuariosCount((int)$row['usuarios_count']);
                return $rol;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Error al obtener rol por ID: " . $e->getMessage());
            return null;
        }
    }

    // Buscar por nombre
    public function findByNombre(string $nombre): ?Rol
    {
        try {
            $sql = "SELECT * FROM roles WHERE nombre = :nombre LIMIT 1";
            $query = $this->PDO->prepare($sql);
            $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);

            return $row ? Rol::fromArray($row) : null;
        } catch (PDOException $e) {
            error_log("Error al buscar rol por nombre: " . $e->getMessage());
            return null;
        }
    }

    // Contar roles
    public function count(): int
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM roles";
            $query = $this->PDO->query($sql);
            return $query->fetch()['total'];
        } catch (PDOException $e) {
            error_log("Error al contar roles: " . $e->getMessage());
            return 0;
        }
    }

    // Eliminar múltiples roles
    public function deleteMultiple(array $ids): int
    {
        if (empty($ids)) return 0;

        try {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "DELETE FROM roles WHERE id IN ($placeholders)";
            $query = $this->PDO->prepare($sql);
            $query->execute($ids);
            return $query->rowCount();
        } catch (PDOException $e) {
            error_log("Error al eliminar múltiples roles: " . $e->getMessage());
            return 0;
        }
    }

    // Obtener por múltiples IDs
    public function getByIds(array $ids): array
    {
        if (empty($ids)) return [];

        try {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "SELECT * FROM roles WHERE id IN ($placeholders) ORDER BY id ASC";
            $query = $this->PDO->prepare($sql);
            $query->execute($ids);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $roles = [];
            foreach ($result as $row) {
                $roles[] = Rol::fromArray($row);
            }
            return $roles;
        } catch (PDOException $e) {
            error_log("Error al obtener roles por IDs: " . $e->getMessage());
            return [];
        }
    }
}
