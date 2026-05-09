<?php

class AuthController extends Controller {
    
    public function showLogin() {
        return $this->view('auth/login');
    }
    
    public function login(Request $request) {
        $email = $request->getBody('email');
        $password = $request->getBody('password');
        $remember = $request->getBody('remember') ?? false;
        
        // Validate input
        $errors = $this->validate($request->getBody(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $request->getBody());
            return $this->redirect('/login');
        }
        
        // Check if user is locked out
        if (User::isLockedOut($email)) {
            Session::flash('error', 'Too many failed attempts. Please try again later.');
            return $this->redirect('/login');
        }
        
        // Attempt login
        $user = User::verifyPassword($email, $password);
        
        if ($user) {
            // Check if email is verified (only for customers)
            if ($user['role'] === ROLE_CUSTOMER && !User::isEmailVerified($user['id'])) {
                Session::flash('error', 'Please verify your email address before logging in. Check your inbox for the verification email.');
                Session::flash('resend_verification', true);
                Session::flash('user_id', $user['id']);
                return $this->redirect('/login');
            }
            
            // Record successful login attempt
            User::recordLoginAttempt($email, $request->getHeader('X-Forwarded-For') ?? $_SERVER['REMOTE_ADDR'], $request->getHeader('User-Agent'), true);
            
            // Set session
            Session::set('user', [
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);
            
            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 days
                // TODO: Store token in database
            }
            
            Session::flash('success', 'Welcome back, ' . $user['first_name'] . '!');
            
            // Check for redirect after login
            $redirectTo = $_SESSION['redirect_after_login'] ?? null;
            if ($redirectTo) {
                // Clear the redirect session
                unset($_SESSION['redirect_after_login']);
                return $this->redirect($redirectTo);
            }
            
            // Redirect based on role
            if ($user['role'] === ROLE_ADMIN) {
                return $this->redirect('/admin');
            } elseif ($user['role'] === ROLE_STAFF) {
                return $this->redirect('/staff');
            } else {
                return $this->redirect('/dashboard');
            }
        } else {
            // Record failed login attempt
            User::recordLoginAttempt($email, $request->getHeader('X-Forwarded-For') ?? $_SERVER['REMOTE_ADDR'], $request->getHeader('User-Agent'), false);
            
            Session::flash('error', 'Invalid email or password');
            Session::flash('old', $request->getBody());
            return $this->redirect('/login');
        }
    }
    
    public function showRegister() {
        return $this->view('auth/register');
    }
    
    public function register(Request $request) {
        $data = $request->getBody();
        
        // Validate input
        $errors = $this->validate($data, [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required'
        ]);
        
        // Check if passwords match
        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'][] = 'Passwords do not match';
        }
        
