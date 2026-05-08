<?php

// ========================
//  STATIC FILE HANDLER (Important!)
// ========================

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove trailing slash (except for root)
if ($uri !== '/' && substr($uri, -1) === '/') {
    $uri = rtrim($uri, '/');
}

// Serve static files directly (images, css, js, fonts, etc.)
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Let PHP built-in server handle the file
}

// ========================
//  APPLICATION BOOTSTRAP
// ========================

// Load configuration (this defines ROOT_PATH)
require_once __DIR__ . '/../config/constants.php';

// Load core classes
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

// Load middleware
require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
require_once ROOT_PATH . '/middleware/GuestMiddleware.php';
require_once ROOT_PATH . '/middleware/AdminMiddleware.php';

// Load controllers
require_once ROOT_PATH . '/controllers/HomeController.php';
require_once ROOT_PATH . '/controllers/AuthController.php';
require_once ROOT_PATH . '/controllers/ProductController.php';
require_once ROOT_PATH . '/controllers/CartController.php';
require_once ROOT_PATH . '/controllers/OrderController.php';
require_once ROOT_PATH . '/controllers/UserController.php';
require_once ROOT_PATH . '/controllers/AdminController.php';
require_once ROOT_PATH . '/controllers/ApiController.php';
require_once ROOT_PATH . '/controllers/CategoryController.php';
require_once ROOT_PATH . '/controllers/CheckoutController.php';

// Load routes
$routes = require_once ROOT_PATH . '/routes/web.php';

// Create request and dispatch
$request = new Request();
$response = $routes->dispatch($request);

// Send response
$response->send();