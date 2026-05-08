-- MacCafe Ordering System Database Schema
-- Created for PHP MVC Application

CREATE DATABASE IF NOT EXISTS maccafe_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE maccafe_db;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    city VARCHAR(100),
    role ENUM('admin', 'staff', 'customer') DEFAULT 'customer',
    email_verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- Product categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    preparation_time INT DEFAULT 0 COMMENT 'Preparation time in minutes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_available (is_available),
    INDEX idx_name (name)
);

-- Product variants (for sizes, flavors, etc.)
CREATE TABLE product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    name VARCHAR(100) NOT NULL COMMENT 'e.g., Small, Medium, Large',
    price_adjustment DECIMAL(10,2) DEFAULT 0.00,
    is_available BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
);

-- Shopping cart (for logged-in users)
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_id VARCHAR(255),
    product_id INT NOT NULL,
    variant_id INT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_session (session_id),
    INDEX idx_product (product_id)
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    subtotal DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) DEFAULT 0.00,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'gcash', 'paymaya') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    customer_name VARCHAR(200),
    customer_phone VARCHAR(20),
    customer_email VARCHAR(150),
    notes TEXT,
    prepared_by INT NULL COMMENT 'Staff who prepared the order',
    preparation_started_at TIMESTAMP NULL,
    ready_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (prepared_by) REFERENCES users(id),
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_order_number (order_number),
    INDEX idx_created_at (created_at)
);

-- Order items
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    variant_name VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (variant_id) REFERENCES product_variants(id),
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
);

-- Order status history
CREATE TABLE order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled') NOT NULL,
    changed_by INT NULL COMMENT 'User who changed the status',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id),
    INDEX idx_order (order_id),
    INDEX idx_status (status)
);

-- Inventory table
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    variant_id INT NULL,
    quantity INT NOT NULL DEFAULT 0,
    reorder_level INT DEFAULT 10,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_quantity (quantity)
);

-- Password reset tokens
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_token (token)
);

-- Login attempts for security
CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    success BOOLEAN NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_ip (ip_address),
    INDEX idx_created_at (created_at)
);

-- Insert default categories
INSERT INTO categories (name, description, sort_order) VALUES
('Coffee', 'Hot and cold coffee beverages', 1),
('Coffee Frappe', 'Blended coffee drinks', 2),
('Milk Tea', 'Tea-based milk beverages', 3),
('Fruit Tea', 'Refreshing fruit-infused teas', 4),
('Non Coffee', 'Non-caffeinated hot and cold beverages', 5),
('Non Coffee Frappe', 'Blended non-caffeinated drinks', 6),
('Snacks', 'Light snacks and finger foods', 7),
('Rice Bowl', 'Rice-based meal bowls', 8),
('Pasta', 'Various pasta dishes', 9),
('2PC Chicken', '2-piece chicken meals with java rice', 10);

-- Insert default admin user (password: admin123)
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('Admin', 'User', 'admin@maccafe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert products from menu
INSERT INTO products (category_id, name, description, price, preparation_time, image) VALUES
-- Coffee Category
((SELECT id FROM categories WHERE name = 'Coffee'), 'Americano', 'Classic Americano coffee available hot or iced', 55.00, 2, 'americano.webp'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Spanish Latte', 'Sweet Spanish latte with condensed milk', 65.00, 3, 'spanish-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Matcha Espresso', 'Unique blend of matcha and espresso', 60.00, 3, 'matcha-espresso.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Caramel Macchiato', 'Espresso with vanilla syrup and caramel drizzle', 65.00, 3, 'caramel-macchiato.jpg'),

-- Coffee Frappe Category
((SELECT id FROM categories WHERE name = 'Coffee Frappe'), 'Java Chip', 'Blended coffee with chocolate chips', 89.00, 4, 'javaF.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee Frappe'), 'Butterscotch', 'Creamy butterscotch blended coffee', 79.00, 4, 'bl.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee Frappe'), 'Almond Frappe', 'Nutty almond flavored blended coffee', 79.00, 4, 'almondf.jpg'),

