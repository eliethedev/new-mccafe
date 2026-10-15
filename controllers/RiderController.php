<?php

class RiderController extends Controller {

    public function index(Request $request) {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $isAdmin = ($user['role'] ?? '') === 'admin';
        if ($isAdmin) {
            // Admins see every active delivery.
            try {
                $deliveries = Model::query(
                    "SELECT d.*, o.order_number, o.status AS order_status, o.total_amount,
                            o.customer_name, o.customer_phone, o.customer_address,
                            CONCAT(u.first_name, ' ', u.last_name) AS rider_name
                     FROM deliveries d
                     JOIN orders o ON o.id = d.order_id
                     LEFT JOIN users u ON u.id = d.rider_id
                     WHERE d.status IN ('assigned','accepted','picked_up','delivering')
                     ORDER BY d.updated_at DESC"
                )->fetchAll();
            } catch (Exception $e) {
                $deliveries = [];
            }
            $stats = ['total' => count($deliveries), 'delivered' => 0, 'active' => count($deliveries)];
        } else {
            $deliveries = Delivery::forRider($user['id'], true);
            $stats = Delivery::riderStats($user['id']);
        }

        $history = [];
        try {
            $all = $isAdmin ? [] : Delivery::forRider($user['id'], false);
            foreach ($all as $d) {
                if (in_array($d['status'], ['delivered', 'failed', 'cancelled'], true)) {
                    $history[] = $d;
                }
                if (count($history) >= 10) {
                    break;
                }
            }
        } catch (Exception $e) {
            $history = [];
        }

        return $this->view('rider/dashboard', [
            'title' => 'Rider Dashboard',
            'deliveries' => $deliveries,
            'history' => $history,
            'stats' => $stats,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function show(Request $request, $id) {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $isAdmin = ($user['role'] ?? '') === 'admin';
        $delivery = Delivery::detail($id, $isAdmin ? null : $user['id']);
        if (!$delivery) {
            Session::flash('error', 'Delivery not found.');
            return $this->redirect('/rider');
        }

        $items = Order::getOrderItems($delivery['order_id']);
        $subs = Substitution::forOrder($delivery['order_id']);
        $needsSub = Substitution::needsSubstitution($delivery['order_id']);
        try {
            $products = Model::query(
                "SELECT p.id, p.name, p.price, c.name AS category_name
                 FROM products p LEFT JOIN categories c ON c.id = p.category_id
                 WHERE p.is_available = 1 ORDER BY c.sort_order, p.name LIMIT 200"
            )->fetchAll();
        } catch (Exception $e) {
            $products = [];
        }

        return $this->view('rider/delivery', [
            'title' => 'Delivery ' . ($delivery['order_number'] ?? ('#' . $delivery['order_id'])),
            'delivery' => $delivery,
            'items' => $items,
            'subs' => $subs,
            'needsSub' => $needsSub,
            'products' => $products,
            'transitions' => Delivery::allowedTransitions($delivery['status']),
            'isAdmin' => $isAdmin,
        ]);
    }

    /** Rider/admin advances a delivery: accepted → picked_up → delivering → delivered/failed. */
    public function updateStatus(Request $request, $id) {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->jsonOut(['success' => false, 'message' => 'Please login'], 401);
        }

        $isAdmin = ($user['role'] ?? '') === 'admin';
        $status = strtolower(trim((string)($request->getBody('status') ?? '')));
        $notes = mb_substr(trim((string)($request->getBody('notes') ?? '')), 0, 1000);

        $delivery = Delivery::detail($id, $isAdmin ? null : $user['id']);
        if (!$delivery) {
            return $this->jsonOut(['success' => false, 'message' => 'Delivery not found'], 404);
        }

        if (!in_array($status, Delivery::allowedTransitions($delivery['status']), true)) {
            return $this->jsonOut([
                'success' => false,
                'message' => 'Cannot change delivery from ' . $delivery['status'] . ' to ' . ($status ?: '(empty)') . '.'
            ], 400);
        }

        try {
            $set = "status = ?";
            $params = [$status];
            $tsCol = Delivery::timestampColumn($status);
            if ($tsCol) {
                $set .= ", $tsCol = NOW()";
            }
            if ($notes !== '') {
                $set .= ", delivery_notes = CONCAT(COALESCE(delivery_notes,''), '\n', ?)";
                $params[] = '[' . date('Y-m-d H:i') . ' ' . $user['first_name'] . '] ' . $notes;
            }
            $params[] = $id;
            Model::query("UPDATE deliveries SET $set, updated_at = NOW() WHERE id = ?", $params);

            $riderName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
            $riderId = $delivery['rider_id'];

            // Update rider availability status
            if ($status === 'accepted' && $riderId) {
                Model::query("UPDATE users SET availability_status = 'on_delivery' WHERE id = ?", [$riderId]);
            } elseif (in_array($status, ['delivered', 'failed', 'cancelled'], true) && $riderId) {
                // Check if rider has any other active deliveries
                $hasActive = Delivery::hasActiveDelivery($riderId);
                if (!$hasActive) {
                    Model::query("UPDATE users SET availability_status = 'available' WHERE id = ?", [$riderId]);
                }
            }

            // Delivered also completes a ready order (fires the standard delivered notices).
            if ($status === 'delivered') {
                $this->completeOrderOnDelivery($delivery, $riderName);
            } else {
                $order = $this->freshOrder($delivery['order_id']);
                if ($order) {
                    Notification::notifyDeliveryUpdated($order, $status, $riderName);
                    $this->mailCustomerDeliveryUpdate($order, $status, $riderName);
                    $this->mailStaffDeliveryUpdate($order, $status, $riderName);
                }
            }

            return $this->jsonOut(['success' => true, 'message' => 'Delivery marked as ' . $status . '.']);
        } catch (Exception $e) {
            error_log('Rider status update failed: ' . $e->getMessage());
            return $this->jsonOut(['success' => false, 'message' => 'Database error'], 500);
        }
    }

    /**
     * Rider/admin suggests a replacement for an unavailable order item.
     * Body: order_item_id, suggested_product_id, suggested_variant_id?, qty?, note?
     */
    public function suggest(Request $request, $id) {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->jsonOut(['success' => false, 'message' => 'Please login'], 401);
        }

        $isAdmin = ($user['role'] ?? '') === 'admin';
        $delivery = Delivery::detail($id, $isAdmin ? null : $user['id']);
        if (!$delivery) {
            return $this->jsonOut(['success' => false, 'message' => 'Delivery not found'], 404);
        }

        $orderId = $delivery['order_id'];
        $itemId = (int)($request->getBody('order_item_id') ?? 0);
        $suggProductId = (int)($request->getBody('suggested_product_id') ?? 0);
        $suggVariantId = $request->getBody('suggested_variant_id');
        $suggVariantId = ($suggVariantId === '' || $suggVariantId === null) ? null : (int)$suggVariantId;
        $qty = max(1, min(99, (int)($request->getBody('qty') ?? 1)));
        $note = mb_substr(trim((string)($request->getBody('note') ?? '')), 0, 1000);

        if (!$itemId || !$suggProductId) {
            return $this->jsonOut(['success' => false, 'message' => 'Item and replacement product are required'], 400);
        }

        try {
            // Original item must belong to this order.
            $item = Model::query("SELECT * FROM order_items WHERE id = ? AND order_id = ?", [$itemId, $orderId])->fetch();
            if (!$item) {
                return $this->jsonOut(['success' => false, 'message' => 'Order item not found'], 404);
            }

            // Suggested product must exist and be available.
            $product = Model::query("SELECT * FROM products WHERE id = ?", [$suggProductId])->fetch();
            if (!$product || empty($product['is_available'])) {
                return $this->jsonOut(['success' => false, 'message' => 'Replacement product is not available'], 400);
            }
            if ((int)$suggProductId === (int)$item['product_id']) {
                return $this->jsonOut(['success' => false, 'message' => 'Replacement must differ from the original item'], 400);
            }

            $variantName = null;
            $unitPrice = (float)$product['price'];
            if ($suggVariantId) {
                $variant = Model::query(
                    "SELECT * FROM product_variants WHERE id = ? AND product_id = ? AND is_available = 1",
                    [$suggVariantId, $suggProductId]
                )->fetch();
                if (!$variant) {
                    return $this->jsonOut(['success' => false, 'message' => 'Replacement variant is not available'], 400);
                }
                $variantName = $variant['name'];
                $unitPrice += (float)($variant['price_adjustment'] ?? 0);
            }

            $suggTotal = round($unitPrice * $qty, 2);
            $origTotal = round((float)$item['total_price'], 2);

            $subId = Substitution::create([
                'order_id' => $orderId,
                'order_item_id' => $itemId,
                'original_name' => $item['product_name'],
                'original_variant' => $item['variant_name'] ?? null,
                'original_total' => $origTotal,
                'suggested_product_id' => $suggProductId,
                'suggested_variant_id' => $suggVariantId,
                'suggested_name' => $product['name'],
                'suggested_variant' => $variantName,
                'suggested_qty' => $qty,
                'suggested_total' => $suggTotal,
                'price_diff' => round($suggTotal - $origTotal, 2),
                'status' => 'suggested',
                'note' => $note !== '' ? $note : null,
                'suggested_by' => $user['id'],
            ]);

            $sub = Substitution::find($subId);
            $order = $this->freshOrder($orderId);
            if ($order && $sub) {
                Notification::notifySubstitutionSuggested($order, $sub);
                $this->mailSubstitution($order, $sub, 'suggested');
            }

            return $this->jsonOut(['success' => true, 'message' => 'Replacement suggested. The customer was notified for approval.', 'id' => $subId]);
        } catch (Exception $e) {
            error_log('Suggest substitution failed: ' . $e->getMessage());
            return $this->jsonOut(['success' => false, 'message' => 'Database error'], 500);
        }
    }

    // ---------------- helpers ----------------

    private function freshOrder($orderId) {
        try {
            $stmt = Model::query(
                "SELECT o.*, u.first_name, u.last_name, u.email AS customer_email, u.phone AS customer_phone
                 FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?",
                [$orderId]
            );
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }

    private function jsonOut($data, $code = 200) {
        $response = new Response(json_encode($data), $code);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }

    private function staffEmails() {
        try {
            return Model::query(
                "SELECT email, first_name, last_name FROM users WHERE role IN ('admin','staff')"
            )->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    private function mailCustomerDeliveryUpdate($order, $status, $riderName) {
        $copy = [
            'accepted' => ['Rider accepted your delivery', ($riderName !== '' ? $riderName : 'Your rider') . ' accepted your delivery and will head to the store shortly.'],
            'picked_up' => ['Order picked up', 'Your order was picked up from the store and is being prepared for dispatch.'],
            'delivering' => ['Rider is on the way', ($riderName !== '' ? $riderName : 'Your rider') . ' is on the way with your order. Please keep your phone nearby.'],
            'failed' => ['Delivery problem reported', 'Your rider reported a problem with the delivery. Our staff will contact you shortly.'],
            'cancelled' => ['Delivery assignment cancelled', 'The delivery assignment for your order was cancelled. Staff will arrange a new rider.'],
        ];
        if (!isset($copy[$status])) {
            return;
        }
        try {
            $email = new Email();
            $email->sendCustomerDeliveryUpdate($order, $copy[$status][0], $copy[$status][1]);
        } catch (Throwable $e) {
            error_log('Rider status email failed: ' . $e->getMessage());
        }
    }

    private function mailStaffDeliveryUpdate($order, $status, $riderName) {
        foreach ($this->staffEmails() as $staff) {
            try {
                $mail = new Email();
                $mail->sendAdminDeliveryUpdate(
                    $staff['email'] ?? '',
                    trim((($staff['first_name'] ?? '') . ' ' . ($staff['last_name'] ?? ''))) ?: 'Admin',
                    $order, $status, $riderName
                );
            } catch (Throwable $e) {
                error_log('Admin rider-status email failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Rider-marked delivered also completes a ready order so the standard
     * delivered receipt flow (customer + admin notices) fires exactly once.
     */
    private function completeOrderOnDelivery($delivery, $riderName) {
        $order = $this->freshOrder($delivery['order_id']);
        if (!$order) {
            return;
        }

        if (($order['status'] ?? '') === 'ready') {
            Model::query(
                "UPDATE orders SET status = 'completed', completed_at = NOW() WHERE id = ? AND status = 'ready'",
                [$delivery['order_id']]
            );
            Model::query(
                "INSERT INTO order_status_history (order_id, status, changed_by, notes) VALUES (?, 'completed', ?, ?)",
                [$delivery['order_id'], $_SESSION['user']['id'] ?? null, 'Marked delivered by rider ' . $riderName]
            );
            $order = $this->freshOrder($delivery['order_id']);
        }

        Notification::notifyOrderDelivered($order);
        try {
            $email = new Email();
            $email->sendOrderDelivered($order);
        } catch (Throwable $e) {
            error_log('Rider delivered email failed: ' . $e->getMessage());
        }
        foreach ($this->staffEmails() as $staff) {
            try {
                $mail = new Email();
                $mail->sendAdminDeliveredNotice(
                    $staff['email'] ?? '',
                    trim((($staff['first_name'] ?? '') . ' ' . ($staff['last_name'] ?? ''))) ?: 'Admin',
                    $order
                );
            } catch (Throwable $e) {
                error_log('Admin rider-delivered email failed: ' . $e->getMessage());
            }
        }
        // Customer + staff also get the delivery-status trail.
        Notification::notifyDeliveryUpdated($order, 'delivered', $riderName);
    }

    private function mailSubstitution($order, $sub, $context) {
        try {
            $email = new Email();
            $email->sendSubstitutionNotice(
                $order['customer_email'] ?? '', $order['customer_name'] ?? 'Customer',
                $order, $sub, $context
            );
        } catch (Throwable $e) {
            error_log('Substitution email failed: ' . $e->getMessage());
        }
        foreach ($this->staffEmails() as $staff) {
            try {
                $mail = new Email();
                $mail->sendSubstitutionNotice(
                    $staff['email'] ?? '',
                    trim((($staff['first_name'] ?? '') . ' ' . ($staff['last_name'] ?? ''))) ?: 'Admin',
                    $order, $sub, $context
                );
            } catch (Throwable $e) {
                error_log('Admin substitution email failed: ' . $e->getMessage());
            }
        }
    }
}
