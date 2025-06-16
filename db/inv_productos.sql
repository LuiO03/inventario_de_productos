
DROP DATABASE IF EXISTS inv_productos;
CREATE DATABASE inv_productos;

USE inv_productos;

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO productos (nombre, precio, stock) VALUES
('Producto A', 10.00, 100),
('Producto B', 20.00, 50),
('Producto C', 30.00, 75),
('Producto D', 40.00, 25),
('Producto E', 50.00, 10),
('Producto F', 50.00, 10),
('Producto G', 50.00, 10),
('Producto h', 50.00, 10),
('Producto I', 50.00, 10),
('Producto J', 50.00, 10),
('Producto K', 50.00, 10),
('Producto L', 50.00, 10);


CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categorias (nombre, descripcion) VALUES
('Camisas', 'Prendas superiores de vestir con botones'),
('Pantalones', 'Prendas inferiores para vestir de diferentes estilos'),
('Zapatos', 'Calzado para diferentes ocasiones'),
('Accesorios', 'Complementos como gorras, cinturones y lentes'),
('Casacas', 'Prendas exteriores para clima frío');

CREATE TABLE marcas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO marcas (nombre, descripcion) VALUES
('Nike', 'Marca deportiva internacional'),
('Zara', 'Marca de moda para todo público'),
('Levi\'s', 'Marca reconocida de jeans y ropa casual'),
('Adidas', 'Marca de ropa y calzado deportivo'),
('H&M', 'Marca de moda económica y versátil');




