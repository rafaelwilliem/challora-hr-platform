-- Migration: tabel password_resets (lupa password) + kolom deadline/max_applicants di jobs
-- Jalankan sekali

USE challora_recruitment;

CREATE TABLE IF NOT EXISTS password_resets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token VARCHAR(64) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE jobs
  ADD COLUMN deadline DATETIME DEFAULT NULL COMMENT 'batas waktu lamaran',
  ADD COLUMN max_applicants INT UNSIGNED DEFAULT NULL COMMENT 'batas jumlah pelamar';
