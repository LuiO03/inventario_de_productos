<?php

require_once 'core/model.php';
require_once 'entities/Permiso.php';

class PermisoModel extends Model
{
    // Devuelve filas asociativas: id, accion, entidad (nombre). Útil para listar todos los permisos agrupados por entidad.
    public function getAllWithEntidad(): array
    {
        $sql = "SELECT p.id, p.entidad_id, p.accion, e.nombre AS entidad
                FROM permisos p
                JOIN entidades e ON e.id = p.entidad_id
                ORDER BY e.nombre, p.accion";
        return $this->PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve objetos Permiso asignados a un rol (incluye entidad_nombre desde JOIN)
    public function getByRol(int $rol_id): array
    {
        $sql = "SELECT p.id, p.entidad_id, p.accion, e.nombre AS entidad_nombre
                FROM rol_permiso rp
                INNER JOIN permisos p ON rp.permiso_id = p.id
                INNER JOIN entidades e ON p.entidad_id = e.id
                WHERE rp.rol_id = :rol_id
                ORDER BY e.nombre, p.accion";
        $stmt = $this->PDO->prepare($sql);
        $stmt->execute(['rol_id' => $rol_id]);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $permiso = new Permiso();
            $permiso->setId((int)$row['id']);
            $permiso->setEntidadId((int)$row['entidad_id']);
            $permiso->setAccion($row['accion']);
            $permiso->setEntidadNombre($row['entidad_nombre']);
            $result[] = $permiso;
        }
        return $result;
    }

    // Devuelve sólo los IDs de permisos asignados al rol (útil para marcar checkboxes)
    public function getByRolId(int $rolId): array
    {
        $sql = "SELECT permiso_id FROM rol_permiso WHERE rol_id = ?";
        $stmt = $this->PDO->prepare($sql);
        $stmt->execute([$rolId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Actualiza permisos (borra e inserta) — ya lo tenías, solo lo dejé igual
    public function actualizarPermisosDeRol(int $rolId, array $permisosSeleccionados): void
    {
        $this->PDO->beginTransaction();
        try {
            $sqlDel = "DELETE FROM rol_permiso WHERE rol_id = ?";
            $stmtDel = $this->PDO->prepare($sqlDel);
            $stmtDel->execute([$rolId]);

            $sqlIns = "INSERT INTO rol_permiso (rol_id, permiso_id) VALUES (?, ?)";
            $stmtIns = $this->PDO->prepare($sqlIns);
            foreach ($permisosSeleccionados as $permisoId) {
                $stmtIns->execute([(int)$rolId, (int)$permisoId]);
            }
            $this->PDO->commit();
        } catch (PDOException $e) {
            $this->PDO->rollBack();
            throw $e;
        }
    }

    // Obtener permiso por id -> devuelve objeto Permiso
    public function getById(int $id): ?Permiso
    {
        $sql = "SELECT p.*, e.nombre AS entidad_nombre
                FROM permisos p
                LEFT JOIN entidades e ON p.entidad_id = e.id
                WHERE p.id = ?";
        $stmt = $this->PDO->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? Permiso::fromArray($data) : null;
    }

    // Obtener todos los permisos como objetos
    public function getAll(): array
    {
        $sql = "SELECT p.*, e.nombre AS entidad_nombre
                FROM permisos p
                LEFT JOIN entidades e ON p.entidad_id = e.id
                ORDER BY e.nombre, p.accion";
        $stmt = $this->PDO->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $permisos = [];
        foreach ($rows as $row) {
            $permisos[] = Permiso::fromArray($row);
        }
        return $permisos;
    }
}
