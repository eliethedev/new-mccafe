<?php
require_once 'C:/xampp/htdocs/MacCafe-MOR-DEMO/config/constants.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/models/User.php';

$pdo = new PDO('mysql:host=localhost;dbname=cafecat_db;charset=utf8mb4', 'root', '');
$users = $pdo->query("SELECT * FROM users WHERE role IN ('admin','staff','customer','rider') LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
echo 'Users found: ' . count($users) . PHP_EOL;
foreach ($users as $u) {
    echo $u['id'] . ' ' . $u['first_name'] . ' ' . $u['role'] . PHP_EOL;
}