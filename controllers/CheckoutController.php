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
        
        // Check if user's email is verified
        if (!User::isEmailVerified($_SESSION['user']['id'])) {
            Session::flash('error', 'Please verify your email address before placing orders.');
            return $this->redirect('/dashboard');
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
        
        // Check if user's email is verified
        if (!User::isEmailVerified($_SESSION['user']['id'])) {
            Session::flash('error', 'Please verify your email address before placing orders.');
            return $this->redirect('/dashboard');
        }
        
        $data = $request->getBody();
        
        // Validate input
        $errors = $this->validate($data, [
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer',
            'customer_name' => 'required|min:3',
            'customer_phone' => 'required|min:10',
            'customer_email' => 'required|email'
        ]);
        
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
            'order_number' => $orderNumber,
            'user_id' => $_SESSION['user']['id'],
            'status' => 'pending',
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'total_amount' => $total,
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'],
            'notes' => $data['notes'] ?? null
        ];
        
        $orderId = Order::create($orderData);
        
        if (!$orderId) {
            Session::flash('error', 'Failed to create order. Please try again.');
            return $this->redirect('/checkout');
        }
        
        // Create order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
                'product_name' => $item['product_name'],
                'variant_name' => $item['variant_name'] ?? null
            ]);
        }
        
        // Add order status history
        OrderStatusHistory::create([
            'order_id' => $orderId,
            'status' => 'pending',
            'notes' => 'Order placed, awaiting payment verification'
        ]);
        
        // Clear cart
        Cart::clear($_SESSION['user']['id']);
        
        // Send order confirmation email
        $orderData['id'] = $orderId;
        $email = new Email();
        $email->sendOrderConfirmation($data['customer_email'], $data['customer_name'], $orderData);
        
        // Redirect to payment page or order confirmation
        if ($data['payment_method'] === 'cash') {
            Session::flash('success', 'Order placed successfully! Please pay upon pickup.');
            return $this->redirect("/order/{$orderId}");
        } else {
            Session::flash('success', 'Order placed successfully! Please upload your payment proof.');
            return $this->redirect("/checkout/payment/{$orderId}");
        }
    }
    
    public function payment(Request $request, $orderId) {
        // Check authentication
        if (!$this->isAuthenticated()) {
            Session::flash('error', 'Please login to continue.');
            return $this->redirect('/login');
        }
        
        // Get order details
        $order = Order::find($orderId);
        
        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            Session::flash('error', 'Order not found.');
            return $this->redirect('/dashboard');
        }
        
        // Check if payment is already verified
        if ($order['payment_status'] === 'paid') {
            Session::flash('info', 'Payment already verified.');
            return $this->redirect("/order/{$orderId}");
        }
        
        return $this->view('checkout/payment', [
            'order' => $order
        ]);
    }
    
    public function uploadPaymentProof(Request $request) {
        // Check authentication
        if (!$this->isAuthenticated()) {
            Session::flash('error', 'Please login to continue.');
            return $this->redirect('/login');
        }
        
        $orderId = $request->getBody('order_id');
        $referenceNumber = $request->getBody('reference_number');
        $paymentMethod = $request->getBody('payment_method');
        
        // Validate input
        $errors = $this->validate($request->getBody(), [
            'order_id' => 'required',
            'reference_number' => 'required|min:5',
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer'
        ]);
        
        // Check if file was uploaded
        if (!isset($_FILES['proof_image']) || $_FILES['proof_image']['error'] !== UPLOAD_ERR_OK) {
            $errors['proof_image'][] = 'Please upload a payment proof image.';
        }
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $request->getBody());
            return $this->redirect("/checkout/payment/{$orderId}");
        }
        
        // Get order details
        $order = Order::find($orderId);
        
        if (!$order || $order['user_id'] != $_SESSION['user']['id']) {
            Session::flash('error', 'Order not found.');
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
            return $this->redirect("/checkout/payment/{$orderId}");
        }
        
        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            Session::flash('error', 'File too large. Maximum size is 5MB.');
            return $this->redirect("/checkout/payment/{$orderId}");
        }
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            Session::flash('error', 'Failed to upload payment proof. Please try again.');
            return $this->redirect("/checkout/payment/{$orderId}");
        }
        
        // Create payment proof record
        $paymentProofData = [
            'order_id' => $orderId,
            'payment_method' => $paymentMethod,
            'reference_number' => $referenceNumber,
            'proof_image' => $fileName,
            'amount' => $order['total_amount'],
            'status' => 'pending'
        ];
        
        $proofId = PaymentProof::create($paymentProofData);
        
        if ($proofId) {
            Session::flash('success', 'Payment proof uploaded successfully! We will verify your payment within 24 hours.');
            return $this->redirect("/order/{$orderId}");
        } else {
            Session::flash('error', 'Failed to save payment proof. Please try again.');
            return $this->redirect("/checkout/payment/{$orderId}");
        }
    }
}
