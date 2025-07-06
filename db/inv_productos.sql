-- Base de datos
DROP DATABASE IF EXISTS inv_productos;
CREATE DATABASE inv_productos;
USE inv_productos;

-- Tabla de roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO roles (nombre, descripcion) VALUES
('Superadmin', 'Acceso total al sistema'),
('Admin', 'Acceso administrativo limitado');

-- Tabla de permisos
CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entidad VARCHAR(50) NOT NULL,
    accion ENUM('ver', 'crear', 'editar', 'eliminar') NOT NULL,
    UNIQUE(entidad, accion)
);

INSERT INTO permisos (entidad, accion) VALUES
('usuarios', 'ver'), ('usuarios', 'crear'), ('usuarios', 'editar'), ('usuarios', 'eliminar'),
('productos', 'ver'), ('productos', 'crear'), ('productos', 'editar'), ('productos', 'eliminar'),
('categorias', 'ver'), ('categorias', 'crear'), ('categorias', 'editar'), ('categorias', 'eliminar'),
('marcas', 'ver'), ('marcas', 'crear'), ('marcas', 'editar'), ('marcas', 'eliminar'),
('roles', 'ver'), ('roles', 'crear'), ('roles', 'editar'), ('roles', 'eliminar'),
('permisos', 'ver'), ('permisos', 'crear'), ('permisos', 'editar'), ('permisos', 'eliminar');

-- Asignación de permisos al rol Superadmin
CREATE TABLE rol_permiso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    permiso_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE,
    UNIQUE(rol_id, permiso_id)
);

INSERT INTO rol_permiso (rol_id, permiso_id)
SELECT 1, id FROM permisos;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    direccion VARCHAR(255),
    dni VARCHAR(20) UNIQUE,
    estado TINYINT(1) DEFAULT 1,
    ultimo_login DATETIME DEFAULT NULL,
    creado_por INT,
    modificado_por INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id),
    FOREIGN KEY (modificado_por) REFERENCES usuarios(id)
);

-- Usuario Superadmin (sin creado_por/modificado_por aún)
INSERT INTO usuarios (
    nombre, apellido, correo, contrasena, rol_id, estado
)
VALUES (
    'Luis', 'Osorio', 'luis@correo.com', 'HASH_SUPERADMIN', 1, 1
);

-- Usuario Admin (con referencias a Luis como creador)
INSERT INTO usuarios (
    nombre, apellido, correo, contrasena, rol_id, estado, creado_por, modificado_por
)
VALUES (
    'Eucladiana', 'Paucar Rosas', 'eucladiana@correo.com', 'HASH_ADMIN', 2, 1, 1, 1
);

-- (Opcional) Actualiza a Luis con su propio ID como creador/modificador
UPDATE usuarios
SET creado_por = 1, modificado_por = 1
WHERE id = 1;


CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    estado TINYINT(1) DEFAULT 1,
    imagen VARCHAR(255) DEFAULT NULL,
    creado_por INT,
    modificado_por INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id),
    FOREIGN KEY (modificado_por) REFERENCES usuarios(id)
);

INSERT INTO categorias (nombre, descripcion, estado,imagen, creado_por, modificado_por)
VALUES
('Ropa de Hombre', 'Categoría que agrupa prendas para caballeros.', 1,'imagen.jpg', 1, 1),
('Ropa de Mujer', 'Categoría dedicada a vestimenta femenina.', 0,'imagen.jpg', 2, 2),
('Niños', 'Prendas para niños de todas las edades.', 1,'imagen.jpg', 1, 2),
('Accesorios', 'Bolsos, cinturones, sombreros, etc.', 0,'imagen.jpg', 2, 1),
('Ofertas', 'Productos en promoción o con descuentos.', 1,'imagen.jpg', 1, 1);

CREATE TABLE marcas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    estado TINYINT(1) DEFAULT 1,
    creado_por INT,
    modificado_por INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id),
    FOREIGN KEY (modificado_por) REFERENCES usuarios(id)
);

INSERT INTO marcas (nombre, descripcion, estado, creado_por, modificado_por)
VALUES
('Nike', 'Marca deportiva internacional', 1, 1, 1),
('Adidas', 'Marca reconocida por su ropa y calzado deportivo', 1, 2, 2),
('Zara', 'Marca de moda urbana y casual', 1, 1, 2);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    categoria_id INT NOT NULL,
    marca_id INT,
    estado TINYINT(1) DEFAULT 1,
    creado_por INT,
    modificado_por INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT,
    FOREIGN KEY (marca_id) REFERENCES marcas(id),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id),
    FOREIGN KEY (modificado_por) REFERENCES usuarios(id)
);

INSERT INTO productos (codigo, nombre, descripcion, precio, stock, categoria_id, marca_id, estado, creado_por, modificado_por)
VALUES
('P001', 'Polo Deportivo Hombre', 'Polo Nike para entrenamientos', 79.90, 15, 1, 1, 1, 1, 1),
('P002', 'Leggins Mujer', 'Leggins cómodos para correr marca Adidas', 99.50, 20, 2, 2, 1, 2, 2),
('P003', 'Casaca Casual Hombre', 'Casaca Zara estilo urbano para hombres', 149.90, 10, 1, 3, 1, 1, 2);
