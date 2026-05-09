<?php

class CheckoutController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    private function requireAuth() {
        if (!$this->isAuthenticated()) {
            Session::flash('error', 'Please login to continue.');
            Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
            return $this->redirect('/login');
        }
    }
    
    public function index() {
        // Check authentication
        if (!$this->isAuthenticated()) {
            Session::flash('error', 'Please login to continue.');
            Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
            return $this->redirect('/login');
        }
        
        // Debug: Check current user session
        $userId = $_SESSION['user']['id'];
        error_log("Checkout Index: Current User ID from session: " . $userId);
        
        // Email verification check removed - users can access checkout without verification
        // Verification will be required before payment proof upload
        
        // Get cart items
        $cartItems = Cart::getItems($_SESSION['user']['id']);
        
        if (empty($cartItems)) {
            Session::flash('error', 'Your cart is empty.');
            return $this->redirect('/cart');
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['total_price'];
        }
        
        $tax = $subtotal * 0.12; // 12% tax
        $total = $subtotal + $tax;
        
        // Get user profile
        $user = User::getProfile($_SESSION['user']['id']);
        
        return $this->view('checkout/index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'user' => $user
        ]);
    }
    
    public function process(Request $request) {
        // Check authentication
        if (!$this->isAuthenticated()) {
            Session::flash('error', 'Please login to continue.');
            return $this->redirect('/login');
        }
        
        // Email verification check temporarily disabled for order placement
        // Users can place order but need to verify email before payment proof
        // if (!User::isEmailVerified($_SESSION['user']['id'])) {
        //     Session::flash('error', 'Please verify your email address before placing orders. Check your inbox for the verification email.');
        //     return $this->redirect('/dashboard');
        // }
        
        $data = $request->getBody();
        
        // Validate input
        $errors = $this->validate($data, [
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer,cash',
            'customer_name' => 'required|min:3',
            'customer_phone' => 'required|min:10',
            'customer_email' => 'required|email',
            'customer_address' => 'required|min:5'
        ]);
        
        // Validate payment proof for manual payments
        $manualPaymentMethods = ['gcash', 'paymaya', 'bank_transfer'];
        if (in_array($data['payment_method'], $manualPaymentMethods)) {
            // Check if file was uploaded
            if (!isset($_FILES['proof_image']) || $_FILES['proof_image']['error'] !== UPLOAD_ERR_OK) {
                $errors['proof_image'][] = 'Please upload a payment proof image.';
            }
            
            // Validate reference number
            if (empty($data['reference_number'])) {
                $errors['reference_number'][] = 'Reference number is required.';
            }
        }
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            return $this->redirect('/checkout');
        }
        
        // Get cart items
        $cartItems = Cart::getItems($_SESSION['user']['id']);
        
        if (empty($cartItems)) {
            Session::flash('error', 'Your cart is empty.');
            return $this->redirect('/cart');
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['total_price'];
        }
        
        $tax = $subtotal * 0.12; // 12% tax
        $total = $subtotal + $tax;
        
        // Generate order number
        $orderNumber = 'ORD' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Create order
        $orderData = [
            'user_id' => $_SESSION['user']['id'],
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'total_amount' => $total,
            'payment_method' => $data['payment_method'],
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'],
            'customer_address' => $data['customer_address'],
            'notes' => $data['notes'] ?? null,
            'items' => $cartItems
        ];
        
        $orderId = Order::createOrder($orderData);
        
        if (!$orderId) {
            Session::flash('error', 'Failed to create order. Please try again.');
            return $this->redirect('/checkout');
        }
        
        // Order items are already created by createOrder method
        
        // Add order status history
        OrderStatusHistory::create([
            'order_id' => $orderId,
            'status' => 'pending',
            'notes' => 'Order placed, awaiting payment verification'
        ]);
        
        // Handle payment proof upload for manual payments
        $manualPaymentMethods = ['gcash', 'paymaya', 'bank_transfer'];
        if (in_array($data['payment_method'], $manualPaymentMethods)) {
            // Check email verification before processing payment proof
            if (!User::isEmailVerified($_SESSION['user']['id'])) {
                Session::flash('error', 'Please verify your email address before placing orders with manual payment. Check your inbox for the verification email.');
                return $this->redirect('/dashboard');
            }
            
            // Handle file upload
            $uploadDir = 'public/assets/images/payment-proofs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $file = $_FILES['proof_image'];
            $fileName = time() . '_' . basename($file['name']);
            $targetPath = $uploadDir . $fileName;
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $fileType = mime_content_type($file['tmp_name']);
            
            if (!in_array($fileType, $allowedTypes)) {
                Session::flash('error', 'Invalid file type. Please upload JPG or PNG images only.');
                return $this->redirect('/checkout');
            }
            
            // Validate file size (5MB max)
            if ($file['size'] > 5 * 1024 * 1024) {
                Session::flash('error', 'File too large. Maximum size is 5MB.');
                return $this->redirect('/checkout');
            }
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                Session::flash('error', 'Failed to upload payment proof. Please try again.');
                return $this->redirect('/checkout');
            }
            
            // Create payment proof record
            $paymentProofData = [
                'order_id' => $orderId,
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'],
                'proof_image' => $fileName,
                'amount' => $total,
                'status' => 'pending'
            ];
            
            $proofId = PaymentProof::create($paymentProofData);
            
            if (!$proofId) {
                Session::flash('error', 'Failed to save payment proof. Please try again.');
                return $this->redirect('/checkout');
            }
            
            // Update order payment status
            Order::update($orderId, ['payment_status' => 'pending']);
        }
        
        // Clear cart
        Cart::clear($_SESSION['user']['id']);
        
        // Send order confirmation email
        $orderData['id'] = $orderId;
        $email = new Email();
        $email->sendOrderConfirmation($data['customer_email'], $data['customer_name'], $orderData);
        
        // Redirect to order confirmation
        if ($data['payment_method'] === 'cash') {
            Session::flash('success', 'Order placed successfully! Please pay upon pickup.');
        } else {
            Session::flash('success', 'Order placed successfully! Payment proof uploaded. We will verify your payment within 24 hours.');
        }
        return $this->redirect("/order/{$orderId}");
    }
    
}
