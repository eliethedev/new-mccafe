<?php
ob_start();

$__escape = static function ($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
$__json = static function ($value): string {
    return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
};
$__money = static function ($value): string {
    return number_format((float) $value, 2, '.', ',');
};

$__fallbackMethods = [
    'gcash' => [
        'label' => 'GCash',
        'type' => 'manual',
        'icon' => 'bi-phone',
        'account_number' => '09708228108',
        'account_name' => 'CafeCat Store',
        'instructions' => 'Send payment via GCash and upload proof.',
        'reference_label' => 'GCash Reference No.',
        'reference_hint' => 'Numeric reference, 8-16 digits.',
    ],
    'cash' => [
        'label' => 'Cash on Delivery',
        'type' => 'cash',
        'icon' => 'bi-cash',
        'instructions' => 'Pay when you receive your order.',
    ],
];
$__pm = $paymentMethods ?? (class_exists('PaymentMethod') ? PaymentMethod::all() : $__fallbackMethods);
$__pm = is_array($__pm) ? $__pm : $__fallbackMethods;
$__policies = $paymentPolicies ?? (class_exists('PaymentMethod') ? PaymentMethod::policies() : []);
$__policies = is_array($__policies) ? $__policies : [];
$__old = Session::get('old') ?? [];
$__old = is_array($__old) ? $__old : [];
$__errors = Session::get('errors') ?? [];
$__errors = is_array($__errors) ? $__errors : [];
$__user = is_array($user ?? null) ? $user : [];
$__csrf = $csrfToken ?? Session::token();
$__dc = $discountTypes ?? (class_exists('Discount') ? Discount::types() : [
    'none' => ['label' => 'No discount'],
    'senior' => ['label' => 'Senior Citizen (20% off)', 'id_label' => 'Senior Citizen ID No.', 'id_hint' => 'ID number is verified by staff.'],
    'pwd' => ['label' => 'PWD (20% off)', 'id_label' => 'PWD ID No.', 'id_hint' => 'ID number is verified by staff.'],
]);
$__dc = is_array($__dc) ? $__dc : [];
if (!isset($__dc['none'])) {
    $__dc = ['none' => ['label' => 'No discount']] + $__dc;
}
$__rate = (float) ($discountRate ?? 0.20);
$__autoDiscount = (string) ($autoDiscount ?? 'none');
if (!isset($__dc[$__autoDiscount])) {
    $__autoDiscount = 'none';
}
$__selectedDiscount = (string) ($discountType ?? ($__old['discount_type'] ?? 'none'));
if (!isset($__dc[$__selectedDiscount])) {
    $__selectedDiscount = $__autoDiscount !== 'none' ? $__autoDiscount : 'none';
}
$__baseSubtotal = (float) ($subtotal ?? 0);
$__discountAmount = (float) ($discount ?? 0);
$__taxAmount = (float) ($tax ?? 0);
$__totalAmount = (float) ($total ?? ($__baseSubtotal + $__taxAmount - $__discountAmount));
$__cartItems = is_array($cartItems ?? null) ? $cartItems : [];
$__savedIdNo = (string) ($__user['discount_id_no'] ?? '');
$__customerName = (string) ($__old['customer_name'] ?? trim((string) ($__user['first_name'] ?? '') . ' ' . (string) ($__user['last_name'] ?? '')));
$__customerPhone = (string) ($__old['customer_phone'] ?? ($__user['phone'] ?? ''));
$__customerAddress = (string) ($__old['customer_address'] ?? ($__user['address'] ?? ''));
$__customerEmail = (string) ($__old['customer_email'] ?? ($__user['email'] ?? ''));
$__selectedMethod = strtolower((string) ($__old['payment_method'] ?? 'gcash'));
if (!isset($__pm[$__selectedMethod])) {
    $__selectedMethod = (string) (array_key_first($__pm) ?? '');
}
$__selectedMethodData = $__pm[$__selectedMethod] ?? [];
$__manualSelected = (($__selectedMethodData['type'] ?? '') === 'manual');
$__idNoValue = (string) ($__old['discount_id_no'] ?? $__savedIdNo);
$__selectedDiscountLabel = (string) ($__dc[$__selectedDiscount]['label'] ?? $__selectedDiscount);
$__taxLabel = $__selectedDiscount !== 'none' ? 'VAT (exempt)' : 'Tax (12%)';

$__methodMeta = [];
$__methodIds = [];
foreach ($__pm as $__index => $__key) {
    $__keyString = (string) $__index;
    $__method = is_array($__key) ? $__key : [];
    $__methodSlug = preg_replace('/[^A-Za-z0-9_-]/', '-', $__keyString) ?: 'method';
    $__methodIds[$__keyString] = 'pay_' . $__methodSlug . '_' . count($__methodIds);
    $__manual = (($__method['type'] ?? '') === 'manual');
    $__fallbackMeta = match (strtolower($__keyString)) {
        'gcash' => ['label' => 'GCash Reference No. (8-16 digits)', 'hint' => 'Numeric GCash transaction ID. Each reference can be used once.', 'placeholder' => 'e.g. 1234567890123'],
        'maya', 'paymaya' => ['label' => 'Maya Reference No.', 'hint' => 'Maya transaction code, 6-32 letters/numbers. Each reference can be used once.', 'placeholder' => 'e.g. MAYA-ABC123'],
        'bank_transfer' => ['label' => 'Bank Reference No.', 'hint' => 'Bank reference / trace number, 6-32 characters.', 'placeholder' => 'e.g. TRX-2026-001'],
        default => ['label' => 'Reference Number', 'hint' => 'Each reference can be used only once.', 'placeholder' => 'Enter transaction reference'],
    };
    $__methodMeta[strtolower($__keyString)] = [
        'manual' => $__manual,
        'label' => (string) ($__method['reference_label'] ?? $__fallbackMeta['label']),
        'hint' => (string) ($__method['reference_hint'] ?? $__fallbackMeta['hint']),
        'placeholder' => (string) ($__method['reference_placeholder'] ?? $__fallbackMeta['placeholder']),
        'name' => (string) ($__method['label'] ?? $__keyString),
    ];
}
$__discountLabels = [];
$__discountIdLabels = [];
$__discountIdHints = [];
$__discountKeys = [];
$__discountIds = [];
foreach ($__dc as $__index => $__option) {
    $__keyString = (string) $__index;
    $__option = is_array($__option) ? $__option : ['label' => (string) $__option];
    $__discountKeys[] = $__keyString;
    $__discountLabels[$__keyString] = (string) ($__option['label'] ?? $__keyString);
    $__discountIdLabels[$__keyString] = (string) ($__option['id_label'] ?? 'ID Number');
    $__discountIdHints[$__keyString] = (string) ($__option['id_hint'] ?? 'ID number is verified by staff.');
    $__slug = preg_replace('/[^A-Za-z0-9_-]/', '-', $__keyString) ?: 'discount';
    $__discountIds[$__keyString] = 'disc_' . $__slug . '_' . count($__discountIds);
}
$__manualConsentRequired = $__manualSelected;
$__discountAttestationRequired = ($__selectedDiscount !== 'none' && $__autoDiscount !== $__selectedDiscount);
?>

<style>
    .cc-checkout {
        --cc-ink: #111111;
        --cc-white: #ffffff;
        --cc-mist: #f3f3f0;
        --cc-gray: #74746e;
        --cc-line: #deded9;
        --cc-soft: #ecece8;
        --cc-serif: "DM Serif Display", Georgia, serif;
        --cc-sans: Manrope, Arial, sans-serif;
        min-height: 70vh;
        padding: 30px 0 64px;
        background: var(--cc-mist);
        color: var(--cc-ink);
        font-family: var(--cc-sans);
        -webkit-font-smoothing: antialiased;
    }

    .cc-checkout *, .cc-checkout *::before, .cc-checkout *::after { box-sizing: border-box; }
    .cc-checkout button, .cc-checkout input, .cc-checkout select, .cc-checkout textarea { font: inherit; }
    .cc-checkout [hidden] { display: none !important; }
    .cc-checkout a:focus-visible, .cc-checkout button:focus-visible, .cc-checkout input:focus-visible, .cc-checkout textarea:focus-visible, .cc-checkout summary:focus-visible { outline: 3px solid #777772; outline-offset: 3px; }

    .cc-checkout__inner { width: min(1240px, 100%); margin: 0 auto; padding: 0 26px; }
    .cc-checkout__header { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 24px; }
    .cc-checkout__eyebrow { margin: 0 0 9px; color: var(--cc-gray); font-size: 10px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; }
    .cc-checkout__title { margin: 0; font: 400 clamp(36px, 5vw, 54px)/1 var(--cc-serif); letter-spacing: -.025em; }
    .cc-checkout__intro { max-width: 580px; margin: 10px 0 0; color: #62625d; font-size: 12px; line-height: 1.7; }
    .cc-checkout__back { display: inline-flex; min-height: 43px; flex: 0 0 auto; align-items: center; justify-content: center; gap: 8px; padding: 0 13px; border: 1px solid #c9c9c3; color: var(--cc-ink); font-size: 11px; font-weight: 800; text-decoration: none; transition: background .16s ease, border-color .16s ease; }
    .cc-checkout__back:hover { border-color: var(--cc-ink); background: #fff; color: var(--cc-ink); }

    .cc-checkout__progress { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); margin: 0 0 24px; padding: 0; list-style: none; border: 1px solid var(--cc-line); background: #fff; }
    .cc-checkout__progress li { display: flex; min-height: 48px; align-items: center; gap: 9px; padding: 8px 13px; color: #696963; font-size: 10px; font-weight: 700; }
    .cc-checkout__progress li + li { border-left: 1px solid var(--cc-line); }
    .cc-checkout__progress span { display: inline-grid; width: 23px; height: 23px; flex: 0 0 23px; place-items: center; border: 1px solid #c8c8c2; border-radius: 50%; color: #555550; font-size: 10px; }
    .cc-checkout__progress li[aria-current="step"] { color: var(--cc-ink); }
    .cc-checkout__progress li[aria-current="step"] span { border-color: var(--cc-ink); background: var(--cc-ink); color: #fff; }

    .cc-checkout__columns { display: grid; grid-template-columns: minmax(0, 1fr) 340px; align-items: start; gap: 22px; }
    .cc-checkout__form { display: grid; gap: 16px; min-width: 0; }
    .cc-checkout__section, .cc-checkout__aside-card { border: 1px solid var(--cc-line); background: #fff; }
    .cc-checkout__section-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; padding: 17px 20px; border-bottom: 1px solid var(--cc-line); }
    .cc-checkout__section-title { display: flex; align-items: flex-start; gap: 11px; margin: 0; font: 400 23px/1.15 var(--cc-serif); }
    .cc-checkout__step { display: inline-grid; width: 24px; height: 24px; flex: 0 0 24px; place-items: center; margin-top: 1px; border: 1px solid #c8c8c2; border-radius: 50%; color: #5c5c56; font: 700 10px/1 var(--cc-sans); }
    .cc-checkout__section-body { padding: 19px 20px; }
    .cc-checkout__section-note { margin: 4px 0 0 35px; color: var(--cc-gray); font-size: 10px; line-height: 1.6; }
    .cc-checkout__required { color: #333; }

    .cc-checkout__table-wrap { overflow-x: auto; }
    .cc-checkout__table { width: 100%; border-collapse: collapse; font-size: 11px; }
    .cc-checkout__table th { padding: 10px 9px; border-bottom: 1px solid var(--cc-ink); color: #565650; font-size: 9px; letter-spacing: .08em; text-align: left; text-transform: uppercase; white-space: nowrap; }
    .cc-checkout__table td { padding: 11px 9px; border-bottom: 1px solid #e8e8e3; vertical-align: middle; }
    .cc-checkout__table tbody tr:last-child td { border-bottom: 0; }
    .cc-checkout__product { display: flex; min-width: 180px; align-items: center; gap: 10px; }
    .cc-checkout__product-image { width: 48px; height: 48px; flex: 0 0 48px; border: 1px solid #e4e4df; background: var(--cc-soft); object-fit: cover; }
    .cc-checkout__product-name { color: var(--cc-ink); font-size: 11px; font-weight: 800; line-height: 1.45; }
    .cc-checkout__variant { color: var(--cc-gray); }
    .cc-checkout__number { text-align: right !important; white-space: nowrap; }
    .cc-checkout__items-empty { padding: 16px 0; color: var(--cc-gray); font-size: 12px; }

    .cc-checkout__fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .cc-checkout__field { min-width: 0; }
    .cc-checkout__field--wide { grid-column: 1 / -1; }
    .cc-checkout__label { display: block; margin: 0 0 7px; color: var(--cc-ink); font-size: 11px; font-weight: 800; }
    .cc-checkout__input, .cc-checkout__textarea { display: block; width: 100%; min-height: 45px; padding: 11px 12px; border: 1px solid #c9c9c3; border-radius: 0; background: #fff; color: var(--cc-ink); font-size: 12px; }
    .cc-checkout__textarea { min-height: 94px; resize: vertical; line-height: 1.55; }
    .cc-checkout__input::placeholder, .cc-checkout__textarea::placeholder { color: #8a8a84; }
    .cc-checkout__input:focus, .cc-checkout__textarea:focus { border-color: var(--cc-ink); outline: 2px solid rgba(17,17,17,.12); outline-offset: 1px; }
    .cc-checkout__help { display: block; margin-top: 6px; color: var(--cc-gray); font-size: 10px; line-height: 1.5; }

    .cc-checkout__options { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 9px; }
    .cc-checkout__choice { position: relative; min-width: 0; }
    .cc-checkout__choice input { position: absolute; top: 13px; left: 13px; width: 17px; height: 17px; margin: 0; accent-color: var(--cc-ink); }
    .cc-checkout__choice label { display: flex; min-height: 96px; flex-direction: column; justify-content: center; gap: 5px; padding: 13px 12px 12px 39px; border: 1px solid #d1d1cb; background: #fff; cursor: pointer; transition: border-color .16s ease, background .16s ease; }
    .cc-checkout__choice label:hover { border-color: #777772; }
    .cc-checkout__choice.selected label { border-color: var(--cc-ink); background: #f7f7f5; box-shadow: inset 0 0 0 1px var(--cc-ink); }
    .cc-checkout__choice-title { color: var(--cc-ink); font-size: 11px; font-weight: 800; line-height: 1.35; }
    .cc-checkout__choice-meta { color: var(--cc-gray); font-size: 9px; line-height: 1.45; }
    .cc-checkout__choice-badge { display: inline-flex; align-self: flex-start; margin-top: 2px; padding: 3px 5px; background: #efefec; color: #4c4c47; font-size: 8px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .cc-checkout__discount-status { display: inline-block; padding: 5px 8px; background: var(--cc-ink); color: #fff; font-size: 9px; font-weight: 800; }

    .cc-checkout__hint { display: flex; gap: 10px; margin-top: 13px; padding: 11px 12px; border-left: 2px solid #777772; background: #f4f4f1; color: #555550; font-size: 10px; line-height: 1.6; }
    .cc-checkout__hint svg { width: 15px; height: 15px; flex: 0 0 15px; margin-top: 1px; }
    .cc-checkout__proof { margin-top: 14px; padding: 17px; border: 1px solid var(--cc-line); background: #fafaf8; }
    .cc-checkout__proof-title { margin: 0 0 4px; font: 400 20px/1.2 var(--cc-serif); }
    .cc-checkout__proof-intro { margin: 0 0 14px; color: var(--cc-gray); font-size: 10px; line-height: 1.6; }
    .cc-checkout__file { display: block; width: 100%; min-height: 44px; padding: 7px; border: 1px solid #c9c9c3; background: #fff; color: #555550; font-size: 11px; }
    .cc-checkout__file::file-selector-button { min-height: 28px; margin-right: 10px; padding: 0 9px; border: 1px solid #bdbdb7; background: #f4f4f1; color: #222; cursor: pointer; font-size: 10px; font-weight: 700; }
    .cc-checkout__proof-preview { display: block; max-width: 100%; max-height: 210px; margin-top: 12px; border: 1px solid var(--cc-line); object-fit: contain; }
    .cc-checkout__file-meta { display: block; margin-top: 5px; color: var(--cc-gray); font-size: 10px; }
    .cc-checkout__file-error { margin-top: 7px; color: #7c1b1b; font-size: 10px; font-weight: 700; }
    .cc-checkout__consent { display: flex; align-items: flex-start; gap: 9px; margin-top: 14px; padding-top: 13px; border-top: 1px solid var(--cc-line); color: #4c4c47; font-size: 10px; line-height: 1.65; }
    .cc-checkout__consent input { flex: 0 0 auto; width: 16px; height: 16px; margin: 1px 0 0; accent-color: var(--cc-ink); }

    .cc-checkout__policy { border: 1px solid var(--cc-line); background: #fff; }
    .cc-checkout__policy summary { padding: 14px 17px; color: var(--cc-ink); cursor: pointer; font-size: 11px; font-weight: 800; }
    .cc-checkout__policy-body { padding: 0 17px 15px; color: #565650; font-size: 10px; line-height: 1.7; }
    .cc-checkout__policy-body ul { margin: 0 0 9px; padding-left: 19px; }
    .cc-checkout__policy-body p { margin: 0; }

    .cc-checkout__actions { display: flex; align-items: center; justify-content: space-between; gap: 14px; padding-top: 2px; }
    .cc-checkout__place-order { display: inline-flex; min-width: 190px; min-height: 49px; align-items: center; justify-content: center; gap: 9px; padding: 0 18px; border: 1px solid var(--cc-ink); background: var(--cc-ink); color: #fff; cursor: pointer; font-size: 12px; font-weight: 800; transition: background .16s ease, transform .16s ease; }
    .cc-checkout__place-order:hover:not(:disabled) { background: #343431; }
    .cc-checkout__place-order:active:not(:disabled) { transform: scale(.99); }
    .cc-checkout__place-order:disabled { opacity: .7; cursor: wait; }
    .cc-checkout__back-inline { color: #4e4e49; font-size: 11px; font-weight: 800; text-decoration: underline; text-underline-offset: 3px; }

    .cc-checkout__aside { position: sticky; top: var(--cafecat-header-height, 86px); display: grid; gap: 13px; max-height: calc(100vh - var(--cafecat-header-height, 86px)); overflow-y: auto; }
    .cc-checkout__aside-card { padding: 18px; }
    .cc-checkout__aside-title { margin: 0 0 15px; font: 400 23px/1.15 var(--cc-serif); }
    .cc-checkout__summary-row { display: flex; justify-content: space-between; gap: 12px; margin-bottom: 11px; color: #5d5d58; font-size: 11px; }
    .cc-checkout__summary-row strong { color: var(--cc-ink); font-weight: 700; text-align: right; white-space: nowrap; }
    .cc-checkout__summary-discount { color: #343431; }
    .cc-checkout__summary-total { display: flex; justify-content: space-between; gap: 12px; margin-top: 14px; padding-top: 13px; border-top: 1px solid var(--cc-ink); color: var(--cc-ink); font-size: 12px; font-weight: 800; }
    .cc-checkout__summary-total strong { font-size: 20px; white-space: nowrap; }
    .cc-checkout__aside-subtitle { margin: 18px 0 10px; color: #62625d; font-size: 9px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
    .cc-checkout__account { padding: 12px 0; border-top: 1px solid #e7e7e2; }
    .cc-checkout__account:first-of-type { border-top: 0; }
    .cc-checkout__account-name { margin: 0 0 6px; color: var(--cc-ink); font-size: 11px; font-weight: 800; }
    .cc-checkout__account-instructions { margin: 0 0 7px; color: var(--cc-gray); font-size: 10px; line-height: 1.55; }
    .cc-checkout__account-line { display: flex; align-items: center; justify-content: space-between; gap: 8px; color: #5b5b55; font-size: 10px; }
    .cc-checkout__account-number { color: var(--cc-ink); font-family: ui-monospace, SFMono-Regular, Consolas, monospace; font-size: 11px; font-weight: 700; overflow-wrap: anywhere; }
    .cc-checkout__copy { display: inline-flex; min-height: 30px; flex: 0 0 auto; align-items: center; gap: 5px; padding: 0 7px; border: 1px solid #c9c9c3; background: #fff; color: var(--cc-ink); cursor: pointer; font-size: 9px; font-weight: 800; }
    .cc-checkout__copy:hover { background: var(--cc-soft); }
    .cc-checkout__copy-status { min-height: 14px; color: #4e4e49; font-size: 9px; }
    .cc-checkout__policy-note { margin: 12px 0 0; padding: 11px 12px; border: 1px solid var(--cc-line); background: #f6f6f3; color: #555550; font-size: 9px; line-height: 1.65; }
    .cc-checkout__policy-note strong { color: var(--cc-ink); }
    .cc-checkout__alert { margin: 0 0 15px; padding: 13px 15px; border: 1px solid #d6baba; background: #fffafa; color: #5e1e1e; font-size: 11px; line-height: 1.6; }
    .cc-checkout__alert p { margin: 0 0 4px; }
    .cc-checkout__alert p:last-child { margin-bottom: 0; }
    .cc-checkout__overlay[hidden] { display: none !important; }
    .cc-checkout__overlay { position: fixed; inset: 0; z-index: 9999; display: grid; place-items: center; padding: 20px; background: rgba(255,255,255,.95); backdrop-filter: blur(5px); }
    .cc-checkout__overlay-card { width: min(400px, 100%); padding: 30px 24px; border: 1px solid var(--cc-line); background: #fff; text-align: center; box-shadow: 0 12px 36px rgba(0,0,0,.12); }
    .cc-checkout__spinner { width: 32px; height: 32px; margin: 0 auto 14px; border: 3px solid #d5d5d0; border-top-color: var(--cc-ink); border-radius: 50%; animation: cc-checkout-spin .8s linear infinite; }
    @keyframes cc-checkout-spin { to { transform: rotate(360deg); } }
    .cc-checkout__overlay-title { margin: 0 0 7px; font: 400 26px/1.2 var(--cc-serif); }
    .cc-checkout__overlay-text { margin: 0; color: var(--cc-gray); font-size: 11px; line-height: 1.6; }

    @media (max-width: 980px) {
        .cc-checkout__columns { grid-template-columns: minmax(0, 1fr) 300px; gap: 14px; }
        .cc-checkout__section-head, .cc-checkout__section-body { padding-right: 16px; padding-left: 16px; }
        .cc-checkout__options { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 760px) {
        .cc-checkout { padding-top: 22px; }
        .cc-checkout__inner { padding: 0 16px; }
        .cc-checkout__header { align-items: flex-start; flex-direction: column; gap: 16px; }
        .cc-checkout__columns { grid-template-columns: 1fr; }
        .cc-checkout__aside { position: static; grid-row: 1; }
        .cc-checkout__progress { overflow-x: auto; grid-template-columns: repeat(4, minmax(120px, 1fr)); }
        .cc-checkout__progress li { padding-right: 9px; padding-left: 9px; }
        .cc-checkout__table { min-width: 610px; }
        .cc-checkout__aside { grid-template-columns: 1fr; }
    }
    @media (max-width: 520px) {
        .cc-checkout__inner { padding: 0 12px; }
        .cc-checkout__fields { grid-template-columns: 1fr; gap: 13px; }
        .cc-checkout__field--wide { grid-column: auto; }
        .cc-checkout__options { grid-template-columns: 1fr; }
        .cc-checkout__choice label { min-height: 76px; }
        .cc-checkout__actions { align-items: stretch; flex-direction: column-reverse; }
        .cc-checkout__place-order { width: 100%; }
        .cc-checkout__back-inline { align-self: center; padding: 7px; }
        .cc-checkout__section-title { font-size: 21px; }
        .cc-checkout__section-head { padding: 15px 13px; }
        .cc-checkout__section-body { padding: 15px 13px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cc-checkout *, .cc-checkout *::before, .cc-checkout *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; }
    }
</style>

<div class="cc-checkout mt-5">
    <div class="cc-checkout__inner">
        <header class="cc-checkout__header">
            <div>
                <p class="cc-checkout__eyebrow">CafeCat · Almost yours</p>
                <h1 class="cc-checkout__title">Checkout</h1>
                <p class="cc-checkout__intro">Confirm your order, add your delivery details, then choose how you’d like to pay.</p>
            </div>
            <a class="cc-checkout__back" href="/cart">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M19 12H5m7 7-7-7 7-7"></path></svg>
                Back to cart
            </a>
        </header>

        <div class="cc-checkout__columns">
            <div>
                <?php if (!empty($__errors)): ?>
                    <div class="cc-checkout__alert" role="alert" aria-labelledby="checkout-errors-title">
                        <strong id="checkout-errors-title">Please review the following:</strong>
                        <?php foreach ($__errors as $__field => $__fieldErrors): ?>
                            <?php foreach ((array) $__fieldErrors as $__error): ?>
                                <p><?= $__escape($__error) ?></p>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="/checkout/process" method="POST" enctype="multipart/form-data" id="checkoutForm">
                    <input type="hidden" name="_csrf" value="<?= $__escape($__csrf) ?>">

                    <section class="cc-checkout__section" aria-labelledby="order-review-title">
                        <div class="cc-checkout__section-head">
                            <div>
                                <h2 class="cc-checkout__section-title" id="order-review-title"><span class="cc-checkout__step">1</span>Review your order</h2>
                                <p class="cc-checkout__section-note">Check item options, quantities and prices before placing your order.</p>
                            </div>
                        </div>
                        <div class="cc-checkout__section-body">
                            <?php if ($__cartItems): ?>
                                <div class="cc-checkout__table-wrap" role="region" aria-label="Order items" tabindex="0">
                                    <table class="cc-checkout__table">
                                        <thead>
                                            <tr><th scope="col">Product</th><th scope="col">Variant</th><th scope="col" class="cc-checkout__number">Qty</th><th scope="col" class="cc-checkout__number">Price</th><th scope="col" class="cc-checkout__number">Total</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($__cartItems as $__item): ?>
                                                <tr>
                                                    <td>
                                                        <div class="cc-checkout__product">
                                                            <?php if (!empty($__item['product_image'])): ?>
                                                                <img src="/assets/images/<?= $__escape($__item['product_image']) ?>" alt="<?= $__escape($__item['product_name'] ?? 'Menu item') ?>" class="cc-checkout__product-image" loading="lazy" onerror="this.onerror=null;this.src='/assets/images/logo.jpg'">
                                                            <?php endif; ?>
                                                            <span class="cc-checkout__product-name"><?= $__escape($__item['product_name'] ?? 'Menu item') ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="cc-checkout__variant"><?= $__escape(!empty($__item['variant_name']) ? $__item['variant_name'] : '—') ?></td>
                                                    <td class="cc-checkout__number"><?= $__escape($__item['quantity'] ?? 0) ?></td>
                                                    <td class="cc-checkout__number">₱<?= $__money($__item['unit_price'] ?? 0) ?></td>
                                                    <td class="cc-checkout__number"><strong>₱<?= $__money($__item['total_price'] ?? 0) ?></strong></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="cc-checkout__items-empty">No order items were found. <a href="/cart">Return to your cart</a>.</p>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="cc-checkout__section" aria-labelledby="customer-info-title">
                        <div class="cc-checkout__section-head">
                            <div>
                                <h2 class="cc-checkout__section-title" id="customer-info-title"><span class="cc-checkout__step">2</span>Your details</h2>
                                <p class="cc-checkout__section-note">We’ll use these details to prepare and deliver your order.</p>
                            </div>
                        </div>
                        <div class="cc-checkout__section-body">
                            <div class="cc-checkout__fields">
                                <div class="cc-checkout__field">
                                    <label class="cc-checkout__label" for="customer_name">Full name <span class="cc-checkout__required">*</span></label>
                                    <input class="cc-checkout__input" type="text" name="customer_name" id="customer_name" value="<?= $__escape($__customerName) ?>" autocomplete="name" required>
                                </div>
                                <div class="cc-checkout__field">
                                    <label class="cc-checkout__label" for="customer_phone">Phone number <span class="cc-checkout__required">*</span></label>
                                    <input class="cc-checkout__input" type="tel" name="customer_phone" id="customer_phone" value="<?= $__escape($__customerPhone) ?>" autocomplete="tel" inputmode="tel" required>
                                </div>
                                <div class="cc-checkout__field cc-checkout__field--wide">
                                    <label class="cc-checkout__label" for="customer_address">Delivery address <span class="cc-checkout__required">*</span></label>
                                    <textarea class="cc-checkout__textarea" name="customer_address" id="customer_address" rows="3" placeholder="House or unit number, street, barangay, city, and any helpful delivery notes" autocomplete="street-address" required><?= $__escape($__customerAddress) ?></textarea>
                                </div>
                                <div class="cc-checkout__field cc-checkout__field--wide">
                                    <label class="cc-checkout__label" for="customer_email">Email address <span class="cc-checkout__required">*</span></label>
                                    <input class="cc-checkout__input" type="email" name="customer_email" id="customer_email" value="<?= $__escape($__customerEmail) ?>" autocomplete="email" inputmode="email" required>
                                    <small class="cc-checkout__help">Please check this address for order updates.</small>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="cc-checkout__section" aria-labelledby="discount-title">
                        <div class="cc-checkout__section-head">
                            <div>
                                <h2 class="cc-checkout__section-title" id="discount-title"><span class="cc-checkout__step">3</span>Senior / PWD discount</h2>
                                <p class="cc-checkout__section-note">Choose the applicable option. Discount and tax totals update below.</p>
                            </div>
                            <?php if ($__autoDiscount !== 'none'): ?>
                                <span class="cc-checkout__discount-status">Profile verified</span>
                            <?php endif; ?>
                        </div>
                        <div class="cc-checkout__section-body">
                            <div class="cc-checkout__options" role="radiogroup" aria-label="Discount type">
                                <?php foreach ($__dc as $__key => $__option): ?>
                                    <?php
                                    $__keyString = (string) $__key;
                                    $__option = is_array($__option) ? $__option : ['label' => (string) $__option];
                                    $__discountId = $__discountIds[$__keyString] ?? 'disc_option_' . count($__discountIds);
                                    $__discountChecked = ($__selectedDiscount === $__keyString);
                                    ?>
                                    <div class="cc-checkout__choice discount-option<?= $__discountChecked ? ' selected' : '' ?>" data-discount="<?= $__escape($__keyString) ?>">
                                        <input type="radio" name="discount_type" id="<?= $__escape($__discountId) ?>" value="<?= $__escape($__keyString) ?>"<?= $__discountChecked ? ' checked' : '' ?>>
                                        <label for="<?= $__escape($__discountId) ?>">
                                            <span class="cc-checkout__choice-title"><?= $__escape($__option['label'] ?? $__keyString) ?></span>
                                            <?php if ($__keyString !== 'none'): ?>
                                                <span class="cc-checkout__choice-meta">20% off + VAT-exempt</span>
                                            <?php else: ?>
                                                <span class="cc-checkout__choice-meta">Standard tax applies</span>
                                            <?php endif; ?>
                                            <?php if ($__keyString !== 'none' && $__autoDiscount === $__keyString): ?>
                                                <span class="cc-checkout__choice-badge">Auto-applied · profile verified</span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div id="discount-id-block" class="cc-checkout__proof"<?= $__selectedDiscount === 'none' ? ' hidden' : '' ?>>
                                <div class="cc-checkout__field">
                                    <label class="cc-checkout__label" for="discount_id_no" id="discountIdLabel"><?= $__escape($__dc[$__selectedDiscount]['id_label'] ?? 'ID Number') ?></label>
                                    <input class="cc-checkout__input" type="text" name="discount_id_no" id="discount_id_no" value="<?= $__escape($__idNoValue) ?>" placeholder="Enter ID number" autocomplete="off" maxlength="32"<?= $__selectedDiscount !== 'none' ? ' required' : '' ?>>
                                    <small class="cc-checkout__help" id="discountIdHint"><?= $__escape($__dc[$__selectedDiscount]['id_hint'] ?? 'ID number is verified by staff.') ?></small>
                                </div>
                                <div class="cc-checkout__consent" id="discount-attest-block"<?= $__discountAttestationRequired ? '' : ' hidden' ?>>
                                    <input type="checkbox" name="accept_discount_terms" id="accept_discount_terms" value="1"<?= !empty($__old['accept_discount_terms']) ? ' checked' : '' ?><?= $__discountAttestationRequired ? ' required' : '' ?>>
                                    <label for="accept_discount_terms">I confirm this Senior/PWD ID is valid, belongs to me (or the person I am ordering for), and I will present it for verification.</label>
                                </div>
                                <?php if ($__autoDiscount !== 'none'): ?>
                                    <p class="cc-checkout__hint" id="discountNotice">Verified profile detected — your <?= $__escape($__dc[$__autoDiscount]['label'] ?? '') ?> discount applies automatically. Staff may still ask to see the ID on pickup/delivery.</p>
                                <?php else: ?>
                                    <p class="cc-checkout__hint" id="discountNotice">Manual claims are marked <strong>pending</strong> until staff verify your physical ID. Misuse may void the order.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>

                    <section class="cc-checkout__section" aria-labelledby="payment-method-title">
                        <div class="cc-checkout__section-head">
                            <div>
                                <h2 class="cc-checkout__section-title" id="payment-method-title"><span class="cc-checkout__step">4</span>Choose a payment method</h2>
                                <p class="cc-checkout__section-note">Select one option. Any requirements will appear below.</p>
                            </div>
                        </div>
                        <div class="cc-checkout__section-body">
                            <div class="cc-checkout__options" role="radiogroup" aria-label="Payment method">
                                <?php foreach ($__pm as $__key => $__method): ?>
                                    <?php
                                    $__method = is_array($__method) ? $__method : ['label' => (string) $__method];
                                    $__keyString = (string) $__key;
                                    $__methodId = $__methodIds[$__keyString] ?? 'pay_option_' . count($__methodIds);
                                    $__methodSelected = ($__selectedMethod === strtolower($__keyString));
                                    $__isManual = (($__method['type'] ?? '') === 'manual');
                                    ?>
                                    <div class="cc-checkout__choice payment-option<?= $__methodSelected ? ' selected' : '' ?>" data-method="<?= $__escape(strtolower($__keyString)) ?>">
                                        <input type="radio" name="payment_method" id="<?= $__escape($__methodId) ?>" value="<?= $__escape($__keyString) ?>" data-manual="<?= $__isManual ? '1' : '0' ?>"<?= $__methodSelected ? ' checked' : '' ?> required>
                                        <label for="<?= $__escape($__methodId) ?>">
                                            <span class="cc-checkout__choice-title"><?= $__escape($__method['label'] ?? $__keyString) ?></span>
                                            <span class="cc-checkout__choice-meta"><?= $__escape($__method['instructions'] ?? '') ?></span>
                                            <span class="cc-checkout__choice-badge"><?= $__isManual ? 'Proof required' : 'No proof needed' ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="cc-checkout__hint" id="methodHint" role="status" aria-live="polite">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 11v5m0-8h.01"></path></svg>
                                <span id="methodHintText">Choose a payment method to see what you’ll need.</span>
                            </div>

                            <div class="cc-checkout__proof" id="payment-proof-section"<?= $__manualSelected ? '' : ' hidden' ?>>
                                <h3 class="cc-checkout__proof-title">Payment proof</h3>
                                <p class="cc-checkout__proof-intro">Pay the exact total first, then provide the transaction reference and a clear screenshot showing the amount and reference.</p>

                                <div class="cc-checkout__field">
                                    <label class="cc-checkout__label" for="reference_number" id="referenceLabel"><?= $__escape($__methodMeta[$__selectedMethod]['label'] ?? 'Reference Number') ?> <span class="cc-checkout__required">*</span></label>
                                    <input class="cc-checkout__input" type="text" name="reference_number" id="reference_number" value="<?= $__escape($__old['reference_number'] ?? '') ?>" placeholder="<?= $__escape($__methodMeta[$__selectedMethod]['placeholder'] ?? 'Enter transaction reference') ?>" autocomplete="off" maxlength="32" aria-describedby="referenceHint"<?= $__manualSelected ? ' required' : ' disabled' ?>>
                                    <small class="cc-checkout__help" id="referenceHint"><?= $__escape($__methodMeta[$__selectedMethod]['hint'] ?? 'Each reference can be used only once.') ?></small>
                                </div>

                                <div class="cc-checkout__field" style="margin-top:14px">
                                    <label class="cc-checkout__label" for="proof_image">Payment screenshot <span class="cc-checkout__required">*</span></label>
                                    <input class="cc-checkout__file" type="file" name="proof_image" id="proof_image" accept="image/jpeg,image/png,image/gif,image/webp" aria-describedby="proofHelp fileError"<?= $__manualSelected ? ' required' : ' disabled' ?>>
                                    <small class="cc-checkout__help" id="proofHelp">JPG, PNG, GIF or WebP only, max 5MB. Must show amount + reference.</small>
                                    <div id="fileError" class="cc-checkout__file-error" role="alert" hidden></div>
                                    <div id="image-preview" hidden>
                                        <img id="preview-img" class="cc-checkout__proof-preview" alt="Preview of the selected payment screenshot">
                                        <small class="cc-checkout__file-meta" id="fileMeta"></small>
                                    </div>
                                </div>

                                <div class="cc-checkout__consent">
                                    <input type="checkbox" name="accept_policy" id="accept_policy" value="1"<?= !empty($__old['accept_policy']) ? ' checked' : '' ?><?= $__manualConsentRequired ? ' required' : '' ?><?= $__manualConsentRequired ? '' : ' disabled' ?>>
                                    <label for="accept_policy">I understand manual payments are verified <strong>within 24 hours</strong>, and <strong>cannot be self-cancelled</strong> once proof is submitted. Approved cancellations are refunded to the original channel.</label>
                                </div>
                            </div>
                        </div>
                    </section>

                    <details class="cc-checkout__policy" id="payPolicyAccordion">
                        <summary>Payment policies &amp; procedures</summary>
                        <div class="cc-checkout__policy-body">
                            <?php if ($__policies): ?>
                                <ul>
                                    <?php foreach ($__policies as $__policyKey => $__policyText): ?>
                                        <li><strong><?= $__escape(ucwords(str_replace('_', ' ', (string) $__policyKey))) ?>:</strong> <?= $__escape($__policyText) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <p>Cash orders: pay on delivery/pickup, cancellable while pending/confirmed. E-payments: pay exact total first, keep your reference until verified.</p>
                        </div>
                    </details>

                    <div class="cc-checkout__actions">
                        <a class="cc-checkout__back-inline" href="/cart">Back to cart</a>
                        <button type="submit" class="cc-checkout__place-order" id="placeOrderBtn">
                            <span id="btnText">Place order</span>
                            <span class="cc-checkout__spinner" id="submitSpinner" aria-hidden="true" hidden style="width:17px;height:17px;margin:0;border-width:2px"></span>
                            <svg id="submitCheckIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m5 12 4 4L19 6"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <aside class="cc-checkout__aside" aria-label="Order total and payment information">
                <section class="cc-checkout__aside-card" aria-labelledby="checkout-summary-title">
                    <h2 class="cc-checkout__aside-title" id="checkout-summary-title">Order total</h2>
                    <div class="cc-checkout__summary-row"><span>Subtotal</span><strong>₱<span id="sumSubtotal"><?= $__money($__baseSubtotal) ?></span></strong></div>
                    <div class="cc-checkout__summary-row cc-checkout__summary-discount" id="discountRow"<?= $__selectedDiscount !== 'none' ? '' : ' hidden' ?>><span><?= $__escape($__selectedDiscountLabel) ?> (20%)</span><strong>−₱<span id="sumDiscount"><?= $__money($__discountAmount) ?></span></strong></div>
                    <span id="discountTag" hidden><?= $__escape($__selectedDiscountLabel) ?></span>
                    <div class="cc-checkout__summary-row"><span id="taxLabel"><?= $__escape($__taxLabel) ?></span><strong>₱<span id="sumTax"><?= $__money($__taxAmount) ?></span></strong></div>
                    <div class="cc-checkout__summary-total"><span>Total</span><strong>₱<span id="sumTotal"><?= $__money($__totalAmount) ?></span></strong></div>
                    <p class="cc-checkout__help">The total updates when you change the discount selection.</p>
                </section>

                <section class="cc-checkout__aside-card" aria-labelledby="payment-information-title">
                    <h2 class="cc-checkout__aside-title" id="payment-information-title">Payment information</h2>
                    <p class="cc-checkout__help">Pay the exact total. Manual e-payments are verified within 24 hours.</p>
                    <?php foreach ($__pm as $__key => $__method): ?>
                        <?php
                        $__method = is_array($__method) ? $__method : ['label' => (string) $__method];
                        $__keyString = (string) $__key;
                        $__methodSlug = preg_replace('/[^A-Za-z0-9_-]/', '-', $__keyString) ?: 'method';
                        $__accountId = 'acct_' . $__methodSlug . '_' . (string) array_search($__key, array_keys($__pm), true);
                        ?>
                        <div class="cc-checkout__account">
                            <h3 class="cc-checkout__account-name"><?= $__escape($__method['label'] ?? $__keyString) ?></h3>
                            <?php if (!empty($__method['account_number'])): ?>
                                <p class="cc-checkout__account-line"><span>Number</span><span class="cc-checkout__account-number" id="<?= $__escape($__accountId) ?>"><?= $__escape($__method['account_number']) ?></span></p>
                                <p class="cc-checkout__account-line" style="margin-top:5px"><span>Account name</span><strong><?= $__escape($__method['account_name'] ?? '') ?></strong></p>
                                <button type="button" class="cc-checkout__copy" data-copy="#<?= $__escape($__accountId) ?>" aria-label="Copy <?= $__escape($__method['label'] ?? $__keyString) ?> account number">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="8" y="8" width="12" height="12" rx="1"></rect><path d="M16 8V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h3"></path></svg>
                                    <span>Copy number</span>
                                </button>
                                <span class="cc-checkout__copy-status" aria-live="polite"></span>
                            <?php else: ?>
                                <p class="cc-checkout__account-instructions"><?= $__escape($__method['instructions'] ?? '') ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <p class="cc-checkout__policy-note"><strong>Cancellation &amp; refund:</strong> E-payment orders (GCash / Maya / Bank Transfer) cannot be self-cancelled once proof is submitted. Contact staff; approved cancellations are refunded to the original channel. Cash orders may be cancelled while pending/confirmed.</p>
                </section>
            </aside>
        </div>
    </div>
</div>

<div id="loadingOverlay" class="cc-checkout__overlay" role="status" aria-live="polite" aria-hidden="true" hidden>
    <div class="cc-checkout__overlay-card">
        <div class="cc-checkout__spinner" aria-hidden="true"></div>
        <h2 class="cc-checkout__overlay-title">Processing your order</h2>
        <p class="cc-checkout__overlay-text">Please wait while we process your order. Don’t close this page.</p>
    </div>
</div>

<script>
const METHOD_META = <?= $__json($__methodMeta) ?>;
const BASE_SUBTOTAL = <?= $__json(round($__baseSubtotal, 2)) ?>;
const DISC_RATE = <?= $__json($__rate) ?>;
const AUTO_DISCOUNT = <?= $__json($__autoDiscount) ?>;
const DISC_LABELS = <?= $__json($__discountLabels) ?>;
const DISC_ID_LABELS = <?= $__json($__discountIdLabels) ?>;
const DISC_ID_HINTS = <?= $__json($__discountIdHints) ?>;
let checkoutSubmitting = false;

function selectedPaymentMethod() {
    const selected = document.querySelector('input[name="payment_method"]:checked');
    return selected ? selected.value.toLowerCase() : '';
}

function selectedDiscountType() {
    const selected = document.querySelector('input[name="discount_type"]:checked');
    return selected ? selected.value : 'none';
}

function peso(value) {
    const number = Number(value);
    return (Number.isFinite(number) ? number : 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function updateDiscountTotals() {
    const selected = selectedDiscountType();
    const hasDiscount = selected !== 'none';
    const rate = Number(DISC_RATE) || 0;
    const discountAmount = hasDiscount ? Math.round(BASE_SUBTOTAL * rate * 100) / 100 : 0;
    const taxAmount = hasDiscount ? 0 : Math.round(BASE_SUBTOTAL * 0.12 * 100) / 100;
    const totalAmount = hasDiscount
        ? Math.round((BASE_SUBTOTAL - discountAmount) * 100) / 100
        : Math.round((BASE_SUBTOTAL + taxAmount) * 100) / 100;

    document.querySelectorAll('.discount-option').forEach(function (element) {
        element.classList.toggle('selected', element.dataset.discount === selected);
    });

    const discountRow = document.getElementById('discountRow');
    if (discountRow) discountRow.hidden = !hasDiscount;
    const discountValue = document.getElementById('sumDiscount');
    if (discountValue) discountValue.textContent = peso(discountAmount);
    const taxValue = document.getElementById('sumTax');
    if (taxValue) taxValue.textContent = peso(taxAmount);
    const totalValue = document.getElementById('sumTotal');
    if (totalValue) totalValue.textContent = peso(totalAmount);
    const taxLabel = document.getElementById('taxLabel');
    if (taxLabel) taxLabel.textContent = hasDiscount ? 'VAT (exempt)' : 'Tax (12%)';

    const label = DISC_LABELS[selected] || selected;
    const discountRowLabel = discountRow ? discountRow.querySelector('span:first-child') : null;
    if (discountRowLabel) discountRowLabel.textContent = label + ' (20%)';
    const discountTag = document.getElementById('discountTag');
    if (discountTag) discountTag.textContent = hasDiscount ? label : '';

    const idBlock = document.getElementById('discount-id-block');
    if (idBlock) idBlock.hidden = !hasDiscount;
    const idInput = document.getElementById('discount_id_no');
    const idLabel = document.getElementById('discountIdLabel');
    const idHint = document.getElementById('discountIdHint');
    if (idInput) {
        idInput.required = hasDiscount;
        if (hasDiscount) {
            idInput.disabled = false;
            if (idLabel) idLabel.textContent = DISC_ID_LABELS[selected] || 'ID Number';
            if (idHint) idHint.textContent = DISC_ID_HINTS[selected] || 'ID number is verified by staff.';
        } else {
            idInput.disabled = false;
        }
    }
    const attestationBlock = document.getElementById('discount-attest-block');
    const attestation = document.getElementById('accept_discount_terms');
    const needsAttestation = hasDiscount && selected !== AUTO_DISCOUNT;
    if (attestationBlock) attestationBlock.hidden = !needsAttestation;
    if (attestation) {
        attestation.required = needsAttestation;
        attestation.disabled = !needsAttestation;
    }
}

function togglePaymentProofSection() {
    const selected = document.querySelector('input[name="payment_method"]:checked');
    const method = selected ? selected.value.toLowerCase() : '';
    const metadata = METHOD_META[method] || {};
    const isManual = Boolean(selected && selected.dataset.manual === '1');
    const proofSection = document.getElementById('payment-proof-section');
    const reference = document.getElementById('reference_number');
    const proof = document.getElementById('proof_image');
    const consent = document.getElementById('accept_policy');

    document.querySelectorAll('.payment-option').forEach(function (element) {
        element.classList.toggle('selected', element.dataset.method === method);
    });

    proofSection.hidden = !isManual;
    reference.required = isManual;
    reference.disabled = !isManual;
    proof.required = isManual;
    proof.disabled = !isManual;
    consent.required = isManual;
    consent.disabled = !isManual;
    document.getElementById('referenceLabel').textContent = (metadata.label || 'Reference Number') + (isManual ? ' *' : '');
    document.getElementById('referenceHint').textContent = metadata.hint || (isManual ? 'Each reference can be used only once.' : '');
    reference.placeholder = metadata.placeholder || (isManual ? 'Enter transaction reference' : '');
    document.getElementById('methodHintText').textContent = isManual
        ? 'Pay the exact total first, then enter your reference and upload the screenshot. Manual payment is verified within 24 hours.'
        : (selected ? 'Cash selected: no payment screenshot or reference is needed. Pay on delivery/pickup.' : 'Choose a payment method to see what you’ll need.');
}

async function copyAccountNumber(button) {
    const selector = button.getAttribute('data-copy');
    const account = selector ? document.querySelector(selector) : null;
    const status = button.parentElement.querySelector('.cc-checkout__copy-status');
    const text = account ? account.textContent.trim() : '';
    if (!text) return;

    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(text);
        } else {
            const temporary = document.createElement('textarea');
            temporary.value = text;
            temporary.setAttribute('readonly', '');
            temporary.style.position = 'fixed';
            temporary.style.opacity = '0';
            document.body.appendChild(temporary);
            temporary.select();
            const copied = document.execCommand('copy');
            temporary.remove();
            if (!copied) throw new Error('Clipboard copy failed.');
        }
        button.querySelector('span').textContent = 'Copied';
        if (status) status.textContent = 'Account number copied.';
        window.setTimeout(function () {
            button.querySelector('span').textContent = 'Copy number';
            if (status) status.textContent = '';
        }, 1600);
    } catch (error) {
        console.error('Unable to copy account number:', error);
        if (status) status.textContent = 'Copy unavailable. Please select and copy the number.';
    }
}

function handleProofFile(file) {
    const input = document.getElementById('proof_image');
    const preview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-img');
    const fileMeta = document.getElementById('fileMeta');
    const error = document.getElementById('fileError');
    error.hidden = true;
    error.textContent = '';
    input.classList.remove('is-invalid');
    if (!file) {
        preview.hidden = true;
        previewImage.removeAttribute('src');
        fileMeta.textContent = '';
        return;
    }

    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        error.textContent = 'Invalid file type. Use JPG, PNG, GIF or WebP.';
    } else if (file.size > 5 * 1024 * 1024) {
        error.textContent = 'This file is too large. The maximum size is 5MB.';
    }
    if (error.textContent) {
        error.hidden = false;
        input.classList.add('is-invalid');
        input.value = '';
        preview.hidden = true;
        previewImage.removeAttribute('src');
        fileMeta.textContent = '';
        return;
    }

    fileMeta.textContent = file.name + ' (' + Math.max(1, Math.round(file.size / 1024)) + ' KB)';
    const reader = new FileReader();
    reader.onload = function (event) {
        previewImage.src = event.target.result;
        preview.hidden = false;
    };
    reader.onerror = function () {
        error.textContent = 'We couldn’t preview this file. You can still select another image.';
        error.hidden = false;
        preview.hidden = true;
    };
    reader.readAsDataURL(file);
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[name="payment_method"]').forEach(function (input) {
        input.addEventListener('change', togglePaymentProofSection);
    });
    document.querySelectorAll('input[name="discount_type"]').forEach(function (input) {
        input.addEventListener('change', updateDiscountTotals);
    });
    document.querySelectorAll('[data-copy]').forEach(function (button) {
        button.addEventListener('click', function () { copyAccountNumber(button); });
    });
    const proofInput = document.getElementById('proof_image');
    if (proofInput) {
        proofInput.addEventListener('change', function () {
            handleProofFile(proofInput.files && proofInput.files.length ? proofInput.files[0] : null);
        });
    }
    togglePaymentProofSection();
    updateDiscountTotals();
});

document.getElementById('checkoutForm').addEventListener('submit', function (event) {
    if (checkoutSubmitting) {
        event.preventDefault();
        return;
    }

    const method = document.querySelector('input[name="payment_method"]:checked');
    if (method && method.dataset.manual === '1') {
        const reference = document.getElementById('reference_number');
        const proof = document.getElementById('proof_image');
        const consent = document.getElementById('accept_policy');
        if (!reference.value.trim() || !proof.files.length || !consent.checked) {
            event.preventDefault();
            if (!reference.value.trim()) reference.focus();
            else if (!proof.files.length) proof.focus();
            else consent.focus();
            return;
        }
    }

    const discount = selectedDiscountType();
    if (discount !== 'none' && discount !== AUTO_DISCOUNT) {
        const idNumber = document.getElementById('discount_id_no');
        const attestation = document.getElementById('accept_discount_terms');
        if (!idNumber.value.trim() || !attestation.checked) {
            event.preventDefault();
            if (!idNumber.value.trim()) idNumber.focus();
            else attestation.focus();
            return;
        }
    }

    checkoutSubmitting = true;
    const submitButton = document.getElementById('placeOrderBtn');
    submitButton.disabled = true;
    document.getElementById('btnText').textContent = 'Processing…';
    document.getElementById('submitSpinner').hidden = false;
    document.getElementById('submitCheckIcon').hidden = true;
    const overlay = document.getElementById('loadingOverlay');
    overlay.hidden = false;
    overlay.setAttribute('aria-hidden', 'false');
});
</script>

<?php
$content = ob_get_clean();
$title = $title ?? 'Checkout - CafeCat';
include __DIR__ . '/../layouts/main.php';
?>

