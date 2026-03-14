-- Tambah kolom diploma_path dan photo_path ke tabel applications
-- Tambah status 'reviewed' untuk CV review
-- Jalankan sekali untuk memperbarui database yang sudah ada.

USE challora_recruitment;

ALTER TABLE applications ADD COLUMN diploma_path VARCHAR(255) DEFAULT NULL;
ALTER TABLE applications ADD COLUMN photo_path VARCHAR(255) DEFAULT NULL;

ALTER TABLE applications
  MODIFY COLUMN status ENUM('pending', 'reviewed', 'accepted', 'rejected') NOT NULL DEFAULT 'pending';
