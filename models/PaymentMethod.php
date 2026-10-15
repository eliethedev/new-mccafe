<?php

/**
 * PaymentMethod — single integration point for payment methods,
 * normalization, validation, policies and procedures.
 */
class PaymentMethod
{
    private static $config = null;

    public static function config()
    {
        if (self::$config === null) {
            self::$config = require __DIR__ . '/../config/payments.php';
        }
        return self::$config;
    }

    public static function all()
    {
        return self::config()['methods'];
    }

    public static function allowedAtCheckout()
    {
        return self::config()['checkout_allowed'];
    }

    /** Normalize legacy / user input to canonical key. */
    public static function normalize($method)
    {
        $method = strtolower(trim((string)$method));
        $methods = self::all();
        if (isset($methods[$method])) {
            return $method;
        }
        $aliases = self::config()['legacy_aliases'] ?? [];
        if (isset($aliases[$method])) {
            return $aliases[$method];
        }
        // Back-compat: stored `paymaya` rows map to `maya`.
        if ($method === 'paymaya') {
            return 'maya';
        }
        return $method;
    }

    public static function isValid($method)
    {
        return isset(self::all()[self::normalize($method)]);
    }

    public static function isManual($method)
    {
        $key = self::normalize($method);
        $methods = self::all();
        return isset($methods[$key]) && ($methods[$key]['type'] === 'manual');
    }

    public static function isCash($method)
    {
        return self::normalize($method) === 'cash';
    }

    public static function label($method)
    {
        $key = self::normalize($method);
        $methods = self::all();
        return $methods[$key]['label'] ?? ucfirst($method);
    }

    public static function details($method)
    {
        $key = self::normalize($method);
        $methods = self::all();
        return $methods[$key] ?? null;
    }

    public static function validateReference($method, $reference)
    {
        $key = self::normalize($method);
        $methods = self::all();
        if (!isset($methods[$key]) || $methods[$key]['type'] !== 'manual') {
            return ['valid' => true, 'message' => ''];
        }
        $reference = trim((string)$reference);
        if ($reference === '') {
            return ['valid' => false, 'message' => 'Reference number is required for ' . $methods[$key]['label'] . '.'];
        }
        $pattern = $methods[$key]['reference_pattern'];
        if ($pattern && !preg_match($pattern, $reference)) {
            return ['valid' => false, 'message' => 'Invalid reference format. ' . $methods[$key]['reference_hint']];
        }
        return ['valid' => true, 'message' => ''];
    }

    /** Check reference uniqueness across payment_proofs to stop double-spend. */
    public static function isReferenceDuplicate($reference, $excludeOrderId = null)
    {
        $reference = trim((string)$reference);
        if ($reference === '') {
            return false;
        }
        try {
            $sql = "SELECT COUNT(*) AS c FROM payment_proofs WHERE reference_number = ?";
            $params = [$reference];
            if ($excludeOrderId) {
                $sql .= " AND order_id != ?";
                $params[] = $excludeOrderId;
            }
            $stmt = Model::query($sql, $params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return ((int)($row['c'] ?? 0)) > 0;
        } catch (Exception $e) {
            // Fail open for missing table; checkout validation still applies format rules.
            return false;
        }
    }

    public static function policies()
    {
        return self::config()['policies'];
    }

    public static function procedures($key = null)
    {
        $procedures = self::config()['procedures'];
        return $key ? ($procedures[$key] ?? []) : $procedures;
    }

    /** DB-compatible value (migration 002 adds `maya`; until then store legacy `paymaya`). */
    public static function storageValue($method)
    {
        $key = self::normalize($method);
        if ($key === 'maya') {
            try {
                $stmt = Model::query("SHOW COLUMNS FROM orders LIKE 'payment_method'", []);
                $col = $stmt->fetch(PDO::FETCH_ASSOC);
                $type = $col['Type'] ?? '';
                if (strpos($type, "'maya'") === false) {
                    return 'paymaya';
                }
            } catch (Exception $e) {
                return 'paymaya';
            }
        }
        return $key;
    }

    /** Customer-facing cancellation rule text. */
    public static function cancellationPolicyText($method)
    {
        if (self::isManual($method)) {
            return 'No self-cancellation: ' . self::label($method) . ' orders cannot be cancelled once payment proof is submitted. Please contact staff for assistance; approved cancellations are refunded.';
        }
        return 'Cash orders may be cancelled while pending or confirmed.';
    }

    /** Whether a given order row may be cancelled by the customer. */
    public static function customerCanCancel($order)
    {
        if (!in_array($order['status'] ?? '', ['pending', 'confirmed'], true)) {
            return ['allowed' => false, 'message' => 'Only pending or confirmed orders can be cancelled.'];
        }
        $method = self::normalize($order['payment_method'] ?? 'cash');
        $paymentStatus = strtolower($order['payment_status'] ?? 'pending');
        if (self::isManual($method)) {
            // Policy: manual payments lock once proof submitted (pending verification counts as submitted).
            if (in_array($paymentStatus, ['pending', 'paid', 'verified'], true)) {
                return ['allowed' => false, 'message' => 'Orders paid via ' . self::label($method) . ' cannot be cancelled once payment is submitted. Please contact staff.'];
            }
        }
        return ['allowed' => true, 'message' => ''];
    }
}
