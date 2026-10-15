-- 005: Order delivery notifications (in-app bell + polling API)
-- Run once: mysql -u root -p cafecat_db < database/migrations/005_notifications.sql
-- Safe to re-run: table uses IF NOT EXISTS; index guarded by existence check.

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
    INDEX idx_notif_user (user_id, is_read, created_at),
    INDEX idx_notif_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER $$

DROP PROCEDURE IF EXISTS add_notifications_fk$$
CREATE PROCEDURE add_notifications_fk()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'notifications'
          AND CONSTRAINT_NAME = 'fk_notifications_user'
    ) THEN
        ALTER TABLE notifications
            ADD CONSTRAINT fk_notifications_user
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'notifications'
          AND CONSTRAINT_NAME = 'fk_notifications_order'
    ) THEN
        ALTER TABLE notifications
            ADD CONSTRAINT fk_notifications_order
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;
    END IF;
END$$

DELIMITER ;

CALL add_notifications_fk();
DROP PROCEDURE IF EXISTS add_notifications_fk;
