-- 003: Admin report resilience — product flag columns
-- Run once: mysql -u root -p cafecat_db < database/migrations/003_admin_report_flags.sql
-- Safe to re-run: each statement is guarded by a column-existence check.
-- Without these columns every admin report query that touches
-- is_bestseller / is_featured fails and the dashboard produces no report.

DELIMITER $$

DROP PROCEDURE IF EXISTS add_product_flag_columns$$
CREATE PROCEDURE add_product_flag_columns()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'products'
          AND COLUMN_NAME = 'is_bestseller'
    ) THEN
        ALTER TABLE products
            ADD COLUMN is_bestseller TINYINT(1) NOT NULL DEFAULT 0 AFTER is_available;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'products'
          AND COLUMN_NAME = 'is_featured'
    ) THEN
        ALTER TABLE products
            ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER is_bestseller;
    END IF;
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'products'
          AND INDEX_NAME = 'idx_products_bestseller'
    ) THEN
        CREATE INDEX idx_products_bestseller ON products (is_bestseller);
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'products'
          AND INDEX_NAME = 'idx_products_featured'
    ) THEN
        CREATE INDEX idx_products_featured ON products (is_featured);
    END IF;
END$$

DELIMITER ;

CALL add_product_flag_columns();
DROP PROCEDURE IF EXISTS add_product_flag_columns;
