<?php
class Usuario
{
    private int $id;
    private string $nombre;
    private string $apellido;
    private string $correo;
    private string $contrasena;
    private int $rolId;
    private ?string $direccion;
    private ?string $dni;
    private ?string $telefono;
    private ?string $imagen;
    private bool $estado;
    private ?string $ultimoLogin;
    private ?int $creadoPor;
    private ?int $modificadoPor;
    private ?string $createdAt;
    private ?string $updatedAt;

    // Propiedades auxiliares (para mostrar nombres en vez de IDs)
    public ?string $nombreRol = null;
    public ?string $nombreCreador = null;
    public ?string $nombreModificador = null;

    public function __construct(
        int $id = 0,
        string $nombre = '',
        string $apellido = '',
        string $correo = '',
        string $contrasena = '',
        int $rolId = 0,
        ?string $direccion = null,
        ?string $dni = null,
        ?string $telefono = null,
        ?string $imagen = null,
        bool $estado = true,
        ?string $ultimoLogin = null,
        ?int $creadoPor = null,
        ?int $modificadoPor = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->rolId = $rolId;
        $this->direccion = $direccion;
        $this->dni = $dni;
        $this->telefono = $telefono;
        $this->imagen = $imagen;
        $this->estado = $estado;
        $this->ultimoLogin = $ultimoLogin;
        $this->creadoPor = $creadoPor;
        $this->modificadoPor = $modificadoPor;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromArray(array $row): Usuario
    {
        return new Usuario(
            (int)$row['id'],
            $row['nombre'],
            $row['apellido'],
            $row['correo'],
            $row['contrasena'],
            (int)$row['rol_id'],
            $row['direccion'] ?? null,
            $row['dni'] ?? null,
            $row['telefono'] ?? null,   // ✔ ahora va teléfono
            $row['imagen'] ?? null,     // ✔ ahora va imagen
            (bool)$row['estado'],       // ✔ ahora sí entra en $estado
            $row['ultimo_login'] ?? null,
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
    public function getApellido(): string { return $this->apellido; }
    public function getCorreo(): string { return $this->correo; }
    public function getContrasena(): string { return $this->contrasena; }
    public function getRolId(): int { return $this->rolId; }
    public function getDireccion(): ?string { return $this->direccion; }
    public function getDni(): ?string { return $this->dni; }
    public function getTelefono(): ?string { return $this->telefono; }
    public function getImagen(): ?string { return $this->imagen; }
    public function getEstado(): bool { return $this->estado; }
    public function getUltimoLogin(): ?string { return $this->ultimoLogin; }
    public function getCreadoPor(): ?int { return $this->creadoPor; }
    public function getModificadoPor(): ?int { return $this->modificadoPor; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getUpdatedAt(): ?string { return $this->updatedAt; }
    public function getNombreRol(): ?string {return $this->nombreRol;}

    public function getAvatar() {
        $ruta = "public/images/usuarios/" . $this->imagen;
        if (!empty($this->imagen) && file_exists($ruta)) {
            return BASE_URL . $ruta;
        }
        return null; // null indica que no hay imagen, para usar iniciales
    }

    public function getIniciales() {
        return TextoHelper::getIniciales($this->nombre . ' ' . $this->apellido);
    }

    public function getColorAvatar() {
        $colores = [
            "#e57373", "#64b5f6", "#81c784", "#ffb74d",
            "#4db6ac", "#9575cd", "#f06292", "#7986cb"
        ];
        $index = crc32($this->nombre . $this->apellido) % count($colores);
        return $colores[$index];
    }

    // ====================
    // Setters
    // ====================
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setApellido(string $apellido): void { $this->apellido = $apellido; }
    public function setCorreo(string $correo): void { $this->correo = $correo; }
    public function setContrasena(string $contrasena): void { $this->contrasena = $contrasena; }
    public function setRolId(int $rolId): void { $this->rolId = $rolId; }
    public function setDireccion(?string $direccion): void { $this->direccion = $direccion; }
    public function setDni(?string $dni): void { $this->dni = $dni; }
    public function setTelefono(?string $telefono): void { $this->telefono = $telefono; }
    public function setImagen(?string $imagen): void { $this->imagen = $imagen; }
    public function setEstado(bool $estado): void { $this->estado = $estado; }
    public function setUltimoLogin(?string $ultimoLogin): void { $this->ultimoLogin = $ultimoLogin; }
    public function setCreadoPor(?int $creadoPor): void { $this->creadoPor = $creadoPor; }
    public function setModificadoPor(?int $modificadoPor): void { $this->modificadoPor = $modificadoPor; }
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(?string $updatedAt): void { $this->updatedAt = $updatedAt; }
    public function setNombreRol(?string $nombreRol): void {$this->nombreRol = $nombreRol;}
}
