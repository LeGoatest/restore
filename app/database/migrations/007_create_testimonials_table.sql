CREATE TABLE testimonials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    author_name TEXT NOT NULL,
    author_location TEXT,
    quote_text TEXT NOT NULL,
    rating INTEGER CHECK(rating >= 1 AND rating <= 5),
    is_featured BOOLEAN DEFAULT 0,
    created_at DATETIME
);
