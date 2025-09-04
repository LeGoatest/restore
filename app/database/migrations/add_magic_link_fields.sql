-- Add magic link fields to users table
ALTER TABLE users
ADD COLUMN magic_link_token VARCHAR(255) DEFAULT NULL,
ADD COLUMN magic_link_expires TIMESTAMP DEFAULT NULL,
ADD INDEX idx_magic_link_token (magic_link_token);