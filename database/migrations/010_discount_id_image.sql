-- 010: Senior/PWD ID image storage for registration uploads
-- Run once: mysql -u root -p cafecat_db < database/migrations/010_discount_id_image.sql
-- Safe to re-run: guarded by existence check.

DELIMITER $$

DROP PROCEDURE IF EXISTS add_discount_id_image$$
CREATE PROCEDURE add_discount_id_image()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'users'
          AND COLUMN_NAME = 'discount_id_image'
    ) THEN
        ALTER TABLE users
            ADD COLUMN discount_id_image VARCHAR(255) NULL AFTER discount_id_no;
    END IF;
END$$

DELIMITER ;

CALL add_discount_id_image();
DROP PROCEDURE IF EXISTS add_discount_id_image;
