-- Seed awal skill & benefit categories
USE challora_recruitment;

INSERT IGNORE INTO skill_categories (id, name) VALUES
(1, 'PHP'), (2, 'JavaScript'), (3, 'Python'), (4, 'Excel'), (5, 'Leadership'),
(6, 'Komunikasi'), (7, 'Manajemen Projek'), (8, 'SQL'), (9, 'Laravel'), (10, 'React');

INSERT IGNORE INTO benefit_categories (id, name) VALUES
(1, 'BPJS'), (2, 'THR'), (3, 'WFH'), (4, 'Tunjangan Transport'), (5, 'Tunjangan Makan'),
(6, 'Asuransi Kesehatan'), (7, 'Cuti Tahunan'), (8, 'Bonus'), (9, 'Training'), (10, 'Parkir');
