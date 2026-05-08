<?php

class Product extends Model {
    protected $table = 'products';
    protected $primaryKey = 'id';
    
    public static function getAllAvailable($page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        
        $stmt = self::query(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.is_available = 1 
             ORDER BY p.sort_order ASC, p.created_at DESC 
             LIMIT ? OFFSET ?", 
            [$limit, $offset]
        );
        
        return $stmt->fetchAll();
    }
    
    public static function getByCategory($category, $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        
        $stmt = self::query(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.is_available = 1 AND c.name = ? 
             ORDER BY p.sort_order ASC, p.created_at DESC 
             LIMIT ? OFFSET ?", 
            [$category, $limit, $offset]
        );
        
        return $stmt->fetchAll();
    }
    
    public static function getWithVariants($id) {
        $stmt = self::query(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.id = ? AND p.is_available = 1", 
            [$id]
        );
        
        $product = $stmt->fetch();
        
        if ($product) {
            // Get variants
            $variantStmt = self::query(
                "SELECT * FROM product_variants WHERE product_id = ? AND is_available = 1 ORDER BY sort_order ASC",
                [$product['id']]
            );
            $product['variants'] = $variantStmt->fetchAll();
        }
        
        return $product;
    }
    
    public static function search($query, $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        $searchTerm = "%{$query}%";
        
        $stmt = self::query(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.is_available = 1 AND (p.name LIKE ? OR p.description LIKE ?) 
             ORDER BY p.name ASC 
             LIMIT ? OFFSET ?", 
            [$searchTerm, $searchTerm, $limit, $offset]
        );
        
        return $stmt->fetchAll();
    }
    
    public static function getFeatured($limit = 6) {
        $stmt = self::query(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.is_available = 1 
             ORDER BY p.sort_order ASC, p.created_at DESC 
             LIMIT ?", 
            [$limit]
        );
        
        return $stmt->fetchAll();
    }
    
    public static function getCategories() {
        $stmt = self::query(
            "SELECT c.*, COUNT(p.id) as product_count 
             FROM categories c 
             LEFT JOIN products p ON c.id = p.category_id AND p.is_available = 1 
             WHERE c.is_active = 1 
             GROUP BY c.id 
             ORDER BY c.sort_order ASC"
        );
        
        return $stmt->fetchAll();
    }
    
    public static function getTotalCount($category = null) {
        if ($category) {
            $stmt = self::query(
                "SELECT COUNT(*) as count 
                 FROM products p 
                 JOIN categories c ON p.category_id = c.id 
                 WHERE p.is_available = 1 AND c.name = ?", 
                [$category]
            );
        } else {
            $stmt = self::query(
                "SELECT COUNT(*) as count FROM products WHERE is_available = 1"
            );
        }
        
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public static function create($data) {
        $stmt = self::query(
            "INSERT INTO products (category_id, name, description, price, image, is_available) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['category_id'],
                $data['name'],
                $data['description'] ?? null,
                $data['price'],
                $data['image'] ?? null,
                $data['is_available'] ?? 1
            ]
        );
        
        return self::$connection->lastInsertId();
    }
    
    public static function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach (['category_id', 'name', 'description', 'price', 'image', 'is_available', 'sort_order', 'preparation_time'] as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        
        if (!empty($fields)) {
            $values[] = $id;
            $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";
            self::query($sql, $values);
        }
        
        return true;
    }
    
    public static function getAdminProducts($page = 1, $category = null, $availability = null, $search = null, $sort = null, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        $params = [];
        
        if ($category) {
            $sql .= " AND c.name = ?";
            $params[] = $category;
        }
        
        if ($availability === 'available') {
            $sql .= " AND p.is_available = 1";
        } elseif ($availability === 'unavailable') {
            $sql .= " AND p.is_available = 0";
        }
        
        if ($search) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Sort
        switch ($sort) {
            case 'price_low':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'created':
                $sql .= " ORDER BY p.created_at DESC";
                break;
            case 'name':
            default:
                $sql .= " ORDER BY p.name ASC";
                break;
        }
        
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public static function getAdminTotalCount($category = null, $availability = null, $search = null) {
        $sql = "SELECT COUNT(*) as count 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        $params = [];
        
        if ($category) {
            $sql .= " AND c.name = ?";
            $params[] = $category;
        }
        
        if ($availability === 'available') {
            $sql .= " AND p.is_available = 1";
        } elseif ($availability === 'unavailable') {
            $sql .= " AND p.is_available = 0";
        }
        
        if ($search) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public static function getBySubcategory($category, $subcategory, $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        
        $stmt = self::query(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.is_available = 1 AND c.name = ? AND p.name LIKE ? 
             ORDER BY p.sort_order ASC, p.created_at DESC 
             LIMIT ? OFFSET ?", 
            [$category, "%{$subcategory}%", $limit, $offset]
        );
        
        return $stmt->fetchAll();
    }
    
    public static function getSubcategoryCount($category, $subcategory) {
        $stmt = self::query(
            "SELECT COUNT(*) as count 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.is_available = 1 AND c.name = ? AND p.name LIKE ?",
            [$category, "%{$subcategory}%"]
        );
        
        $result = $stmt->fetch();
        return $result['count'];
    }
}
