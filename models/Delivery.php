<?php

/**
 * Delivery — rider dispatch rows (one active row per order).
 * Flow: assigned → accepted → picked_up → delivering → delivered
 *       (failed / cancelled are terminal side-states set by rider/admin).
 */
class Delivery extends Model {
    protected $table = 'deliveries';
    protected $primaryKey = 'id';

    const ACTIVE = ['assigned', 'accepted', 'picked_up', 'delivering'];
    const TERMINAL = ['delivered', 'failed', 'cancelled'];

    /** Valid next statuses per current status (rider-driven). */
    public static function allowedTransitions($current) {
        $map = [
            'assigned' => ['accepted', 'cancelled'],
            'accepted' => ['picked_up', 'cancelled'],
            'picked_up' => ['delivering', 'failed'],
            'delivering' => ['delivered', 'failed'],
            'delivered' => [],
            'failed' => [],
            'cancelled' => [],
        ];
        return $map[$current] ?? [];
    }

    public static function timestampColumn($status) {
        $map = [
            'accepted' => 'accepted_at',
            'picked_up' => 'picked_up_at',
            'delivering' => 'delivering_at',
            'delivered' => 'delivered_at',
        ];
        return $map[$status] ?? null;
    }

    public static function findByOrder($orderId) {
        $stmt = self::query("SELECT * FROM deliveries WHERE order_id = ? LIMIT 1", [$orderId]);
        return $stmt->fetch();
    }

    public static function forRider($riderId, $activeOnly = true) {
        $sql = "SELECT d.*, o.order_number, o.status AS order_status,
                       o.total_amount, o.customer_name, o.customer_phone, o.customer_address
                FROM deliveries d
                JOIN orders o ON o.id = d.order_id
                WHERE d.rider_id = ?";
        $params = [$riderId];
        if ($activeOnly) {
            $sql .= " AND d.status IN ('assigned','accepted','picked_up','delivering')";
        }
        $sql .= " ORDER BY d.updated_at DESC";
        return self::query($sql, $params)->fetchAll();
    }

    public static function detail($deliveryId, $riderId = null) {
        $sql = "SELECT d.*, o.order_number, o.status AS order_status, o.total_amount,
                       o.customer_name, o.customer_phone, o.customer_email, o.customer_address,
                       o.payment_method, o.payment_status,
                       CONCAT(u.first_name, ' ', u.last_name) AS rider_name
                FROM deliveries d
                JOIN orders o ON o.id = d.order_id
                LEFT JOIN users u ON u.id = d.rider_id
                WHERE d.id = ?";
        $params = [$deliveryId];
        if ($riderId !== null) {
            $sql .= " AND d.rider_id = ?";
            $params[] = $riderId;
        }
        return self::query($sql, $params)->fetch();
    }

    public static function riderStats($riderId) {
        $row = self::query(
            "SELECT COUNT(*) AS total,
                    SUM(status = 'delivered') AS delivered,
                    SUM(status IN ('assigned','accepted','picked_up','delivering')) AS active
             FROM deliveries WHERE rider_id = ?",
            [$riderId]
        )->fetch();
        return [
            'total' => (int)($row['total'] ?? 0),
            'delivered' => (int)($row['delivered'] ?? 0),
            'active' => (int)($row['active'] ?? 0),
        ];
    }

    /** All users with the rider role (for the admin assign dropdown). */
    public static function allRiders() {
        try {
            return self::query(
                "SELECT id, first_name, last_name, email, phone, availability_status FROM users WHERE role = 'rider' ORDER BY first_name, last_name"
            )->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /** Check if a rider has an active delivery (most recent one). */
    public static function hasActiveDelivery($riderId) {
        try {
            $delivery = self::query(
                "SELECT id FROM deliveries WHERE rider_id = ? AND status IN ('assigned','accepted','picked_up','delivering') ORDER BY updated_at DESC LIMIT 1",
                [$riderId]
            )->fetch();
            return $delivery !== false;
        } catch (Exception $e) {
            return false;
        }
    }
}
