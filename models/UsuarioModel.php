<?php

require_once 'core/model.php';
require_once 'entities/Usuario.php';

class UsuarioModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create(Usuario $usuario): int|false
    {
        try {
            $sql = "INSERT INTO usuarios 
                        (nombre, apellido, correo, contrasena, telefono, direccion, rol_id, imagen, estado, creado_por) 
                    VALUES 
                        (:nombre, :apellido, :correo, :contrasena, :telefono, :direccion, :rol_id, :imagen, :estado, :creado_por)";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $usuario->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':apellido', $usuario->getApellido(), PDO::PARAM_STR);
            $query->bindValue(':correo', $usuario->getCorreo(), PDO::PARAM_STR);
            $query->bindValue(':contrasena', $usuario->getContrasena(), PDO::PARAM_STR);
            $query->bindValue(':telefono', $usuario->getTelefono(), PDO::PARAM_STR);
            $query->bindValue(':direccion', $usuario->getDireccion(), PDO::PARAM_STR);
            $query->bindValue(':rol_id', $usuario->getRolId(), PDO::PARAM_INT);
            $query->bindValue(':imagen', $usuario->getImagen(), PDO::PARAM_STR);
            $query->bindValue(':estado', $usuario->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':creado_por', $usuario->getCreadoPor(), PDO::PARAM_INT);

            $query->execute();
            return $this->PDO->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    public function update(Usuario $usuario): bool
    {
        try {
            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, apellido = :apellido, correo = :correo, contrasena = :contrasena,
                        telefono = :telefono, direccion = :direccion, rol_id = :rol_id,
                        imagen = :imagen, estado = :estado, modificado_por = :modificado_por
                    WHERE id = :id";
            $query = $this->PDO->prepare($sql);

            $query->bindValue(':nombre', $usuario->getNombre(), PDO::PARAM_STR);
            $query->bindValue(':apellido', $usuario->getApellido(), PDO::PARAM_STR);
            $query->bindValue(':correo', $usuario->getCorreo(), PDO::PARAM_STR);
            $query->bindValue(':contrasena', $usuario->getContrasena(), PDO::PARAM_STR);
            $query->bindValue(':telefono', $usuario->getTelefono(), PDO::PARAM_STR);
            $query->bindValue(':direccion', $usuario->getDireccion(), PDO::PARAM_STR);
            $query->bindValue(':rol_id', $usuario->getRolId(), PDO::PARAM_INT);
            $query->bindValue(':imagen', $usuario->getImagen(), PDO::PARAM_STR);
            $query->bindValue(':estado', $usuario->getEstado() ? 1 : 0, PDO::PARAM_INT);
            $query->bindValue(':modificado_por', $usuario->getModificadoPor(), PDO::PARAM_INT);
            $query->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $query = $this->PDO->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function getAll(): array
    {
        try {
            $sql = "SELECT u.*, r.nombre AS rol_nombre,
                           c.nombre AS nombre_creador,
                           m.nombre AS nombre_modificador
                    FROM usuarios u
                    INNER JOIN roles r ON u.rol_id = r.id
                    LEFT JOIN usuarios c ON u.creado_por = c.id
                    LEFT JOIN usuarios m ON u.modificado_por = m.id
                    ORDER BY u.id ASC";

            $query = $this->PDO->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $usuarios = [];
            foreach ($result as $row) {
                $usuario = Usuario::fromArray($row);
                $usuario->nombreRol = $row['rol_nombre'] ?? null;
                $usuario->nombreCreador = $row['nombre_creador'] ?? null;
                $usuario->nombreModificador = $row['nombre_modificador'] ?? null;
                $usuarios[] = $usuario;
            }
            return $usuarios;
        } catch (PDOException $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id): ?Usuario
    {
        try {
            $sql = "SELECT 
                u.*, 
                r.nombre AS rol_nombre,
                CONCAT(c.nombre, ' ', c.apellido) AS nombre_creador,
                CONCAT(m.nombre, ' ', m.apellido) AS nombre_modificador
            FROM usuarios u
            INNER JOIN roles r ON u.rol_id = r.id
            LEFT JOIN usuarios c ON u.creado_por = c.id
            LEFT JOIN usuarios m ON u.modificado_por = m.id
            WHERE u.id = :id";

            $query = $this->PDO->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $usuario = Usuario::fromArray($row);
                $usuario->nombreRol = $row['rol_nombre'] ?? null;
                $usuario->nombreCreador = $row['nombre_creador'] ?? null;
                $usuario->nombreModificador = $row['nombre_modificador'] ?? null;
                return $usuario;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por ID: " . $e->getMessage());
            return null;
        }
    }

    public function findByCorreo(string $correo): ?Usuario
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE correo = :correo LIMIT 1";
            $query = $this->PDO->prepare($sql);
            $query->bindParam(':correo', $correo, PDO::PARAM_STR);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row ? Usuario::fromArray($row) : null;
        } catch (PDOException $e) {
            error_log("Error al buscar usuario por correo: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarEstado($id, $estado, $modificadoPor): bool
    {
        $sql = "UPDATE usuarios 
                SET estado = :estado, modificado_por = :modificado_por, updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->PDO->prepare($sql);
        return $stmt->execute([
            'estado' => $estado,
            'modificado_por' => $modificadoPor,
            'id' => $id
        ]);
    }

    public function updateUltimoLogin(int $id): bool
    {
        $sql = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id";
        $stmt = $this->PDO->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM usuarios";
        $query = $this->PDO->query($sql);
        return (int)$query->fetch()['total'];
    }

    public function deleteMultiple(array $ids): int
    {
        if (empty($ids)) return 0;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM usuarios WHERE id IN ($placeholders)";
        $query = $this->PDO->prepare($sql);
        $query->execute($ids);
        return $query->rowCount();
    }

    public function getByIds(array $ids): array
    {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM usuarios WHERE id IN ($placeholders) ORDER BY id ASC";
        $query = $this->PDO->prepare($sql);
        $query->execute($ids);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];
        foreach ($result as $row) {
            $usuarios[] = Usuario::fromArray($row);
        }
        return $usuarios;
    }

    public function obtenerPrimero(): ?array
    {
        try {
            $sql = "SELECT * FROM usuarios ORDER BY id ASC LIMIT 1";
            $stmt = $this->PDO->prepare($sql);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario ?: null;
        } catch (PDOException $e) {
            error_log("Error al obtener el primer usuario: " . $e->getMessage());
            return null;
        }
    }

}
