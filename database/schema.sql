-- CafeCat Ordering System Database Schema
-- Created for PHP MVC Application

CREATE DATABASE IF NOT EXISTS cafecat_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cafecat_db;

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
    role ENUM('admin', 'staff', 'customer', 'rider') DEFAULT 'customer',
    is_senior TINYINT(1) NOT NULL DEFAULT 0,
    is_pwd TINYINT(1) NOT NULL DEFAULT 0,
    discount_id_no VARCHAR(64) NULL,
    discount_id_image VARCHAR(255) NULL,
    discount_verified TINYINT(1) NOT NULL DEFAULT 0,
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
    is_bestseller BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
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
    discount_type ENUM('none', 'senior', 'pwd') DEFAULT 'none',
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    discount_id_no VARCHAR(64) NULL,
    discount_status ENUM('none', 'pending', 'verified', 'rejected') DEFAULT 'none',
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'gcash', 'paymaya') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    customer_name VARCHAR(200),
    customer_phone VARCHAR(20),
    customer_email VARCHAR(150),
    customer_address VARCHAR(255),
    customer_city VARCHAR(100),
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
    code CHAR(6) NULL,
    expires_at TIMESTAMP NOT NULL,
    attempts INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_token (token),
    INDEX idx_code (code)
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

-- CafeCat default categories (from revision menu boards)
INSERT INTO categories (name, description, sort_order) VALUES
('Coffee', 'Our best-loved coffee creations. Maliit 12oz / Malaki 16oz.', 1),
('Matcha Series', 'Ceremonial Grade Matcha. Maliit 12oz / Malaki 16oz.', 2),
('Non Coffee', 'Strawberry, blueberry, chocolate and choco berry lattes.', 3),
('Fruit Teas', 'Refreshing fruit-infused teas.', 4),
('Boneless Chicken', 'Crispy boneless fried chicken with rice. Choose your flavor!', 5),
('Rice Bowls', 'Savory rice bowls with corn and creamy sauce.', 6),
('Chicken Tenders', 'OG and flavored chick tenders. Choose your dip!', 7),
('Sandwiches', 'Crispy chicken sandwiches. Signature: Chick N Pro Max.', 8),
('Snacks', 'Fries, cheese sticks, salads and mixes.', 9),
('Add Ons & Extras', 'Extra shots, milk, creme, dips, rice and brew upgrades. Your coffee, your rules.', 10);

