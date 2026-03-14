-- Skill & Benefit categories
USE challora_recruitment;

CREATE TABLE IF NOT EXISTS skill_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS benefit_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS job_skills (
  job_id INT UNSIGNED NOT NULL,
  skill_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (job_id, skill_id),
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  FOREIGN KEY (skill_id) REFERENCES skill_categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS job_benefits (
  job_id INT UNSIGNED NOT NULL,
  benefit_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (job_id, benefit_id),
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  FOREIGN KEY (benefit_id) REFERENCES benefit_categories(id) ON DELETE CASCADE
);
