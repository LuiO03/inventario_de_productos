<?php
class Categoria
{
    private int $id;
    private string $nombre;
    private ?string $descripcion;
    private bool $estado;
    private ?string $imagen = null;
    private ?string $slug = null;
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
        ?string $slug = null,
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
        $this->slug = $slug;
        $this->imagen = $imagen;
        $this->creadoPor = $creadoPor;
        $this->modificadoPor = $modificadoPor;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromArray(array $row): Categoria
    {
        return new Categoria(
            (int)$row['id'],
            $row['nombre'],
            $row['descripcion'],
            (bool)$row['estado'],
            $row['slug'] ?? null,
            $row['imagen'] ?? null,
            isset($row['creado_por']) ? (int)$row['creado_por'] : null,
            isset($row['modificado_por']) ? (int)$row['modificado_por'] : null,
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
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
    public function getImagen(): ?string
    {
        return $this->imagen;
    }
    public function getSlug(): ?string
    {
        return $this->slug;
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
    public function setImagen(?string $imagen): void
    {
        $this->imagen = $imagen;
    }
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
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
