-- 006: Refunds ledger (canonical) for refund processing + status notifications
-- Run once: mysql -u root -p cafecat_db < database/migrations/006_refunds.sql
-- Safe to re-run: table uses IF NOT EXISTS; FKs guarded by existence checks.
-- NOTE: OrderController::createRefundRecord also auto-creates this table at
-- runtime with the same shape, so app behavior is unchanged on old databases.

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
    INDEX idx_order (order_id),
    INDEX idx_status (refund_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER $$

DROP PROCEDURE IF EXISTS add_refunds_fks$$
CREATE PROCEDURE add_refunds_fks()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'refunds'
          AND CONSTRAINT_NAME = 'fk_refunds_order'
    ) THEN
        ALTER TABLE refunds
            ADD CONSTRAINT fk_refunds_order
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'refunds'
          AND CONSTRAINT_NAME = 'fk_refunds_processor'
    ) THEN
        ALTER TABLE refunds
            ADD CONSTRAINT fk_refunds_processor
            FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL;
    END IF;
END$$

DELIMITER ;

CALL add_refunds_fks();
DROP PROCEDURE IF EXISTS add_refunds_fks;
