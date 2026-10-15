<?php
require_once 'config/constants.php';
require_once 'core/Model.php';
require_once 'models/User.php';

$passwords = array('admin', 'admin123', 'password', 'password123', 'cafecat', 'cafecat123', '123456', '12345678');
foreach ($passwords as $pw) {
    $u = User::verifyPassword('admin@cafecat.com', $pw);
    echo "Testing: $pw => " . ($u ? 'SUCCESS' : 'FAIL') . PHP_EOL;
    if ($u) break;
}