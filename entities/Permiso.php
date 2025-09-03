<?php
class Permiso
{
    private int $id = 0;
    private int $entidadId = 0;
    private string $accion = '';
    private ?string $entidadNombre = null; // proviene del JOIN con entidades

    public function __construct(int $id = 0, int $entidadId = 0, string $accion = '', ?string $entidadNombre = null)
    {
        $this->id = $id;
        $this->entidadId = $entidadId;
        $this->accion = $accion;
        $this->entidadNombre = $entidadNombre;
    }

    public static function fromArray(array $row): Permiso
    {
        return new Permiso(
            (int)($row['id'] ?? 0),
            (int)($row['entidad_id'] ?? 0),
            $row['accion'] ?? '',
            $row['entidad_nombre'] ?? ($row['entidad'] ?? null)
        );
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getEntidadId(): int { return $this->entidadId; }
    public function getAccion(): string { return $this->accion; }
    public function getEntidadNombre(): ?string { return $this->entidadNombre; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setEntidadId(int $entidadId): void { $this->entidadId = $entidadId; }
    public function setAccion(string $accion): void { $this->accion = $accion; }
    public function setEntidadNombre(?string $nombre): void { $this->entidadNombre = $nombre; }
}
