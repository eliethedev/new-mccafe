<?php
// Centralized payment methods, policies and procedures.
// Single source of truth for checkout, orders, admin verification, and UX.

return [
    'methods' => [
        'gcash' => [
            'label' => 'GCash',
            'type' => 'manual',
            'icon' => 'bi-phone',
            'account_number' => '09708228108',
            'account_name' => 'CafeCat Store',
            'instructions' => 'Send the exact order total to the GCash number below, then upload your payment screenshot and reference number.',
            'reference_label' => 'GCash Reference No. (13 digits)',
            'reference_pattern' => '/^\d{8,16}$/',
            'reference_hint' => 'Numeric GCash transaction / reference ID, 8-16 digits.',
            'verification_sla_hours' => 24,
            'cancellable' => [
                'before_verification' => false,
                'after_verification' => false,
            ],
        ],
        'maya' => [
            'label' => 'Maya',
            'type' => 'manual',
            'icon' => 'bi-wallet2',
            'aliases' => ['paymaya'],
            'account_number' => '09708228108',
            'account_name' => 'CafeCat Store',
            'instructions' => 'Send the exact order total via Maya, then upload your payment screenshot and reference number.',
            'reference_label' => 'Maya Reference No.',
            'reference_pattern' => '/^[A-Za-z0-9-]{6,32}$/',
            'reference_hint' => 'Maya transaction code, 6-32 letters/numbers.',
            'verification_sla_hours' => 24,
            'cancellable' => [
                'before_verification' => false,
                'after_verification' => false,
            ],
        ],
        'bank_transfer' => [
            'label' => 'Bank Transfer',
            'type' => 'manual',
            'icon' => 'bi-bank',
            'account_number' => 'Ask staff for current account',
            'account_name' => 'CafeCat Store',
            'instructions' => 'Transfer the exact order total, then upload your deposit slip / screenshot and bank reference number.',
            'reference_label' => 'Bank Reference No.',
            'reference_pattern' => '/^[A-Za-z0-9-]{6,32}$/',
            'reference_hint' => 'Bank reference / trace number, 6-32 characters.',
            'verification_sla_hours' => 24,
            'cancellable' => [
                'before_verification' => false,
                'after_verification' => false,
            ],
        ],
        'cash' => [
            'label' => 'Cash on Delivery / Pickup',
            'type' => 'cash',
            'icon' => 'bi-cash',
            'account_number' => null,
            'account_name' => null,
            'instructions' => 'Pay in cash when you receive / pick up your order. Please prepare exact amount if possible.',
            'reference_label' => null,
            'reference_pattern' => null,
            'reference_hint' => null,
            'verification_sla_hours' => 0,
            'cancellable' => [
                'before_verification' => true,
                'after_verification' => true,
            ],
        ],
    ],

    // Canonical list accepted at checkout (keys of methods above).
    'checkout_allowed' => ['gcash', 'maya', 'bank_transfer', 'cash'],

    // Legacy values still accepted and normalized (e.g. old `paymaya` rows).
    'legacy_aliases' => [
        'paymaya' => 'maya',
        'cash_on_delivery' => 'cash',
        'cod' => 'cash',
        'card' => 'cash',
    ],

    'policies' => [
        'verification_sla' => 'Manual payments (GCash / Maya / Bank Transfer) are verified by staff within 24 hours. Orders move to "confirmed" only after payment is marked verified/paid.',
        'no_cancellation_manual' => 'Manual e-payments cannot be self-cancelled once proof is submitted. Contact staff for cancellation; approved cancellations are refunded to the original channel.',
        'cod_cancellation' => 'Cash orders may be cancelled while status is pending or confirmed.',
        'refund' => 'Verified manual payments cancelled by staff qualify for refund (GCash / cash / bank transfer / store credit). Refunds are recorded in the refunds table with staff, amount, method and timestamp.',
        'duplicate_reference' => 'Each e-payment reference number may be used only once. Re-using a reference blocks checkout to prevent double-spend fraud.',
        'proof_requirements' => 'Proof must be a genuine JPG/PNG/GIF/WebP screenshot, max 5MB, matching the order total and submitted reference.',
    ],

    'procedures' => [
        'checkout' => [
            '1. Choose one payment method.',
            '2. For manual methods: pay the exact total first using the displayed account.',
            '3. Enter the transaction reference number and upload a clear screenshot.',
            '4. Tick the payment policy consent box, then place the order.',
            '5. Track verification under My Orders; contact staff if pending over 24 hours.',
        ],
        'verification' => [
            '1. Admin opens Orders > Verify Payment and inspects proof image, reference, and amount.',
            '2. Approve sets payment_proofs.status=verified, orders.payment_status=paid, and allows confirm.',
            '3. Reject sets status=rejected/unpaid with a reason visible to staff history.',
            '4. GCash confirm is blocked until payment is verified or marked paid.',
        ],
        'refund' => [
            '1. Staff cancels the order with reason, refund amount, method and notes.',
            '2. A row is written to refunds with status processed, staff id and timestamp.',
            '3. Customer is notified; manual-method refunds go back to the original channel unless store credit is agreed.',
        ],
    ],

    'upload' => [
        'dir' => 'public/assets/images/payment-proofs/',
        'max_size' => 5 * 1024 * 1024,
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'allowed_mimes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    ],

    'security' => [
        'require_csrf' => true,
        'checkout_throttle_seconds' => 10,
        'require_policy_consent_for_manual' => true,
    ],
];
