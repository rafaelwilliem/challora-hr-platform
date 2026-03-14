-- Skills and Benefits as free-form JSON arrays
USE challora_recruitment;

ALTER TABLE jobs
  ADD COLUMN skills_json TEXT DEFAULT NULL COMMENT 'JSON array of skill keywords',
  ADD COLUMN benefits_json TEXT DEFAULT NULL COMMENT 'JSON array of benefit keywords';
