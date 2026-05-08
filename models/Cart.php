<?php

class Cart extends Model {
    protected $table = 'cart';
    
    public function addToCart($userId, $productId, $quantity = 1, $variantId = null, $unitPrice = null) {
        // Check if item already exists in cart
        $existingItem = $this->getExistingItem($userId, $productId, $variantId);
        
        if ($existingItem) {
            // Update quantity of existing item
            $newQuantity = $existingItem['quantity'] + $quantity;
            return $this->updateQuantity($existingItem['id'], $newQuantity);
        } else {
            // Add new item to cart
            $data = [
                'user_id' => $userId,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            return $this->create($data);
        }
    }
    
    public function getExistingItem($userId, $productId, $variantId = null) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? AND product_id = ?";
        $params = [$userId, $productId];
        
        if ($variantId) {
            $sql .= " AND variant_id = ?";
            $params[] = $variantId;
        } else {
            $sql .= " AND (variant_id IS NULL OR variant_id = '')";
        }
        
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }
    
    public function updateQuantity($cartId, $quantity) {
        $sql = "UPDATE {$this->table} SET quantity = ?, updated_at = ? WHERE id = ?";
        return $this->query($sql, [$quantity, date('Y-m-d H:i:s'), $cartId]);
    }
    
    public function getUserCart($userId) {
        $sql = "SELECT c.*, p.name as product_name, p.image as product_image 
                 FROM {$this->table} c 
                 LEFT JOIN products p ON c.product_id = p.id 
                 WHERE c.user_id = ? 
                 ORDER BY c.created_at DESC";
        
        $stmt = $this->query($sql, [$userId]);
        return $stmt->fetchAll();
    }
    
    public function removeFromCart($cartId, $userId) {
        $sql = "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?";
        return $this->query($sql, [$cartId, $userId]);
    }
    
    public function clearUserCart($userId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
        return $this->query($sql, [$userId]);
    }
    
    public function getCartTotal($userId) {
        $sql = "SELECT SUM(quantity * unit_price) as total FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->query($sql, [$userId]);
        $result = $stmt->fetch();
        return $result ? $result['total'] : 0;
    }
    
    public function getCartItemCount($userId) {
        $sql = "SELECT SUM(quantity) as count FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->query($sql, [$userId]);
        $result = $stmt->fetch();
        return $result ? $result['count'] : 0;
    }
    
    public function getProductVariants($productId) {
        $sql = "SELECT * FROM product_variants WHERE product_id = ? ORDER BY sort_order, name";
        $stmt = $this->query($sql, [$productId]);
        return $stmt->fetchAll();
    }
    
    public static function getItems($userId) {
        $sql = "SELECT c.*, p.name as product_name, p.image as product_image,
                       pv.name as variant_name, (c.quantity * c.unit_price) as total_price
                 FROM cart c 
                 LEFT JOIN products p ON c.product_id = p.id 
                 LEFT JOIN product_variants pv ON c.variant_id = pv.id
                 WHERE c.user_id = ? 
                 ORDER BY c.created_at DESC";
        
        $stmt = self::query($sql, [$userId]);
        return $stmt->fetchAll();
    }
    
    public static function clear($userId) {
        $sql = "DELETE FROM cart WHERE user_id = ?";
        return self::query($sql, [$userId]);
    }
}
