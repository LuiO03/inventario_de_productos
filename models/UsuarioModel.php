<?php

require_once 'core/model.php';

class UsuarioModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene el primer usuario registrado (por ID ascendente)
     */
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
