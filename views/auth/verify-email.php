<?php ob_start(); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Email Verification Required</h4>
                </div>
                <div class="card-body">
                    <?php if (Session::has('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo Session::get('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (Session::has('success')): ?>
                        <div class="alert alert-success">
                            <?php echo Session::get('success'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (Session::has('warning')): ?>
                        <div class="alert alert-warning">
                            <?php echo Session::get('warning'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="text-center mb-4">
                        <i class="bi bi-envelope-check fs-1 text-primary mb-3"></i>
                        <h5>Verify Your Email Address</h5>
                        <p class="text-muted">Please check your inbox and click the verification link to activate your account.</p>
                        <?php if ($newUserEmail = Session::getFlash('new_user_email')): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                Verification email sent to: <strong><?php echo htmlspecialchars($newUserEmail); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle me-2"></i>Next Steps:</h6>
                        <ol class="mb-0">
                            <li>Check your email inbox for the verification message</li>
                            <li>Click the verification link in the email</li>
                            <li>You'll be able to login and place orders after verification</li>
                        </ol>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="/login" class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Go to Login
                        </a>
                        
                        <?php 
                        $newUserId = Session::getFlash('new_user_id');
                        if ($newUserId): 
                        ?>
                            <form action="/resend-verification" method="POST" class="mt-3">
                                <input type="hidden" name="user_id" value="<?php echo $newUserId; ?>">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-envelope me-2"></i>Resend Verification Email
                                </button>
                            </form>
                        <?php elseif (isset($_SESSION['user']['id'])): ?>
                            <form action="/resend-verification" method="POST" class="mt-3">
                                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['id']; ?>">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-envelope me-2"></i>Resend Verification Email
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Verify Email - MacCafe';
include __DIR__ . '/../layouts/auth-header.php';
echo $content;
include __DIR__ . '/../layouts/auth-footer.php';
?>