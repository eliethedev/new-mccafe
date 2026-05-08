<?php

class Session {
    private static $started = false;
    
    public static function start() {
        if (self::$started) {
            return;
        }
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        self::$started = true;
    }
    
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    public static function flash($key, $value) {
        self::start();
        $_SESSION['_flash'][$key] = $value;
    }
    
    public static function getFlash($key, $default = null) {
        self::start();
        
        if (isset($_SESSION['_flash'][$key])) {
            $value = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $value;
        }
        
        return $default;
    }
    
    public static function all() {
        self::start();
        return $_SESSION;
    }
    
    public static function flush() {
        self::start();
        session_unset();
    }
    
    public static function destroy() {
        self::start();
        
        // Unset all session variables
        $_SESSION = [];
        
        // Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        self::$started = false;
    }
    
    public static function regenerateId($deleteOldSession = false) {
        self::start();
        session_regenerate_id($deleteOldSession);
    }
    
    public static function id() {
        self::start();
        return session_id();
    }
    
    public static function put($key, $value) {
        self::set($key, $value);
    }
    
    public static function pull($key, $default = null) {
        $value = self::get($key, $default);
        self::remove($key);
        return $value;
    }
    
    public static function forget($keys) {
        self::start();
        
        if (is_array($keys)) {
            foreach ($keys as $key) {
                unset($_SESSION[$key]);
            }
        } else {
            unset($_SESSION[$keys]);
        }
    }
    
    public static function token() {
        if (!self::has('_token')) {
            self::set('_token', bin2hex(random_bytes(32)));
        }
        
        return self::get('_token');
    }
    
    public static function validateToken($token) {
        return hash_equals(self::token(), $token);
    }
}
