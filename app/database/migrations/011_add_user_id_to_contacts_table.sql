ALTER TABLE contacts ADD COLUMN user_id INTEGER;
CREATE INDEX IF NOT EXISTS idx_contacts_user_id ON contacts(user_id);
