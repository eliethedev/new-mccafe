-- 009: Live chat (order threads + customer support)
-- Run once: mysql -u root -p cafecat_db < database/migrations/009_chat_messages.sql
-- Safe to re-run: table uses IF NOT EXISTS; FKs guarded by existence checks.
--
-- Threads:
--   * Order chat:  order_id set, recipient_id NULL (customer + rider + staff).
--   * Support chat: order_id NULL; customer sends with recipient_id NULL
--     (staff pool), staff replies with recipient_id = customer id.

CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NULL COMMENT 'NULL = general support thread',
    sender_id INT NOT NULL,
    recipient_id INT NULL COMMENT 'Support replies: target customer id',
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_chat_order (order_id, id),
    INDEX idx_chat_support (recipient_id, sender_id, id),
    INDEX idx_chat_sender (sender_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER $$

DROP PROCEDURE IF EXISTS add_chat_fks$$
CREATE PROCEDURE add_chat_fks()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'chat_messages'
          AND CONSTRAINT_NAME = 'fk_chat_order'
    ) THEN
        ALTER TABLE chat_messages
            ADD CONSTRAINT fk_chat_order
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'chat_messages'
          AND CONSTRAINT_NAME = 'fk_chat_sender'
    ) THEN
        ALTER TABLE chat_messages
            ADD CONSTRAINT fk_chat_sender
            FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'chat_messages'
          AND CONSTRAINT_NAME = 'fk_chat_recipient'
    ) THEN
        ALTER TABLE chat_messages
            ADD CONSTRAINT fk_chat_recipient
            FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE;
    END IF;
END$$

DELIMITER ;

CALL add_chat_fks();
DROP PROCEDURE IF EXISTS add_chat_fks;
