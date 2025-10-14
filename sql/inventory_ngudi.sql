-- SQL dump for inventory_ngudi (minimal schema)
CREATE DATABASE IF NOT EXISTS inventory_ngudi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventory_ngudi;

-- roles
CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  description VARCHAR(255)
);

INSERT INTO roles (name,description) VALUES ('admin','Administrator sistem') ON DUPLICATE KEY UPDATE name=name;

-- users (passwords created via create_admin.php)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(200),
  role_id INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- equipments
CREATE TABLE IF NOT EXISTS equipments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL UNIQUE,
  name VARCHAR(200) NOT NULL,
  brand VARCHAR(100),
  model VARCHAR(100),
  quantity INT DEFAULT 1,
  `condition` ENUM('Baik','Rusak','Perbaikan') DEFAULT 'Baik',
  location VARCHAR(150),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- sample data
INSERT INTO equipments (code,name,brand,model,quantity,`condition`,location) VALUES
('ALK-001','Stetoskop Littmann','Littmann','Classic III',10,'Baik','Gudang Kesehatan'),
('ALK-002','Tensimeter Digital','Omron','HEM-7120',5,'Baik','Poliklinik'),
('ALK-003','Thermometer Infrared','Xiaomi','MZ-T1',3,'Baik','Gudang Kesehatan')
ON DUPLICATE KEY UPDATE code=code;
