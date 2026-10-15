<?php
require_once 'config/constants.php';
require_once 'core/Model.php';
require_once 'models/User.php';

$pdo = new PDO('mysql:host=localhost;dbname=cafecat_db;charset=utf8mb4', 'root', '');
$u = $pdo->query("SELECT * FROM users WHERE email = 'admin@cafecat.com'")->fetch(PDO::FETCH_ASSOC);
var_dump($u);