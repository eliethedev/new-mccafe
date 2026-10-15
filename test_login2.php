<?php
require_once 'config/constants.php';
require_once 'core/Model.php';
require_once 'models/User.php';

$u = User::verifyPassword('admin@cafecat.com', 'admin');
var_dump($u);