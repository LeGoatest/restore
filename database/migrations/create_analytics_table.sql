-- Create analytics table for website traffic tracking
CREATE TABLE IF NOT EXISTS website_analytics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_url VARCHAR(255) NOT NULL,
    page_title VARCHAR(255),
    user_agent TEXT,
    ip_address VARCHAR(45),
    referrer VARCHAR(255),
    session_id VARCHAR(100),
    visit_date DATE NOT NULL,
    visit_time TIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create index for faster queries
CREATE INDEX IF NOT EXISTS idx_analytics_date ON website_analytics(visit_date);
CREATE INDEX IF NOT EXISTS idx_analytics_page ON website_analytics(page_url);
CREATE INDEX IF NOT EXISTS idx_analytics_session ON website_analytics(session_id);

-- Insert some sample data for the last 30 days
INSERT INTO website_analytics (page_url, page_title, user_agent, ip_address, referrer, session_id, visit_date, visit_time) VALUES
-- Today's data
('/', 'Home - Restore Removal', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '192.168.1.100', 'https://google.com', 'sess_001', date('now'), '09:15:30'),
('/', 'Home - Restore Removal', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)', '192.168.1.101', 'https://facebook.com', 'sess_002', date('now'), '10:22:15'),
('/services', 'Services - Restore Removal', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '192.168.1.102', '/', 'sess_003', date('now'), '11:45:22'),
('/contact', 'Contact - Restore Removal', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', '192.168.1.103', '/services', 'sess_004', date('now'), '14:30:45'),
('/', 'Home - Restore Removal', 'Mozilla/5.0 (Android 11; Mobile; rv:68.0)', '192.168.1.104', 'https://bing.com', 'sess_005', date('now'), '16:20:10'),

-- Yesterday's data
('/', 'Home - Restore Removal', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '192.168.1.105', 'https://google.com', 'sess_006', date('now', '-1 day'), '08:30:15'),
('/services', 'Services - Restore Removal', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)', '192.168.1.106', '/', 'sess_007', date('now', '-1 day'), '12:15:30'),
('/contact', 'Contact - Restore Removal', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '192.168.1.107', '/services', 'sess_008', date('now', '-1 day'), '15:45:20'),

-- Last 7 days sample data
('/', 'Home - Restore Removal', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '192.168.1.108', 'https://google.com', 'sess_009', date('now', '-2 days'), '09:20:15'),
('/', 'Home - Restore Removal', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)', '192.168.1.109', 'https://facebook.com', 'sess_010', date('now', '-3 days'), '11:30:45'),
('/services', 'Services - Restore Removal', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', '192.168.1.110', '/', 'sess_011', date('now', '-4 days'), '13:15:22'),
('/contact', 'Contact - Restore Removal', 'Mozilla/5.0 (Android 11; Mobile; rv:68.0)', '192.168.1.111', '/services', 'sess_012', date('now', '-5 days'), '16:45:30'),
('/', 'Home - Restore Removal', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '192.168.1.112', 'https://bing.com', 'sess_013', date('now', '-6 days'), '10:20:15');