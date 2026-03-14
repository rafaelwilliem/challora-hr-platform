-- HR UX: job_type, min_education, is_urgent, lokasi, skill/benefit
USE challora_recruitment;

-- Jenis pekerjaan, minimal edukasi, urgent
ALTER TABLE jobs ADD COLUMN job_type VARCHAR(50) DEFAULT NULL;
ALTER TABLE jobs ADD COLUMN min_education VARCHAR(50) DEFAULT NULL;
ALTER TABLE jobs ADD COLUMN is_urgent TINYINT(1) DEFAULT 0;

-- Lokasi terpisah
ALTER TABLE jobs ADD COLUMN provinsi VARCHAR(100) DEFAULT NULL;
ALTER TABLE jobs ADD COLUMN kota VARCHAR(100) DEFAULT NULL;
ALTER TABLE jobs ADD COLUMN kecamatan VARCHAR(100) DEFAULT NULL;
