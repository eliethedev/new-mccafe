<?php
// Direct test of the orders functionality
session_start();

// Load everything like the main app
require_once 'config/constants.php';
require_once 'vendor/autoload.php';
require_once ROOT_PATH . '/core/Session.php';
require_once ROOT_PATH . '/core/Request.php';
require_once ROOT_PATH . '/core/Response.php';
require_once ROOT_PATH . '/core/Router.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Email.php';

// Load models
require_once ROOT_PATH . '/models/User.php';
require_once ROOT_PATH . '/models/Product.php';
require_once ROOT_PATH . '/models/Cart.php';
require_once ROOT_PATH . '/models/Order.php';
require_once ROOT_PATH . '/models/OrderItem.php';
require_once ROOT_PATH . '/models/OrderStatusHistory.php';
require_once ROOT_PATH . '/models/PaymentProof.php';

// Start session
Session::start();

// Simulate logged-in user
$_SESSION['user'] = [
    'id' => 17,
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'test@example.com'
];

echo "=== Direct Order Test ===\n";

// Test the model directly
echo "1. Testing Order::getUserOrders() directly:\n";
$orders = Order::getUserOrders(17, 1, 10);
echo "Result: " . count($orders) . " orders found\n";
if (!empty($orders)) {
    foreach ($orders as $order) {
        echo "- {$order['order_number']} ({$order['status']}) - {$order['items_summary']}\n";
    }
}

echo "\n2. Testing Order::getUserOrderCount() directly:\n";
$count = Order::getUserOrderCount(17);
echo "Result: $count total orders\n";

echo "\n3. Testing view rendering:\n";

// Mock the controller view method
class TestController extends Controller {
    public function testView($orders, $totalOrders) {
        return $this->view('user/my-orders', [
            'title' => 'My Orders',
            'orders' => $orders,
            'currentPage' => 1,
            'totalPages' => 1,
            'totalOrders' => $totalOrders
        ]);
    }
}

$controller = new TestController();
$response = $controller->testView($orders, $count);

echo "View rendered. Content length: " . strlen($response->getContent()) . " characters\n";

// Check if "No orders yet" text appears in the output
$content = $response->getContent();
if (strpos($content, 'No orders yet') !== false) {
    echo "⚠ WARNING: 'No orders yet' found in output despite having data!\n";
} else {
    echo "✓ No 'No orders yet' text found - orders should be displayed\n";
}

if (strpos($content, 'ORD202605098202') !== false) {
    echo "✓ Order ORD202605098202 found in output\n";
} else {
    echo "✗ Order ORD202605098202 NOT found in output\n";
}
?>
