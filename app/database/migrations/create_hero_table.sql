CREATE TABLE IF NOT EXISTS hero (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    subtitle TEXT,
    description TEXT,
    background_type TEXT NOT NULL DEFAULT 'image',
    background_value TEXT,
    overlay_opacity INTEGER NOT NULL DEFAULT 50,
    text_alignment TEXT NOT NULL DEFAULT 'center',
    animation_style TEXT NOT NULL DEFAULT 'fade',
    cta_text TEXT,
    cta_url TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);