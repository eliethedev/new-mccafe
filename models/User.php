<?php

class User extends Model {
    protected $table = 'users';
    protected $primaryKey = 'id';
    
    public static function findByEmail($email) {
        $stmt = self::query("SELECT * FROM users WHERE email = ?", [$email]);
        return $stmt->fetch();
    }
    
    public static function create($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return parent::create($data);
    }
    
    public static function verifyPassword($email, $password) {
        $user = self::findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public static function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return self::update($userId, ['password' => $hashedPassword]);
    }
    
    public static function getProfile($userId) {
        $stmt = self::query(
            "SELECT id, first_name, last_name, email, phone, address, role, email_verified_at, created_at,
                    is_senior, is_pwd, discount_id_no, discount_id_image, discount_verified
             FROM users WHERE id = ?",
            [$userId]
        );
        return $stmt->fetch();
    }
    
    public static function getAllCustomers($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $stmt = self::query(
            "SELECT id, first_name, last_name, email, phone, created_at 
             FROM users WHERE role = 'customer' 
             ORDER BY created_at DESC 
             LIMIT ? OFFSET ?", 
            [$limit, $offset]
        );
        
        return $stmt->fetchAll();
    }
    
    public static function getCustomerCount() {
        $stmt = self::query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'");
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public static function getTotalCustomers() {
        $stmt = self::query("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }
    
    public static function recordLoginAttempt($email, $ipAddress, $userAgent, $success) {
        self::query(
            "INSERT INTO login_attempts (email, ip_address, user_agent, success) 
             VALUES (?, ?, ?, ?)",
            [$email, $ipAddress, $userAgent, $success]
        );
    }
    
    public static function getRecentLoginAttempts($email, $minutes = 15) {
        $stmt = self::query(
            "SELECT COUNT(*) as attempts FROM login_attempts 
             WHERE email = ? AND success = 0 AND created_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)",
            [$email, $minutes]
        );
        
        $result = $stmt->fetch();
        return $result['attempts'];
    }
    
    public static function isLockedOut($email) {
        $attempts = self::getRecentLoginAttempts($email);
        return $attempts >= MAX_LOGIN_ATTEMPTS;
    }
    
    /** Generate a cryptographically secure 6-digit code. */
    public static function generateCode() {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public static function createVerificationToken($userId) {
        $token = bin2hex(random_bytes(32));
        $code = self::generateCode();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Delete any existing tokens for this user
        Model::query("DELETE FROM email_verifications WHERE user_id = ?", [$userId]);

        // Insert new code (token column kept for legacy links)
        Model::query(
            "INSERT INTO email_verifications (user_id, token, code, expires_at, attempts) VALUES (?, ?, ?, ?, 0)",
            [$userId, $token, $code, $expiresAt]
        );

        return $code;
    }

    /**
     * Verify a 6-digit email verification code for the given email address.
     * Returns user id on success, or ['error' => message] on failure.
     */
    public static function verifyEmailByCode($email, $code) {
        $code = preg_replace('/\D/', '', (string)$code);
        if (strlen($code) !== 6) {
            return ['error' => 'Please enter the 6-digit code from your email.'];
        }

        $user = self::findByEmail($email);
        if (!$user) {
            return ['error' => 'No account found for that email address.'];
        }

        if (self::isEmailVerified($user['id'])) {
            return ['error' => 'This email address is already verified. You can log in.'];
        }

        $stmt = Model::query(
            "SELECT * FROM email_verifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1",
            [$user['id']]
        );
        $row = $stmt->fetch();

        if (!$row || strtotime($row['expires_at']) < time()) {
            if ($row) {
                Model::query("DELETE FROM email_verifications WHERE id = ?", [$row['id']]);
            }
            return ['error' => 'This code has expired. Please request a new one.'];
        }

        if ((int)($row['attempts'] ?? 0) >= 5) {
            Model::query("DELETE FROM email_verifications WHERE id = ?", [$row['id']]);
            return ['error' => 'Too many incorrect attempts. Please request a new code.'];
        }

        if (!hash_equals((string)($row['code'] ?? ''), $code)) {
            Model::query("UPDATE email_verifications SET attempts = attempts + 1 WHERE id = ?", [$row['id']]);
            $left = 5 - ((int)($row['attempts'] ?? 0) + 1);
            return ['error' => $left > 0
                ? "Incorrect code. You have $left attempt(s) left."
                : 'Too many incorrect attempts. Please request a new code.'];
        }

        // Mark email as verified
        Model::query(
            "UPDATE users SET email_verified_at = NOW() WHERE id = ?",
            [$user['id']]
        );

        // Delete the verification code
        Model::query("DELETE FROM email_verifications WHERE user_id = ?", [$user['id']]);

        return $user['id'];
    }

    public static function verifyEmail($token) {
        $stmt = Model::query(
            "SELECT ev.*, u.email FROM email_verifications ev 
             JOIN users u ON ev.user_id = u.id 
             WHERE ev.token = ? AND ev.expires_at > NOW()",
            [$token]
        );
        $verification = $stmt->fetch();
        
        if (!$verification) {
            return false;
        }
        
        // Mark email as verified
        Model::query(
            "UPDATE users SET email_verified_at = NOW() WHERE id = ?",
            [$verification['user_id']]
        );
        
        // Delete the verification token
        Model::query("DELETE FROM email_verifications WHERE token = ?", [$token]);
        
        return $verification['user_id'];
    }
    
    public static function isEmailVerified($userId) {
        $stmt = Model::query("SELECT email_verified_at FROM users WHERE id = ?", [$userId]);
        $user = $stmt->fetch();
        return $user && !is_null($user['email_verified_at']);
    }
    
    public static function resendVerification($userId) {
        // Check if email is already verified
        if (self::isEmailVerified($userId)) {
            return false;
        }

        // Generate new code
        $code = self::createVerificationToken($userId);

        // Get user details
        $user = self::getProfile($userId);

        // Send email
        $email = new Email();
        return $email->sendVerificationEmail($user['email'], $user['first_name'], $code);
    }

    /**
     * Issue a 6-digit password-reset code for the given email.
     * Always returns true so account existence is never leaked.
     */
    public static function createPasswordResetCode($email) {
        $user = self::findByEmail($email);
        if (!$user) {
            return true;
        }

        $code = self::generateCode();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        Model::query("DELETE FROM password_resets WHERE email = ?", [$email]);
        Model::query(
            "INSERT INTO password_resets (email, token, code, expires_at, attempts) VALUES (?, ?, ?, ?, 0)",
            [$email, bin2hex(random_bytes(16)), $code, $expiresAt]
        );

        $mail = new Email();
        return (bool)$mail->sendPasswordResetCode($user['email'], $user['first_name'], $code);
    }

    /**
     * Validate a reset code and set the new password.
     * Returns true on success, or ['error' => message] on failure.
     */
    public static function resetPasswordWithCode($email, $code, $newPassword) {
        $code = preg_replace('/\D/', '', (string)$code);
        if (strlen($code) !== 6) {
            return ['error' => 'Please enter the 6-digit code from your email.'];
        }

        $stmt = Model::query(
            "SELECT * FROM password_resets WHERE email = ? ORDER BY created_at DESC LIMIT 1",
            [$email]
        );
        $row = $stmt->fetch();

        if (!$row || strtotime($row['expires_at']) < time()) {
            if ($row) {
                Model::query("DELETE FROM password_resets WHERE id = ?", [$row['id']]);
            }
            return ['error' => 'This code has expired. Please request a new one.'];
        }

        if ((int)($row['attempts'] ?? 0) >= 5) {
            Model::query("DELETE FROM password_resets WHERE id = ?", [$row['id']]);
            return ['error' => 'Too many incorrect attempts. Please request a new code.'];
        }

        if (!hash_equals((string)($row['code'] ?? ''), $code)) {
            Model::query("UPDATE password_resets SET attempts = attempts + 1 WHERE id = ?", [$row['id']]);
            $left = 5 - ((int)($row['attempts'] ?? 0) + 1);
            return ['error' => $left > 0
                ? "Incorrect code. You have $left attempt(s) left."
                : 'Too many incorrect attempts. Please request a new code.'];
        }

        $user = self::findByEmail($row['email']);
        if (!$user) {
            return ['error' => 'Password reset failed. Please try again.'];
        }

        self::updatePassword($user['id'], $newPassword);
        Model::query("DELETE FROM password_resets WHERE email = ?", [$row['email']]);
        return true;
    }
}
