-- Base de datos para el proyecto Google Store (referencia)
-- Impórtalo desde phpMyAdmin: pestaña "Importar" -> selecciona este archivo

CREATE DATABASE IF NOT EXISTS tienda_google CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tienda_google;

-- Cabecera de cada pedido (una fila por cada "compra" finalizada en el carrito)
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL
);

-- Detalle: qué productos y cuántos venían en cada pedido
CREATE TABLE IF NOT EXISTS detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_nombre VARCHAR(150) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
);
