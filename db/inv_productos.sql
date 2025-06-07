
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
('Producto E', 50.00, 10);