<?php

/**
 * ChatMessage — live chat threads.
 * Order threads (order_id set): customer + assigned rider + staff.
 * Support threads (order_id NULL): customer <-> staff pool.
 */
class ChatMessage extends Model {
    protected $table = 'chat_messages';
    protected $primaryKey = 'id';

    const MAX_LEN = 1000;

    public static function send($senderId, $message, $orderId = null, $recipientId = null) {
        $message = trim(mb_substr((string)$message, 0, self::MAX_LEN));
        if ($message === '') {
            return false;
        }
        return parent::create([
            'order_id' => $orderId,
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
            'message' => $message,
        ]);
    }

    /** Messages of one order thread (optionally only newer than $afterId). */
    public static function forOrder($orderId, $afterId = 0, $limit = 100) {
        $limit = max(1, min(200, (int)$limit));
        $sql = "SELECT m.*, CONCAT(u.first_name, ' ', u.last_name) AS sender_name, u.role AS sender_role
                FROM chat_messages m
                JOIN users u ON u.id = m.sender_id
                WHERE m.order_id = ? AND m.id > ?
                ORDER BY m.id ASC LIMIT $limit";
        return self::query($sql, [$orderId, (int)$afterId])->fetchAll();
    }

    /** Support thread of one customer (their messages + staff replies to them). */
    public static function supportThread($customerId, $afterId = 0, $limit = 100) {
        $limit = max(1, min(200, (int)$limit));
        $sql = "SELECT m.*, CONCAT(u.first_name, ' ', u.last_name) AS sender_name, u.role AS sender_role
                FROM chat_messages m
                JOIN users u ON u.id = m.sender_id
                WHERE m.order_id IS NULL
                  AND (m.sender_id = ? OR m.recipient_id = ?)
                  AND m.id > ?
                ORDER BY m.id ASC LIMIT $limit";
        return self::query($sql, [$customerId, $customerId, (int)$afterId])->fetchAll();
    }

    /** Support inbox for staff: one row per customer with preview + counts. */
    public static function supportThreads() {
        try {
            return self::query(
                "SELECT u.id AS customer_id,
                        CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
                        u.email AS customer_email,
                        COUNT(*) AS total,
                        SUM(m.sender_id = u.id) AS from_customer,
                        MAX(m.created_at) AS last_at,
                        MAX(m.id) AS last_id
                 FROM users u
                 JOIN chat_messages m ON m.order_id IS NULL
                     AND (m.sender_id = u.id OR m.recipient_id = u.id)
                 WHERE u.role NOT IN ('admin', 'staff')
                 GROUP BY u.id
                 ORDER BY last_at DESC
                 LIMIT 50"
            )->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Can this user read/post in the given order thread?
     * Customer owner, assigned rider, or admin/staff.
     */
    public static function canAccessOrder($user, $orderId) {
        if (empty($user) || empty($orderId)) {
            return false;
        }
        $role = $user['role'] ?? '';
        if (in_array($role, ['admin', 'staff'], true)) {
            return true;
        }
        try {
            $order = self::query("SELECT user_id FROM orders WHERE id = ?", [$orderId])->fetch();
            if (!$order) {
                return false;
            }
            if ((int)($order['user_id'] ?? 0) === (int)$user['id']) {
                return true;
            }
            if ($role === 'rider') {
                $del = self::query(
                    "SELECT id FROM deliveries WHERE order_id = ? AND rider_id = ? LIMIT 1",
                    [$orderId, $user['id']]
                )->fetch();
                return (bool)$del;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
