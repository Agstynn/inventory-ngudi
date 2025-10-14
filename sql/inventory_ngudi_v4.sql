-- inventory_ngudi_v4.sql
USE inventory_ngudi;

-- add gambar column to alat and equipments if not exists
ALTER TABLE alat ADD COLUMN gambar VARCHAR(255) DEFAULT NULL;
ALTER TABLE equipments ADD COLUMN gambar VARCHAR(255) DEFAULT NULL;
