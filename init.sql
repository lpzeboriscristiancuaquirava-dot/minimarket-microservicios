CREATE DATABASE IF NOT EXISTS db_minimarket_pro413 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_minimarket_pro413;

CREATE TABLE IF NOT EXISTS Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    proveedor VARCHAR(100) NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    precio DECIMAL(10,2) NOT NULL CHECK (precio >= 0),
    stock INT NOT NULL CHECK (stock >= 0),
    eliminado TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO Producto (nombre, proveedor, fecha_vencimiento, precio, stock, eliminado) VALUES 
('Leche Entera 1L', 'Lacteos S.A.', '2026-12-31', 1.50, 50, 0),
('Pan Integral', 'Panaderia Central', '2026-11-15', 2.00, 30, 0);