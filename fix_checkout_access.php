<?php
/**
 * Fix Checkout Access Script
 * This script helps resolve the checkout redirect issue
 */

require_once 'config/config.php';
require_once 'core/Model.php';
require_once 'models/User.php';
require_once 'models/Cart.php';

// Start session
session_start();

echo "=== Fix Checkout Access ===\n\n";

// Step 1: Create/Verify Test User
echo "1. Setting up Test User:\n";

$testEmail = 'test@maccafe.com';
$testPassword = 'password123';

// Check if user exists
$existingUser = User::findByEmail($testEmail);

if ($existingUser) {
    echo "✅ Test user found: $testEmail\n";
    $userId = $existingUser['id'];
} else {
    echo "📝 Creating test user...\n";
    $userData = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => $testEmail,
        'password' => $testPassword,
        'phone' => '09123456789',
        'address' => '123 Test Street',
        'city' => 'Quezon City',
        'role' => 'customer'
    ];
    
    $userId = User::create($userData);
    if ($userId) {
        echo "✅ Test user created with ID: $userId\n";
    } else {
        echo "❌ Failed to create test user\n";
        exit;
    }
}

// Step 2: Verify Email
echo "\n2. Verifying Email:\n";
try {
    Model::query(
        "UPDATE users SET email_verified_at = NOW() WHERE id = ?",
        [$userId]
    );
    echo "✅ Email verified for user ID: $userId\n";
} catch (Exception $e) {
    echo "❌ Failed to verify email: " . $e->getMessage() . "\n";
}

// Step 3: Log in the user
echo "\n3. Logging in User:\n";
$user = User::findByEmail($testEmail);
if ($user) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'role' => $user['role']
    ];
    echo "✅ User logged in successfully\n";
    echo "   - Email: " . $_SESSION['user']['email'] . "\n";
    echo "   - Name: " . $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'] . "\n";
} else {
    echo "❌ Failed to log in user\n";
}

// Step 4: Add items to cart
echo "\n4. Adding Items to Cart:\n";

// Clear existing cart
Cart::clear($userId);

// Add sample products
$sampleItems = [
    [
        'product_id' => 1,
        'quantity' => 2,
        'unit_price' => 55.00
    ],
    [
        'product_id' => 5,
        'quantity' => 1,
        'unit_price' => 89.00
    ]
];

foreach ($sampleItems as $item) {
    try {
        // Use direct SQL insertion since Cart::add is an instance method
        Model::query(
            "INSERT INTO cart (user_id, product_id, quantity, unit_price, created_at, updated_at) 
             VALUES (?, ?, ?, ?, NOW(), NOW())",
            [$userId, $item['product_id'], $item['quantity'], $item['unit_price']]
        );
        echo "✅ Added item to cart\n";
    } catch (Exception $e) {
        echo "⚠️  Could not add item to cart: " . $e->getMessage() . "\n";
    }
}

// Check cart contents
$cartItems = Cart::getItems($userId);
echo "📦 Cart now has " . count($cartItems) . " items\n";

// Step 5: Verify all requirements
echo "\n5. Checkout Requirements Check:\n";

$requirements = [
    'User logged in' => isset($_SESSION['user']),
    'Email verified' => User::isEmailVerified($userId),
    'Cart not empty' => !empty($cartItems)
];

$allMet = true;
foreach ($requirements as $requirement => $met) {
    $status = $met ? '✅' : '❌';
    echo "   $status $requirement: " . ($met ? 'MET' : 'NOT MET') . "\n";
    if (!$met) $allMet = false;
}

if ($allMet) {
    echo "\n🎉 ALL REQUIREMENTS MET!\n";
    echo "You can now access: http://localhost:8000/checkout\n";
    echo "\nLogin credentials:\n";
    echo "Email: $testEmail\n";
    echo "Password: $testPassword\n";
} else {
    echo "\n❌ Some requirements not met. Checkout will still redirect.\n";
}

// Step 6: Test direct access
echo "\n6. Testing Checkout Access:\n";

// Simulate checkout logic
if (!isset($_SESSION['user'])) {
    echo "❌ Would redirect to /login (not logged in)\n";
} elseif (!User::isEmailVerified($userId)) {
    echo "❌ Would redirect to /dashboard (email not verified)\n";
} elseif (empty($cartItems)) {
    echo "❌ Would redirect to /cart (empty cart)\n";
} else {
    echo "✅ Checkout should load successfully!\n";
}

echo "\n=== Fix Complete ===\n";
echo "Next steps:\n";
echo "1. Visit http://localhost:8000/checkout\n";
echo "2. The checkout page should load without redirects\n";
echo "3. Test the complete checkout process\n";
echo "4. If issues persist, check browser console for errors\n";

?>
