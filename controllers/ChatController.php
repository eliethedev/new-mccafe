<?php

class ChatController extends Controller {

    /** Staff support inbox page. */
    public function support(Request $request) {
        try {
            $threads = ChatMessage::supportThreads();
        } catch (Throwable $e) {
            $threads = [];
        }
        return $this->view('admin/support', [
            'title' => 'Support Chats',
            'currentPage' => 'support',
            'threads' => $threads,
        ]);
    }

    /** GET /api/chat?order_id=&after= — one order thread. */
    public function messages(Request $request) {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->jsonOut(['success' => false, 'message' => 'Please login'], 401);
        }

        $orderId = (int)($request->getQuery('order_id') ?? 0);
        $after = (int)($request->getQuery('after') ?? 0);

        if (!$orderId || !ChatMessage::canAccessOrder($user, $orderId)) {
            return $this->jsonOut(['success' => false, 'message' => 'Not allowed'], 403);
        }

        try {
            $items = ChatMessage::forOrder($orderId, $after);
        } catch (Throwable $e) {
            return $this->jsonOut(['success' => false, 'message' => 'Chat unavailable'], 500);
        }

        return $this->jsonOut(['success' => true, 'messages' => $this->present($items, $user)]);
    }

    /** GET /api/support-chat?after=&customer_id= — support thread (staff pass customer). */
    public function supportMessages(Request $request) {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->jsonOut(['success' => false, 'message' => 'Please login'], 401);
        }

        $role = $user['role'] ?? '';
        $isStaff = in_array($role, ['admin', 'staff'], true);
        $customerId = (int)($request->getQuery('customer_id') ?? 0);
        if ($isStaff) {
            if (!$customerId) {
                return $this->jsonOut(['success' => false, 'message' => 'Customer required'], 400);
            }
        } else {
            $customerId = (int)$user['id'];
        }

        $after = (int)($request->getQuery('after') ?? 0);
        try {
            $items = ChatMessage::supportThread($customerId, $after);
        } catch (Throwable $e) {
            return $this->jsonOut(['success' => false, 'message' => 'Chat unavailable'], 500);
        }

        return $this->jsonOut(['success' => true, 'messages' => $this->present($items, $user)]);
    }

    /**
     * POST /api/chat — send to an order thread or the support thread.
     * Body: message, order_id? (absent = support).
     */
    public function send(Request $request) {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->jsonOut(['success' => false, 'message' => 'Please login'], 401);
        }

        $message = trim(mb_substr((string)$request->getBody('message'), 0, ChatMessage::MAX_LEN));
        $orderId = (int)($request->getBody('order_id') ?? 0);

        if ($message === '') {
            return $this->jsonOut(['success' => false, 'message' => 'Message is empty'], 400);
        }

        $role = $user['role'] ?? '';
        $isStaff = in_array($role, ['admin', 'staff'], true);
        $senderName = trim((($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));

        try {
            if ($orderId) {
                if (!ChatMessage::canAccessOrder($user, $orderId)) {
                    return $this->jsonOut(['success' => false, 'message' => 'Not allowed'], 403);
                }
                $id = ChatMessage::send($user['id'], $message, $orderId, null);
                if (!$id) {
                    return $this->jsonOut(['success' => false, 'message' => 'Message is empty'], 400);
                }
                Notification::notifyChatMessage($user['id'], $senderName, $message, ['order_id' => $orderId]);
                $row = ChatMessage::query("SELECT m.*, CONCAT(u.first_name,' ',u.last_name) AS sender_name, u.role AS sender_role FROM chat_messages m JOIN users u ON u.id=m.sender_id WHERE m.id = ?", [$id])->fetch();
            } else {
                // Support thread: staff replies target the customer; customers broadcast to staff.
                $customerId = $isStaff ? (int)($request->getBody('customer_id') ?? 0) : (int)$user['id'];
                if (!$customerId) {
                    return $this->jsonOut(['success' => false, 'message' => 'Customer required'], 400);
                }
                $recipient = $isStaff ? $customerId : null;
                $id = ChatMessage::send($user['id'], $message, null, $recipient);
                if (!$id) {
                    return $this->jsonOut(['success' => false, 'message' => 'Message is empty'], 400);
                }
                Notification::notifyChatMessage($user['id'], $senderName, $message, ['order_id' => null, 'customer_id' => $customerId]);
                $row = ChatMessage::query("SELECT m.*, CONCAT(u.first_name,' ',u.last_name) AS sender_name, u.role AS sender_role FROM chat_messages m JOIN users u ON u.id=m.sender_id WHERE m.id = ?", [$id])->fetch();
            }
        } catch (Throwable $e) {
            error_log('Chat send failed: ' . $e->getMessage());
            return $this->jsonOut(['success' => false, 'message' => 'Chat unavailable'], 500);
        }

        return $this->jsonOut(['success' => true, 'message' => $this->presentOne($row, $user)]);
    }

    // ---------------- helpers ----------------

    private function present($items, $user) {
        return array_map(function ($m) use ($user) {
            return $this->presentOne($m, $user);
        }, (array)$items);
    }

    private function presentOne($m, $user) {
        if (!$m) {
            return null;
        }
        return [
            'id' => (int)$m['id'],
            'message' => $m['message'],
            'sender_name' => trim($m['sender_name'] ?? 'User'),
            'sender_role' => $m['sender_role'] ?? '',
            'mine' => (int)($m['sender_id'] ?? 0) === (int)($user['id'] ?? 0),
            'created_at' => $m['created_at'] ?? '',
        ];
    }

    private function jsonOut($data, $code = 200) {
        $response = new Response(json_encode($data), $code);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }
}
