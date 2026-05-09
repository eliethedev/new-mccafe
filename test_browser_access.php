<?php
// Test what happens when accessing /orders via browser
session_start();

echo "=== Browser Access Test ===\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";
echo "Session ID: " . session_id() . "\n";
echo "Session data: " . json_encode($_SESSION) . "\n";

if (isset($_SESSION['user'])) {
    echo "✓ User is logged in: " . $_SESSION['user']['id'] . "\n";
} else {
    echo "✗ User is NOT logged in\n";
}

echo "\nTo test: Access http://localhost:8000/orders in your browser\n";
echo "Then check what appears here.\n";
?>