-- Insert default admin user (password: admin123)
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('Admin', 'User', 'admin@cafecat.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- CafeCat products (prices from revision menu boards; base = Maliit 12oz / single serve)
INSERT INTO products (category_id, name, description, price, preparation_time, image) VALUES
-- Coffee: Our best-loved coffee creations (Maliit 12oz base; Malaki 16oz via variants)
((SELECT id FROM categories WHERE name = 'Coffee'), 'Americano', 'Classic Americano. Maliit 12oz 75 / Malaki 16oz 85.', 75.00, 2, 'spanish-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Spanish Latte', 'Bestseller. Sweet Spanish latte with condensed milk. Maliit 80 / Malaki 90.', 80.00, 3, 'spanish-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Caramel Latte', 'Smooth caramel latte. Maliit 80 / Malaki 90.', 80.00, 3, 'caramel-macchiato.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Hazelnut', 'Nutty hazelnut latte. Maliit 80 / Malaki 90.', 80.00, 3, 'spanish-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'White Mocha Latte', 'Bestseller. Creamy white mocha. Maliit 85 / Malaki 95.', 85.00, 3, 'vanilla-creme.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Vanilla Creme', 'Silky vanilla creme latte. Maliit 85 / Malaki 95.', 85.00, 3, 'vanilla-creme.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Caramel Macchiato', 'Bestseller. Espresso with vanilla and caramel drizzle. Maliit 85 / Malaki 95.', 85.00, 3, 'caramel-macchiato.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Caradamia Latte', 'Rich caradamia latte. Malaki 16oz only.', 100.00, 3, 'signature-blend.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Seasalt Spanish Latte', 'Spanish latte with seasalt creme. Malaki 16oz only.', 100.00, 3, 'spanish-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Cacao Latte', 'Chocolatey cacao latte topped with cocoa. Malaki 16oz only.', 120.00, 3, 'cacao-latte.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'Spanish Oat Blend', 'Spanish latte made with oat milk. Malaki 16oz only.', 120.00, 3, 'vanilla-creme.jpg'),
((SELECT id FROM categories WHERE name = 'Coffee'), 'CafeCat Signature Blend', 'MUST TRY. House signature blend. Malaki 16oz only.', 120.00, 3, 'signature-blend.jpg'),
-- Matcha Series: Ceremonial Grade Matcha
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'Matcha Latte', 'Bestseller. Ceremonial grade matcha latte. Maliit 90 / Malaki 100.', 90.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'White Mocha Matcha', 'Matcha with white mocha. Maliit 90 / Malaki 100.', 90.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'Berry Matcha', 'Bestseller. Matcha with berry. Maliit 100 / Malaki 110.', 100.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'Matcha Oat Blend', 'Matcha with oat milk. Malaki 16oz only.', 135.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Matcha Series'), 'Seasalt Matcha Latte', 'Matcha latte with seasalt creme. Malaki 16oz only.', 125.00, 3, NULL),
-- Non Coffee
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Strawberry Latte', 'Bestseller. Creamy strawberry milk.', 95.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Blueberry Latte', 'Creamy blueberry milk.', 95.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Chocolate Latte', 'Bestseller. Rich chocolate milk.', 100.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Non Coffee'), 'Choco Berry', 'Chocolate with berry.', 100.00, 3, NULL),
-- Fruit Teas
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Lychee', 'Bestseller. Refreshing lychee fruit tea.', 85.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Mixed Berries', 'Mixed berries fruit tea.', 85.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Blueberry', 'Blueberry fruit tea.', 85.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Kiwi', 'Bestseller. Kiwi fruit tea.', 85.00, 3, NULL),
((SELECT id FROM categories WHERE name = 'Fruit Teas'), 'Passion Fruit', 'Passion fruit tea.', 85.00, 3, NULL),
-- Boneless Chicken: flavors CH Cheesy, Snowcheese, Salted Egg, Buffalo, Spicy Salted Egg, Original
((SELECT id FROM categories WHERE name = 'Boneless Chicken'), '2PCS with Rice', '2pcs boneless fried chicken with rice. Choose 1 flavor.', 110.00, 10, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Boneless Chicken'), '3PCS with Rice', '3pcs boneless fried chicken with rice. Choose 1 flavor.', 150.00, 12, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Boneless Chicken'), '6PCS Boneless Fried Chicken (1 Flavor)', '6pcs boneless fried chicken, 1 flavor. Flavors: CH Cheesy, Snowcheese, Salted Egg, Buffalo, Spicy Salted Egg, Original.', 280.00, 15, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Boneless Chicken'), '12PCS Boneless Fried Chicken (2 Flavors)', '12pcs boneless fried chicken, 2 flavors.', 550.00, 20, 'chicken-platter.jpg'),
-- Rice Bowls
((SELECT id FROM categories WHERE name = 'Rice Bowls'), 'Pork Bulgogi', 'Savory pork bulgogi rice bowl with corn and creamy sauce.', 150.00, 10, 'rice-bowls.jpg'),
((SELECT id FROM categories WHERE name = 'Rice Bowls'), 'Ham & Bacon', 'Ham and bacon rice bowl with corn and creamy sauce.', 150.00, 10, 'rice-bowls.jpg'),
((SELECT id FROM categories WHERE name = 'Rice Bowls'), 'Chicken Pepper Steak', 'Chicken pepper steak rice bowl with corn and creamy sauce.', 155.00, 10, 'rice-bowls.jpg'),
-- Chicken Tenders: dips Cheese, Garlic Mayo, CH Dip (Signature)
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'Salted Egg - 3PCS with 1 Dip', 'Flavored tenders: salted egg, 3pcs with 1 dip.', 190.00, 8, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH Buffalo - 3PCS with 1 Dip', 'Flavored tenders: buffalo, 3pcs with 1 dip.', 195.00, 8, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'Spicy Salted Egg - 3PCS with 1 Dip', 'Flavored tenders: spicy salted egg, 3pcs with 1 dip.', 190.00, 8, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH 1 - 3PCS Chicken Tenders with 1 Dip', 'OG chick tenders combo 1.', 175.00, 8, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH 2 - 3PCS Chicken Tenders, 1 Dip, Fries', 'OG chick tenders combo 2 with fries.', 190.00, 10, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH 3 - 3PCS Chicken Tenders with 3 Dips', 'OG chick tenders combo 3.', 215.00, 10, 'chicken-platter.jpg'),
((SELECT id FROM categories WHERE name = 'Chicken Tenders'), 'CH 4 - 5PCS Chicken Tenders with 3 Dips', 'OG chick tenders combo 4.', 300.00, 12, 'chicken-platter.jpg'),
-- Sandwiches
((SELECT id FROM categories WHERE name = 'Sandwiches'), 'Chick N Pro Max', 'Signature dish. Crispy chicken sandwich with creamy sauce.', 125.00, 8, NULL),
((SELECT id FROM categories WHERE name = 'Sandwiches'), 'Buffalo Pro Max', 'Buffalo crispy chicken sandwich.', 150.00, 8, NULL),
((SELECT id FROM categories WHERE name = 'Sandwiches'), 'Pancakey Sandwich', 'Sweet-savory pancakey sandwich.', 100.00, 8, NULL),
((SELECT id FROM categories WHERE name = 'Sandwiches'), 'Overload Sandwich', 'Loaded overload sandwich.', 125.00, 8, NULL),
-- Snacks
((SELECT id FROM categories WHERE name = 'Snacks'), 'CH Fries', 'Classic fries.', 85.00, 5, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'Cheese Stick', 'Crispy cheese sticks.', 95.00, 5, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'Cheesy Fries', 'Fries topped with melted cheese.', 100.00, 6, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'CH Salad', 'Fresh chick salad.', 160.00, 6, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'Chick N Fries', 'Crispy chicken with fries and creamy topping.', 165.00, 8, NULL),
((SELECT id FROM categories WHERE name = 'Snacks'), 'CH Mix', 'Fries with cheese sticks and dips.', 165.00, 8, NULL),
-- Add Ons & Extras
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Extra Coffee Shot', 'Add-on: extra coffee shot.', 20.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Oat Milk', 'Add-on: oat milk.', 35.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Seasalt Creme', 'Add-on: seasalt creme.', 25.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Nata', 'Add-on: nata.', 15.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Rice', 'Extra rice.', 20.00, 2, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Dip Sauce', 'Extra dip sauce. Dips: Cheese, Garlic Mayo, CH Dip (Signature).', 40.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), 'Extra Flavor', 'Extra flavor for chicken.', 20.00, 1, NULL),
((SELECT id FROM categories WHERE name = 'Add Ons & Extras'), '2PCS Extra Tenders', 'Add 2pcs chicken tenders.', 120.00, 5, NULL);

