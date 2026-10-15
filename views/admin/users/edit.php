<?php ob_start(); ?>

<style>
:root {
    --cafecat-primary: #111111;
    --cafecat-secondary: #6c757d;
    --cafecat-accent: #000000;
    --cafecat-dark: #000000;
    --cafecat-light: #f8f9fa;
    --primary: #111111;
}

.users-container {
    background: var(--cafecat-light);
    min-height: 100vh;
}

.users-header {
    background: var(--cafecat-primary);
    color: white;
    padding: 1rem 0;
    margin-bottom: 0.2rem;
    border-radius: 0 0 20px 20px;
}

.users-header h1 {
    font-weight: bold;
    margin: 0;
}

.users-header .subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
}

.detail-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: none;
    margin-bottom: 1.5rem;
}

.detail-card .card-header {
    background: var(--cafecat-light);
    border-bottom: 2px solid #e9ecef;
    border-radius: 15px 15px 0 0 !important;
    padding: 1rem 1.5rem;
}

.detail-card .card-body {
    padding: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--cafecat-dark);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--cafecat-primary);
    box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.1);
}

.form-control::placeholder {
    color: #adb5bd;
}

.form-check-input {
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.form-check-input:checked {
    background-color: var(--cafecat-primary);
    border-color: var(--cafecat-primary);
}

.form-check-label {
    font-weight: 500;
    color: var(--cafecat-dark);
    cursor: pointer;
}

.form-text {
    font-size: 0.8rem;
    color: var(--cafecat-secondary);
    margin-top: 0.375rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-primary {
    background: var(--cafecat-primary);
    color: white;
}

.btn-primary:hover {
    background: #000;
    color: white;
}

.btn-secondary {
    background: #e9ecef;
    color: var(--cafecat-dark);
    border: 1px solid #dee2e6;
}

.btn-secondary:hover {
    background: var(--cafecat-light);
    border-color: var(--cafecat-primary);
    color: var(--cafecat-primary);
}

.btn-outline-secondary {
    background: white;
    border: 1px solid #dee2e6;
    color: var(--cafecat-dark);
}

.btn-outline-secondary:hover {
    background: var(--cafecat-light);
    border-color: var(--cafecat-primary);
    color: var(--cafecat-primary);
}

.invalid-feedback {
    font-size: 0.8rem;
    color: #dc3545;
    margin-top: 0.25rem;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15) !important;
}

.alert {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.5rem;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--cafecat-secondary);
    cursor: pointer;
    padding: 0.25rem;
}

.password-toggle:hover {
    color: var(--cafecat-primary);
}

.password-wrapper {
    position: relative;
}

.section-divider {
    border-top: 1px solid #e9ecef;
    margin: 1.5rem 0;
    padding-top: 1.5rem;
}

@media (max-width: 767px) {
    .users-header h1 {
        font-size: 1.5rem;
    }
    
    .detail-card .card-body {
        padding: 1rem;
    }
}
</style>

<div class="users-container">
    <!-- Header -->
    <div class="users-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="text-white"><i class="bi bi-pencil me-2"></i>Edit User</h1>
                    <div class="subtitle">Update user information and permissions</div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="/admin/users/<?= $user['id'] ?>" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); endif; ?>

        <?php if (!empty($errors ?? [])): ?>
        <div class="alert alert-danger mb-4">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $field => $fieldErrors): ?>
                    <?php foreach ($fieldErrors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="/admin/users/<?= $user['id'] ?>" novalidate>
            <input type="hidden" name="_method" value="PUT">
            
            <!-- Basic Information -->
            <div class="detail-card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-person me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($old['first_name'] ?? $user['first_name'] ?? '') ?>" required>
                            <?php if (isset($errors['first_name'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['first_name'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($old['last_name'] ?? $user['last_name'] ?? '') ?>" required>
                            <?php if (isset($errors['last_name'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['last_name'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($old['email'] ?? $user['email'] ?? '') ?>" required>
                            <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['email'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" 
                                   value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>" placeholder="+63 9XX XXX XXXX">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Street, Barangay, City, Province"><?= htmlspecialchars($old['address'] ?? $user['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <!-- Role & Permissions -->
            <div class="detail-card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-shield me-2"></i>Role & Permissions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>" required>
                                <option value="customer" <?= ($old['role'] ?? $user['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Customer</option>
                                <option value="rider" <?= ($old['role'] ?? $user['role'] ?? '') === 'rider' ? 'selected' : '' ?>>Rider</option>
                                <option value="staff" <?= ($old['role'] ?? $user['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Staff</option>
                                <option value="admin" <?= ($old['role'] ?? $user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <?php if (isset($errors['role'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['role'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="email_verified" class="form-check-input" id="email_verified" 
                                       <?= (!empty($old['email_verified'] ?? $user['email_verified_at'] ?? '')) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="email_verified">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i> Email Verified
                                </label>
                                <div class="form-text">Check to mark the user's email as verified</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <!-- Password (Optional) -->
            <div class="detail-card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-key me-2"></i>Change Password (Optional)</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-4">Leave blank to keep current password. Minimum 8 characters.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <div class="password-wrapper">
                                <input type="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Enter new password" minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['password'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <div class="password-wrapper">
                                <input type="password" name="password_confirmation" class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Confirm new password" minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($errors['password_confirmation'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['password_confirmation'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="detail-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <a href="/admin/users/<?= $user['id'] ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const input = document.querySelector('input[name="' + fieldId + '"]');
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>

<?php
$content = ob_get_clean();
$title = $title ?? 'Edit User - CafeCat';
$currentPage = 'users';
include __DIR__ . '/../layout.php';
?>