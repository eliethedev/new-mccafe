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
            "INSERT INTO products (category_id, name, description, price, image, is_available, is_bestseller, is_featured, sort_order, preparation_time) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['category_id'],
                $data['name'],
                $data['description'] ?? null,
                $data['price'],
                $data['image'] ?? null,
                $data['is_available'] ?? 1,
                $data['is_bestseller'] ?? 0,
                $data['is_featured'] ?? 0,
                $data['sort_order'] ?? 0,
                $data['preparation_time'] ?? 0
            ]
        );
        
        return self::$connection->lastInsertId();
    }
    
    public static function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach (['category_id', 'name', 'description', 'price', 'image', 'is_available', 'is_bestseller', 'is_featured', 'sort_order', 'preparation_time'] as $field) {
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
    
    public static function getAdminProducts($page = 1, $category = null, $availability = null, $search = null, $sort = null, $flag = null, $limit = 20) {
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

        if ($flag === 'bestseller') {
            $sql .= " AND p.is_bestseller = 1";
        } elseif ($flag === 'featured') {
            $sql .= " AND p.is_featured = 1";
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
            case 'bestseller':
                $sql .= " ORDER BY p.is_bestseller DESC, p.name ASC";
                break;
            case 'availability':
                $sql .= " ORDER BY p.is_available DESC, p.name ASC";
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
    
    public static function getAdminTotalCount($category = null, $availability = null, $search = null, $flag = null) {
        $sql = "SELECT COUNT(*) as count 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        $params = [];
        
        if ($category) {
            $sql .= " AND c.name = ?";
            $params[] = $category;
        }

        if ($flag === 'bestseller') {
            $sql .= " AND p.is_bestseller = 1";
        } elseif ($flag === 'featured') {
            $sql .= " AND p.is_featured = 1";
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

    /* ---------- Cafe admin analytics ---------- */

    /** Top-selling products by quantity sold (completed/ready orders only). */
    public static function getBestSellers($limit = 8) {
        $limit = max(1, min(50, (int)$limit));
        // NOTE: every non-aggregated column is in GROUP BY so this stays valid
        // under ONLY_FULL_GROUP_BY; the status filter is inside CASE so
        // cancelled or pending orders never inflate the totals.
        $stmt = self::query(
            "SELECT p.id, p.category_id, p.name, p.description, p.price, p.image,
                    p.is_available, p.is_bestseller, p.is_featured, p.sort_order,
                    p.preparation_time, p.created_at, p.updated_at,
                    MAX(c.name) AS category_name,
                    COALESCE(SUM(CASE WHEN o.status IN ('completed','ready') THEN oi.quantity ELSE 0 END), 0) AS total_sold,
                    COALESCE(SUM(CASE WHEN o.status IN ('completed','ready') THEN oi.total_price ELSE 0 END), 0) AS total_revenue
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN order_items oi ON oi.product_id = p.id
             LEFT JOIN orders o ON o.id = oi.order_id
             GROUP BY p.id, p.category_id, p.name, p.description, p.price, p.image,
                      p.is_available, p.is_bestseller, p.is_featured, p.sort_order,
                      p.preparation_time, p.created_at, p.updated_at
             ORDER BY p.is_bestseller DESC, total_sold DESC, p.name ASC
             LIMIT $limit"
        );
        return $stmt->fetchAll();
    }

    /** Manually flagged bestsellers first, then by sales. */
    public static function getFlaggedBestSellers($limit = 12) {
        $limit = max(1, min(50, (int)$limit));
        $stmt = self::query(
            "SELECT p.id, p.category_id, p.name, p.description, p.price, p.image,
                    p.is_available, p.is_bestseller, p.is_featured, p.sort_order,
                    p.preparation_time, p.created_at, p.updated_at,
                    MAX(c.name) AS category_name,
                    COALESCE(SUM(CASE WHEN o.status IN ('completed','ready') THEN oi.quantity ELSE 0 END), 0) AS total_sold
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN order_items oi ON oi.product_id = p.id
             LEFT JOIN orders o ON o.id = oi.order_id
             WHERE p.is_bestseller = 1
             GROUP BY p.id, p.category_id, p.name, p.description, p.price, p.image,
                      p.is_available, p.is_bestseller, p.is_featured, p.sort_order,
                      p.preparation_time, p.created_at, p.updated_at
             ORDER BY total_sold DESC, p.sort_order ASC
             LIMIT $limit"
        );
        return $stmt->fetchAll();
    }

    /** Featured items for the storefront. */
    public static function getFeaturedItems($limit = 8) {
        $limit = max(1, min(50, (int)$limit));
        $stmt = self::query(
            "SELECT p.*, c.name AS category_name FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_available = 1 AND p.is_featured = 1
             ORDER BY p.sort_order ASC LIMIT $limit"
        );
        return $stmt->fetchAll();
    }

    public static function getMenuStats() {
        $row = self::query(
            "SELECT COUNT(*) AS total,
                    SUM(CASE WHEN is_available = 1 THEN 1 ELSE 0 END) AS available,
                    SUM(CASE WHEN is_available = 0 THEN 1 ELSE 0 END) AS unavailable,
                    SUM(CASE WHEN is_bestseller = 1 THEN 1 ELSE 0 END) AS bestsellers,
                    SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) AS featured
             FROM products"
        )->fetch();
        return [
            'total' => (int)($row['total'] ?? 0),
            'available' => (int)($row['available'] ?? 0),
            'unavailable' => (int)($row['unavailable'] ?? 0),
            'bestsellers' => (int)($row['bestsellers'] ?? 0),
            'featured' => (int)($row['featured'] ?? 0),
        ];
    }

    /** Daily revenue + order counts for the last N days (for charts). */
    public static function getSalesTrend($days = 14) {
        $days = max(1, min(90, (int)$days));
        $stmt = self::query(
            "SELECT DATE(created_at) AS day, COUNT(*) AS orders,
                    COALESCE(SUM(total_amount),0) AS revenue
             FROM orders
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
               AND status != 'cancelled'
             GROUP BY DATE(created_at)
             ORDER BY day ASC"
        );
        $map = [];
        foreach ($stmt->fetchAll() as $r) { $map[$r['day']] = $r; }
        $out = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $out[] = [
                'day' => $d,
                'label' => date('M j', strtotime($d)),
                'orders' => (int)($map[$d]['orders'] ?? 0),
                'revenue' => (float)($map[$d]['revenue'] ?? 0),
            ];
        }
        return $out;
    }

    public static function getOrdersByStatus() {
        $stmt = self::query(
            "SELECT status, COUNT(*) AS n FROM orders GROUP BY status"
        );
        $out = [];
        foreach ($stmt->fetchAll() as $r) { $out[$r['status']] = (int)$r['n']; }
        return $out;
    }

    public static function getRevenueByCategory($limit = 8) {
        $limit = max(1, min(50, (int)$limit));
        $stmt = self::query(
            "SELECT c.name AS category, COALESCE(SUM(oi.total_price),0) AS revenue,
                    COALESCE(SUM(oi.quantity),0) AS qty
             FROM order_items oi
             JOIN orders o ON o.id = oi.order_id AND o.status IN ('completed','ready')
             JOIN products p ON p.id = oi.product_id
             JOIN categories c ON c.id = p.category_id
             GROUP BY c.id, c.name
             ORDER BY revenue DESC
             LIMIT $limit"
        );
        return $stmt->fetchAll();
    }

    public static function getLowAvailability($limit = 8) {
        $limit = max(1, min(50, (int)$limit));
        $stmt = self::query(
            "SELECT p.*, c.name AS category_name FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.is_available = 0
             ORDER BY p.updated_at DESC LIMIT $limit"
        );
        return $stmt->fetchAll();
    }
}