-- CafeCat variants: Malaki 16oz upgrades (+10) and Espresso brew style (+30)
INSERT INTO product_variants (product_id, name, price_adjustment) VALUES
((SELECT id FROM products WHERE name = 'Americano'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Spanish Latte'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Caramel Latte'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Hazelnut'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'White Mocha Latte'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Vanilla Creme'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Caramel Macchiato'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Matcha Latte'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'White Mocha Matcha'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Berry Matcha'), 'Malaki 16oz', 10.00),
((SELECT id FROM products WHERE name = 'Americano'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Spanish Latte'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Caramel Latte'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Hazelnut'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'White Mocha Latte'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Vanilla Creme'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Caramel Macchiato'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'CafeCat Signature Blend'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Matcha Latte'), 'Espresso brew style', 30.00),
((SELECT id FROM products WHERE name = 'Berry Matcha'), 'Espresso brew style', 30.00);

-- Add address and city columns to existing users table (for database updates)
ALTER TABLE users 
ADD COLUMN address VARCHAR(255) AFTER phone,
ADD COLUMN city VARCHAR(100) AFTER address;

-- Email verification tokens
CREATE TABLE email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    code CHAR(6) NULL,
    expires_at TIMESTAMP NOT NULL,
    attempts INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_token (token),
    INDEX idx_code (code)
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

-- Add address columns to existing orders table
ALTER TABLE orders 
ADD COLUMN customer_address VARCHAR(255) AFTER customer_email,
ADD COLUMN customer_city VARCHAR(100) AFTER customer_address;

-- In-app notifications (order delivery bell + polling API)
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'Recipient user id (customer or staff)',
    order_id INT NULL COMMENT 'Related order, if any',
    type VARCHAR(50) NOT NULL DEFAULT 'info' COMMENT 'order_ready, order_delivered, info, ...',
    title VARCHAR(200) NOT NULL,
    message TEXT,
    link VARCHAR(255) NULL COMMENT 'Where the bell item points',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_notif_user (user_id, is_read, created_at),
    INDEX idx_notif_order (order_id)
);

-- Refunds ledger (refund processing + status-update notifications)
CREATE TABLE IF NOT EXISTS refunds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    refund_amount DECIMAL(10,2) NOT NULL,
    refund_method ENUM('gcash', 'maya', 'paymaya', 'cash', 'bank_transfer', 'store_credit') NOT NULL,
    refund_notes TEXT,
    refund_status ENUM('pending', 'processed', 'failed') DEFAULT 'pending',
    processed_by INT,
    processed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_order (order_id),
    INDEX idx_status (refund_status)
);

