-- 008: Six-digit verification codes for email verification + password reset
-- Run once: mysql -u root -p cafecat_db < database/migrations/008_verification_codes.sql
-- Safe to re-run: every change is guarded by existence checks.

DELIMITER $$

DROP PROCEDURE IF EXISTS add_verification_code_columns$$
CREATE PROCEDURE add_verification_code_columns()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'email_verifications'
          AND COLUMN_NAME = 'code'
    ) THEN
        ALTER TABLE email_verifications
            ADD COLUMN code CHAR(6) NULL AFTER token,
            ADD COLUMN attempts INT NOT NULL DEFAULT 0 AFTER expires_at,
            ADD INDEX idx_code (code);
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'password_resets'
          AND COLUMN_NAME = 'code'
    ) THEN
        ALTER TABLE password_resets
            ADD COLUMN code CHAR(6) NULL AFTER token,
            ADD COLUMN attempts INT NOT NULL DEFAULT 0 AFTER expires_at,
            ADD INDEX idx_code (code);
    END IF;
END$$

DELIMITER ;

CALL add_verification_code_columns();
DROP PROCEDURE IF EXISTS add_verification_code_columns;
