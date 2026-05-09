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
            "SELECT id, first_name, last_name, email, phone, address, role, email_verified_at, created_at 
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
    
    public static function createVerificationToken($userId) {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        // Delete any existing tokens for this user
        Model::query("DELETE FROM email_verifications WHERE user_id = ?", [$userId]);
        
        // Insert new token
        Model::query(
            "INSERT INTO email_verifications (user_id, token, expires_at) VALUES (?, ?, ?)",
            [$userId, $token, $expiresAt]
        );
        
        return $token;
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
        
        // Generate new token
        $token = self::createVerificationToken($userId);
        
        // Get user details
        $user = self::getProfile($userId);
        
        // Send email
        $email = new Email();
        return $email->sendVerificationEmail($user['email'], $user['first_name'], $token);
    }
}
