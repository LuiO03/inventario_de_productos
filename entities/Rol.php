<?php 
class Rol
{
    private int $id;
    private string $nombre;
    private ?string $descripcion;
    private bool $estado;
    private ?int $creadoPor;
    private ?int $modificadoPor;
    private ?string $createdAt;
    private ?string $updatedAt;

    // Propiedades auxiliares (mostrar nombres de usuarios en vez de IDs)
    private int $usuariosCount = 0; // Nuevo atributo para contar usuarios
    public ?string $nombreCreador = null;
    public ?string $nombreModificador = null;
    private array $permisos = []; // Lista de objetos Permiso

    public function __construct(
        int $id = 0,
        string $nombre = '',
        ?string $descripcion = null,
        bool $estado = true,
        ?int $creadoPor = null,
        ?int $modificadoPor = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->creadoPor = $creadoPor;
        $this->modificadoPor = $modificadoPor;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromArray(array $row): Rol
    {
        return new Rol(
            (int)$row['id'],
            $row['nombre'],
            $row['descripcion'] ?? null,
            isset($row['estado']) ? (bool)$row['estado'] : true,
            isset($row['creado_por']) ? (int)$row['creado_por'] : null,
            isset($row['modificado_por']) ? (int)$row['modificado_por'] : null,
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
    }

    // ====================
    // Getters
    // ====================
    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getDescripcion(): ?string { return $this->descripcion; }
    public function getEstado(): bool { return $this->estado; }
    public function getCreadoPor(): ?int { return $this->creadoPor; }
    public function getModificadoPor(): ?int { return $this->modificadoPor; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getUpdatedAt(): ?string { return $this->updatedAt; }
    public function getNombreCreador(): ?string { return $this->nombreCreador; }
    public function getNombreModificador(): ?string { return $this->nombreModificador; }
    public function getPermisos(): array { return $this->permisos; }

    // getter para usuariosCount
    public function getUsuariosCount(): int {
        return $this->usuariosCount;
    }

    // setter para usuariosCount
    public function setUsuariosCount(int $count): void {
        $this->usuariosCount = $count;
    }

    // ====================
    // Setters
    // ====================
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setDescripcion(?string $descripcion): void { $this->descripcion = $descripcion; }
    public function setEstado(bool $estado): void { $this->estado = $estado; }
    public function setCreadoPor(?int $creadoPor): void { $this->creadoPor = $creadoPor; }
    public function setModificadoPor(?int $modificadoPor): void { $this->modificadoPor = $modificadoPor; }
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(?string $updatedAt): void { $this->updatedAt = $updatedAt; }
    public function setNombreCreador(?string $nombreCreador): void { $this->nombreCreador = $nombreCreador; }
    public function setNombreModificador(?string $nombreModificador): void { $this->nombreModificador = $nombreModificador; }
    public function setPermisos(array $permisos): void { $this->permisos = $permisos; }
}
