-- 1. Rename the existing services table
ALTER TABLE services RENAME TO services_old;

-- 2. Create the new services table with the desired schema
CREATE TABLE services (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    icon TEXT,
    category TEXT,
    sort_order INTEGER DEFAULT 0,
    is_featured BOOLEAN DEFAULT 0
);

-- 3. Copy the data from the old table to the new one
INSERT INTO services (id, name, description, category, sort_order)
SELECT id, name, description, category, sort_order FROM services_old;

-- 4. Drop the old table
DROP TABLE services_old;
