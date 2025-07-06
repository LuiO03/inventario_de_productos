<?php
class Categoria
{
    private int $id;
    private string $nombre;
    private ?string $descripcion;
    private bool $estado;
    private ?string $imagen = null;
    private ?int $creadoPor;
    private ?int $modificadoPor;
    private ?string $createdAt;
    private ?string $updatedAt;
    public ?string $nombreCreador = null;
    public ?string $nombreModificador = null;

    public function __construct(
        int $id = 0,
        string $nombre = '',
        ?string $descripcion = null,
        bool $estado = true,
        ?string $imagen = null,
        ?int $creadoPor = null,
        ?int $modificadoPor = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->imagen = $imagen;
        $this->creadoPor = $creadoPor;
        $this->modificadoPor = $modificadoPor;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }
    public function getEstado(): bool
    {
        return $this->estado;
    }
    public function getCreadoPor(): ?int
    {
        return $this->creadoPor;
    }
    public function getModificadoPor(): ?int
    {
        return $this->modificadoPor;
    }
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    // Setters
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }
    public function setEstado(bool $estado): void
    {
        $this->estado = $estado;
    }
    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): void
    {
        $this->imagen = $imagen;
    }
    public function setCreadoPor(?int $creadoPor): void
    {
        $this->creadoPor = $creadoPor;
    }
    public function setModificadoPor(?int $modificadoPor): void
    {
        $this->modificadoPor = $modificadoPor;
    }
    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
