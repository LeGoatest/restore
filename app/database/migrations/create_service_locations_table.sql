-- Create service_locations table for schema.org structured data
CREATE TABLE IF NOT EXISTS service_locations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type VARCHAR(50) NOT NULL DEFAULT 'City',
    name VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create index for faster lookups
CREATE INDEX IF NOT EXISTS idx_service_locations_type ON service_locations(type);
CREATE INDEX IF NOT EXISTS idx_service_locations_name ON service_locations(name);

-- Insert the Florida cities data
INSERT INTO service_locations (type, name) VALUES
('City', 'Homosassa Springs'),
('City', 'Homosassa'),
('City', 'Crystal River'),
('City', 'Inverness'),
('City', 'Ocala'),
('City', 'The Villages'),
('City', 'Lecanto'),
('City', 'Beverly Hills'),
('City', 'Pine Ridge'),
('City', 'Citrus Springs'),
('City', 'Floral City'),
('City', 'Wildwood'),
('City', 'Brooksville'),
('City', 'Dade City'),
('City', 'Leesburg'),
('City', 'Belleview'),
('City', 'Silver Springs'),
('City', 'Citrus Hills'),
('City', 'On Top of the World'),
('City', 'Lady Lake'),
('City', 'Sugarmill Woods'),
('City', 'Spring Hill'),
('City', 'Hernando Beach'),
('City', 'Hudson'),
('City', 'New Port Richey'),
('City', 'Clermont'),
('City', 'Tavares'),
('City', 'Black Diamond'),
('City', 'Groveland'),
('City', 'Sumterville'),
('City', 'Lake Panasoffkee'),
('City', 'Oxford'),
('City', 'Fruitland Park'),
('City', 'Ocklawaha'),
('City', 'Weeki Wachee');