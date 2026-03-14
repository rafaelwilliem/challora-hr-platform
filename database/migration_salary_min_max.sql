-- Tambah kolom min_salary dan max_salary untuk filter range
USE challora_recruitment;

ALTER TABLE jobs
  ADD COLUMN min_salary INT UNSIGNED DEFAULT NULL COMMENT 'gaji minimal (angka)',
  ADD COLUMN max_salary INT UNSIGNED DEFAULT NULL COMMENT 'gaji maksimal (angka)';
