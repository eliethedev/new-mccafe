<?php
/**
 * Quick Checkout Fix - Get existing products and set up checkout
 */

require_once 'config/config.php';
require_once 'core/Model.php';
require_once 'models/User.php';
require_once 'models/Cart.php';

// Start session
session_start();

echo "=== Quick Checkout Fix ===\n\n";

// Step 1: Get existing products
echo "1. Checking Available Products:\n";
try {
    $stmt = Model::query("SELECT id, name, price FROM products LIMIT 5");
    $products = $stmt->fetchAll();
    
    if (empty($products)) {
        echo "❌ No products found in database\n";
        exit;
    }
    
    echo "✅ Found " . count($products) . " products:\n";
    foreach ($products as $product) {
        echo "   - ID: {$product['id']}, Name: {$product['name']}, Price: ₱{$product['price']}\n";
    }
} catch (Exception $e) {
    echo "❌ Error getting products: " . $e->getMessage() . "\n";
    exit;
}

// Step 2: Set up test user (or use existing)
echo "\n2. Setting up User:\n";

$testEmail = 'test@maccafe.com';
$existingUser = User::findByEmail($testEmail);

if ($existingUser) {
    echo "✅ Using existing test user: $testEmail\n";
    $userId = $existingUser['id'];
} else {
    echo "📝 Creating test user...\n";
    $userData = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => $testEmail,
        'password' => password_hash('password123', PASSWORD_DEFAULT),
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

// Step 3: Verify email
echo "\n3. Verifying Email:\n";
try {
    Model::query(
        "UPDATE users SET email_verified_at = NOW() WHERE id = ?",
        [$userId]
    );
    echo "✅ Email verified\n";
} catch (Exception $e) {
    echo "❌ Failed to verify email: " . $e->getMessage() . "\n";
}

// Step 4: Log in user
echo "\n4. Logging in User:\n";
$user = User::findByEmail($testEmail);
if ($user) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'role' => $user['role']
    ];
    echo "✅ User logged in\n";
} else {
    echo "❌ Failed to log in user\n";
    exit;
}

// Step 5: Clear and add cart items
echo "\n5. Setting up Cart:\n";

// Clear existing cart
Cart::clear($userId);
echo "🗑️  Cleared existing cart\n";

// Add first 2 products to cart
$itemsToAdd = array_slice($products, 0, 2);
foreach ($itemsToAdd as $product) {
    try {
        Model::query(
            "INSERT INTO cart (user_id, product_id, quantity, unit_price, created_at, updated_at) 
             VALUES (?, ?, ?, ?, NOW(), NOW())",
            [$userId, $product['id'], 1, $product['price']]
        );
        echo "✅ Added: {$product['name']} (₱{$product['price']})\n";
    } catch (Exception $e) {
        echo "❌ Failed to add {$product['name']}: " . $e->getMessage() . "\n";
    }
}

// Check cart
$cartItems = Cart::getItems($userId);
echo "📦 Cart now has " . count($cartItems) . " items\n";

// Step 6: Final verification
echo "\n6. Final Verification:\n";

$requirements = [
    'User logged in' => isset($_SESSION['user']),
    'Email verified' => User::isEmailVerified($userId),
    'Cart has items' => !empty($cartItems)
];

$allMet = true;
foreach ($requirements as $requirement => $met) {
    $status = $met ? '✅' : '❌';
    echo "   $status $requirement: " . ($met ? 'MET' : 'NOT MET') . "\n";
    if (!$met) $allMet = false;
}

if ($allMet) {
    echo "\n🎉 SUCCESS! All requirements met.\n";
    echo "\n📋 LOGIN DETAILS:\n";
    echo "   Email: $testEmail\n";
    echo "   Password: password123\n";
    echo "\n🌐 ACCESS URL:\n";
    echo "   http://localhost:8000/checkout\n";
    echo "\n✅ The checkout page should now load without redirects!\n";
} else {
    echo "\n❌ Some requirements still not met.\n";
}

echo "\n=== Fix Complete ===\n";

?>
