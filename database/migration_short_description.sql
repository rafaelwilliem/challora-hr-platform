-- Deskripsi singkat untuk tampilan di card (max 2-3 kalimat)
USE challora_recruitment;

ALTER TABLE jobs ADD COLUMN short_description VARCHAR(255) DEFAULT NULL COMMENT 'Deskripsi singkat untuk card, max 2-3 kalimat';