-- Milk Tea Category
((SELECT id FROM categories WHERE name = 'Milk Tea'), 'Okinawa', 'Traditional Okinawa milk tea', 39.00, 3, 'okinawa.jpg'),
((SELECT id FROM categories WHERE name = 'Milk Tea'), 'Wintermelon', 'Refreshing wintermelon milk tea', 39.00, 3, 'wintermt.jpg'),
((SELECT id FROM categories WHERE name = 'Milk Tea'), 'Salted Caramel', 'Sweet and salty caramel Milk tea', 39.00, 3, 'saltedcaramel.png'),

-- Fruit Tea Category
((SELECT id FROM categories WHERE name = 'Fruit Tea'), 'Strawberry Tea', 'Fresh strawberry infused tea', 45.00, 3, 'fruitteaa.jpg'),
((SELECT id FROM categories WHERE name = 'Fruit Tea'), 'Green Apple Tea', 'Crisp green apple flavored tea', 45.00, 3, 'fruitteaa.jpg'),
((SELECT id FROM categories WHERE name = 'Fruit Tea'), 'Lychee Tea', 'Sweet lychee flavored tea', 45.00, 3, 'fruitteaa.jpg'),

-- Non Coffee Category
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Strawberries and Cream', 'Creamy strawberry milk drink', 65.00, 3, 'sc.jpg'),
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Matcha Milk', 'Smooth matcha milk beverage', 70.00, 3, 'noncoffe.jpg'),
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Iced Chocolate', 'Rich chocolate cold drink', 65.00, 3, 'ic.jpeg'),

-- Non Coffee Frappe Category
((SELECT id FROM categories WHERE name = 'Non Coffee Frappe'), 'Dark Choco', 'Intense dark chocolate blended drink', 69.00, 4, 'dc.jpg'),
((SELECT id FROM categories WHERE name = 'Non Coffee Frappe'), 'Caramel Frappe', 'Sweet caramel blended beverage', 69.00, 4, 'cf.jpg'),
((SELECT id FROM categories WHERE name = 'Non Coffee Frappe'), 'Strawberry Frappe', 'Fresh strawberry blended drink', 69.00, 4, 'scc.jpg'),

-- Snacks Category
((SELECT id FROM categories WHERE name = 'Snacks'), 'Regular Fries', 'Classic golden french fries', 50.00, 5, 'regularfries.jpg'),
((SELECT id FROM categories WHERE name = 'Snacks'), 'Cheesy Fries', 'French fries topped with melted cheese', 79.00, 6, 'cheesyfries.jpg'),
((SELECT id FROM categories WHERE name = 'Snacks'), 'Nachos', 'Crispy nachos with toppings', 89.00, 6, 'nachoos.jpg'),

-- Rice Bowl Category
((SELECT id FROM categories WHERE name = 'Rice Bowl'), 'Cheesy Hungarian', 'Hungarian sausage with cheesy rice bowl', 89.00, 10, 'cheesyhungarian.jpg'),
((SELECT id FROM categories WHERE name = 'Rice Bowl'), 'Bacon & Cheese', 'Bacon and cheese rice bowl', 89.00, 10, 'cheesyricebowl.jpg'),

-- Pasta Category
((SELECT id FROM categories WHERE name = 'Pasta'), 'Garlic Tuna Parmesan', 'Tuna pasta with garlic and parmesan', 109.00, 12, 'garlicTparme.jpg'),
((SELECT id FROM categories WHERE name = 'Pasta'), 'Meaty Spaghetti', 'Classic spaghetti with meat sauce', 109.00, 12, 'meatyspag.jpg'),

-- 2PC Chicken Category
((SELECT id FROM categories WHERE name = '2PC Chicken'), 'Garlic Parmesan Chicken', '2-piece chicken with garlic parmesan flavor and java rice', 200.00, 15, 'garlicp.png'),
((SELECT id FROM categories WHERE name = '2PC Chicken'), 'Teriyaki Chicken', '2-piece chicken with teriyaki glaze and java rice', 210.00, 15, 'teriyaki.jpg'),
((SELECT id FROM categories WHERE name = '2PC Chicken'), 'Honey Butter Chicken', '2-piece chicken with honey butter flavor and java rice', 220.00, 15, 'honeybutter.jpg');