        // Check if email already exists
        if (User::findByEmail($data['email'])) {
            $errors['email'][] = 'Email already exists';
        }
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            return $this->redirect('/register');
        }
        
        // Create user
        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => ROLE_CUSTOMER
        ];
        
        $userId = User::create($userData);
        
        if ($userId) {
            // Create and send email verification
            $token = User::createVerificationToken($userId);
            $email = new Email();
            $emailSent = $email->sendVerificationEmail($data['email'], $data['first_name'], $token);
            
            if ($emailSent) {
                Session::flash('success', 'Registration successful! Please check your email to verify your account.');
            } else {
                Session::flash('warning', 'Registration successful! However, we couldn\'t send the verification email. Please contact support.');
            }
            
            // Store user info for resend verification functionality
            Session::flash('new_user_id', $userId);
            Session::flash('new_user_email', $data['email']);
            
            return $this->redirect('/verify-email');
        } else {
            Session::flash('error', 'Registration failed. Please try again.');
            return $this->redirect('/register');
        }
    }
    
    public function logout(Request $request) {
        // Clear all session data
        Session::destroy();
        
        // Clear remember me cookie if it exists
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
            unset($_COOKIE['remember_token']);
        }
        
        // Start a new session for the flash message
        Session::start();
        Session::flash('success', 'You have been logged out successfully.');
        
        return $this->redirect('/login');
    }
    
    public function showForgotPassword() {
        return $this->view('auth/forgot-password');
    }
    
    public function sendResetLink(Request $request) {
        $email = $request->getBody('email');
        
        $user = User::findByEmail($email);
        if (!$user) {
            Session::flash('error', 'If an account with that email exists, a reset link has been sent.');
            return $this->redirect('/forgot-password');
        }
        
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token in database
        Model::query(
            "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)",
            [$email, $token, $expiresAt]
        );
        
        // TODO: Send email with reset link
        // For now, just show success message
        Session::flash('success', 'If an account with that email exists, a reset link has been sent.');
        return $this->redirect('/forgot-password');
    }
    
    public function showResetPassword(Request $request, $token) {
        // Verify token
        $stmt = Model::query(
            "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()",
            [$token]
        );
        $reset = $stmt->fetch();
        
        if (!$reset) {
            Session::flash('error', 'Invalid or expired reset token.');
            return $this->redirect('/forgot-password');
        }
        
        return $this->view('auth/reset-password', ['token' => $token]);
    }
    
    public function resetPassword(Request $request) {
        $token = $request->getBody('token');
        $password = $request->getBody('password');
        $passwordConfirmation = $request->getBody('password_confirmation');
        
        // Validate input
        $errors = $this->validate($request->getBody(), [
            'password' => 'required|min:8',
            'password_confirmation' => 'required'
        ]);
        
        if ($password !== $passwordConfirmation) {
            $errors['password_confirmation'][] = 'Passwords do not match';
        }
        
        if (!empty($errors)) {
            Session::flash('errors', $errors);
            return $this->redirect("/reset-password/$token");
        }
        
        // Verify token and get email
        $stmt = Model::query(
            "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()",
            [$token]
        );
        $reset = $stmt->fetch();
        
        if (!$reset) {
            Session::flash('error', 'Invalid or expired reset token.');
            return $this->redirect('/forgot-password');
        }
        
        // Update password
        $user = User::findByEmail($reset['email']);
        if ($user) {
            User::updatePassword($user['id'], $password);
            
            // Delete used token
            Model::query("DELETE FROM password_resets WHERE token = ?", [$token]);
            
            Session::flash('success', 'Password reset successful! Please login with your new password.');
            return $this->redirect('/login');
        } else {
            Session::flash('error', 'Password reset failed. Please try again.');
            return $this->redirect('/forgot-password');
        }
    }
    
    public function verifyEmail(Request $request) {
        $token = $request->getQuery('token');
        
        // If no token, show the verification page
        if (!$token) {
            return $this->view('auth/verify-email');
        }
        
        // Process email verification
        $userId = User::verifyEmail($token);
        
        if ($userId) {
            Session::flash('success', 'Email verified successfully! You can now login with your account.');
            
            // Check if there's a redirect URL after verification
            $redirectUrl = Session::get('redirect_after_verification');
            if ($redirectUrl) {
                Session::remove('redirect_after_verification');
                Session::flash('success', 'Email verified successfully! You can now login and proceed with payment proof upload.');
            }
            
            // Ensure session data is written before redirect
            session_write_close();
            
            return $this->redirect('/login');
        } else {
            Session::flash('error', 'Invalid or expired verification link.');
            session_write_close();
            return $this->redirect('/login');
        }
    }
    
    public function resendVerification(Request $request) {
        $userId = $request->getBody('user_id');
        
        if (!$userId) {
            Session::flash('error', 'Invalid request.');
            return $this->redirect('/login');
        }
        
        $emailSent = User::resendVerification($userId);
        
        if ($emailSent) {
            Session::flash('success', 'Verification email sent! Please check your inbox.');
        } else {
            Session::flash('error', 'Failed to send verification email. Please try again later.');
        }
        
        return $this->redirect('/verify-email');
    }
}
