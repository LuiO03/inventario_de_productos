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
    slug VARCHAR(100) UNIQUE,
    creado_por INT,
    modificado_por INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    parent_id INT DEFAULT NULL,

    FOREIGN KEY (creado_por) REFERENCES usuarios(id),
    FOREIGN KEY (modificado_por) REFERENCES usuarios(id),
    FOREIGN KEY (parent_id) REFERENCES categorias(id) ON DELETE RESTRICT
);

INSERT INTO categorias (nombre, descripcion, estado, imagen, slug, creado_por, modificado_por, parent_id) 
VALUES
('Ropa para Hombre', 'Categoría que agrupa prendas para caballeros.', 1, 'imagen.jpg', 'ropa-para-hombre', 2, NULL, NULL),
('Ropa para Niñas', 'Categoría de vestimenta infantil femenina.', 1, 'imagen.jpg', 'ropa-para-ninas', 2, NULL, NULL),
('Ropa para Adolescentes', 'Moda juvenil para adolescentes en tendencia.', 1, 'imagen.jpg', 'ropa-para-adolescentes', 2, NULL, NULL),
('Ropa para Ancianos', 'Categoría enfocada en vestimenta cómoda y funcional para personas mayores.', 1, 'imagen.jpg', 'ropa-para-ancianos', 2, NULL, NULL),
('Ropa para Mascotas', 'Ropa y accesorios diseñados para mascotas.', 1, 'imagen.jpg', 'ropa-para-mascotas', 2, NULL, NULL),

-- Subcategorías ejemplo bajo "Ropa para Hombre" (id = 1)
('Polos para Hombre', 'Polos cómodos y frescos para caballeros.', 1, 'imagen.jpg', 'polos-para-hombre', 1, NULL, 1),
('Pantalones para Hombre', 'Pantalones formales y casuales para hombres.', 1, 'imagen.jpg', 'pantalones-para-hombre', 1, NULL, 1),

-- Subcategorías bajo "Ropa para Mascotas" (id = 5)
('Ropa para Perros', 'Ropa y accesorios diseñados para perros.', 1, 'imagen.jpg', 'ropa-para-perros', 1, NULL, 5),
('Ropa para Gatos', 'Ropa y complementos pensados para gatos.', 1, 'imagen.jpg', 'ropa-para-gatos', 1, NULL, 5),
('Ropa para Conejos', 'Prendas y accesorios para conejos como mascotas.', 1, 'imagen.jpg', 'ropa-para-conejos', 1, NULL, 5);


CREATE TABLE marcas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    estado TINYINT(1) DEFAULT 1,
    imagen VARCHAR(255) DEFAULT NULL,
    slug VARCHAR(100) UNIQUE,
    creado_por INT,
    modificado_por INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id),
    FOREIGN KEY (modificado_por) REFERENCES usuarios(id)
);

INSERT INTO marcas (nombre, descripcion, estado, imagen, slug, creado_por, modificado_por)
VALUES 
('Nike', 'Marca líder en ropa y calzado deportivo', 1, 'nike.png', 'nike', 1, NULL),
('Adidas', 'Marca reconocida mundialmente por calzado y ropa deportiva', 1, 'adidas.png', 'adidas', 1, NULL),
('Puma', 'Marca deportiva con estilo urbano', 1, 'puma.png', 'puma', 1, NULL),
('Reebok', 'Marca especializada en fitness y deportes de alto rendimiento', 1, 'reebok.png', 'reebok', 1, NULL),
('Under Armour', 'Marca de ropa deportiva con tecnología avanzada', 1, 'underarmour.png', 'under-armour', 1, NULL);

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
