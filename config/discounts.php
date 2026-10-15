<?php
// Senior Citizen & PWD discount policy (RA 9994 / RA 10754).
// 20% off the subtotal + VAT exemption. Non-combinable: one type per order.

return [
    'rate' => 0.20,
    'vat_rate' => 0.12,
    'types' => [
        'none' => ['label' => 'No discount', 'id_label' => null],
        'senior' => [
            'label' => 'Senior Citizen (20% off)',
            'id_label' => 'Senior Citizen ID No.',
            'id_hint' => 'OSCA-issued Senior Citizen ID number.',
        ],
        'pwd' => [
            'label' => 'PWD (20% off)',
            'id_label' => 'PWD ID No.',
            'id_hint' => 'PWD ID number issued by the city/municipal PDAO.',
        ],
    ],

    'policies' => [
        'coverage' => 'Senior citizens and persons with disabilities get 20% off the subtotal and are exempt from the 12% VAT on their order.',
        'one_per_order' => 'Only one discount type (Senior or PWD) may be applied per order; discounts cannot be combined or split across beneficiaries.',
        'verification' => 'Manual claims require a valid ID number and are marked pending until staff verify the physical ID at pickup/delivery. Misuse may void the order.',
        'automatic' => 'Customers whose Senior/PWD status was verified on their profile get the discount applied automatically at checkout.',
        'rejection' => 'If staff reject a discount claim, the order total reverts to the regular price (subtotal + 12% VAT) and the difference is collected on pickup/delivery.',
    ],

    'security' => [
        // ID number: letters/digits/dashes, 4-32 chars (covers OSCA + PDAO formats).
        'id_pattern' => '/^[A-Za-z0-9-]{4,32}$/',
    ],
];