-- Live chat: per-order threads + general customer support.
CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NULL COMMENT 'NULL = general support thread',
    sender_id INT NOT NULL,
    recipient_id INT NULL COMMENT 'Support replies: target customer id',
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_chat_order (order_id, id),
    INDEX idx_chat_support (recipient_id, sender_id, id),
    INDEX idx_chat_sender (sender_id)
);

-- Rider dispatch: one active delivery row per order.
CREATE TABLE IF NOT EXISTS deliveries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    rider_id INT NOT NULL,
    status ENUM('assigned', 'accepted', 'picked_up', 'delivering', 'delivered', 'failed', 'cancelled') NOT NULL DEFAULT 'assigned',
    pickup_notes TEXT NULL,
    delivery_notes TEXT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    accepted_at TIMESTAMP NULL,
    picked_up_at TIMESTAMP NULL,
    delivering_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (rider_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE INDEX uniq_delivery_order (order_id),
    INDEX idx_delivery_rider (rider_id, status),
    INDEX idx_delivery_status (status)
);

-- Out-of-stock substitutions suggested by riders/admins.
CREATE TABLE IF NOT EXISTS substitutions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    order_item_id INT NOT NULL,
    original_name VARCHAR(200) NOT NULL,
    original_variant VARCHAR(100) NULL,
    original_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    suggested_product_id INT NOT NULL,
    suggested_variant_id INT NULL,
    suggested_name VARCHAR(200) NOT NULL,
    suggested_variant VARCHAR(100) NULL,
    suggested_qty INT NOT NULL DEFAULT 1,
    suggested_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    price_diff DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'suggested_total - original_total',
    status ENUM('suggested', 'approved', 'rejected', 'applied') NOT NULL DEFAULT 'suggested',
    note TEXT NULL,
    suggested_by INT NULL COMMENT 'rider/admin user who proposed it',
    decided_by INT NULL COMMENT 'customer/admin who approved or rejected',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE,
    FOREIGN KEY (suggested_product_id) REFERENCES products(id),
    INDEX idx_sub_order (order_id, status),
    INDEX idx_sub_item (order_item_id)
);
