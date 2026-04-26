CREATE DATABASE IF NOT EXISTS salon_social CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE salon_social;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','residente','supervisor') NOT NULL DEFAULT 'residente',
  phone VARCHAR(20) DEFAULT NULL,
  whatsapp VARCHAR(20) DEFAULT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS inventario_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(140) NOT NULL,
  unidad INT DEFAULT 1,
  notas TEXT,
  creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS inventario_movimientos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  item_id INT UNSIGNED NOT NULL,
  cantidad INT NOT NULL,
  tipo ENUM('entrada','salida') NOT NULL,
  motivo VARCHAR(255) DEFAULT NULL,
  usuario_id INT UNSIGNED DEFAULT NULL,
  creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_item FOREIGN KEY (item_id) REFERENCES inventario_items(id) ON DELETE CASCADE,
  CONSTRAINT fk_mov_user FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_item (item_id)
);

CREATE TABLE IF NOT EXISTS reservas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  titulo VARCHAR(140) NOT NULL,
  fecha_evento DATE NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  asistentes INT DEFAULT 0,
  notas TEXT,
  estado ENUM('pendiente','aprobada','rechazada','cancelada') NOT NULL DEFAULT 'pendiente',
  creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_res_user FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_fecha (fecha_evento)
);
