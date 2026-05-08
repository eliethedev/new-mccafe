<?php

return [
    'app' => [
        'name' => 'MacCafe Ordering System',
        'url' => 'http://localhost/maccafe-mor-demo',
        'timezone' => 'Asia/Manila',
        'debug' => true,
    ],
    
    'session' => [
        'lifetime' => 120, // minutes
        'secure' => false,
        'httponly' => true,
        'samesite' => 'lax',
    ],
    
    'security' => [
        'csrf_token' => true,
        'password_min_length' => 8,
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutes
    ],
    
    'upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
        'path' => 'public/assets/images/uploads/',
    ],
    
    'pagination' => [
        'per_page' => 12,
    ],
    
    'cache' => [
        'enabled' => false,
        'lifetime' => 3600, // 1 hour
    ],
    
    'email' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'your-email@gmail.com', // Update with your email
        'password' => 'your-app-password',    // Update with your app password
        'from_name' => 'MacCafe Ordering System',
        'from_email' => 'your-email@gmail.com', // Update with your email
    ],
];
