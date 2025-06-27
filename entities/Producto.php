<?php
class Producto {
    private int $id;
    private string $nombre;
    private float $precio;
    private int $stock;

    public function __construct(int $id = 0, string $nombre = '', float $precio = 0.0, int $stock = 0) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
    }

    // Getters...
    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getPrecio(): float { return $this->precio; }
    public function getStock(): int { return $this->stock; }

    // Setters...
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setPrecio(float $precio): void { $this->precio = $precio; }
    public function setStock(int $stock): void { $this->stock = $stock; }
}
