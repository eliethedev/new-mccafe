<?php

/**
 * Notification — in-app delivery notifications.
 * Rows are addressed to individual users: the customer who owns the order
 * plus every admin/staff user. Polled in real time by the bell widgets.
 */
class Notification extends Model {
    protected $table = 'notifications';
    protected $primaryKey = 'id';

    public static function createFor($userId, $type, $title, $message = '', $orderId = null, $link = null) {
        return parent::create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => 0,
        ]);
    }

    /** Latest notifications for a user (newest first). */
    public static function forUser($userId, $limit = 10, $unreadOnly = false) {
        $limit = max(1, min(50, (int)$limit));
        $sql = "SELECT * FROM notifications WHERE user_id = ?";
        $params = [$userId];
        if ($unreadOnly) {
            $sql .= " AND is_read = 0";
        }
        $sql .= " ORDER BY created_at DESC, id DESC LIMIT $limit";
        return self::query($sql, $params)->fetchAll();
    }

    public static function unreadCount($userId) {
        $stmt = self::query(
            "SELECT COUNT(*) AS c FROM notifications WHERE user_id = ? AND is_read = 0",
            [$userId]
        );
        $row = $stmt->fetch();
        return (int)($row['c'] ?? 0);
    }

    public static function markRead($userId, $ids = null) {
        if ($ids === null) {
            self::query("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0", [$userId]);
            return true;
        }
        $ids = array_values(array_filter(array_map('intval', (array)$ids)));
        if (empty($ids)) {
            return true;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        self::query(
            "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND id IN ($placeholders)",
            array_merge([$userId], $ids)
        );
        return true;
    }

    /** All staff recipients for admin-side delivery notices. */
    public static function staffRecipients() {
        try {
            return self::query(
                "SELECT id FROM users WHERE role IN ('admin', 'staff')"
            )->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Notify the customer that their order is ready for pickup/delivery.
     * Returns number of notification rows created.
     */
    public static function notifyOrderReady($order) {
        $created = 0;
        try {
            if (empty($order['user_id'])) {
                return 0;
            }
            $title = 'Order ' . ($order['order_number'] ?? ('#' . $order['id'])) . ' is ready!';
            $message = 'Your order (₱' . number_format($order['total_amount'] ?? 0, 2) . ') is ready for pickup/delivery. Thank you for choosing CafeCat!';
            self::createFor(
                $order['user_id'],
                'order_ready',
                $title,
                $message,
                $order['id'] ?? null,
                isset($order['id']) ? ('/orders/' . $order['id']) : null
            );
            $created++;
        } catch (Exception $e) {
            error_log('notifyOrderReady failed: ' . $e->getMessage());
        }
        return $created;
    }

    /**
     * Notify the customer AND all admin/staff that an order was delivered
     * (status completed). Returns number of notification rows created.
     */    public static function notifyOrderDelivered($order) {
        $created = 0;
        $orderNo = $order['order_number'] ?? ('#' . ($order['id'] ?? ''));
        try {
            // Customer receipt.
            if (!empty($order['user_id'])) {
                self::createFor(
                    $order['user_id'],
                    'order_delivered',
                    "Order $orderNo delivered — enjoy!",
                    'Your order (₱' . number_format($order['total_amount'] ?? 0, 2) . ') was marked as delivered. We hope you enjoyed it — order again anytime!',
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/orders/' . $order['id']) : null
                );
                $created++;
            }
            // Admin/staff notice (every staff member gets their own row).
            foreach (self::staffRecipients() as $staffId) {
                if (!empty($order['user_id']) && (int)$staffId === (int)$order['user_id']) {
                    continue; // Don't double-notify if staff placed the order.
                }
                self::createFor(
                    $staffId,
                    'order_delivered',
                    "Order $orderNo delivered",
                    'Order ' . $orderNo . ' (' . ($order['customer_name'] ?? 'customer') . ', ₱' . number_format($order['total_amount'] ?? 0, 2) . ') was completed/delivered.',
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/admin/orders/' . $order['id']) : null
                );
                $created++;
            }
        } catch (Exception $e) {
            error_log('notifyOrderDelivered failed: ' . $e->getMessage());
        }
        return $created;
    }

    /**
     * Notify the customer AND all admin/staff that a refund was processed
     * for an order. $refund needs: refund_amount, refund_method.
     * Returns number of notification rows created.
     */
    public static function notifyRefundProcessed($order, $refund) {
        $created = 0;
        $orderNo = $order['order_number'] ?? ('#' . ($order['id'] ?? ''));
        $amount = number_format($refund['refund_amount'] ?? 0, 2);
        $method = ucfirst($refund['refund_method'] ?? 'original channel');
        try {
            if (!empty($order['user_id'])) {
                self::createFor(
                    $order['user_id'],
                    'refund_processed',
                    "Refund of ₱$amount processed for order $orderNo",
                    "A refund of ₱$amount via $method has been processed for order $orderNo. Please allow time for it to reflect in your account.",
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/orders/' . $order['id']) : null
                );
                $created++;
            }
            foreach (self::staffRecipients() as $staffId) {
                if (!empty($order['user_id']) && (int)$staffId === (int)$order['user_id']) {
                    continue;
                }
                self::createFor(
                    $staffId,
                    'refund_processed',
                    "Refund of ₱$amount processed ($orderNo)",
                    "A refund of ₱$amount via $method was processed for order $orderNo (" . ($order['customer_name'] ?? 'customer') . ").",
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/admin/orders/' . $order['id']) : null
                );
                $created++;
            }
        } catch (Exception $e) {
            error_log('notifyRefundProcessed failed: ' . $e->getMessage());
        }
        return $created;
    }

    /**
     * Notify the customer AND all admin/staff of a refund status change
     * (e.g. pending → processed, processed → failed).
     * Returns number of notification rows created.
     */
    public static function notifyRefundStatusChanged($order, $refund, $oldStatus, $newStatus) {
        $created = 0;
        $orderNo = $order['order_number'] ?? ('#' . ($order['id'] ?? ''));
        $amount = number_format($refund['refund_amount'] ?? 0, 2);
        $statusText = [
            'pending' => 'is now pending',
            'processed' => 'has been processed',
            'failed' => 'has failed',
        ];
        $verb = $statusText[$newStatus] ?? ("is now $newStatus");
        try {
            if (!empty($order['user_id'])) {
                self::createFor(
                    $order['user_id'],
                    'refund_updated',
                    "Refund update for order $orderNo",
                    "Your refund of ₱$amount for order $orderNo $verb. Please contact staff if you have questions.",
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/orders/' . $order['id']) : null
                );
                $created++;
            }
            foreach (self::staffRecipients() as $staffId) {
                if (!empty($order['user_id']) && (int)$staffId === (int)$order['user_id']) {
                    continue;
                }
                self::createFor(
                    $staffId,
                    'refund_updated',
                    "Refund $oldStatus → $newStatus ($orderNo)",
                    "Refund of ₱$amount for order $orderNo changed from $oldStatus to $newStatus.",
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/admin/orders/' . $order['id']) : null
                );
                $created++;
            }
        } catch (Exception $e) {
            error_log('notifyRefundStatusChanged failed: ' . $e->getMessage());
        }
        return $created;
    }

    /**
     * New rider assignment: notify the rider AND the customer.
     * Returns number of notification rows created.
     */
    public static function notifyDeliveryAssigned($order, $riderId, $riderName = '') {
        $created = 0;
        $orderNo = $order['order_number'] ?? ('#' . ($order['id'] ?? ''));
        $addr = $order['customer_address'] ?? 'see order details';
        try {
            self::createFor(
                $riderId,
                'delivery_assigned',
                "New delivery assigned: order $orderNo",
                'Deliver to ' . ($order['customer_name'] ?? 'customer') . ' at ' . $addr . ' (₱' . number_format($order['total_amount'] ?? 0, 2) . '). Please accept the assignment.',
                $order['id'] ?? null,
                isset($order['id']) ? ('/rider/deliveries/' . $order['id']) : '/rider'
            );
            $created++;
            if (!empty($order['user_id'])) {
                self::createFor(
                    $order['user_id'],
                    'delivery_assigned',
                    "Rider assigned to order $orderNo",
                    trim($riderName) !== '' ? ($riderName . ' will deliver your order. Track it under My Orders.') : 'A rider has been assigned to your order. Track it under My Orders.',
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/orders/' . $order['id']) : null
                );
                $created++;
            }
        } catch (Exception $e) {
            error_log('notifyDeliveryAssigned failed: ' . $e->getMessage());
        }
        return $created;
    }

    /**
     * Rider status change (accepted/picked_up/delivering/delivered/failed):
     * notify the customer AND all admin/staff.
     */
    public static function notifyDeliveryUpdated($order, $newStatus, $riderName = '') {
        $created = 0;
        $orderNo = $order['order_number'] ?? ('#' . ($order['id'] ?? ''));
        $labels = [
            'accepted' => 'accepted the delivery',
            'picked_up' => 'picked up your order',
            'delivering' => 'is on the way with your order',
            'delivered' => 'delivered your order',
            'failed' => 'reported a delivery problem',
            'cancelled' => 'assignment was cancelled',
        ];
        $verb = $labels[$newStatus] ?? ("updated delivery to $newStatus");
        $who = trim($riderName) !== '' ? $riderName : 'Your rider';
        try {
            if (!empty($order['user_id'])) {
                self::createFor(
                    $order['user_id'],
                    'delivery_updated',
                    "Delivery update for order $orderNo",
                    "$who $verb.",
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/orders/' . $order['id']) : null
                );
                $created++;
            }
            foreach (self::staffRecipients() as $staffId) {
                if (!empty($order['user_id']) && (int)$staffId === (int)$order['user_id']) {
                    continue;
                }
                self::createFor(
                    $staffId,
                    'delivery_updated',
                    "Delivery $newStatus ($orderNo)",
                    trim($riderName) !== '' ? ("Rider $riderName: $verb (order $orderNo).") : ("Order $orderNo delivery: $verb."),
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/admin/orders/' . $order['id']) : null
                );
                $created++;
            }
        } catch (Exception $e) {
            error_log('notifyDeliveryUpdated failed: ' . $e->getMessage());
        }
        return $created;
    }

    /**
     * Substitution suggested: notify the customer (approval needed) AND staff.
     */
    public static function notifySubstitutionSuggested($order, $sub) {
        $created = 0;
        $orderNo = $order['order_number'] ?? ('#' . ($order['id'] ?? ''));
        $orig = ($sub['original_name'] ?? 'item') . ' (₱' . number_format($sub['original_total'] ?? 0, 2) . ')';
        $sugg = ($sub['suggested_name'] ?? 'replacement') . ' (₱' . number_format($sub['suggested_total'] ?? 0, 2) . ')';
        try {
            if (!empty($order['user_id'])) {
                self::createFor(
                    $order['user_id'],
                    'substitution_suggested',
                    "Replacement suggested for order $orderNo",
                    "$orig is unavailable. We suggest $sugg instead. Please approve or reject it on your order page.",
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/orders/' . $order['id']) : null
                );
                $created++;
            }
            foreach (self::staffRecipients() as $staffId) {
                if (!empty($order['user_id']) && (int)$staffId === (int)$order['user_id']) {
                    continue;
                }
                self::createFor(
                    $staffId,
                    'substitution_suggested',
                    "Substitution suggested ($orderNo)",
                    "Suggest replacing $orig with $sugg for order $orderNo. Awaiting decision.",
                    $order['id'] ?? null,
                    isset($order['id']) ? ('/admin/orders/' . $order['id']) : null
                );
                $created++;
            }
        } catch (Exception $e) {
            error_log('notifySubstitutionSuggested failed: ' . $e->getMessage());
        }
        return $created;
    }

    /**
     * Substitution decided (approved/rejected/applied): notify the customer
     * and the suggesting rider (if the decider wasn't them).
     */
    public static function notifySubstitutionDecided($order, $sub, $decision) {
        $created = 0;
        $orderNo = $order['order_number'] ?? ('#' . ($order['id'] ?? ''));
        $sugg = ($sub['suggested_name'] ?? 'replacement');
        $msgs = [
            'approved' => "approved: $sugg will replace the unavailable item.",
            'rejected' => "rejected: $sugg will NOT replace the unavailable item.",
            'applied' => "applied: your order now contains $sugg.",
        ];
        $msg = $msgs[$decision] ?? "updated to $decision.";
        try {
            $targets = [];
            if (!empty($order['user_id'])) {
                $targets[] = [$order['user_id'], '/orders/' . ($order['id'] ?? '')];
            }
            if (!empty($sub['suggested_by']) && (int)$sub['suggested_by'] !== (int)($sub['decided_by'] ?? 0)
                && (int)$sub['suggested_by'] !== (int)($order['user_id'] ?? 0)) {
                $targets[] = [$sub['suggested_by'], '/rider'];
            }
            foreach ($targets as $t) {
                list($uid, $link) = $t;
                self::createFor(
                    $uid,
                    'substitution_decided',
                    "Substitution $decision ($orderNo)",
                    "The suggested replacement $msg",
                    $order['id'] ?? null,
                    $link
                );
                $created++;
            }
        } catch (Exception $e) {
            error_log('notifySubstitutionDecided failed: ' . $e->getMessage());
        }
        return $created;
    }

    /**
     * New chat message: notify every other participant of the thread.
     * $context: ['order_id' => ?int, 'customer_id' => ?int, 'link' => ?string].
     * Returns number of notification rows created.
     */
    public static function notifyChatMessage($senderId, $senderName, $preview, $context = []) {
        $created = 0;
        $orderId = $context['order_id'] ?? null;
        $preview = mb_substr(trim((string)$preview), 0, 120);
        try {
            $targets = []; // userId => link
            if ($orderId) {
                $order = self::query("SELECT user_id, order_number FROM orders WHERE id = ?", [$orderId])->fetch();
                if ($order && !empty($order['user_id']) && (int)$order['user_id'] !== (int)$senderId) {
                    $targets[(int)$order['user_id']] = '/orders/' . $orderId;
                }
                try {
                    $del = self::query("SELECT d.rider_id, d.id FROM deliveries d WHERE d.order_id = ? LIMIT 1", [$orderId])->fetch();
                    if ($del && (int)$del['rider_id'] !== (int)$senderId) {
                        $targets[(int)$del['rider_id']] = '/rider/deliveries/' . $del['id'];
                    }
                } catch (Exception $e) {
                    // No deliveries table — skip rider target.
                }
                $orderNo = $order['order_number'] ?? ('#' . $orderId);
                $title = "New message on order $orderNo";
                $link = null;
            } else {
                $customerId = $context['customer_id'] ?? null;
                if ($customerId && (int)$customerId !== (int)$senderId) {
                    $targets[(int)$customerId] = '/orders';
                }
                $title = 'New support message';
                $link = null;
                $orderNo = '';
            }
            foreach (self::staffRecipients() as $staffId) {
                if ((int)$staffId === (int)$senderId) {
                    continue;
                }
                $targets[(int)$staffId] = $orderId ? ('/admin/orders/' . $orderId) : '/admin/support';
            }
            foreach ($targets as $uid => $link) {
                self::createFor(
                    $uid, 'chat_message', $title,
                    trim($senderName) !== '' ? ($senderName . ': ' . $preview) : $preview,
                    $orderId, $link
                );
                $created++;
            }
        } catch (Exception $e) {
            error_log('notifyChatMessage failed: ' . $e->getMessage());
        }
        return $created;
    }
}
