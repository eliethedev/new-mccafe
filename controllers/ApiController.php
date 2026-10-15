<?php

class ApiController extends Controller {
    public function products(Request $request) {
        $products = [
            ['id' => 1, 'name' => 'Espresso', 'price' => 120, 'category' => 'coffee'],
            ['id' => 2, 'name' => 'Cappuccino', 'price' => 150, 'category' => 'coffee'],
            ['id' => 3, 'name' => 'Croissant', 'price' => 80, 'category' => 'food'],
        ];
        
        $response = new Response(json_encode($products), 200);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }
    
    public function product(Request $request, $id) {
        $product = [
            'id' => $id,
            'name' => 'Espresso',
            'price' => 120,
            'category' => 'coffee',
            'description' => 'Rich and bold espresso coffee'
        ];
        
        $response = new Response(json_encode($product), 200);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }
    
    public function addToCart(Request $request) {
        // Get current user from session
        $user = Session::get('user');
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            $response = new Response(json_encode(['success' => false, 'message' => 'Please login']), 401);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        $productId = $request->getBody('product_id');
        $quantity = $request->getBody('quantity', 1);
        
        // Get product details for unit price
        $productModel = new Product();
        $product = $productModel->find($productId);
        
        if (!$product) {
            $response = new Response(json_encode(['success' => false, 'message' => 'Product not found']), 404);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        // Add to cart database
        $cartModel = new Cart();
        $result = $cartModel->addToCart($userId, $productId, $quantity, null, $product['price']);
        
        $response = new Response(json_encode(['success' => true]), 200);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }
    
    public function getCart(Request $request) {        // Get current user from session
        $user = Session::get('user');
        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            $response = new Response(json_encode(['items' => []]), 200);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }
        
        // Get cart from database
        $cartModel = new Cart();
        $cartItems = $cartModel->getUserCart($userId);
        
        // Format cart items
        $items = [];
        $subtotal = 0;
        
        foreach ($cartItems as $item) {
            $items[] = [
                'id' => $item['id'],
                'product_id' => $item['product_id'],
                'name' => $item['product_name'],
                'image' => $item['product_image'],
                'quantity' => $item['quantity'],
                'price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price']
            ];
            $subtotal += $item['quantity'] * $item['unit_price'];
        }
        
        $cartData = [
            'items' => $items,
            'subtotal' => $subtotal,
            'delivery_fee' => 0, // You can calculate this based on your logic
            'total' => $subtotal
        ];
        
        $response = new Response(json_encode($cartData), 200);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }

    /** Real-time bell feed: unread count + latest notifications for the session user. */
    public function notifications(Request $request) {
        $user = Session::get('user');
        $userId = $user['id'] ?? null;

        if (!$userId) {
            $response = new Response(json_encode(['success' => false, 'message' => 'Please login']), 401);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }

        try {
            $items = Notification::forUser($userId, 10);
            $unread = Notification::unreadCount($userId);
        } catch (Throwable $e) {
            $items = [];
            $unread = 0;
        }

        $response = new Response(json_encode([
            'success' => true,
            'unread_count' => $unread,
            'notifications' => array_map(function ($n) {
                return [
                    'id' => (int)$n['id'],
                    'type' => $n['type'],
                    'title' => $n['title'],
                    'message' => $n['message'],
                    'link' => $n['link'],
                    'is_read' => (int)$n['is_read'],
                    'created_at' => $n['created_at'],
                ];
            }, $items),
        ]), 200);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }

    /** Mark notifications read (specific ids or all). */
    public function markNotificationsRead(Request $request) {
        $user = Session::get('user');
        $userId = $user['id'] ?? null;

        if (!$userId) {
            $response = new Response(json_encode(['success' => false, 'message' => 'Please login']), 401);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }

        $ids = $request->getBody('ids');
        try {
            Notification::markRead($userId, $ids);
            $unread = Notification::unreadCount($userId);
        } catch (Throwable $e) {
            $response = new Response(json_encode(['success' => false, 'message' => 'Failed to update']), 500);
            $response->setHeader('Content-Type', 'application/json');
            return $response;
        }

        $response = new Response(json_encode(['success' => true, 'unread_count' => $unread]), 200);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }
}
