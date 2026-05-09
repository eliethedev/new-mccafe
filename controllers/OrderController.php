<?php

require_once __DIR__ . '/../models/Order.php';

class OrderController extends Controller {
    public function index(Request $request) {
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        $page = $request->getQuery('page', 1);
        $limit = 10;
        
        try {
            $orders = Order::getUserOrders($userId, $page, $limit);
            $totalOrders = Order::getUserOrderCount($userId);
            $totalPages = ceil($totalOrders / $limit);
            
        } catch (Exception $e) {
            // Handle any errors gracefully
            error_log("OrderController error: " . $e->getMessage());
            $orders = [];
            $totalOrders = 0;
            $totalPages = 0;
            
            // Show error message to user
            Session::flash('message', 'Unable to load orders. Please try again later.');
        }
        
        return $this->view('user/my-orders', [
            'title' => 'My Orders',
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalOrders' => $totalOrders
        ]);
    }
    
    public function show(Request $request, $id) {
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        $order = Order::getOrderById($id, $userId);
        
        if (!$order) {
            header('Location: /orders');
            exit;
        }
        
        $orderItems = Order::getOrderItems($id);
        $statusHistory = Order::getOrderStatusHistory($id);
        
        return $this->view('user/order-details', [
            'order' => $order,
            'orderItems' => $orderItems,
            'statusHistory' => $statusHistory,
            'title' => 'Order Details - ' . $order['order_number']
        ]);
    }
    
    public function cancel(Request $request, $id) {
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        $result = Order::cancelOrder($id, $userId);
        
        if ($request->isAjax()) {
            $response = new Response(
                json_encode(['success' => $result, 'message' => $result ? 'Order cancelled successfully' : 'Failed to cancel order']), 
                200
            );
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        $_SESSION['message'] = $result ? 'Order cancelled successfully' : 'Failed to cancel order';
        header('Location: /orders');
        exit;
    }
    
    public function checkout(Request $request) {
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }
        
        return $this->view('checkout/index', [
            'cart' => $cart,
            'title' => 'Checkout'
        ]);
    }
    
