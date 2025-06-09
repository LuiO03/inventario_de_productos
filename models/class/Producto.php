<?php 
    class Producto{
        public $id;
        public $nombre;
        public $precio;
        public $stock;

        public function __construct($id = null, $nombre = '', $precio = 0.0, $stock = 0) {
            $this->id = $id;
            $this->nombre = $nombre;
            $this->precio = $precio;
            $this->stock = $stock;
        }

        public function estaDisponible(): bool {
            return $this->stock > 0;
        }

        public function aplicarDescuento($porcentaje): float {
            return $this->precio - ($this->precio * $porcentaje / 100);
        }
    }
?>