<?php ob_start(); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill fs-1 text-success"></i>
                        <h3 class="mt-2">Create Account</h3>
                        <p class="text-muted">Join MacCafe today</p>
                    </div>
                    
                    <?php if (Session::getFlash('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (Session::getFlash('errors') as $field => $errors): ?>
                                <?php foreach ($errors as $error): ?>
                                    <small><?= $error ?></small><br>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/register">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?= Session::getFlash('old.first_name') ?? '' ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?= Session::getFlash('old.last_name') ?? '' ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= Session::getFlash('old.email') ?? '' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number (Optional)</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= Session::getFlash('old.phone') ?? '' ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="<?= Session::getFlash('old.address') ?? '' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" 
                                   value="<?= Session::getFlash('old.city') ?? '' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 mb-3">
                            Create Account
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0">
                                Already have an account? 
                                <a href="/login" class="text-success text-decoration-none">Sign in</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Register - MacCafe';
include __DIR__ . '/../layouts/auth-header.php';
echo $content;
include __DIR__ . '/../layouts/auth-footer.php';
?>
