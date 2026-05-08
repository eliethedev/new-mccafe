<?php ob_start(); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-cup-hot-fill fs-1 text-success"></i>
                        <h3 class="mt-2">Welcome Back</h3>
                        <p class="text-muted">Sign in to your account</p>
                    </div>
                    
                    <!-- Registration Success Notification -->
                    <?php if (Session::getFlash('registered')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>Registration Successful!</strong> Please sign in to your new account.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Login Success Notification -->
                    <?php if (Session::getFlash('logged_in')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>Welcome back!</strong> You have successfully logged in.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Form Validation Errors -->
                    <?php 
$errors = Session::getFlash('errors');
if ($errors): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Please fix the following errors:</strong><br>
                            <?php foreach ($errors as $field => $fieldErrors): ?>
                                <?php foreach ($fieldErrors as $error): ?>
                                    <small><?= $error ?></small><br>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- General Error Notification -->
                    <?php if (Session::getFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= Session::getFlash('error') ?: 'An unknown error occurred. Please try again.' ?>
                            <?php if (Session::getFlash('resend_verification')): ?>
                                <form method="POST" action="/resend-verification" class="mt-3">
                                    <input type="hidden" name="user_id" value="<?= Session::getFlash('user_id') ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-envelope-fill me-1"></i> Resend Verification Email
                                    </button>
                                </form>
                            <?php endif; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- General Success Notification -->
                    <?php if (Session::getFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= Session::getFlash('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= Session::getFlash('old.email') ?? '' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 mb-3">
                            Sign In
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0">
                                Don't have an account? 
                                <a href="/register" class="text-success text-decoration-none">Sign up</a>
                            </p>
                            <p class="mt-2">
                                <a href="/forgot-password" class="text-decoration-none">Forgot password?</a>
                            </p>
                        </div>
                    </form>
                    <button class="btn justify-content-center" onclick="window.location.href='/';">Go back to home</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Login - MacCafe';
include __DIR__ . '/../layouts/auth-header.php';
echo $content;
include __DIR__ . '/../layouts/auth-footer.php';
?>
