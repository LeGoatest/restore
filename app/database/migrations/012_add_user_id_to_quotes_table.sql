ALTER TABLE quotes ADD COLUMN user_id INTEGER;
CREATE INDEX IF NOT EXISTS idx_quotes_user_id ON quotes(user_id);
