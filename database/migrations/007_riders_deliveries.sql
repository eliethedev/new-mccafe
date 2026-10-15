-- 007: Rider dispatch + out-of-stock substitutions
-- Run once: mysql -u root -p cafecat_db < database/migrations/007_riders_deliveries.sql
-- Safe to re-run: every change is guarded by existence checks.

-- 1) Allow the rider role (uses the same guarded-procedure pattern as 003/004).
DELIMITER $$

DROP PROCEDURE IF EXISTS add_rider_role$$
CREATE PROCEDURE add_rider_role()
BEGIN
    -- Bail out quietly if the column type already includes rider.
    IF EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'users'
          AND COLUMN_TYPE LIKE '%rider%'
    ) THEN
        SELECT 'users.role already supports rider' AS note;
    ELSE
        ALTER TABLE users
            MODIFY COLUMN role ENUM('admin', 'staff', 'customer', 'rider') DEFAULT 'customer';
    END IF;
END$$

DELIMITER ;

CALL add_rider_role();
DROP PROCEDURE IF EXISTS add_rider_role;

-- 2) Deliveries: one active dispatch row per order.
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
    UNIQUE INDEX uniq_delivery_order (order_id),
    INDEX idx_delivery_rider (rider_id, status),
    INDEX idx_delivery_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3) Substitutions: rider/admin replacement suggestions for unavailable items.
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
    INDEX idx_sub_order (order_id, status),
    INDEX idx_sub_item (order_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER $$

DROP PROCEDURE IF EXISTS add_delivery_fks$$
CREATE PROCEDURE add_delivery_fks()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'deliveries'
          AND CONSTRAINT_NAME = 'fk_deliveries_order'
    ) THEN
        ALTER TABLE deliveries
            ADD CONSTRAINT fk_deliveries_order
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'deliveries'
          AND CONSTRAINT_NAME = 'fk_deliveries_rider'
    ) THEN
        ALTER TABLE deliveries
            ADD CONSTRAINT fk_deliveries_rider
            FOREIGN KEY (rider_id) REFERENCES users(id) ON DELETE CASCADE;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'substitutions'
          AND CONSTRAINT_NAME = 'fk_sub_order'
    ) THEN
        ALTER TABLE substitutions
            ADD CONSTRAINT fk_sub_order
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'substitutions'
          AND CONSTRAINT_NAME = 'fk_sub_item'
    ) THEN
        ALTER TABLE substitutions
            ADD CONSTRAINT fk_sub_item
            FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'substitutions'
          AND CONSTRAINT_NAME = 'fk_sub_product'
    ) THEN
        ALTER TABLE substitutions
            ADD CONSTRAINT fk_sub_product
            FOREIGN KEY (suggested_product_id) REFERENCES products(id);
    END IF;
END$$

DELIMITER ;

CALL add_delivery_fks();
DROP PROCEDURE IF EXISTS add_delivery_fks;

-- 4) Seed demo rider (rider@cafecat.com / rider123). No-op if already present.
INSERT INTO users (first_name, last_name, email, password, phone, role, email_verified_at)
SELECT 'Demo', 'Rider', 'rider@cafecat.com',
       '$2y$10$zEyI.y22O5zstQyyGXtz6eOXheE6Un1tS4xzJPKKx8l3UFycgfYAi',
       '09170000001', 'rider', NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'rider@cafecat.com');
