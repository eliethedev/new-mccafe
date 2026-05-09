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
        
        // Recipients
        $this->mailer->setFrom($emailConfig['from_email'], $emailConfig['from_name']);
        
        // Content
        $this->mailer->isHTML(true);
        $this->mailer->CharSet = 'UTF-8';
    }
    
    public function sendVerificationEmail($email, $name, $token) {
        try {
            $this->mailer->addAddress($email, $name);
            $this->mailer->Subject = 'Verify Your Email - McCafe';
            
            $config = require __DIR__ . '/../config/config.php';
$verificationUrl = $config['app']['url'] . "/verify-email?token=" . urlencode($token);
            
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #8B4513;'>Welcome to MCCafe!</h2>
                    <p>Hi {$name},</p>
                    <p>Thank you for registering with McCafe Ordering System. To complete your registration and start placing orders, please verify your email address.</p>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='{$verificationUrl}' style='background-color: #8B4513; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;'>Verify Email Address</a>
                    </div>
                    
                    <p>Or copy and paste this link in your browser:</p>
                    <p style='word-break: break-all; color: #666;'>{$verificationUrl}</p>
                    
                    <p><strong>Note:</strong> This verification link will expire in 24 hours.</p>
                    
                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>If you didn't create an account with McCafe, you can safely ignore this email.</p>
                </div>
            ";
            
            $this->mailer->AltBody = "
                Welcome to McCafe!
                
                Hi {$name},
                
                Thank you for registering with McCafe Ordering System. To complete your registration, please visit this link:
                
                {$verificationUrl}
                
                This verification link will expire in 24 hours.
                
                If you didn't create an account with McCafe, you can safely ignore this email.
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
            $this->mailer->Subject = "Order Confirmation - McCafe Order #{$orderData['order_number']}";
            
            $this->mailer->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #8B4513;'>Order Confirmed!</h2>
                    <p>Hi {$name},</p>
                    <p>Thank you for your order! We've received your order and it's now being processed.</p>
                    
                    <div style='background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                        <h3>Order Details</h3>
                        <p><strong>Order Number:</strong> {$orderData['order_number']}</p>
                        <p><strong>Total Amount:</strong> ₱" . number_format($orderData['total_amount'], 2) . "</p>
                        <p><strong>Payment Method:</strong> " . ucfirst($orderData['payment_method']) . "</p>
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
                    
                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <p style='color: #666; font-size: 14px;'>Thank you for choosing McCafe!</p>
                </div>
            ";
            
            $this->mailer->AltBody = "
                Order Confirmed!
                
                Hi {$name},
                
                Thank you for your order! We've received your order and it's now being processed.
                
                Order Number: {$orderData['order_number']}
                Total Amount: ₱" . number_format($orderData['total_amount'], 2) . "
                Payment Method: " . ucfirst($orderData['payment_method']) . "
                Status: " . ucfirst($orderData['status']) . "
                
                Next Steps:
                1. If you chose manual payment, please upload your payment proof.
                2. We'll verify your payment within 24 hours.
                3. Once verified, we'll start preparing your order.
                4. You'll receive another notification when your order is ready.
                
                You can track your order status by logging into your account.
                
                Thank you for choosing McCafe!
            ";
            
            return $this->mailer->send();
            
        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            return false;
        }
    }
}
