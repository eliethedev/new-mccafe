# Checkout System Setup Guide

## Overview
The MacCafe checkout system includes:
- Email verification for user registration
- Manual payment system (GCash, PayMaya, Bank Transfer)
- Payment proof upload functionality
- Complete order management

## Setup Instructions

### 1. Database Setup
Run the updated database schema:
```sql
-- Execute the schema.sql file to add new tables:
-- - email_verifications
-- - payment_proofs
-- Updated orders table with new payment methods
```

### 2. Email Configuration
Update `config/config.php` with your SMTP settings:
```php
'email' => [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'your-email@gmail.com',
    'password' => 'your-app-password', // Use app password for Gmail
    'from_name' => 'MacCafe Ordering System',
    'from_email' => 'your-email@gmail.com',
],
```

### 3. Payment QR Codes
Add QR code images to `public/assets/images/`:
- `gcash-qr.png` - Your GCash QR code
- `paymaya-qr.png` - Your PayMaya QR code

### 4. Payment Details
Update payment information in checkout views:
- GCash number: `views/checkout/index.php` (line ~120)
- PayMaya number: `views/checkout/index.php` (line ~125)
- Bank details: `views/checkout/index.php` (line ~130)
- Payment page details: `views/checkout/payment.php` (lines ~30-60)

## Testing the Checkout Flow

### 1. User Registration & Email Verification
1. Go to `/register`
2. Fill registration form
3. Check email for verification link
4. Click verification link
5. Try to login (should work now)

### 2. Add Items to Cart
1. Browse menu at `/menu`
2. Add items to cart
3. Go to cart at `/cart`
4. Verify cart items are displayed

### 3. Checkout Process
1. Click "Proceed to Checkout" from cart
2. Should be redirected to `/checkout`
3. Fill customer information
4. Select payment method
5. Place order

### 4. Payment Proof Upload (for manual payments)
1. After placing order, redirect to payment page
2. Upload payment screenshot
3. Enter reference number
4. Submit payment proof

## Key Features

### Email Verification
- Users must verify email before placing orders
- 24-hour token expiry
- Resend verification functionality
- Automatic email sending

### Manual Payment System
- GCash payments with QR code
- PayMaya payments with QR code
- Bank transfer with account details
- Cash on pickup option

### Payment Proof Upload
- Image upload validation (JPG/PNG, max 5MB)
- Reference number tracking
- Admin verification workflow
- Payment status management

### Security Features
- Authentication required for checkout
- File upload security
- Input validation
- CSRF protection

## Troubleshooting

### Common Issues

1. **"Controller class not found: CheckoutController"**
   - Fixed: Added CheckoutController to `public/index.php` autoloader

2. **Email not sending**
   - Check SMTP credentials in `config/config.php`
   - Use app password for Gmail (not regular password)
   - Ensure PHPMailer is installed (`composer require phpmailer/phpmailer`)

3. **Payment proof upload fails**
   - Check `payment-proofs` directory permissions
   - Verify file size and type restrictions
   - Ensure PHP upload limits are sufficient

4. **Database errors**
   - Run updated schema.sql
   - Check database connection in `config/database.php`

### File Structure
```
├── controllers/
│   └── CheckoutController.php
├── models/
│   ├── OrderItem.php
│   ├── OrderStatusHistory.php
│   └── PaymentProof.php
├── views/checkout/
│   ├── index.php
│   └── payment.php
├── core/
│   └── Email.php
└── public/assets/images/
    ├── gcash-qr.png (add your QR code)
    ├── paymaya-qr.png (add your QR code)
    └── payment-proofs/ (auto-created)
```

## Next Steps

1. Set up actual payment QR codes
2. Configure email settings
3. Test complete checkout flow
4. Set up admin payment verification interface
5. Add order status notifications

The checkout system is now fully functional and ready for testing!
