<?php

class Order extends Model {
    protected $table = 'orders';
    protected $primaryKey = 'id';
    
    public static function getUserOrders($userId, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $stmt = self::query(
            "SELECT o.*, 
                    COUNT(oi.id) as item_count,
                    GROUP_CONCAT(CONCAT(oi.product_name, ' (', oi.quantity, ')') SEPARATOR ', ') as items_summary
             FROM orders o
             LEFT JOIN order_items oi ON o.id = oi.order_id
             WHERE o.user_id = ?
             GROUP BY o.id
             ORDER BY o.created_at DESC
             LIMIT ? OFFSET ?", 
            [$userId, $limit, $offset]
        );
        
        return $stmt->fetchAll();
    }
    
    public static function getUserOrderCount($userId) {
        $stmt = self::query("SELECT COUNT(*) as count FROM orders WHERE user_id = ?", [$userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public static function getOrderById($orderId, $userId = null) {
        $query = "SELECT o.*, u.first_name, u.last_name, u.email, u.phone
                  FROM orders o
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE o.id = ?";
        
        $params = [$orderId];
        
        if ($userId) {
            $query .= " AND o.user_id = ?";
            $params[] = $userId;
        }
        
        $stmt = self::query($query, $params);
        return $stmt->fetch();
    }
    
    public static function getOrderItems($orderId) {
        $stmt = self::query(
            "SELECT oi.*, p.image
             FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = ?
             ORDER BY oi.id", 
            [$orderId]
        );
        
        return $stmt->fetchAll();
    }
    
    public static function createOrder($data) {
        $orderNumber = 'ORD' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => $data['user_id'],
            'status' => 'pending',
            'subtotal' => $data['subtotal'],
            'tax_amount' => $data['tax_amount'] ?? 0,
            'total_amount' => $data['total_amount'],
            'payment_method' => $data['payment_method'] ?? 'cash',
            'payment_status' => 'pending',
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'],
            'notes' => $data['notes'] ?? null
        ];
        
        $orderId = parent::create($orderData);
        
        if ($orderId && isset($data['items'])) {
            foreach ($data['items'] as $item) {
                self::query(
                    "INSERT INTO order_items (order_id, product_id, variant_id, quantity, unit_price, total_price, product_name, variant_name)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $orderId,
                        $item['product_id'],
                        $item['variant_id'] ?? null,
                        $item['quantity'],
                        $item['unit_price'],
                        $item['total_price'],
                        $item['product_name'],
                        $item['variant_name'] ?? null
                    ]
                );
            }
        }
        
        return $orderId;
    }
    
    public static function updateStatus($orderId, $status, $changedBy = null, $notes = null) {
        $updateData = ['status' => $status];
        
        $statusFields = [
            'confirmed' => 'preparation_started_at',
            'ready' => 'ready_at',
            'completed' => 'completed_at'
        ];
        
        if (isset($statusFields[$status])) {
            $updateData[$statusFields[$status]] = date('Y-m-d H:i:s');
        }
        
        $result = self::update($orderId, $updateData);
        
        if ($result) {
            self::query(
                "INSERT INTO order_status_history (order_id, status, changed_by, notes)
                 VALUES (?, ?, ?, ?)",
                [$orderId, $status, $changedBy, $notes]
            );
        }
        
        return $result;
    }
    
    public static function cancelOrder($orderId, $userId = null) {
        $order = self::getOrderById($orderId, $userId);
        
        if (!$order) {
            return false;
        }
        
        if (!in_array($order['status'], ['pending', 'confirmed'])) {
            return false;
        }
        
        return self::updateStatus($orderId, 'cancelled', $userId, 'Order cancelled by customer');
    }
    
    public static function getOrderStatusHistory($orderId) {
        $stmt = self::query(
            "SELECT osh.*, u.first_name, u.last_name
             FROM order_status_history osh
             LEFT JOIN users u ON osh.changed_by = u.id
             WHERE osh.order_id = ?
             ORDER BY osh.created_at DESC", 
            [$orderId]
        );
        
        return $stmt->fetchAll();
    }
    
    public static function getStatusBadgeClass($status) {
        $classes = [
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'preparing' => 'bg-primary',
            'ready' => 'bg-success',
            'completed' => 'bg-secondary',
            'cancelled' => 'bg-danger'
        ];
        
        return $classes[$status] ?? 'bg-secondary';
    }
    
    public static function getStatusText($status) {
        $texts = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'ready' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
        
        return $texts[$status] ?? 'Unknown';
    }
}
