<?php

/**
 * Substitution — out-of-stock replacement suggestions by riders/admins.
 * Flow: suggested → approved/rejected (customer or admin decides) → applied
 * (rider/admin swaps the order item and adjusts totals).
 */
class Substitution extends Model {
    protected $table = 'substitutions';
    protected $primaryKey = 'id';

    public static function forOrder($orderId) {
        return self::query(
            "SELECT s.*,
                    CONCAT(su.first_name, ' ', su.last_name) AS suggested_by_name,
                    CONCAT(du.first_name, ' ', du.last_name) AS decided_by_name
             FROM substitutions s
             LEFT JOIN users su ON su.id = s.suggested_by
             LEFT JOIN users du ON du.id = s.decided_by
             WHERE s.order_id = ?
             ORDER BY s.created_at DESC, s.id DESC",
            [$orderId]
        )->fetchAll();
    }

    public static function pendingForRiderOrders($riderId) {
        return self::query(
            "SELECT s.*, o.order_number
             FROM substitutions s
             JOIN deliveries d ON d.order_id = s.order_id
             JOIN orders o ON o.id = s.order_id
             WHERE d.rider_id = ? AND s.status = 'suggested'
             ORDER BY s.created_at DESC",
            [$riderId]
        )->fetchAll();
    }

    /** Available replacement candidates (same category first). */
    public static function candidates($excludeProductId = null, $limit = 12) {
        $limit = max(1, min(50, (int)$limit));
        $categoryId = null;
        if ($excludeProductId) {
            try {
                $row = self::query("SELECT category_id FROM products WHERE id = ?", [$excludeProductId])->fetch();
                $categoryId = $row['category_id'] ?? null;
            } catch (Exception $e) {
                $categoryId = null;
            }
        }
        $sql = "SELECT p.id, p.name, p.price, p.category_id, c.name AS category_name
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id
                WHERE p.is_available = 1";
        $params = [];
        if ($excludeProductId) {
            $sql .= " AND p.id <> ?";
            $params[] = $excludeProductId;
        }
        if ($categoryId) {
            $sql .= " ORDER BY (p.category_id = ?) DESC, p.name ASC";
            $params[] = $categoryId;
        } else {
            $sql .= " ORDER BY p.name ASC";
        }
        $sql .= " LIMIT $limit";
        try {
            return self::query($sql, $params)->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /** Unavailable order items that still need a substitution decision. */
    public static function needsSubstitution($orderId) {
        return self::query(
            "SELECT oi.*, p.is_available
             FROM order_items oi
             LEFT JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = ?
               AND (p.id IS NULL OR p.is_available = 0)
               AND NOT EXISTS (
                   SELECT 1 FROM substitutions s
                   WHERE s.order_item_id = oi.id AND s.status IN ('suggested','approved','applied')
               )",
            [$orderId]
        )->fetchAll();
    }
}
