<?php
/**
 * MacCafe Checkout Process Simulation
 * 
 * This script simulates the complete checkout flow including:
 * 1. User registration and email verification
 * 2. Adding items to cart
 * 3. Proceeding to checkout with payment method selection
 * 4. Manual payment system with proof upload
 * 5. Order confirmation and status tracking
 */

// Include required files
require_once 'config/config.php';
require_once 'core/Model.php';
require_once 'models/User.php';
require_once 'models/Cart.php';
require_once 'models/Order.php';
require_once 'models/PaymentProof.php';
require_once 'models/OrderItem.php';
require_once 'models/OrderStatusHistory.php';

// Initialize session
session_start();

class CheckoutSimulation {
    private $testUser;
    private $testOrder;
    
    public function __construct() {
        echo "=== MacCafe Checkout Process Simulation ===\n\n";
    }
    
    /**
     * Step 1: User Registration and Email Verification
     */
    public function simulateUserRegistration() {
        echo "📝 STEP 1: User Registration and Email Verification\n";
        echo str_repeat("-", 50) . "\n";
        
        // Create test user
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'phone' => '09123456789',
            'address' => '123 Test Street',
            'city' => 'Quezon City',
            'role' => 'customer'
        ];
        
        echo "Creating test user: {$userData['email']}\n";
        
        try {
            // Check if user already exists
            $existingUser = User::findByEmail($userData['email']);
            if ($existingUser) {
                echo "✅ User already exists. Using existing user.\n";
                $this->testUser = $existingUser;
            } else {
                // Create new user
                $userId = User::create($userData);
                if ($userId) {
                    echo "✅ User created successfully with ID: $userId\n";
                    $this->testUser = User::getProfile($userId);
                    
                    // Simulate email verification
                    $this->simulateEmailVerification($userId);
                } else {
                    echo "❌ Failed to create user\n";
                    return false;
                }
            }
        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
            return false;
        }
        
        echo "User Details:\n";
        echo "- Name: {$this->testUser['first_name']} {$this->testUser['last_name']}\n";
        echo "- Email: {$this->testUser['email']}\n";
        echo "- Phone: {$this->testUser['phone']}\n";
        echo "- Email Verified: " . ($this->testUser['email_verified_at'] ? 'Yes' : 'No') . "\n\n";
        
