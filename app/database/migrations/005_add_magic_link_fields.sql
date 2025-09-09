-- Add magic link fields to users table
ALTER TABLE users ADD COLUMN magic_link_token VARCHAR(255) DEFAULT NULL;
ALTER TABLE users ADD COLUMN magic_link_expires TIMESTAMP DEFAULT NULL;
CREATE INDEX idx_magic_link_token ON users (magic_link_token);
