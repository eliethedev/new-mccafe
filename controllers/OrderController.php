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
        
        $orders = Order::getUserOrders($userId, $page, $limit);
        $totalOrders = Order::getUserOrderCount($userId);
        $totalPages = ceil($totalOrders / $limit);
        
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
        return $this->view('admin/orders/index', [
            'title' => 'Manage Orders'
        ]);
    }
    
    public function adminShow(Request $request, $id) {
        return $this->view('admin/orders/show', [
            'orderId' => $id,
            'title' => 'Order Details'
        ]);
    }
    
    public function updateStatus(Request $request, $id) {
        $status = $request->getBody('status');
        $notes = $request->getBody('notes') ?? null;
        
        // Validate status
        $validStatuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Invalid status']), 400);
            }
            Session::flash('error', 'Invalid status');
            return $this->redirect("/admin/orders/{$id}");
        }
        
        // Get order
        $order = $this->getOrderById($id);
        if (!$order) {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Order not found']), 404);
            }
            Session::flash('error', 'Order not found');
            return $this->redirect('/admin/orders');
        }
        
        // Update order status
        $success = $this->updateOrderStatus($id, $status, $notes);
        
        if ($success) {
            // Record status change in history
            $this->recordStatusChange($id, $status, $notes, Session::get('user.id'));
            
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => true]));
            }
            Session::flash('success', 'Order status updated successfully!');
            return $this->redirect("/admin/orders/{$id}");
        } else {
            if ($request->isAjax()) {
                return new Response(json_encode(['success' => false, 'message' => 'Failed to update status']), 500);
            }
            Session::flash('error', 'Failed to update order status');
            return $this->redirect("/admin/orders/{$id}");
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
}