    public function process(Request $request) {
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /login');
            exit;
        }
        
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }
        
        unset($_SESSION['cart']);
        
        if ($request->isAjax()) {
            $response = new Response(json_encode(['success' => true, 'order_id' => 1]), 200);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        header('Location: /orders');
        exit;
    }
    
    public function adminIndex(Request $request) {
        $status = $request->getQuery('status');
        $dateFrom = $request->getQuery('date_from');
        $dateTo = $request->getQuery('date_to');
        $search = $request->getQuery('search');
        $page = $request->getQuery('page', 1);
        $limit = 20;
        
        try {
            // First, try a simple query to see if we can get any orders
            $testQuery = "SELECT o.*, u.first_name as customer_name, u.email as customer_email 
                         FROM orders o 
                         LEFT JOIN users u ON o.user_id = u.id 
                         ORDER BY o.created_at DESC LIMIT 10";
            
            $stmt = $this->query($testQuery, []);
            $orders = $stmt->fetchAll();
            
            // Add item count and refund info for each order
            foreach ($orders as &$order) {
                $itemCount = $this->query("SELECT COUNT(*) as count FROM order_items WHERE order_id = ?", [$order['id']])->fetch();
                $order['items_count'] = $itemCount['count'] ?? 0;
                
                // Check for refund info if order is cancelled
                if ($order['status'] === 'cancelled') {
                    $refundInfo = $this->getRefundInfo($order['id']);
                    $order['refund_info'] = $refundInfo;
                } else {
                    $order['refund_info'] = null;
                }
            }
            
            $totalOrders = $this->getAdminOrdersCount($status, $dateFrom, $dateTo, $search);
            $totalPages = ceil($totalOrders / $limit);
            
            return $this->view('admin/orders/index', [
                'orders' => $orders,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'title' => 'Manage Orders'
            ]);
        } catch (Exception $e) {
            error_log("Admin orders error: " . $e->getMessage());
            
            // Return empty array for debugging
            return $this->view('admin/orders/index', [
                'orders' => [],
                'currentPage' => 1,
                'totalPages' => 1,
                'title' => 'Manage Orders'
            ]);
        }
    }
    
    public function adminShow(Request $request, $id) {
        // Get order details
        $order = $this->getOrderById($id);
        
        if (!$order) {
            Session::flash('error', 'Order not found');
            return $this->redirect('/admin/orders');
        }
        
        $orderItems = $this->getOrderItems($id);
        $statusHistory = $this->getOrderStatusHistory($id);
        
        return $this->view('admin/orders/show', [
            'order' => $order,
            'orderItems' => $orderItems,
            'statusHistory' => $statusHistory,
            'title' => 'Order Details - ' . $order['order_number']
        ]);
    }
    
    public function updateStatus(Request $request, $id) {
        // Debug: Log the request method and data
        error_log("updateStatus called - Method: " . $request->getMethod());
        error_log("updateStatus called - ID: " . $id);
        error_log("updateStatus called - POST data: " . print_r($_POST, true));
        
        // Use $_POST directly to ensure we get the data
        $status = $_POST['status'] ?? null;
        $notes = $_POST['notes'] ?? null;
        
        // Handle GCash cancellation data
        $isGcashCancel = $_POST['is_gcash_cancel'] ?? false;
        $refundAmount = $_POST['refund_amount'] ?? null;
        $refundMethod = $_POST['refund_method'] ?? null;
        $refundNotes = $_POST['refund_notes'] ?? null;
        
        error_log("Status: " . $status);
        error_log("Notes: " . $notes);
        error_log("Is GCash Cancel: " . ($isGcashCancel ? 'Yes' : 'No'));
        if ($isGcashCancel) {
            error_log("Refund Amount: " . $refundAmount);
            error_log("Refund Method: " . $refundMethod);
            error_log("Refund Notes: " . $refundNotes);
        }
        
        // Get current order
        $order = $this->getOrderById($id);
        if (!$order) {
            error_log("Order not found for ID: " . $id);
            Session::flash('error', 'Order not found');
            return $this->redirect('/admin/orders');
        }
        
        // Validate status flow
        $validation = $this->validateStatusFlow($order['status'], $status, $id);
        if (!$validation['valid']) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => $validation['message']]), 400);
            }
            Session::flash('error', $validation['message']);
            return $this->redirect("/admin/orders/{$id}");
        }
        
        // Update order status
        $success = $this->updateOrderStatus($id, $status, $notes);
        
        if ($success) {
            // Record status change in history
            $this->recordStatusChange($id, $status, $notes, Session::get('user.id'));
            
            // Handle GCash cancellation with refund data
            if ($isGcashCancel && $refundAmount && $refundMethod && $refundMethod !== 'no_refund') {
                // Create refund record
                try {
                    $this->createRefundRecord($id, $refundAmount, $refundMethod, $refundNotes);
                    error_log("Refund record created for Order ID: $id");
                } catch (Exception $e) {
                    error_log("Error creating refund record: " . $e->getMessage());
                }
            }
            
            if ($request->isAjax()) {
                $message = $isGcashCancel ? 'GCash order cancelled successfully!' : 'Order status updated successfully!';
                return new Response(json_encode(['success' => true, 'message' => $message]), 200);
            }
            
            Session::flash('success', 'Order status updated successfully!');
            return $this->redirect("/admin/orders");
        } else {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Failed to update order status']), 500);
            }
            Session::flash('error', 'Failed to update order status');
            return $this->redirect("/admin/orders");
        }
    }
    
    // Helper methods
    private function getOrderById($id) {
        $stmt = $this->query("
            SELECT o.*, u.first_name, u.last_name, u.email as customer_email, u.phone as customer_phone
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?
        ", [$id]);
        return $stmt->fetch();
    }
    
    private function getOrderItems($orderId) {
        $stmt = $this->query("
            SELECT oi.*, p.name as product_name, pv.name as variant_name
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            LEFT JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE oi.order_id = ?
            ORDER BY oi.id
        ", [$orderId]);
        return $stmt->fetchAll();
    }
    
    private function getOrderStatusHistory($orderId) {
        $stmt = $this->query("
            SELECT osh.*, u.first_name, u.last_name
            FROM order_status_history osh
            LEFT JOIN users u ON osh.changed_by = u.id
            WHERE osh.order_id = ?
            ORDER BY osh.created_at DESC
        ", [$orderId]);
        return $stmt->fetchAll();
    }
    
    private function updateOrderStatus($id, $status, $notes) {
        $sql = "UPDATE orders SET status = ?";
        $params = [$status];
        
        if ($notes) {
            $sql .= ", notes = ?";
            $params[] = $notes;
        }
        
        // Update timestamps based on status
        switch ($status) {
            case 'preparing':
                $sql .= ", preparation_started_at = NOW()";
                break;
            case 'ready':
                $sql .= ", ready_at = NOW()";
                break;
            case 'completed':
                $sql .= ", completed_at = NOW()";
                break;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount() > 0;
    }
    
    private function recordStatusChange($orderId, $status, $notes, $changedBy) {
        $sql = "INSERT INTO order_status_history (order_id, status, notes, changed_by) VALUES (?, ?, ?, ?)";
        $params = [$orderId, $status, $notes, $changedBy];
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount() > 0;
    }
    
    public function payments(Request $request) {
    $status = $request->getQuery('status');
        
    try {
        $payments = $this->getPaymentVerifications($status);
        $stats = $this->getPaymentStats();
        
        return $this->view('admin/payments/index', [
            'payments' => $payments,
            'pendingCount' => $stats['pending'],
            'verifiedCount' => $stats['verified'],
            'rejectedCount' => $stats['rejected'],
            'title' => 'Payment Verification'
        ]);
    } catch (Exception $e) {
        error_log("Payment verification error: " . $e->getMessage());
        return $this->view('admin/payments/index', [
            'payments' => [],
            'pendingCount' => 0,
            'verifiedCount' => 0,
            'rejectedCount' => 0,
            'title' => 'Payment Verification'
        ]);
    }
}

public function approvePayment(Request $request) {
    $paymentId = $request->getBody('payment_id');
    
    if (!$paymentId) {
        return new Response(json_encode(['success' => false, 'message' => 'Payment ID required']), 400);
    }
    
    try {
        $success = $this->updatePaymentStatus($paymentId, 'verified');
        
        if ($success) {
            // Update order status to confirmed
            $this->confirmOrderAfterPayment($paymentId);
        }
        
        return new Response(json_encode(['success' => $success]), 200);
    } catch (Exception $e) {
        error_log("Payment approval error: " . $e->getMessage());
        return new Response(json_encode(['success' => false, 'message' => 'Database error']), 500);
    }
}

public function rejectPayment(Request $request) {
    $paymentId = $request->getBody('payment_id');
    $reason = $request->getBody('rejection_reason');
    
    if (!$paymentId || !$reason) {
        return new Response(json_encode(['success' => false, 'message' => 'Payment ID and reason required']), 400);
    }
    
    try {
        $success = $this->updatePaymentStatus($paymentId, 'rejected', $reason);
        
        return new Response(json_encode(['success' => $success]), 200);
    } catch (Exception $e) {
        error_log("Payment rejection error: " . $e->getMessage());
        return new Response(json_encode(['success' => false, 'message' => 'Database error']), 500);
    }
}

public function getPaymentProof(Request $request, $id) {
    try {
        // Get order details
        $order = $this->getOrderById($id);
        if (!$order) {
            return new Response(json_encode(['success' => false, 'message' => 'Order not found']), 404);
        }
        
        error_log("Getting payment proof for Order ID: $id");
        
        $proofImage = null;
        $proofData = null;
        
        // First, check payment_proofs table for the correct filename
        try {
            $stmt = $this->query("SELECT proof_image, payment_method, amount FROM payment_proofs WHERE order_id = ? ORDER BY created_at DESC LIMIT 1", [$id]);
            $proofData = $stmt->fetch();
            
            if ($proofData && $proofData['proof_image']) {
                $proofImage = '/assets/images/payment-proofs/' . $proofData['proof_image'];
                error_log("Found proof image in payment_proofs table: $proofImage");
            }
        } catch (Exception $e) {
            error_log("Error checking payment_proofs table: " . $e->getMessage());
        }
        
        // If not found in payment_proofs, check payment_verifications table
        if (!$proofImage) {
            try {
                $tableCheck = $this->query("SHOW TABLES LIKE 'payment_verifications'", []);
                $tableExists = $tableCheck->rowCount() > 0;
                
                if ($tableExists) {
                    $stmt = $this->query("SELECT proof_image, payment_method, amount FROM payment_verifications WHERE order_id = ? ORDER BY created_at DESC LIMIT 1", [$id]);
                    $verification = $stmt->fetch();
                    
                    if ($verification && $verification['proof_image']) {
                        $proofImage = $verification['proof_image'];
                        $proofData = $verification;
                        error_log("Found proof image in verification table: $proofImage");
                    }
                }
            } catch (Exception $e) {
                error_log("Error checking payment_verifications table: " . $e->getMessage());
            }
        }
        
        // If still no proof, check local files as fallback
        if (!$proofImage) {
            $uploadPath = __DIR__ . '/../public/assets/images/payment-proofs/';
            
            // Check for files with order_id as prefix (timestamp naming pattern)
            $files = glob($uploadPath . "{$id}_*");
            if (!empty($files)) {
                $proofImage = '/assets/images/payment-proofs/' . basename($files[0]);
                error_log("Found local proof image with order prefix: $proofImage");
            } else {
                // Check for the old naming pattern
                $possibleFiles = [
                    $uploadPath . "order_{$id}_proof.jpg",
                    $uploadPath . "order_{$id}_proof.png",
                    $uploadPath . "order_{$id}_proof.jpeg"
                ];
                
                foreach ($possibleFiles as $file) {
                    if (file_exists($file)) {
                        $proofImage = '/assets/images/payment-proofs/' . basename($file);
                        error_log("Found local proof image: $proofImage");
                        break;
                    }
                }
            }
        }
        
        // If still no proof, create a placeholder for GCash orders
        if (!$proofImage && ($order['payment_method'] ?? 'gcash') === 'gcash') {
            $proofImage = "https://via.placeholder.com/400x300/e09407/ffffff?text=GCash+Payment+Proof+Order+" . $order['order_number'];
            error_log("Using placeholder proof image: $proofImage");
        }
        
        // Return response
        if ($proofImage) {
            return new Response(json_encode([
                'success' => true,
                'proof_image' => $proofImage,
                'payment_method' => $proofData['payment_method'] ?? $order['payment_method'] ?? 'gcash',
                'amount' => number_format($proofData['amount'] ?? $order['total_amount'], 2)
            ]), 200);
        } else {
            error_log("No proof image found for Order ID: $id");
            return new Response(json_encode([
                'success' => false,
                'payment_method' => $order['payment_method'] ?? 'gcash',
                'amount' => number_format($order['total_amount'], 2)
            ]), 200);
        }
        
    } catch (Exception $e) {
        error_log("Error getting payment proof: " . $e->getMessage());
        return new Response(json_encode(['success' => false, 'message' => 'Database error']), 500);
    }
}

public function verifyPayment(Request $request) {
    $orderId = $request->getBody('order_id');
    $approved = $request->getBody('approved');
    $reason = $request->getBody('reason') ?? '';
    
    if (!$orderId) {
        return new Response(json_encode(['success' => false, 'message' => 'Order ID required']), 400);
    }
    
    try {
        if ($approved) {
            // Update payment_proofs table first
            try {
                $this->query("UPDATE payment_proofs SET status = 'verified', updated_at = NOW() WHERE order_id = ?", [$orderId]);
                error_log("Updated payment_proofs table for approved order: $orderId");
            } catch (Exception $e) {
                error_log("Error updating payment_proofs: " . $e->getMessage());
            }
            
            // Mark order as paid and update payment status
            $this->query("UPDATE orders SET payment_status = 'paid' WHERE id = ?", [$orderId]);
            error_log("Updated order payment_status to 'paid' for approved order: $orderId");
            
            // Also update payment_verifications table if it exists
            $tableCheck = $this->query("SHOW TABLES LIKE 'payment_verifications'", []);
            if ($tableCheck->rowCount() > 0) {
                // Check if verification record exists
                $existingCheck = $this->query("SELECT id FROM payment_verifications WHERE order_id = ?", [$orderId]);
                if ($existingCheck->rowCount() > 0) {
                    // Update existing record
                    $this->query("UPDATE payment_verifications SET verification_status = 'verified', verified_by = ?, verified_at = NOW() WHERE order_id = ?", [$_SESSION['user']['id'], $orderId]);
                } else {
                    // Create new record
                    $order = $this->getOrderById($orderId);
                    $this->query("INSERT INTO payment_verifications (order_id, user_id, payment_method, amount, verification_status, verified_by, verified_at) VALUES (?, ?, ?, ?, 'verified', ?, NOW())", [
                        $orderId,
                        $order['user_id'],
                        $order['payment_method'] ?? 'gcash',
                        $order['total_amount'],
                        $_SESSION['user']['id']
                    ]);
                }
            }
            
            return new Response(json_encode(['success' => true, 'message' => 'Payment verified successfully']), 200);
        } else {
            // Reject payment - update payment_proofs table first
            try {
                $this->query("UPDATE payment_proofs SET status = 'rejected', notes = ?, updated_at = NOW() WHERE order_id = ?", [$reason, $orderId]);
                error_log("Updated payment_proofs table for rejected order: $orderId");
            } catch (Exception $e) {
                error_log("Error updating payment_proofs: " . $e->getMessage());
            }
            
            // Mark order payment status as unpaid (or rejected)
            $this->query("UPDATE orders SET payment_status = 'unpaid' WHERE id = ?", [$orderId]);
            error_log("Updated order payment_status to 'unpaid' for rejected order: $orderId");
            
            // Also update payment_verifications table if it exists
            $tableCheck = $this->query("SHOW TABLES LIKE 'payment_verifications'", []);
            if ($tableCheck->rowCount() > 0) {
                $existingCheck = $this->query("SELECT id FROM payment_verifications WHERE order_id = ?", [$orderId]);
                if ($existingCheck->rowCount() > 0) {
                    // Update existing record
                    $this->query("UPDATE payment_verifications SET verification_status = 'rejected', rejection_reason = ?, verified_by = ?, verified_at = NOW() WHERE order_id = ?", [$reason, $_SESSION['user']['id'], $orderId]);
                } else {
                    // Create new record
                    $order = $this->getOrderById($orderId);
                    $this->query("INSERT INTO payment_verifications (order_id, user_id, payment_method, amount, verification_status, rejection_reason, verified_by, verified_at) VALUES (?, ?, ?, ?, 'rejected', ?, ?, NOW())", [
                        $orderId,
                        $order['user_id'],
                        $order['payment_method'] ?? 'gcash',
                        $order['total_amount'],
                        $reason,
                        $_SESSION['user']['id']
                    ]);
                }
            }
            
            return new Response(json_encode(['success' => true, 'message' => 'Payment rejected successfully']), 200);
        }
    } catch (Exception $e) {
        error_log("Payment verification error: " . $e->getMessage());
        return new Response(json_encode(['success' => false, 'message' => 'Database error']), 500);
    }
}

private function getPaymentVerifications($status = null) {
    try {
        // Check if payment_verifications table exists
        $tableCheck = $this->query("SHOW TABLES LIKE 'payment_verifications'", []);
        $tableExists = $tableCheck->rowCount() > 0;
        
        if ($tableExists) {
            // Get actual payment verification records
            $sql = "SELECT pv.*, o.order_number, u.first_name as customer_name, u.email as customer_email
                      FROM payment_verifications pv
                      JOIN orders o ON pv.order_id = o.id
                      JOIN users u ON pv.user_id = u.id";
            
            $params = [];
            
            if ($status) {
                $sql .= " WHERE pv.verification_status = ?";
                $params[] = $status;
            }
            
            $sql .= " ORDER BY pv.created_at DESC";
            
            $stmt = $this->query($sql, $params);
            return $stmt->fetchAll();
        } else {
            // Table doesn't exist, return empty array
            return [];
        }
    } catch (Exception $e) {
        error_log("Error getting payment verifications: " . $e->getMessage());
        return [];
    }
}

private function getPaymentStats() {
    try {
        // Check if payment_verifications table exists
        $tableCheck = $this->query("SHOW TABLES LIKE 'payment_verifications'", []);
        $tableExists = $tableCheck->rowCount() > 0;
        
        if ($tableExists) {
            $today = date('Y-m-d');
            
            $pending = $this->query("SELECT COUNT(*) as count FROM payment_verifications WHERE verification_status = 'pending'")->fetch()['count'];
            $verified = $this->query("SELECT COUNT(*) as count FROM payment_verifications WHERE verification_status = 'verified' AND DATE(created_at) = ?", [$today])->fetch()['count'];
            $rejected = $this->query("SELECT COUNT(*) as count FROM payment_verifications WHERE verification_status = 'rejected' AND DATE(created_at) = ?", [$today])->fetch()['count'];
            
            return [
                'pending' => $pending,
                'verified' => $verified,
                'rejected' => $rejected
            ];
        } else {
            // Table doesn't exist
            return [
                'pending' => 0,
                'verified' => 0,
                'rejected' => 0
            ];
        }
    } catch (Exception $e) {
        error_log("Error getting payment stats: " . $e->getMessage());
        return [
            'pending' => 0,
            'verified' => 0,
            'rejected' => 0
        ];
    }
}

private function updatePaymentStatus($paymentId, $status, $reason = null) {
    $sql = "UPDATE payment_verifications SET verification_status = ?";
    $params = [$status];
    
    if ($reason) {
        $sql .= ", rejection_reason = ?, verified_by = ?, verified_at = NOW()";
        $params[] = $reason;
        $params[] = $_SESSION['user']['id'];
    } elseif ($status === 'verified') {
        $sql .= ", verified_by = ?, verified_at = NOW()";
        $params[] = $_SESSION['user']['id'];
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $paymentId;
    
    $stmt = $this->query($sql, $params);
    return $stmt->rowCount() > 0;
}

private function validateStatusFlow($currentStatus, $newStatus, $orderId) {
    // Define allowed status transitions
    $statusFlow = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['preparing', 'cancelled'],
        'preparing' => ['ready', 'cancelled'],
        'ready' => ['completed', 'cancelled'],
        'completed' => [], // Final state - no further transitions
        'cancelled' => []  // Final state - no further transitions
    ];
    
    // If status is the same, no change needed
    if ($currentStatus === $newStatus) {
        return ['valid' => false, 'message' => 'Order is already in ' . $currentStatus . ' status'];
    }
    
    // Check if the transition is allowed
    if (!isset($statusFlow[$currentStatus])) {
        return ['valid' => false, 'message' => 'Invalid current status: ' . $currentStatus];
    }
    
    if (!in_array($newStatus, $statusFlow[$currentStatus])) {
        $allowedTransitions = implode(', ', $statusFlow[$currentStatus]);
        return ['valid' => false, 'message' => 'Cannot change status from ' . $currentStatus . ' to ' . $newStatus . '. Allowed transitions: ' . $allowedTransitions];
    }
    
    // Additional business logic validations
    switch ($newStatus) {
        case 'confirmed':
            // Get order details to check payment method
            $order = $this->getOrderById($orderId);
            $paymentMethod = $order['payment_method'] ?? 'cash';
            
            // For GCash orders, payment must be verified first
            if ($paymentMethod === 'gcash' && !$this->isPaymentVerified($orderId)) {
                return ['valid' => false, 'message' => 'Cannot confirm GCash order. Payment proof must be verified first.'];
            }
            break;
            
        case 'preparing':
            // Check if order has items
            if (!$this->orderHasItems($orderId)) {
                return ['valid' => false, 'message' => 'Cannot start preparation. Order has no items.'];
            }
            break;
            
        case 'ready':
            // Check if preparation was started
            if (!$this->isPreparationStarted($orderId)) {
                return ['valid' => false, 'message' => 'Cannot mark as ready. Order preparation must be started first.'];
            }
            break;
            
        case 'completed':
            // Check if order was marked as ready
            if ($currentStatus !== 'ready') {
                return ['valid' => false, 'message' => 'Cannot complete order. Order must be marked as ready first.'];
            }
            break;
            
        case 'cancelled':
            // Prevent cancellation of completed orders
            if ($currentStatus === 'completed') {
                return ['valid' => false, 'message' => 'Cannot cancel completed orders.'];
            }
            break;
    }
    
    return ['valid' => true, 'message' => 'Status transition is valid'];
}

private function isPaymentVerified($orderId) {
    try {
        // Get order details first
        $order = $this->getOrderById($orderId);
        $paymentMethod = $order['payment_method'] ?? 'cash';
        
        // For cash orders, no verification needed
        if ($paymentMethod === 'cash' || $paymentMethod === 'cash_on_delivery') {
            return true;
        }
        
        // For GCash orders, check payment verification
        if ($paymentMethod === 'gcash') {
            // Check if payment verification table exists
            $stmt = $this->query("SHOW TABLES LIKE 'payment_verifications'", []);
            $tableExists = $stmt->rowCount() > 0;
            
            if (!$tableExists) {
                // If table doesn't exist, check if payment status is already paid
                return $order['payment_status'] === 'paid';
            }
            
            // Check if payment verification exists and is approved
            $stmt = $this->query("SELECT COUNT(*) as count FROM payment_verifications WHERE order_id = ? AND verification_status = 'verified'", [$orderId]);
            $isVerified = $stmt->fetch()['count'] > 0;
            
            // Also check if payment status is already marked as paid
            $isPaid = $order['payment_status'] === 'paid';
            
            return $isVerified || $isPaid;
        }
        
        // Default to true for other payment methods
        return true;
    } catch (Exception $e) {
        // If there's any error, check payment status as fallback
        error_log("Payment verification check failed: " . $e->getMessage());
        try {
            $order = $this->getOrderById($orderId);
            return $order['payment_status'] === 'paid';
        } catch (Exception $fallbackError) {
            return true; // Avoid blocking orders
        }
    }
}

private function orderHasItems($orderId) {
    $stmt = $this->query("SELECT COUNT(*) as count FROM order_items WHERE order_id = ?", [$orderId]);
    return $stmt->fetch()['count'] > 0;
}

private function isPreparationStarted($orderId) {
    $stmt = $this->query("SELECT preparation_started_at FROM orders WHERE id = ?", [$orderId]);
    $order = $stmt->fetch();
    return !empty($order['preparation_started_at']);
}


private function confirmOrderAfterPayment($paymentId) {
    // Get order ID from payment verification
    $payment = $this->query("SELECT order_id FROM payment_verifications WHERE id = ?", [$paymentId])->fetch();
    
    if ($payment && $payment['order_id']) {
        // Update order status to confirmed
        $this->query("UPDATE orders SET status = 'confirmed' WHERE id = ? AND status = 'pending'", [$payment['order_id']]);
    }
}

private function getAdminOrders($status = null, $dateFrom = null, $dateTo = null, $search = null, $page = 1, $limit = 20) {
    $offset = ($page - 1) * $limit;
    
    // Simple query to start
    $sql = "SELECT o.*, 
                    u.first_name as customer_name,
                    u.email as customer_email
              FROM orders o 
              LEFT JOIN users u ON o.user_id = u.id";
    
    $params = [];
    $conditions = [];
    
    if ($status) {
        $conditions[] = "o.status = ?";
        $params[] = $status;
    }
    
    if ($dateFrom) {
        $conditions[] = "DATE(o.created_at) >= ?";
        $params[] = $dateFrom;
    }
    
    if ($dateTo) {
        $conditions[] = "DATE(o.created_at) <= ?";
        $params[] = $dateTo;
    }
    
    if ($search) {
        $conditions[] = "(o.order_number LIKE ? OR u.first_name LIKE ? OR u.email LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    try {
        $stmt = $this->query($sql, $params);
        $orders = $stmt->fetchAll();
        
        // Add item count for each order
        foreach ($orders as &$order) {
            $itemCount = $this->query("SELECT COUNT(*) as count FROM order_items WHERE order_id = ?", [$order['id']])->fetch();
            $order['items_count'] = $itemCount['count'] ?? 0;
        }
        
        return $orders;
    } catch (Exception $e) {
        error_log("getAdminOrders error: " . $e->getMessage());
        return [];
    }
}

private function getAdminOrdersCount($status = null, $dateFrom = null, $dateTo = null, $search = null) {
    $sql = "SELECT COUNT(*) as count FROM orders o LEFT JOIN users u ON o.user_id = u.id";
    
    $params = [];
    $conditions = [];
    
    if ($status) {
        $conditions[] = "o.status = ?";
        $params[] = $status;
    }
    
    if ($dateFrom) {
        $conditions[] = "DATE(o.created_at) >= ?";
        $params[] = $dateFrom;
    }
    
    if ($dateTo) {
        $conditions[] = "DATE(o.created_at) <= ?";
        $params[] = $dateTo;
    }
    
    if ($search) {
        $conditions[] = "(o.order_number LIKE ? OR u.first_name LIKE ? OR u.email LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    try {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch()['count'];
    } catch (Exception $e) {
        error_log("getAdminOrdersCount error: " . $e->getMessage());
        return 0;
    }
}

private function query($sql, $params = []) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    private function createRefundRecord($orderId, $refundAmount, $refundMethod, $refundNotes) {
        // Check if refunds table exists, if not create it
        $tableCheck = $this->query("SHOW TABLES LIKE 'refunds'", []);
        if ($tableCheck->rowCount() === 0) {
            // Create refunds table
            $this->query("
                CREATE TABLE refunds (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    order_id INT NOT NULL,
                    refund_amount DECIMAL(10,2) NOT NULL,
                    refund_method ENUM('gcash', 'cash', 'bank_transfer', 'store_credit') NOT NULL,
                    refund_notes TEXT,
                    refund_status ENUM('pending', 'processed', 'failed') DEFAULT 'pending',
                    processed_by INT,
                    processed_at TIMESTAMP NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                    FOREIGN KEY (processed_by) REFERENCES users(id),
                    INDEX idx_order (order_id),
                    INDEX idx_status (refund_status)
                )
            ");
            error_log("Created refunds table");
        }
        
        // Insert refund record
        $this->query("
            INSERT INTO refunds (order_id, refund_amount, refund_method, refund_notes, refund_status, processed_by, processed_at) 
            VALUES (?, ?, ?, ?, 'processed', ?, NOW())
        ", [
            $orderId,
            $refundAmount,
            $refundMethod,
            $refundNotes,
            $_SESSION['user']['id']
        ]);
        
        error_log("Refund record created: Order $orderId, Amount $refundAmount, Method $refundMethod");
    }

    public function processRefund(Request $request) {
        $orderId = $request->getBody('order_id');
        $refundAmount = $request->getBody('refund_amount');
        $refundMethod = $request->getBody('refund_method');
        $refundNotes = $request->getBody('refund_notes');
        
        if (!$orderId || !$refundAmount || !$refundMethod) {
            return new Response(json_encode(['success' => false, 'message' => 'Missing required fields']), 400);
        }
        
        try {
            // Create refund record
            $this->createRefundRecord($orderId, $refundAmount, $refundMethod, $refundNotes);
            
            return new Response(json_encode(['success' => true, 'message' => 'Refund processed successfully']), 200);
        } catch (Exception $e) {
            error_log("Error processing refund: " . $e->getMessage());
            return new Response(json_encode(['success' => false, 'message' => 'Failed to process refund']), 500);
        }
    }

    private function getRefundInfo($orderId) {
        try {
            // Check if refunds table exists
            $tableCheck = $this->query("SHOW TABLES LIKE 'refunds'", []);
            if ($tableCheck->rowCount() === 0) {
                return null;
            }
            
            // Get refund info
            $stmt = $this->query("SELECT refund_amount, refund_method, refund_status, refund_notes, processed_at FROM refunds WHERE order_id = ? ORDER BY created_at DESC LIMIT 1", [$orderId]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Error getting refund info: " . $e->getMessage());
            return null;
        }
    }
}