-- Insert product variants for size options
INSERT INTO product_variants (product_id, name, price_adjustment) VALUES
-- Coffee variants (Americano)
((SELECT id FROM products WHERE name = 'Americano'), '16 oz', 10.00),
((SELECT id FROM products WHERE name = 'Americano'), '22 oz', 30.00),

-- Coffee variants (Spanish Latte)
((SELECT id FROM products WHERE name = 'Spanish Latte'), '16 oz', 5.00),
((SELECT id FROM products WHERE name = 'Spanish Latte'), '22 oz', 25.00),

-- Coffee variants (Matcha Espresso)
((SELECT id FROM products WHERE name = 'Matcha Espresso'), '16 oz', 10.00),
((SELECT id FROM products WHERE name = 'Matcha Espresso'), '22 oz', 30.00),

-- Coffee variants (Caramel Macchiato)
((SELECT id FROM products WHERE name = 'Caramel Macchiato'), '16 oz', 10.00),
((SELECT id FROM products WHERE name = 'Caramel Macchiato'), '22 oz', 30.00),

-- Coffee Frappe variants (Java Chip)
((SELECT id FROM products WHERE name = 'Java Chip'), '22 oz', 20.00),

-- Coffee Frappe variants (Butterscotch)
((SELECT id FROM products WHERE name = 'Butterscotch'), '22 oz', 20.00),

-- Coffee Frappe variants (Almond Frappe)
((SELECT id FROM products WHERE name = 'Almond Frappe'), '22 oz', 20.00),

-- Milk Tea variants (Okinawa)
((SELECT id FROM products WHERE name = 'Okinawa'), '22 oz', 10.00),

-- Milk Tea variants (Wintermelon)
((SELECT id FROM products WHERE name = 'Wintermelon'), '22 oz', 10.00),

-- Milk Tea variants (Salted Caramel)
((SELECT id FROM products WHERE name = 'Salted Caramel'), '22 oz', 10.00),

-- Fruit Tea variants (Strawberry Tea)
((SELECT id FROM products WHERE name = 'Strawberry Tea'), '22 oz', 10.00),

-- Fruit Tea variants (Green Apple Tea)
((SELECT id FROM products WHERE name = 'Green Apple Tea'), '22 oz', 10.00),

-- Fruit Tea variants (Lychee Tea)
((SELECT id FROM products WHERE name = 'Lychee Tea'), '22 oz', 10.00),

-- Non Coffee variants (Strawberries and Cream)
((SELECT id FROM products WHERE name = 'Strawberries and Cream'), '22 oz', 15.00),

-- Non Coffee variants (Matcha Milk)
((SELECT id FROM products WHERE name = 'Matcha Milk'), '22 oz', 15.00),

-- Non Coffee variants (Iced Chocolate)
((SELECT id FROM products WHERE name = 'Iced Chocolate'), '22 oz', 15.00),

-- Non Coffee Frappe variants (Dark Choco)
((SELECT id FROM products WHERE name = 'Dark Choco'), '22 oz', 20.00),

-- Non Coffee Frappe variants (Caramel Frappe)
((SELECT id FROM products WHERE name = 'Caramel Frappe'), '22 oz', 20.00),

-- Non Coffee Frappe variants (Strawberry Frappe)
((SELECT id FROM products WHERE name = 'Strawberry Frappe'), '22 oz', 20.00);

-- Add address and city columns to existing users table (for database updates)
ALTER TABLE users 
ADD COLUMN address VARCHAR(255) AFTER phone,
ADD COLUMN city VARCHAR(100) AFTER address;

-- Email verification tokens
CREATE TABLE email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_token (token)
);

-- Payment proofs
CREATE TABLE payment_proofs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM('gcash', 'paymaya', 'bank_transfer') NOT NULL,
    reference_number VARCHAR(100),
    proof_image VARCHAR(255),
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    verified_by INT NULL,
    verified_at TIMESTAMP NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id),
    INDEX idx_order (order_id),
    INDEX idx_status (status)
);

-- Update orders table to support manual payment
ALTER TABLE orders 
MODIFY COLUMN payment_method ENUM('cash', 'card', 'gcash', 'paymaya', 'bank_transfer') DEFAULT 'gcash';
