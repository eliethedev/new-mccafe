<?php

// Use Composer autoloader instead of manual requires
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        
        // Get email config
        $config = require __DIR__ . '/../config/config.php';
        $emailConfig = $config['email'];
        
        // Server settings
        $this->mailer->isSMTP();
        $this->mailer->Host       = $emailConfig['host'];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $emailConfig['username'];
        $this->mailer->Password   = $emailConfig['password'];
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = $emailConfig['port'];
        // Fail fast: notification emails must never hang a request.
        $this->mailer->Timeout = 10;
        
        // Recipients
        $this->mailer->setFrom($emailConfig['from_email'], $emailConfig['from_name']);
        
        // Content
        $this->mailer->isHTML(true);
        $this->mailer->CharSet = 'UTF-8';
    }
    
    public function sendVerificationEmail($email, $name, $code) {
        try {
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = 'Your CafeCat Verification Code';

            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Welcome to CafeCat!</h2>
                    <p>Hi {$name},</p>
                    <p>Thank you for registering with CafeCat Ordering System. Enter this 6-digit code to verify your email address:</p>

                    <div style='text-align: center; margin: 30px 0;'>
                        <span style='font-size: 36px; font-weight: bold; letter-spacing: 12px; background: #f4f4f4; border: 1px solid #ddd; border-radius: 8px; padding: 12px 20px 12px 32px; display: inline-block;'>{$code}</span>
                    </div>

                    <p><strong>Note:</strong> This code expires in 24 hours. After 5 incorrect attempts you will need to request a new one.</p>

                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>If you didn't create an account with CafeCat, you can safely ignore this email.</p>
                </div>
            ";

            $this->mailer->AltBody = "
                Welcome to CafeCat!

                Hi {$name},

                Your CafeCat email verification code is: {$code}

                Enter it on the verification page within 24 hours.

                If you didn't create an account with CafeCat, you can safely ignore this email.
            ";

            return $this->mailer->send();

        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            return false;
        }
    }

    public function sendPasswordResetCode($email, $name, $code) {
        try {
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = 'Your CafeCat Password Reset Code';

            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Password Reset Request</h2>
                    <p>Hi {$name},</p>
                    <p>We received a request to reset your CafeCat password. Enter this 6-digit code to continue:</p>

                    <div style='text-align: center; margin: 30px 0;'>
                        <span style='font-size: 36px; font-weight: bold; letter-spacing: 12px; background: #f4f4f4; border: 1px solid #ddd; border-radius: 8px; padding: 12px 20px 12px 32px; display: inline-block;'>{$code}</span>
                    </div>

                    <p><strong>Note:</strong> This code expires in 1 hour. After 5 incorrect attempts you will need to request a new one.</p>

                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>If you didn't request a password reset, you can safely ignore this email.</p>
                </div>
            ";

            $this->mailer->AltBody = "
                Password Reset Request

                Hi {$name},

                Your CafeCat password reset code is: {$code}

                Enter it on the reset page within 1 hour.

                If you didn't request a password reset, you can safely ignore this email.
            ";

            return $this->mailer->send();

        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function sendOrderConfirmation($email, $name, $orderData) {
        try {
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = "Order Confirmation - CafeCat Order #{$orderData['order_number']}";
            $config = require __DIR__ . '/../config/config.php';
            $orderUrl = rtrim($config['app']['url'], '/') . '/orders/' . ($orderData['id'] ?? '');
            $orderLink = !empty($orderData['id'])
                ? "<p><a href='{$orderUrl}'>View your order</a></p>"
                : "";
            
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #8B4513;'>Order Confirmed!</h2>
                    <p>Hi {$name},</p>
                    <p>Thank you for your order! We've received your order and it's now being processed.</p>
                    
                    <div style='background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                        <h3>Order Details</h3>
                        <p><strong>Order Number:</strong> {$orderData['order_number']}</p>
                        <p><strong>Total Amount:</strong> ₱" . number_format($orderData['total_amount'], 2) . "</p>
                        <p><strong>Payment Method:</strong> " . ucfirst($orderData['payment_method']) . "</p>" . (!empty($orderData['discount_type']) && $orderData['discount_type'] !== 'none' ? "<p><strong>Discount:</strong> " . ucfirst($orderData['discount_type']) . " 20% (−₱" . number_format($orderData['discount_amount'] ?? 0, 2) . ")</p>" : "") . "
                        <p><strong>Status:</strong> " . ucfirst($orderData['status']) . "</p>
                    </div>
                    
                    <h3>Next Steps:</h3>
                    <ol>
                        <li>If you chose manual payment, please upload your payment proof.</li>
                        <li>We'll verify your payment within 24 hours.</li>
                        <li>Once verified, we'll start preparing your order.</li>
                        <li>You'll receive another notification when your order is ready.</li>
                    </ol>
                    
                    <p>You can track your order status by logging into your account.</p>
                    {$orderLink}
                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>Thank you for choosing CafeCat!</p>
                </div>
            ";
            
            $this->mailer->AltBody = "
                Order Confirmed!
                
                Hi {$name},
                
                Thank you for your order! We've received your order and it's now being processed.
                
                Order Number: {$orderData['order_number']}
                Total Amount: ₱" . number_format($orderData['total_amount'], 2) . "
                Payment Method: " . ucfirst($orderData['payment_method']) . "
                " . (!empty($orderData['discount_type']) && $orderData['discount_type'] !== 'none' ? "Discount: " . ucfirst($orderData['discount_type']) . " 20% (-P" . number_format($orderData['discount_amount'] ?? 0, 2) . ")\n                " : "") . "Status: " . ucfirst($orderData['status']) . "
                
                Next Steps:
                1. If you chose manual payment, please upload your payment proof.
                2. We'll verify your payment within 24 hours.
                3. Once verified, we'll start preparing your order.
                4. You'll receive another notification when your order is ready.
                
                You can track your order status by logging into your account.
                
                Thank you for choosing CafeCat!
            ";
            
            return $this->mailer->send();
            
        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            return false;
        }
    }

    /** Absolute "view order" URL + link HTML for customer emails (empty when no id). */
    private function orderLink($order) {
        if (empty($order['id'])) {
            return '';
        }
        $config = require __DIR__ . '/../config/config.php';
        $url = rtrim($config['app']['url'], '/') . '/orders/' . $order['id'];
        return "<p><a href='{$url}'>View your order</a></p>";
    }

    /**
     * Customer notice: order is ready for pickup/delivery.
     * $order needs: order_number, total_amount, customer_email, customer_name.
     */
    public function sendOrderReady($order) {        try {
            $email = $order['customer_email'] ?? null;
            $name = $order['customer_name'] ?? 'Customer';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = "Your order {$order['order_number']} is ready! - CafeCat";
            $amount = number_format($order['total_amount'] ?? 0, 2);
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Your order is ready!</h2>
                    <p>Hi {$name},</p>
                    <p>Good news — order <strong>#{$order['order_number']}</strong> (₱{$amount}) is ready for pickup/delivery.</p>
                    <p>Please present your order number at the counter. Thank you for choosing CafeCat!</p>
                    {$this->orderLink($order)}
                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>caf&eacute;cat — crafted for your cravings.</p>
                </div>
            ";
            $this->mailer->AltBody = "Hi {$name}, order #{$order['order_number']} (P{$amount}) is ready for pickup/delivery. Thank you for choosing CafeCat!";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Ready email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Customer receipt: order delivered/completed.
     */
    public function sendOrderDelivered($order) {
        try {
            $email = $order['customer_email'] ?? null;
            $name = $order['customer_name'] ?? 'Customer';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = "Order {$order['order_number']} delivered - Thank you! - CafeCat";
            $amount = number_format($order['total_amount'] ?? 0, 2);
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Delivered — enjoy!</h2>
                    <p>Hi {$name},</p>
                    <p>Order <strong>#{$order['order_number']}</strong> (₱{$amount}) has been marked as delivered. We hope you enjoyed it!</p>
                    <p>Craving more? Order again anytime.</p>
                    {$this->orderLink($order)}
                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>caf&eacute;cat — crafted for your cravings.</p>
                </div>
            ";
            $this->mailer->AltBody = "Hi {$name}, order #{$order['order_number']} (P{$amount}) was delivered. We hope you enjoyed it! Thank you for choosing CafeCat!";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Delivered email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Staff notice: an order was just delivered/completed.
     */
    public function sendAdminDeliveredNotice($adminEmail, $adminName, $order) {
        try {
            if (empty($adminEmail) || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $this->mailer->addAddress($adminEmail, $adminName);
            $this->mailer->Subject = "Delivered: order {$order['order_number']} - CafeCat";
            $amount = number_format($order['total_amount'] ?? 0, 2);
            $customer = $order['customer_name'] ?? 'customer';
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Order delivered</h2>
                    <p>Hi {$adminName},</p>
                    <p>Order <strong>#{$order['order_number']}</strong> ({$customer}, ₱{$amount}) was just marked as completed/delivered.</p>
                </div>
            ";
            $this->mailer->AltBody = "Order #{$order['order_number']} ({$customer}, P{$amount}) was delivered.";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Admin delivered email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Customer notice: a refund was processed for their order.
     * $refund needs: refund_amount, refund_method.
     */
    public function sendRefundProcessed($order, $refund) {
        try {
            $email = $order['customer_email'] ?? null;
            $name = $order['customer_name'] ?? 'Customer';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $amount = number_format($refund['refund_amount'] ?? 0, 2);
            $method = ucfirst($refund['refund_method'] ?? 'original payment channel');
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = "Refund of ₱{$amount} processed for order {$order['order_number']} - CafeCat";
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Refund processed</h2>
                    <p>Hi {$name},</p>
                    <p>A refund of <strong>₱{$amount}</strong> via {$method} has been processed for order <strong>#{$order['order_number']}</strong>.</p>
                    <p>Please allow time for it to reflect in your account. Contact us if it hasn't arrived within a few business days.</p>
                    {$this->orderLink($order)}
                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>caf&eacute;cat — crafted for your cravings.</p>
                </div>
            ";
            $this->mailer->AltBody = "Hi {$name}, a refund of P{$amount} via {$method} was processed for order #{$order['order_number']}. Please allow time for it to reflect in your account.";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Refund email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Customer notice: a refund's status changed (e.g. pending → processed).
     */
    public function sendRefundStatusUpdate($order, $refund, $oldStatus, $newStatus) {
        try {
            $email = $order['customer_email'] ?? null;
            $name = $order['customer_name'] ?? 'Customer';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $amount = number_format($refund['refund_amount'] ?? 0, 2);
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = "Refund update for order {$order['order_number']} ({$newStatus}) - CafeCat";
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Refund status update</h2>
                    <p>Hi {$name},</p>
                    <p>Your refund of <strong>₱{$amount}</strong> for order <strong>#{$order['order_number']}</strong> changed from <strong>{$oldStatus}</strong> to <strong>{$newStatus}</strong>.</p>
                    <p>Please contact us if you have any questions.</p>
                    {$this->orderLink($order)}
                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>caf&eacute;cat — crafted for your cravings.</p>
                </div>
            ";
            $this->mailer->AltBody = "Hi {$name}, your refund of P{$amount} for order #{$order['order_number']} changed from {$oldStatus} to {$newStatus}.";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Refund status email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Staff notice: a refund was processed or its status changed.
     */
    public function sendAdminRefundNotice($adminEmail, $adminName, $order, $refund, $context = 'processed') {
        try {
            if (empty($adminEmail) || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $amount = number_format($refund['refund_amount'] ?? 0, 2);
            $method = ucfirst($refund['refund_method'] ?? '');
            $customer = $order['customer_name'] ?? 'customer';
            $this->mailer->addAddress($adminEmail, $adminName);
            $this->mailer->Subject = "Refund {$context}: order {$order['order_number']} - CafeCat";
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Refund {$context}</h2>
                    <p>Hi {$adminName},</p>
                    <p>Refund of <strong>₱{$amount}</strong> via {$method} for order <strong>#{$order['order_number']}</strong> ({$customer}) was {$context}.</p>
                </div>
            ";
            $this->mailer->AltBody = "Refund of P{$amount} for order #{$order['order_number']} ({$customer}) was {$context}.";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Admin refund email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rider notice: a new delivery was assigned to them.
     */
    public function sendRiderAssignment($riderEmail, $riderName, $order) {
        try {
            if (empty($riderEmail) || !filter_var($riderEmail, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $amount = number_format($order['total_amount'] ?? 0, 2);
            $addr = $order['customer_address'] ?? 'see order details';
            $customer = $order['customer_name'] ?? 'customer';
            $phone = $order['customer_phone'] ?? '';
            $this->mailer->addAddress($riderEmail, $riderName);
            $this->mailer->Subject = "New delivery assigned: order {$order['order_number']} - CafeCat";
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>New delivery assignment</h2>
                    <p>Hi {$riderName},</p>
                    <p>Order <strong>#{$order['order_number']}</strong> (₱{$amount}) was assigned to you.</p>
                    <p><strong>Deliver to:</strong> {$customer}<br>{$addr}<br>{$phone}</p>
                    <p>Please log in and accept the assignment.</p>
                </div>
            ";
            $this->mailer->AltBody = "Hi {$riderName}, order #{$order['order_number']} (P{$amount}) was assigned to you. Deliver to {$customer}, {$addr} {$phone}. Please accept it in the rider workspace.";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Rider assignment email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Customer notice: rider status updates (assigned / on the way / problem).
     */
    public function sendCustomerDeliveryUpdate($order, $headline, $detail) {
        try {
            $email = $order['customer_email'] ?? null;
            $name = $order['customer_name'] ?? 'Customer';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = "{$headline} (order {$order['order_number']}) - CafeCat";
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>{$headline}</h2>
                    <p>Hi {$name},</p>
                    <p>{$detail}</p>
                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>caf&eacute;cat — crafted for your cravings.</p>
                </div>
            ";
            $this->mailer->AltBody = "Hi {$name}, {$detail}";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Customer delivery email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Staff notice: a delivery's status changed.
     */
    public function sendAdminDeliveryUpdate($adminEmail, $adminName, $order, $deliveryStatus, $riderName = '') {
        try {
            if (empty($adminEmail) || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $who = trim($riderName) !== '' ? " by {$riderName}" : '';
            $this->mailer->addAddress($adminEmail, $adminName);
            $this->mailer->Subject = "Delivery {$deliveryStatus}: order {$order['order_number']} - CafeCat";
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Delivery update</h2>
                    <p>Hi {$adminName},</p>
                    <p>Order <strong>#{$order['order_number']}</strong> delivery status changed to <strong>{$deliveryStatus}</strong>{$who}.</p>
                </div>
            ";
            $this->mailer->AltBody = "Order #{$order['order_number']} delivery is now {$deliveryStatus}{$who}.";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Admin delivery email failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Substitution notice (suggested or decided) to any recipient.
     */
    public function sendSubstitutionNotice($email, $name, $order, $sub, $context) {
        try {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $orig = ($sub['original_name'] ?? 'item') . ' (P' . number_format($sub['original_total'] ?? 0, 2) . ')';
            $sugg = ($sub['suggested_name'] ?? 'replacement') . ' (P' . number_format($sub['suggested_total'] ?? 0, 2) . ')';
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = "Substitution {$context} for order {$order['order_number']} - CafeCat";
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #111111;'>Substitution {$context}</h2>
                    <p>Hi {$name},</p>
                    <p>For order <strong>#{$order['order_number']}</strong>: {$orig} is unavailable, suggested replacement is {$sugg}.</p>
                    <p>Status: <strong>{$context}</strong>.</p>
                </div>
            ";
            $this->mailer->AltBody = "Order #{$order['order_number']}: {$orig} unavailable, suggested {$sugg}. Status: {$context}.";
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Substitution email failed: " . $e->getMessage());
            return false;
        }
    }
}
