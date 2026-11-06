CREATE DATABASE IF NOT EXISTS techstore_u3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techstore_u3;
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  descripcion TEXT NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  icono VARCHAR(30) NOT NULL DEFAULT '📦',
  activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  total DECIMAL(12,2) NOT NULL,
  estado ENUM('pendiente','completado','cancelado') NOT NULL DEFAULT 'pendiente',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pedidos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS pedido_detalles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  producto_id INT NULL,
  nombre_producto VARCHAR(150) NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  CONSTRAINT fk_detalles_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
  CONSTRAINT fk_detalles_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
) ENGINE=InnoDB;
INSERT INTO productos (nombre,descripcion,precio,stock,icono,activo)
SELECT 'Audífonos inalámbricos','Disfruta tu música favorita.',399,20,'🎧',1
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre='Audífonos inalámbricos');
INSERT INTO productos (nombre,descripcion,precio,stock,icono,activo)
SELECT 'Teclado inalámbrico','Ideal para estudiar y trabajar.',299,15,'⌨️',1
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre='Teclado inalámbrico');
INSERT INTO productos (nombre,descripcion,precio,stock,icono,activo)
SELECT 'Mouse inalámbrico','Comodidad para tu computadora.',199,30,'🖱️',1
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre='Mouse inalámbrico');
INSERT INTO productos (nombre,descripcion,precio,stock,icono,activo)
SELECT 'Bocina Bluetooth','Lleva tu música a todas partes.',499,12,'🔊',1
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE nombre='Bocina Bluetooth');
