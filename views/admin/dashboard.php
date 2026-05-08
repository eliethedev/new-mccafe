<?php ob_start(); ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Admin Dashboard</h1>
            <div>
                <span class="text-muted">Welcome back, <?= Session::get('user.first_name') ?>!</span>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Total Orders</h5>
                        <h2 class="mb-0">0</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-cart-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Products</h5>
                        <h2 class="mb-0">0</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Customers</h5>
                        <h2 class="mb-0">0</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Revenue</h5>
                        <h2 class="mb-0">₱0</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity and Quick Actions -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Orders</h5>
                <a href="/admin/orders" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No recent orders found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/admin/products/create" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Product
                    </a>
                    <a href="/admin/orders" class="btn btn-success">
                        <i class="bi bi-cart-check me-2"></i>View Orders
                    </a>
                    <a href="/admin/users" class="btn btn-info">
                        <i class="bi bi-people me-2"></i>Manage Users
                    </a>
                    <a href="/admin/categories" class="btn btn-warning">
                        <i class="bi bi-tags me-2"></i>Manage Categories
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">System Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Database</span>
                    <span class="badge bg-success">Connected</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Storage</span>
                    <span class="badge bg-success">Available</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Last Backup</span>
                    <span class="text-muted">Never</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Admin Dashboard - MacCafe';
$currentPage = 'dashboard';
include __DIR__ . '/layout.php';
?>
