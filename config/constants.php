<?php

// Application constants
define('APP_NAME', 'MacCafe Ordering System');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost:8000');

// Paths
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', ROOT_PATH);
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('UPLOADS_PATH', PUBLIC_PATH . '/assets/images/uploads');

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'maccafe_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Session
define('SESSION_LIFETIME', 7200); // 2 hours

// Security
define('HASH_ALGO', PASSWORD_DEFAULT);
define('MIN_PASSWORD_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_DURATION', 900); // 15 minutes

// Upload
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_UPLOAD_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Pagination
define('ITEMS_PER_PAGE', 12);

// Order statuses
define('ORDER_PENDING', 'pending');
define('ORDER_CONFIRMED', 'confirmed');
define('ORDER_PREPARING', 'preparing');
define('ORDER_READY', 'ready');
define('ORDER_COMPLETED', 'completed');
define('ORDER_CANCELLED', 'cancelled');

// User roles
define('ROLE_ADMIN', 'admin');
define('ROLE_STAFF', 'staff');
define('ROLE_CUSTOMER', 'customer');

// Product categories
define('CATEGORY_COFFEE', 'coffee');
define('CATEGORY_FOOD', 'food');
define('CATEGORY_BEVERAGE', 'beverage');
define('CATEGORY_DESSERT', 'dessert');
define('CATEGORY_MERCHANDISE', 'merchandise');

// Payment methods
define('PAYMENT_CASH', 'cash');
define('PAYMENT_CARD', 'card');
define('PAYMENT_GCASH', 'gcash');
define('PAYMENT_PAYMAYA', 'paymaya');
