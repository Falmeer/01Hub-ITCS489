-- Create database
CREATE DATABASE IF NOT EXISTS components DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE components;

-- ----------------------------------------
-- Users Table
-- ----------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'customer') NOT NULL
) ENGINE=InnoDB;

-- ----------------------------------------
-- Categories Table
-- ----------------------------------------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Sample categories
INSERT INTO categories (name) VALUES
('Monitors'), ('Keyboards'), ('Graphics Cards'), ('Motherboards'),
('RAM'), ('Storage'), ('Power Supplies'), ('Cases');

-- ----------------------------------------
-- Products Table
-- ----------------------------------------
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    image VARCHAR(255),
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------
-- Orders Table
-- ----------------------------------------
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status ENUM('acknowledged','in process','in transit','completed') DEFAULT 'acknowledged',
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------
-- Order Items Table
-- ----------------------------------------
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------
-- Checkout Table
-- ----------------------------------------
CREATE TABLE checkout (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------
-- Product Reviews Table
-- ----------------------------------------
CREATE TABLE product_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- Users
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$Y.zz6nBzemde3hNiEymupeuAjSIkNPk32faIxNpX61qEHvwitEWIm', 'admin'),
('staff', '$2y$10$Y.zz6nBzemde3hNiEymupeuAjSIkNPk32faIxNpX61qEHvwitEWIm', 'staff'),
('customer', '$2y$10$Y.zz6nBzemde3hNiEymupeuAjSIkNPk32faIxNpX61qEHvwitEWIm', 'customer');

-- Products
INSERT INTO products (name, description, price, quantity, image, category_id) VALUES
('Acer 27\" Monitor', 'Full HD IPS display, 144Hz', 109.99, 30, 'monitor_acer.jpg', 1),
('Logitech Mechanical Keyboard', 'RGB Backlit, Brown Switches', 79.99, 50, 'keyboard_logitech.jpg', 2),
('NVIDIA RTX 4060', '8GB GDDR6 Graphic Card', 379.99, 20, 'gcard_4060.jpg', 3),
('Corsair 16GB RAM', 'DDR4 3200MHz', 69.99, 40, 'ram_16gb.jpg', 5),
('Intel i5 CPU', '10th Gen Processor', 399.99, 50, 'cpu_i5.jpg', 4),
('Samsung SSD 1TB', 'NVMe M.2 storage', 99.99, 60, 'ssd_1tb.jpg', 6),
('AOC 24\" Monitor', '144Hz Full HD', 149.99, 30, 'monitor_aoc.jpg', 1);


-- Product Reviews
-- INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES
-- (1, 3, 5, 'Amazing clarity for gaming!'),
-- (2, 3, 4, 'Nice tactile keys, loud sound though.'),
-- (3, 3, 5, 'Runs everything at ultra settings.'),
-- (4, 3, 4, 'Fast RAM, good value.');

