-- Additional schema for alat, pemeliharaan, and perbaikan
USE inventory_ngudi;

-- table alat (main equipment table)
CREATE TABLE IF NOT EXISTS alat (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(50) NOT NULL UNIQUE,
  nama VARCHAR(200) NOT NULL,
  no_seri VARCHAR(100),
  kategori VARCHAR(100),
  jumlah INT DEFAULT 1,
  satuan VARCHAR(50),
  kondisi VARCHAR(50) DEFAULT 'Baik',
  ruangan VARCHAR(100),
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- sample alat data
INSERT INTO alat (kode,nama,no_seri,kategori,jumlah,satuan,kondisi,ruangan,keterangan) VALUES
('ALK-001','Stetoskop Littmann','ST-LIT-001','Keperawatan',10,'unit','Baik','Lab Keperawatan','Stetoskop untuk praktik mahasiswa'),
('ALK-002','Tensimeter Digital Omron','TM-OM-002','Keperawatan',5,'unit','Baik','Poliklinik','Tensimeter digital otomatis'),
('ALK-003','Thermometer Infrared','TH-XM-003','Keperawatan',3,'unit','Baik','Gudang Kesehatan','Termometer non-kontak')
ON DUPLICATE KEY UPDATE kode=kode;

-- table pemeliharaan (maintenance)
CREATE TABLE IF NOT EXISTS pemeliharaan_alat (
  id_pemeliharaan INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  alat_id INT,
  nama_alat VARCHAR(200),
  no_seri VARCHAR(100),
  ruangan VARCHAR(100),
  jenis_pemeliharaan VARCHAR(100),
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (alat_id) REFERENCES alat(id) ON DELETE SET NULL
);

-- table perbaikan (repairs)
CREATE TABLE IF NOT EXISTS perbaikan_alat (
  id_perbaikan INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  alat_id INT,
  nama_alat VARCHAR(200),
  no_seri VARCHAR(100),
  ruangan VARCHAR(100),
  status VARCHAR(50) DEFAULT 'Dalam Proses',
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (alat_id) REFERENCES alat(id) ON DELETE SET NULL
);

-- sample maintenance & repair
INSERT INTO pemeliharaan_alat (tanggal, alat_id, nama_alat, no_seri, ruangan, jenis_pemeliharaan, keterangan) VALUES
('2025-10-01', 1, 'Stetoskop Littmann', 'ST-LIT-001', 'Lab Keperawatan', 'Rutin', 'Pembersihan dan pengecekan selang');
INSERT INTO perbaikan_alat (tanggal, alat_id, nama_alat, no_seri, ruangan, status, keterangan) VALUES
('2025-10-05', 2, 'Tensimeter Digital Omron', 'TM-OM-002', 'Poliklinik', 'Selesai', 'Penggantian baterai dan kalibrasi');
