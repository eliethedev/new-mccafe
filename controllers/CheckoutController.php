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

        $paymentConfig = PaymentMethod::config();

        // Senior/PWD automatic discount: pre-select when the profile is verified.
        $autoDiscount = Discount::profileEligibility($user);
        $oldDiscount = strtolower(Session::get('old')['discount_type'] ?? '');
        $selectedDiscount = in_array($oldDiscount, ['senior', 'pwd'], true)
            ? $oldDiscount
            : $autoDiscount;
        $totals = Discount::compute($subtotal, $selectedDiscount);

        return $this->view('checkout/index', [
            'cartItems' => $cartItems,
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'discountType' => $selectedDiscount,
            'tax' => $totals['tax'],
            'total' => $totals['total'],
            'user' => $user,
            'paymentMethods' => PaymentMethod::all(),
            'paymentPolicies' => PaymentMethod::policies(),
            'discountTypes' => Discount::types(),
            'discountRate' => Discount::rate(),
            'discountPolicies' => Discount::config()['policies'],
            'autoDiscount' => $autoDiscount,
            'csrfToken' => Session::token(),
        ]);
    }
    
    public function process(Request $request) {
        // Check authentication
        if (!$this->isAuthenticated()) {
            Session::flash('error', 'Please login to continue.');
            return $this->redirect('/login');
        }

        $data = $request->getBody();
        $data = array_map(function ($v) { return is_string($v) ? trim($v) : $v; }, $data);

        // CSRF protection
        $csrf = $data['_csrf'] ?? $data['_token'] ?? '';
        if (empty($csrf) || !Session::validateToken($csrf)) {
            Session::flash('error', 'Security check failed. Please refresh the checkout page and try again.');
            return $this->redirect('/checkout');
        }

        // Checkout throttle (double-submit / rapid retry protection)
        $now = time();
        $lastAttempt = (int)(Session::get('_last_checkout_attempt') ?? 0);
        if (($now - $lastAttempt) < 10) {
            Session::flash('error', 'Please wait a few seconds before placing another order.');
            return $this->redirect('/checkout');
        }
        Session::set('_last_checkout_attempt', $now);

        // Normalize + validate payment method via central registry
        $rawMethod = strtolower($data['payment_method'] ?? '');
        $paymentMethod = PaymentMethod::normalize($rawMethod);
        $allowed = PaymentMethod::allowedAtCheckout();
        if (!in_array($paymentMethod, $allowed, true)) {
            Session::flash('errors', ['payment_method' => ['Please choose a valid payment method: GCash, Maya, Bank Transfer, or Cash.']]);
            Session::flash('old', $data);
            return $this->redirect('/checkout');
        }
        $isManual = PaymentMethod::isManual($paymentMethod);

        // Validate input (base rules)
        $errors = $this->validate($data, [
            'customer_name' => 'required|min:3',
            'customer_phone' => 'required|min:10',
            'customer_email' => 'required|email',
            'customer_address' => 'required|min:5'
        ]);

        // Stricter phone format (PH mobile / general digits)
        if (!empty($data['customer_phone']) && !preg_match('/^[+\d][\d\s\-()]{9,18}$/', $data['customer_phone'])) {
            $errors['customer_phone'][] = 'Please enter a valid phone number.';
        }

        // Manual-payment specific validation
        if ($isManual) {
            $refCheck = PaymentMethod::validateReference($paymentMethod, $data['reference_number'] ?? '');
            if (!$refCheck['valid']) {
                $errors['reference_number'][] = $refCheck['message'];
            } elseif (PaymentMethod::isReferenceDuplicate($data['reference_number'] ?? '')) {
                $errors['reference_number'][] = 'This reference number has already been used. Please check your transaction and try again.';
            }

            $fileError = $this->validateProofUpload($_FILES['proof_image'] ?? null);
            if ($fileError !== null) {
                $errors['proof_image'][] = $fileError;
            }

            if (!empty(PaymentMethod::config()['security']['require_policy_consent_for_manual']) && empty($data['accept_policy'])) {
                $errors['accept_policy'][] = 'Please confirm you understand the payment, verification (within 24 hours), and no-cancellation policy.';
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

        $user = User::getProfile($_SESSION['user']['id']);

        // Out-of-stock re-check: stock may have changed after items were added.
        $unavailable = $this->findUnavailableItems($cartItems);
        if (!empty($unavailable)) {
            Session::flash('error', 'Some items just went out of stock and were kept in your cart: ' . implode(', ', $unavailable) . '. Please remove them or ask staff for a replacement.');
            return $this->redirect('/cart');
        }

        // Calculate totals server-side (never trust client totals)
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['total_price'];
        }
        $subtotal = round($subtotal, 2);

        // Senior/PWD discount (automatic from verified profile, or manual claim).
        $discountType = Discount::normalize($data['discount_type'] ?? 'none');
        $discountIdNo = trim((string)($data['discount_id_no'] ?? ''));
        $discountStatus = 'none';
        if ($discountType !== 'none') {
            $profileEligible = Discount::profileEligibility(User::getProfile($_SESSION['user']['id']));
            if ($profileEligible === $discountType) {
                // Automatic: profile already verified by staff.
                $discountStatus = 'verified';
                if ($discountIdNo === '') {
                    $discountIdNo = trim((string)($user['discount_id_no'] ?? ''));
                }
                // Refresh saved ID number if the user typed a new one.
                if ($discountIdNo !== '' && $discountIdNo !== trim((string)($user['discount_id_no'] ?? ''))) {
                    try {
                        User::update($_SESSION['user']['id'], ['discount_id_no' => $discountIdNo]);
                    } catch (Exception $e) {
                        error_log('Discount ID save failed: ' . $e->getMessage());
                    }
                }
            } else {
                // Manual claim: require valid ID + attestation; staff verify later.
                $idCheck = Discount::validateIdNumber($discountIdNo);
                if (!$idCheck['valid']) {
                    $errors['discount_id_no'][] = $idCheck['message'];
                }
                if (empty($data['accept_discount_terms'])) {
                    $errors['accept_discount_terms'][] = 'Please confirm that the Senior/PWD ID presented is valid and belongs to you.';
                }
                $discountStatus = 'pending';
            }
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            return $this->redirect('/checkout');
        }

        $totals = Discount::compute($subtotal, $discountType);
        $tax = $totals['tax'];
        $total = $totals['total'];
        $discountAmount = $totals['discount'];

        // Create order (store DB-compatible method value for legacy ENUMs)
        $orderData = [
            'user_id' => $_SESSION['user']['id'],
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'discount_type' => $discountType,
            'discount_amount' => $discountAmount,
            'discount_id_no' => $discountType !== 'none' ? $discountIdNo : null,
            'discount_status' => $discountStatus,
            'total_amount' => $total,
            'payment_method' => PaymentMethod::storageValue($paymentMethod),
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

        // Add order status history
        $historyNote = $isManual
            ? 'Order placed via ' . PaymentMethod::label($paymentMethod) . ', awaiting payment verification (within 24 hours)'
            : 'Cash order placed, pay on delivery/pickup';
        if ($discountType !== 'none') {
            $historyNote .= $discountStatus === 'verified'
                ? ' | ' . Discount::label($discountType) . ' discount auto-applied (profile verified)'
                : ' | ' . Discount::label($discountType) . ' discount claimed, pending ID verification';
        }
        OrderStatusHistory::create([
            'order_id' => $orderId,
            'status' => 'pending',
            'notes' => $historyNote
        ]);

        // Handle payment proof upload for manual payments
        if ($isManual) {
            // Email must be verified before manual payment proof is accepted
            if (!User::isEmailVerified($_SESSION['user']['id'])) {
                Session::flash('error', 'Please verify your email address before placing orders with manual payment. Check your inbox for the verification email.');
                return $this->redirect('/dashboard');
            }

            $stored = $this->storeProofUpload($_FILES['proof_image'], $orderId);
            if ($stored['error'] !== null) {
                error_log('Payment proof store failed for order ' . $orderId . ': ' . $stored['error']);
                Session::flash('error', $stored['error'] . ' Your order #' . $orderId . ' was created — please contact staff with your payment screenshot.');
                return $this->redirect("/order/{$orderId}");
            }

            $paymentProofData = [
                'order_id' => $orderId,
                'payment_method' => PaymentMethod::storageValue($paymentMethod),
                'reference_number' => trim($data['reference_number']),
                'proof_image' => $stored['filename'],
                'amount' => $total,
                'status' => 'pending'
            ];

            $proofId = PaymentProof::create($paymentProofData);

            if (!$proofId) {
                @unlink($stored['path']);
                Session::flash('error', 'Failed to save payment proof. Please contact staff with order #' . $orderId . '.');
                return $this->redirect("/order/{$orderId}");
            }

            Order::update($orderId, ['payment_status' => 'pending']);
        }

        // Clear cart
        Cart::clear($_SESSION['user']['id']);

        // Send order confirmation email
        try {
            $orderData['id'] = $orderId;
            $orderData['payment_method'] = PaymentMethod::label($paymentMethod);
            $email = new Email();
            $email->sendOrderConfirmation($data['customer_email'], $data['customer_name'], $orderData);
        } catch (Exception $e) {
            error_log('Order confirmation email failed for order ' . $orderId . ': ' . $e->getMessage());
        }

        // Redirect to order confirmation
        $discountSuffix = '';
        if ($discountType !== 'none') {
            $discountSuffix = $discountStatus === 'verified'
                ? ' ' . Discount::label($discountType) . ' discount applied.'
                : ' ' . Discount::label($discountType) . ' discount claimed — please present your ID for verification.';
        }
        if (!$isManual) {
            Session::flash('success', 'Order placed successfully! Please pay in cash on delivery/pickup.' . $discountSuffix);
        } else {
            Session::flash('success', 'Order placed successfully! Payment proof uploaded. We will verify your ' . PaymentMethod::label($paymentMethod) . ' payment within 24 hours.' . $discountSuffix);
        }
        return $this->redirect("/order/{$orderId}");
    }

    /** Names of cart items whose product/variant is currently unavailable. */
    private function findUnavailableItems($cartItems) {
        $bad = [];
        foreach ((array)$cartItems as $item) {
            try {
                $product = Model::query("SELECT is_available FROM products WHERE id = ?", [$item['product_id']])->fetch();
                if (!$product || empty($product['is_available'])) {
                    $bad[] = $item['product_name'] ?? ('#' . $item['product_id']);
                    continue;
                }
                if (!empty($item['variant_id'])) {
                    $variant = Model::query("SELECT is_available FROM product_variants WHERE id = ?", [$item['variant_id']])->fetch();
                    if (!$variant || empty($variant['is_available'])) {
                        $bad[] = ($item['product_name'] ?? 'Item') . ' (' . ($item['variant_name'] ?? 'variant') . ')';
                    }
                }
            } catch (Exception $e) {
                error_log('Checkout stock check failed: ' . $e->getMessage());
            }
        }
        return $bad;
    }

    /** Pre-validation of uploaded proof without moving the file. Returns error string or null. */    private function validateProofUpload($file) {
        $cfg = PaymentMethod::config()['upload'];
        if (empty($file) || !is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return 'Please upload a payment proof image.';
        }
        if (($file['error'] ?? 0) !== UPLOAD_ERR_OK) {
            return 'Upload failed. Please try again with a smaller image.';
        }
        if (($file['size'] ?? 0) > $cfg['max_size']) {
            return 'File too large. Maximum size is 5MB.';
        }
        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($ext, $cfg['allowed_extensions'], true)) {
            return 'Invalid file type. Please upload JPG, PNG, GIF or WebP only.';
        }
        if (!is_uploaded_file($file['tmp_name'] ?? '')) {
            return 'Upload failed. Please try again.';
        }
        $mime = @mime_content_type($file['tmp_name']);
        if (!in_array($mime, $cfg['allowed_mimes'], true)) {
            return 'Invalid file type. Please upload a genuine image (JPG/PNG/GIF/WebP).';
        }
        if (@getimagesize($file['tmp_name']) === false) {
            return 'The uploaded file is not a valid image.';
        }
        return null;
    }

    /** Securely move proof upload with random filename. Returns ['filename'=>..., 'path'=>..., 'error'=>...]. */
    private function storeProofUpload($file, $orderId) {
        $cfg = PaymentMethod::config()['upload'];
        $preError = $this->validateProofUpload($file);
        if ($preError !== null) {
            return ['filename' => null, 'path' => null, 'error' => $preError];
        }
        $uploadDir = ROOT_PATH . '/' . $cfg['dir'];
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            return ['filename' => null, 'path' => null, 'error' => 'Upload directory unavailable.'];
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $cfg['allowed_extensions'], true)) {
            $ext = 'jpg';
        }
        try {
            $filename = 'proof_' . (int)$orderId . '_' . bin2hex(random_bytes(12)) . '.' . $ext;
        } catch (Exception $e) {
            $filename = 'proof_' . (int)$orderId . '_' . time() . '_' . mt_rand(100000, 999999) . '.' . $ext;
        }
        $targetPath = $uploadDir . $filename;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['filename' => null, 'path' => null, 'error' => 'Failed to upload payment proof. Please try again.'];
        }
        @chmod($targetPath, 0644);
        return ['filename' => $filename, 'path' => $targetPath, 'error' => null];
    }

}
