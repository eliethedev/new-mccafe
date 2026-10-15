<?php

/**
 * Discount — Senior Citizen / PWD discount computation and validation.
 * 20% off subtotal + VAT exemption (RA 9994 / RA 10754). One type per order.
 */
class Discount
{
    private static $config = null;

    public static function config()
    {
        if (self::$config === null) {
            self::$config = require __DIR__ . '/../config/discounts.php';
        }
        return self::$config;
    }

    public static function rate()
    {
        return (float)(self::config()['rate'] ?? 0.20);
    }

    public static function types()
    {
        return self::config()['types'];
    }

    public static function normalize($type)
    {
        $type = strtolower(trim((string)$type));
        if ($type === '') {
            return 'none';
        }
        return isset(self::types()[$type]) ? $type : 'none';
    }

    public static function label($type)
    {
        $type = self::normalize($type);
        return self::types()[$type]['label'] ?? 'No discount';
    }

    /**
     * Compute order totals. Returns [subtotal, discount, tax, total] rounded to 2dp.
     * Discounted orders are VAT-exempt (tax = 0); regular orders keep 12% VAT.
     */
    public static function compute($subtotal, $type)
    {
        $subtotal = round(max(0, (float)$subtotal), 2);
        $type = self::normalize($type);
        $cfg = self::config();

        if ($type === 'none') {
            $tax = round($subtotal * (float)($cfg['vat_rate'] ?? 0.12), 2);
            return [
                'subtotal' => $subtotal,
                'discount' => 0.0,
                'tax' => $tax,
                'total' => round($subtotal + $tax, 2),
            ];
        }

        $discount = round($subtotal * self::rate(), 2);
        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => 0.0,
            'total' => round($subtotal - $discount, 2),
        ];
    }

    public static function validateIdNumber($idNo)
    {
        $idNo = trim((string)$idNo);
        if ($idNo === '') {
            return ['valid' => false, 'message' => 'ID number is required for Senior/PWD discount.'];
        }
        $pattern = self::config()['security']['id_pattern'] ?? '/^[A-Za-z0-9-]{4,32}$/';
        if (!preg_match($pattern, $idNo)) {
            return ['valid' => false, 'message' => 'Invalid ID number format (letters, digits, dashes; 4-32 characters).'];
        }
        return ['valid' => true, 'message' => ''];
    }

    /**
     * Automatic eligibility from a verified profile.
     * Returns 'senior'|'pwd'|'none'.
     */
    public static function profileEligibility($user)
    {
        if (empty($user) || empty($user['discount_verified'])) {
            return 'none';
        }
        if (!empty($user['is_senior'])) {
            return 'senior';
        }
        if (!empty($user['is_pwd'])) {
            return 'pwd';
        }
        return 'none';
    }
}
