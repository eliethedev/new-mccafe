<?php

class CartController extends Controller {
    private $cartModel;
    
    public function __construct() {
        parent::__construct();
        $this->cartModel = new Cart();
    }
    
    public function index(Request $request) {
        // Get current user from session
        $user = Session::get('user');
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            // Redirect to login if not authenticated
            header('Location: /login');
            exit;
        }
        
        $cart = $this->cartModel->getUserCart($userId);
        
        return $this->view('cart/index', [
            'cart' => $cart,
            'title' => 'Shopping Cart'
        ]);
    }
    
    public function add(Request $request) {
        // Get current user from session
        $user = Session::get('user');
        $userId = $user['id'] ?? null;
        
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id');
        $variantPrice = $request->input('variant_price');
        
        // Store form data in session for modal display
        $_SESSION['add_to_cart_data'] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'variant_id' => $variantId,
            'variant_price' => $variantPrice
        ];
        
        if (!$userId) {
            // User not logged in - set flag to show login modal and redirect back
            $_SESSION['show_login_modal'] = true;
            $_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'] ?? '/menu';
            header('Location: ' . $_SESSION['redirect_after_login']);
            exit;
        }
        
        // Get product details for unit price
        $productModel = new Product();
        $product = $productModel->find($productId);
        
        if (!$product) {
            // Product not found
            header('Location: /menu?error=product_not_found');
            exit;
        }
        
        // Calculate unit price
        $unitPrice = $product['price'];
        
        if ($variantId && $variantPrice) {
            $unitPrice = floatval($variantPrice);
        }
        
        // Add to cart database
        $result = $this->cartModel->addToCart($userId, $productId, $quantity, $variantId, $unitPrice);
        
        if ($request->isAjax()) {
            $response = new Response(json_encode(['success' => true]), 200);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        // For non-AJAX requests, redirect to cart
        header('Location: /cart');
        exit;
    }
    
    public function confirmAdd(Request $request) {
        // Get current user from session
        $user = Session::get('user');
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id');
        
        // Get product details for unit price
        $productModel = new Product();
        $product = $productModel->find($productId);
        
        if (!$product) {
            header('Location: /menu?error=product_not_found');
            exit;
        }
        
        // Calculate unit price
        $unitPrice = $product['price'];
        
        if ($variantId) {
            // Get variant price from database
            $variantSql = "SELECT price_adjustment FROM product_variants WHERE id = ? AND product_id = ?";
            $stmt = $this->cartModel->query($variantSql, [$variantId, $productId]);
            $variantResult = $stmt->fetch();
            if ($variantResult) {
                $unitPrice = $product['price'] + $variantResult['price_adjustment'];
            }
        }
        
        // Add to cart database
        $result = $this->cartModel->addToCart($userId, $productId, $quantity, $variantId, $unitPrice);
        
        // Clear session data
        unset($_SESSION['show_confirmation_modal']);
        unset($_SESSION['added_product']);
        unset($_SESSION['added_quantity']);
        unset($_SESSION['added_variant_id']);
        unset($_SESSION['added_unit_price']);
        
        // Redirect to cart page
        header('Location: /cart');
        exit;
    }
    
    public function update(Request $request) {
        $user = Session::get('user');
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            if ($request->isAjax()) {
                $response = new Response(json_encode(['success' => false, 'message' => 'Please login']), 401);
                $response->setHeader('Content-Type', 'application/json');
                return $response;
            }
            header('Location: /login');
            exit;
        }
        
        $cartId = $request->getBody('cart_id');
        $quantity = $request->getBody('quantity');
        
        if ($quantity > 0) {
            $this->cartModel->updateQuantity($cartId, $quantity);
        } else {
            $this->cartModel->removeFromCart($cartId, $userId);
        }
        
        if ($request->isAjax()) {
            $response = new Response(json_encode(['success' => true]), 200);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        header('Location: /cart');
        exit;
    }
    
    public function remove(Request $request) {
        $user = Session::get('user');
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            if ($request->isAjax()) {
                $response = new Response(json_encode(['success' => false, 'message' => 'Please login']), 401);
                $response->setHeader('Content-Type', 'application/json');
                return $response;
            }
            header('Location: /login');
            exit;
        }
        
        $cartId = $request->getBody('cart_id');
        $this->cartModel->removeFromCart($cartId, $userId);
        
        if ($request->isAjax()) {
            $response = new Response(json_encode(['success' => true]), 200);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        header('Location: /cart');
        exit;
    }
    
    public function clear(Request $request) {
        $user = Session::get('user');
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            if ($request->isAjax()) {
                $response = new Response(json_encode(['success' => false, 'message' => 'Please login']), 401);
                $response->setHeader('Content-Type', 'application/json');
                return $response;
            }
            header('Location: /login');
            exit;
        }
        
        $this->cartModel->clearUserCart($userId);
        
        if ($request->isAjax()) {
            $response = new Response(json_encode(['success' => true]), 200);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        header('Location: /cart');
        exit;
    }
}
