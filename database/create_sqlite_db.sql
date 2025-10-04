-- SQLite version of your users table
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    number TEXT NOT NULL,
    password TEXT DEFAULT NULL
);

-- Insert your existing data
INSERT OR IGNORE INTO users (id, name, email, number, password) VALUES
(1, 'John Doe', 'john.doe@example.com', '09171234567', ''),
(2, 'Jane Smith', 'jane.smith@example.com', '09182345678', ''),
(3, 'Michael Johnson', 'michael.johnson@example.com', '09193456789', ''),
(4, 'Emily Davis', 'emily.davis@example.com', '09204567890', ''),
(5, 'Daniel Garcia', 'daniel.garcia@example.com', '09215678901', ''),
(6, 'dan', 'icalladan@gmail.com', '09362144338', ''),
(8, 'John Doe', 'johndoe@example.com', '09123456789', '');
