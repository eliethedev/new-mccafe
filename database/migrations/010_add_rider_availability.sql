-- Add availability_status column to users table for rider availability management
-- Default 'available' for all existing users
ALTER TABLE users ADD COLUMN availability_status ENUM('available', 'unavailable', 'on_delivery') DEFAULT 'available' AFTER role;
