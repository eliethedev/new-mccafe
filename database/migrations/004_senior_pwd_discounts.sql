-- 004: Senior Citizen / PWD discounts (RA 9994 / RA 10754)
-- Run once: mysql -u root -p cafecat_db < database/migrations/004_senior_pwd_discounts.sql
-- Safe to re-run: every change is guarded by existence checks.

DELIMITER $$

DROP PROCEDURE IF EXISTS add_discount_columns$$
CREATE PROCEDURE add_discount_columns()
BEGIN
    -- orders.discount_type
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'orders'
          AND COLUMN_NAME = 'discount_type'
    ) THEN
        ALTER TABLE orders
            ADD COLUMN discount_type ENUM('none','senior','pwd') NOT NULL DEFAULT 'none' AFTER tax_amount;
    END IF;

    -- orders.discount_amount
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'orders'
          AND COLUMN_NAME = 'discount_amount'
    ) THEN
        ALTER TABLE orders
            ADD COLUMN discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER discount_type;
    END IF;

    -- orders.discount_id_no
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'orders'
          AND COLUMN_NAME = 'discount_id_no'
    ) THEN
        ALTER TABLE orders
            ADD COLUMN discount_id_no VARCHAR(64) NULL AFTER discount_amount;
    END IF;

    -- orders.discount_status
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'orders'
          AND COLUMN_NAME = 'discount_status'
    ) THEN
        ALTER TABLE orders
            ADD COLUMN discount_status ENUM('none','pending','verified','rejected') NOT NULL DEFAULT 'none' AFTER discount_id_no;
    END IF;

    -- users.is_senior
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'users'
          AND COLUMN_NAME = 'is_senior'
    ) THEN
        ALTER TABLE users
            ADD COLUMN is_senior TINYINT(1) NOT NULL DEFAULT 0 AFTER role;
    END IF;

    -- users.is_pwd
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'users'
          AND COLUMN_NAME = 'is_pwd'
    ) THEN
        ALTER TABLE users
            ADD COLUMN is_pwd TINYINT(1) NOT NULL DEFAULT 0 AFTER is_senior;
    END IF;

    -- users.discount_id_no
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'users'
          AND COLUMN_NAME = 'discount_id_no'
    ) THEN
        ALTER TABLE users
            ADD COLUMN discount_id_no VARCHAR(64) NULL AFTER is_pwd;
    END IF;

    -- users.discount_verified
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'users'
          AND COLUMN_NAME = 'discount_verified'
    ) THEN
        ALTER TABLE users
            ADD COLUMN discount_verified TINYINT(1) NOT NULL DEFAULT 0 AFTER discount_id_no;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'orders'
          AND INDEX_NAME = 'idx_orders_discount'
    ) THEN
        CREATE INDEX idx_orders_discount ON orders (discount_type, discount_status);
    END IF;
END$$

DELIMITER ;

CALL add_discount_columns();
DROP PROCEDURE IF EXISTS add_discount_columns;
