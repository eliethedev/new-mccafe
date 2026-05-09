-- Create payment_verifications table
CREATE TABLE IF NOT EXISTS `payment_verifications` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `payment_method` varchar(50) NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    `proof_image` varchar(255) DEFAULT NULL,
    `verification_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
    `rejection_reason` text DEFAULT NULL,
    `verified_by` int(11) DEFAULT NULL,
    `verified_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`verified_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_verification_status` (`verification_status`),
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
