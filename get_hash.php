<?php
$pdo = new PDO('mysql:host=localhost;dbname=cafecat_db;charset=utf8mb4', 'root', '');
$hash = $pdo->query('SELECT password FROM users WHERE email = "admin@cafecat.com"')->fetchColumn();
echo $hash . PHP_EOL;