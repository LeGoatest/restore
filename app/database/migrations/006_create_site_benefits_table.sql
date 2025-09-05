CREATE TABLE site_benefits (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    icon TEXT NOT NULL,
    sort_order INTEGER DEFAULT 0
);
