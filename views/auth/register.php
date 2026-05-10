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
                    
                    <?php 
$errors = Session::getFlash('errors');
if ($errors && is_array($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $field => $fieldErrors): ?>
                                <?php foreach ($fieldErrors as $error): ?>
                                    <small><?= $error ?></small><br>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form id="registerForm" method="POST" action="/register" onsubmit="return handleRegisterSubmit(event);">
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

<!-- Loading Screen -->
<div id="loadingScreen" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; backdrop-filter: blur(5px);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white;">
        <div class="spinner-border text-light mb-3" style="width: 3rem; height: 3rem; border-width: 0.3em;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5 class="mb-2">Creating Your Account</h5>
        <p class="mb-0 text-muted">Please wait while we process your registration...</p>
    </div>
</div>

<script>
let isSubmitting = false;

function handleRegisterSubmit(event) {
    // Prevent multiple submissions
    if (isSubmitting) {
        event.preventDefault();
        return false;
    }
    
    isSubmitting = true;
    
    // Show loading screen immediately
    const loadingScreen = document.getElementById('loadingScreen');
    loadingScreen.style.display = 'block';
    
    // Get form data BEFORE disabling inputs
    const form = document.getElementById('registerForm');
    const formData = new FormData(form);
    
    // Disable all form inputs AFTER capturing data
    const inputs = form.querySelectorAll('input, button');
    inputs.forEach(input => input.disabled = true);
    
    // Create and submit a new form with the captured data
    setTimeout(() => {
        const newForm = document.createElement('form');
        newForm.method = 'POST';
        newForm.action = '/register';
        
        // Add all form data to new form
        for (let [key, value] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            newForm.appendChild(input);
        }
        
        // Submit the new form
        document.body.appendChild(newForm);
        newForm.submit();
    }, 100);
    
    // Prevent original form submission
    event.preventDefault();
    return false;
}

// Prevent form resubmission on page reload
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// Reset submitting flag on page load
document.addEventListener('DOMContentLoaded', function() {
    isSubmitting = false;
});
</script>

<?php
$content = ob_get_clean();
$title = 'Register - MacCafe';
include __DIR__ . '/../layouts/auth-header.php';
echo $content;
include __DIR__ . '/../layouts/auth-footer.php';
?>
