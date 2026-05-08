<?php ob_start(); ?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Welcome, <?= Session::get('user')['first_name'] ?? 'User' ?>!</h2>
                <a href="/" class="btn btn-primary">
                    <i class="bi bi-house me-2"></i>Back to Home
                </a>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <i class="bi bi-person-circle fs-1 text-primary mb-3"></i>
                            <h5><?= Session::get('user')['first_name'] ?? 'First' ?> <?= Session::get('user')['last_name'] ?? 'Name' ?></h5>
                            <p class="text-muted"><?= Session::get('user')['email'] ?? 'email@example.com' ?></p>
                            <span class="badge bg-success"><?= ucfirst(Session::get('user')['role'] ?? 'customer') ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <a href="/orders" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-bag-check me-2"></i>My Orders
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="/menu" class="btn btn-outline-success w-100">
                                        <i class="bi bi-cup-hot me-2"></i>View Menu
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="/profile" class="btn btn-outline-info w-100">
                                        <i class="bi bi-gear me-2"></i>Edit Profile
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="/cart" class="btn btn-outline-warning w-100">
                                        <i class="bi bi-cart3 me-2"></i>View Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Dashboard - MacCafe';
include __DIR__ . '/../layouts/main.php';
?>