        return true;
    }
    
    /**
     * Simulate email verification process
     */
    private function simulateEmailVerification($userId) {
        echo "📧 Simulating email verification...\n";
        
        // Create verification token
        $token = User::createVerificationToken($userId);
        echo "✅ Verification token generated: " . substr($token, 0, 8) . "...\n";
        
        // Simulate email sending
        echo "📨 Verification email sent to user\n";
        
        // Simulate user clicking verification link
        $verifiedUserId = User::verifyEmail($token);
        if ($verifiedUserId) {
            echo "✅ Email verified successfully!\n";
        } else {
            echo "❌ Email verification failed\n";
        }
    }
    
    /**
     * Step 2: Add items to cart
     */
    public function simulateAddToCart() {
        echo "🛒 STEP 2: Adding Items to Cart\n";
        echo str_repeat("-", 50) . "\n";
        
        // Simulate adding products to cart
        $cartItems = [
            [
                'product_id' => 1, // Americano
                'product_name' => 'Americano',
                'quantity' => 2,
                'unit_price' => 55.00,
                'variant_name' => '16 oz'
            ],
            [
                'product_id' => 5, // Java Chip Frappe
                'product_name' => 'Java Chip Frappe',
                'quantity' => 1,
                'unit_price' => 89.00,
                'variant_name' => 'Regular'
            ]
        ];
        
        echo "Adding items to cart:\n";
        
        foreach ($cartItems as $item) {
            echo "- {$item['quantity']}x {$item['product_name']} ({$item['variant_name']}) - ₱" . number_format($item['unit_price'], 2) . "\n";
            
            // Add to cart database
            try {
                Model::query(
                    "INSERT INTO cart (user_id, product_id, quantity, unit_price, created_at) 
                     VALUES (?, ?, ?, ?, NOW())",
                    [$this->testUser['id'], $item['product_id'], $item['quantity'], $item['unit_price']]
                );
            } catch (Exception $e) {
                echo "⚠️  Note: " . $e->getMessage() . "\n";
            }
        }
        
        // Calculate totals
        $subtotal = ($cartItems[0]['quantity'] * $cartItems[0]['unit_price']) + 
                   ($cartItems[1]['quantity'] * $cartItems[1]['unit_price']);
        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;
        
        echo "\nCart Summary:\n";
        echo "- Subtotal: ₱" . number_format($subtotal, 2) . "\n";
        echo "- Tax (12%): ₱" . number_format($tax, 2) . "\n";
        echo "- Total: ₱" . number_format($total, 2) . "\n\n";
        
        return ['items' => $cartItems, 'subtotal' => $subtotal, 'tax' => $tax, 'total' => $total];
    }
    
    /**
     * Step 3: Proceed to Checkout
     */
    public function simulateCheckout($cartData) {
        echo "🧾 STEP 3: Proceeding to Checkout\n";
        echo str_repeat("-", 50) . "\n";
        
        // Check email verification (this is enforced in the actual controller)
        if (!User::isEmailVerified($this->testUser['id'])) {
            echo "❌ Email not verified. Redirecting to dashboard for verification.\n";
            return false;
        }
        
        echo "✅ Email verified. Proceeding to checkout.\n";
        echo "\nCheckout Form Fields:\n";
        echo "- Full Name: {$this->testUser['first_name']} {$this->testUser['last_name']}\n";
        echo "- Email: {$this->testUser['email']}\n";
        echo "- Phone: {$this->testUser['phone']}\n";
        echo "- Address: {$this->testUser['address']}\n";
        echo "- City: {$this->testUser['city']}\n";
        
        // Payment method selection
        $paymentMethods = ['gcash', 'paymaya', 'bank_transfer', 'cash'];
        $selectedPayment = 'gcash'; // Simulating GCash selection
        
        echo "\n💳 Payment Method Selection:\n";
        foreach ($paymentMethods as $method) {
            $status = ($method === $selectedPayment) ? '✅ SELECTED' : '⚪ Available';
            echo "- " . ucfirst($method) . ": $status\n";
        }
        
        if (in_array($selectedPayment, ['gcash', 'paymaya', 'bank_transfer'])) {
            echo "\n📱 Manual Payment Requirements for $selectedPayment:\n";
            echo "- Reference Number: Required\n";
            echo "- Payment Proof Screenshot: Required\n";
            echo "- File Types: JPG, PNG (max 5MB)\n";
        }
        
        return ['payment_method' => $selectedPayment, 'cart_data' => $cartData];
    }
    
    /**
     * Step 4: Process Order with Manual Payment
     */
    public function simulateOrderProcessing($checkoutData) {
        echo "\n🔄 STEP 4: Processing Order with Manual Payment\n";
        echo str_repeat("-", 50) . "\n";
        
        $cartData = $checkoutData['cart_data'];
        $paymentMethod = $checkoutData['payment_method'];
        
        // Generate order number
        $orderNumber = 'ORD' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        echo "📋 Creating Order:\n";
        echo "- Order Number: $orderNumber\n";
        echo "- Payment Method: " . ucfirst($paymentMethod) . "\n";
        echo "- Total Amount: ₱" . number_format($cartData['total'], 2) . "\n";
        
        // Create order data
        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => $this->testUser['id'],
            'status' => 'pending',
            'subtotal' => $cartData['subtotal'],
            'tax_amount' => $cartData['tax'],
            'total_amount' => $cartData['total'],
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'customer_name' => $this->testUser['first_name'] . ' ' . $this->testUser['last_name'],
            'customer_phone' => $this->testUser['phone'],
            'customer_email' => $this->testUser['email'],
            'customer_address' => $this->testUser['address'],
            'customer_city' => $this->testUser['city']
        ];
        
        try {
            // Create order
            $orderId = Order::create($orderData);
            if ($orderId) {
                echo "✅ Order created successfully with ID: $orderId\n";
                $this->testOrder = array_merge($orderData, ['id' => $orderId]);
                
                // Create order items
                foreach ($cartData['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $orderId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['quantity'] * $item['unit_price'],
                        'product_name' => $item['product_name'],
                        'variant_name' => $item['variant_name']
                    ]);
                }
                
                // Add status history
                OrderStatusHistory::create([
                    'order_id' => $orderId,
                    'status' => 'pending',
                    'notes' => 'Order placed, awaiting payment verification'
                ]);
                
                echo "✅ Order items and status history created\n";
                
                // Process manual payment
                if (in_array($paymentMethod, ['gcash', 'paymaya', 'bank_transfer'])) {
                    $this->simulateManualPayment($orderId, $paymentMethod, $cartData['total']);
                }
                
                return true;
            } else {
                echo "❌ Failed to create order\n";
                return false;
            }
        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Step 5: Manual Payment Processing
     */
    private function simulateManualPayment($orderId, $paymentMethod, $amount) {
        echo "\n💰 STEP 5: Manual Payment Processing\n";
        echo str_repeat("-", 50) . "\n";
        
        echo "📱 Payment Details for " . ucfirst($paymentMethod) . ":\n";
        echo "- Number: 0912-345-6789\n";
        echo "- Name: MacCafe Store\n";
        
        if ($paymentMethod === 'bank_transfer') {
            echo "- Bank: BPI\n";
            echo "- Account Name: MacCafe Store\n";
        }
        
        echo "\n📸 Simulating Payment Proof Upload:\n";
        $referenceNumber = 'GCASH' . date('YmdHis') . mt_rand(100, 999);
        $proofImage = 'payment_proof_' . time() . '.jpg';
        
        echo "- Reference Number: $referenceNumber\n";
        echo "- Proof Image: $proofImage\n";
        echo "- Amount: ₱" . number_format($amount, 2) . "\n";
        
        // Validate file (simulated)
        echo "\n🔍 File Validation:\n";
        echo "- File Type: JPG ✅\n";
        echo "- File Size: 2.3MB ✅ (under 5MB limit)\n";
        
        // Create payment proof record
        $paymentProofData = [
            'order_id' => $orderId,
            'payment_method' => $paymentMethod,
            'reference_number' => $referenceNumber,
            'proof_image' => $proofImage,
            'amount' => $amount,
            'status' => 'pending'
        ];
        
        try {
            $proofId = PaymentProof::create($paymentProofData);
            if ($proofId) {
                echo "✅ Payment proof uploaded successfully!\n";
                echo "📧 Notification: Payment proof submitted for verification\n";
                echo "⏱️  Expected verification time: Within 24 hours\n";
                
                // Update order notes
                Model::query(
                    "UPDATE orders SET notes = CONCAT(IFNULL(notes, ''), ' - Payment proof uploaded: $referenceNumber') WHERE id = ?",
                    [$orderId]
                );
                
                return true;
            } else {
                echo "❌ Failed to save payment proof\n";
                return false;
            }
        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Step 6: Order Confirmation and Status Tracking
     */
    public function simulateOrderConfirmation() {
        echo "\n📧 STEP 6: Order Confirmation and Status Tracking\n";
        echo str_repeat("-", 50) . "\n";
        
        echo "📋 Order Confirmation Details:\n";
        echo "- Order Number: {$this->testOrder['order_number']}\n";
        echo "- Status: {$this->testOrder['status']}\n";
        echo "- Payment Status: {$this->testOrder['payment_status']}\n";
        echo "- Total Amount: ₱" . number_format($this->testOrder['total_amount'], 2) . "\n";
        
        echo "\n📧 Order Confirmation Email Sent:\n";
        echo "- To: {$this->testOrder['customer_email']}\n";
        echo "- Subject: Order Confirmation - {$this->testOrder['order_number']}\n";
        echo "- Contents: Order details, payment instructions, and tracking info\n";
        
        echo "\n📊 Order Status Flow:\n";
        echo "1. Pending → Awaiting Payment Verification\n";
        echo "2. Pending → Payment Verified → Confirmed\n";
        echo "3. Confirmed → Preparing → Ready for Pickup\n";
        echo "4. Ready for Pickup → Completed\n";
        
        echo "\n🔔 Customer Notifications:\n";
        echo "- ✅ Order placed successfully\n";
        echo "- 📧 Payment proof received (pending verification)\n";
        echo "- ⏳ Awaiting admin payment verification\n";
        echo "- 📱 SMS updates will be sent for status changes\n";
        
        echo "\n👤 Admin Dashboard Actions:\n";
        echo "- Review payment proof\n";
        echo "- Approve/Reject payment\n";
        echo "- Update order status\n";
        echo "- Notify customer of changes\n";
        
        return true;
    }
    
    /**
     * Run complete simulation
     */
    public function runSimulation() {
        // Step 1: User Registration and Email Verification
        if (!$this->simulateUserRegistration()) {
            return false;
        }
        
        // Step 2: Add items to cart
        $cartData = $this->simulateAddToCart();
        
        // Step 3: Proceed to checkout
        $checkoutData = $this->simulateCheckout($cartData);
        if (!$checkoutData) {
            return false;
        }
        
        // Step 4: Process order
        if (!$this->simulateOrderProcessing($checkoutData)) {
            return false;
        }
        
        // Step 5: Order confirmation
        $this->simulateOrderConfirmation();
        
        echo "\n🎉 SIMULATION COMPLETED SUCCESSFULLY!\n";
        echo str_repeat("=", 50) . "\n";
        echo "Summary:\n";
        echo "- ✅ User registered and email verified\n";
        echo "- ✅ Items added to cart\n";
        echo "- ✅ Checkout process completed\n";
        echo "- ✅ Manual payment proof uploaded\n";
        echo "- ✅ Order created and confirmed\n";
        echo "- ✅ Notifications sent\n";
        
        return true;
    }
}

// Run simulation
$simulation = new CheckoutSimulation();
$simulation->runSimulation();

?>
