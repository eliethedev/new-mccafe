<?php

// Simple redirect to public/verify-email
$token = $_GET['token'] ?? '';
$newUrl = 'http://localhost:8000/verify-email';
if ($token) {
    $newUrl .= '?token=' . urlencode($token);
}

header("Location: $newUrl");
exit();
?>
