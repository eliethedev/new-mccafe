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
            } elseif (defined('ROLE_RIDER') && $user['role'] === ROLE_RIDER) {
                return $this->redirect('/rider');
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
        foreach ($data as $k => $v) {
            if (is_string($v)) {
                $data[$k] = trim($v);
            }
        }

        // Validate input
        $errors = $this->validate($data, [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required'
        ]);

        // Check if passwords match
        if (($data['password'] ?? '') !== ($data['password_confirmation'] ?? '')) {
            $errors['password_confirmation'][] = 'Passwords do not match';
        }

        // Check if email already exists
        if (!empty($data['email']) && User::findByEmail($data['email'])) {
            $errors['email'][] = 'Email already exists';
        }

        // Senior / PWD discount claim (optional, mutually exclusive).
        $isSenior = !empty($data['is_senior']);
        $isPwd = !empty($data['is_pwd']);
        if ($isSenior && $isPwd) {
            $errors['discount'][] = 'Please choose only one: Senior Citizen or PWD (discounts cannot be combined).';
        }
        $claimsDiscount = $isSenior || $isPwd;
        $discountIdNo = trim((string)($data['discount_id_no'] ?? ''));
        if ($claimsDiscount) {
            if (class_exists('Discount')) {
                $idCheck = Discount::validateIdNumber($discountIdNo);
                if (!$idCheck['valid']) {
                    $errors['discount_id_no'][] = $idCheck['message'];
                }
            } elseif ($discountIdNo === '') {
                $errors['discount_id_no'][] = 'ID number is required for Senior/PWD discount.';
            }
            $idFileError = $this->validateIdUpload($_FILES['discount_id_image'] ?? null);
            if ($idFileError !== null) {
                $errors['discount_id_image'][] = $idFileError;
            }
        }

        // Mandatory consents.
        if (empty($data['accept_terms'])) {
            $errors['accept_terms'][] = 'Please read and accept the Terms and Conditions.';
        }
        if (empty($data['accept_privacy'])) {
            $errors['accept_privacy'][] = 'Please read and accept the Data Privacy Notice.';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            $old = $data;
            unset($old['password'], $old['password_confirmation']);
            Session::flash('old', $old);
            return $this->redirect('/register');
        }

        // Store the ID image before creating the user (orphan cleanup on failure).
        $idImageName = null;
        if ($claimsDiscount) {
            $stored = $this->storeIdUpload($_FILES['discount_id_image']);
            if ($stored['error'] !== null) {
                Session::flash('errors', ['discount_id_image' => [$stored['error']]]);
                $old = $data;
                unset($old['password'], $old['password_confirmation']);
                Session::flash('old', $old);
                return $this->redirect('/register');
            }
            $idImageName = $stored['filename'];
        }

        // Create user
        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => ROLE_CUSTOMER,
            'is_senior' => $isSenior ? 1 : 0,
            'is_pwd' => $isPwd ? 1 : 0,
            'discount_id_no' => $claimsDiscount ? $discountIdNo : null,
            'discount_id_image' => $idImageName,
            'discount_verified' => 0,
        ];

        try {
            $userId = User::create($userData);
        } catch (Throwable $e) {
            if ($idImageName !== null) {
                @unlink(ROOT_PATH . '/public/assets/images/id-uploads/' . $idImageName);
            }
            error_log('Registration failed: ' . $e->getMessage());
            Session::flash('error', 'Registration failed. Please try again.');
            return $this->redirect('/register');
        }

        if ($userId) {
            // Create and send email verification
            $token = User::createVerificationToken($userId);
            $email = new Email();
            $emailSent = $email->sendVerificationEmail($data['email'], $data['first_name'], $token);

            if ($emailSent) {
                $msg = 'Registration successful! Please check your email to verify your account.';
            } else {
                $msg = 'Registration successful! However, we couldn\'t send the verification email. Please contact support.';
            }
            if ($claimsDiscount) {
                $msg .= ' Your Senior/PWD claim and ID are pending staff verification.';
            }
            Session::flash($emailSent ? 'success' : 'warning', $msg);

            // Store user info for resend verification functionality
            Session::flash('new_user_id', $userId);
            Session::flash('new_user_email', $data['email']);

            return $this->redirect('/verify-email');
        } else {
            if ($idImageName !== null) {
                @unlink(ROOT_PATH . '/public/assets/images/id-uploads/' . $idImageName);
            }
            Session::flash('error', 'Registration failed. Please try again.');
            return $this->redirect('/register');
        }
    }

    /** Validate the Senior/PWD ID upload without moving it. Returns error or null. */
    private function validateIdUpload($file) {
        if (empty($file) || !is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return 'Please upload a clear photo of your Senior Citizen / PWD ID.';
        }
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            return 'ID photo too large. Maximum size is 5MB.';
        }
        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return 'Invalid file type. Upload JPG, PNG or WebP only.';
        }
        if (!is_uploaded_file($file['tmp_name'] ?? '')) {
            return 'Upload failed. Please try again.';
        }
        $mime = @mime_content_type($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return 'The uploaded file is not a valid image.';
        }
        if (@getimagesize($file['tmp_name']) === false) {
            return 'The uploaded file is not a valid image.';
        }
        return null;
    }

    /** Securely store the ID upload. Returns ['filename'=>..., 'error'=>...]. */
    private function storeIdUpload($file) {
        $err = $this->validateIdUpload($file);
        if ($err !== null) {
            return ['filename' => null, 'error' => $err];
        }
        $dir = ROOT_PATH . '/public/assets/images/id-uploads/';
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            return ['filename' => null, 'error' => 'Upload directory unavailable. Please try again later.'];
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $ext = 'jpg';
        }
        try {
            $filename = 'id_' . date('Ymd') . '_' . bin2hex(random_bytes(12)) . '.' . $ext;
        } catch (Exception $e) {
            $filename = 'id_' . time() . '_' . mt_rand(100000, 999999) . '.' . $ext;
        }
        $target = $dir . $filename;
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            return ['filename' => null, 'error' => 'Failed to save the ID photo. Please try again.'];
        }
        @chmod($target, 0644);
        return ['filename' => $filename, 'error' => null];
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
        $email = trim((string)$request->getBody('email'));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid email address.');
            return $this->redirect('/forgot-password');
        }

        // Issues a 6-digit code (silently skips unknown emails).
        User::createPasswordResetCode($email);

        Session::flash('success', 'If an account with that email exists, a 6-digit reset code has been sent. It expires in 1 hour.');
        return $this->redirect('/reset-password?email=' . urlencode($email));
    }

    public function showResetPassword(Request $request) {
        $email = $request->getQuery('email', '');
        return $this->view('auth/reset-password', ['email' => $email]);
    }

    public function resetPassword(Request $request) {
        $body = $request->getBody();
        $email = trim((string)($body['email'] ?? ''));
        $code = preg_replace('/\D/', '', (string)($body['code'] ?? ''));
        $password = $body['password'] ?? '';
        $passwordConfirmation = $body['password_confirmation'] ?? '';

        // Validate input
        $errors = $this->validate($body, [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required'
        ]);

        if (strlen($code) !== 6) {
            $errors['code'][] = 'Please enter the 6-digit code from your email.';
        }

        if ($password !== $passwordConfirmation) {
            $errors['password_confirmation'][] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', ['email' => $email]);
            return $this->redirect('/reset-password?email=' . urlencode($email));
        }

        $result = User::resetPasswordWithCode($email, $code, $password);

        if ($result === true) {
            Session::flash('success', 'Password reset successful! Please login with your new password.');
            return $this->redirect('/login');
        }

        Session::flash('error', $result['error'] ?? 'Password reset failed. Please try again.');
        Session::flash('old', ['email' => $email]);
        return $this->redirect('/reset-password?email=' . urlencode($email));
    }

    public function verifyEmail(Request $request) {
        // Legacy path: old verification links with ?token= still work.
        $token = $request->getQuery('token');

        if ($token) {
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
            }

            Session::flash('error', 'Invalid or expired verification link. Please enter the 6-digit code instead.');
            session_write_close();
            return $this->redirect('/verify-email');
        }

        // Code entry form. Prefill email from query string or recent flash.
        $email = $request->getQuery('email', '');
        if ($email === '') {
            $email = Session::getFlash('verify_email') ?? '';
        }
        return $this->view('auth/verify-email', ['email' => $email]);
    }

    public function verifyEmailCode(Request $request) {
        $body = $request->getBody();
        $email = trim((string)($body['email'] ?? ''));
        $code = preg_replace('/\D/', '', (string)($body['code'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid email address.');
            Session::flash('verify_email', $email);
            return $this->redirect('/verify-email');
        }

        $result = User::verifyEmailByCode($email, $code);

        if (is_int($result) || (is_numeric($result) && (int)$result > 0)) {
            Session::flash('success', 'Email verified successfully! You can now login with your account.');
            session_write_close();
            return $this->redirect('/login');
        }

        Session::flash('error', (is_array($result) ? ($result['error'] ?? null) : null) ?? 'Verification failed. Please try again.');
        Session::flash('verify_email', $email);
        return $this->redirect('/verify-email?email=' . urlencode($email));
    }
    
    public function resendVerification(Request $request) {
        $userId = $request->getBody('user_id');
        $email = trim((string)$request->getBody('email'));

        // Allow resending by email address as well (code entry page).
        if (!$userId && $email !== '') {
            $found = User::findByEmail($email);
            if ($found) {
                $userId = $found['id'];
            }
        }

        if (!$userId) {
            Session::flash('error', 'Enter your email address above, then resend the code.');
            return $this->redirect('/verify-email' . ($email !== '' ? '?email=' . urlencode($email) : ''));
        }
        
        $emailSent = User::resendVerification($userId);

        if ($emailSent) {
            Session::flash('success', 'Verification code sent! Please check your inbox and enter the 6-digit code.');
        } else {
            Session::flash('error', 'Failed to send verification code. Please try again later.');
        }

        try {
            $user = User::getProfile($userId);
            $email = $user['email'] ?? '';
        } catch (Exception $e) {
            $email = '';
        }

        return $this->redirect('/verify-email' . ($email !== '' ? '?email=' . urlencode($email) : ''));
    }
}
