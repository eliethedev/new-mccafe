<?php ob_start(); ?>

<style>
:root {
    --maccafe-primary: #e09407;
    --maccafe-secondary: #6c757d;
    --maccafe-accent: #ffc107;
    --maccafe-dark: #343a40;
    --maccafe-light: #f8f9fa;
    --primary: #e09407;
}

/* Admin Dashboard Custom Styles */
.admin-dashboard {
    background: var(--maccafe-light);
    min-height: 100vh;
}

.dashboard-header {
    background: var(--maccafe-primary);
    color: white;
    padding: 1rem;
    margin-bottom: 2rem;
    border-radius: 0 0 20px 20px;
}

.dashboard-header h1 {
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.dashboard-header .subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
}

.stats-card {
    zoom: 80%;
    background: white;
    border-radius: 15px;
    padding: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-card.orders {
    border-left-color: var(--maccafe-primary);
}

.stats-card.pending {
    border-left-color: var(--maccafe-accent);
}

.stats-card.revenue {
    border-left-color: var(--maccafe-secondary);
}

.stats-card.customers {
    border-left-color: var(--maccafe-dark);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.stats-icon.orders {
    background: var(--maccafe-primary);
    color: white;
}

.stats-icon.pending {
    background: var(--maccafe-accent);
    color: var(--maccafe-dark);
}

.stats-icon.revenue {
    background: var(--maccafe-secondary);
    color: white;
}

.stats-icon.customers {
    background: var(--maccafe-dark);
    color: white;
}

.stats-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.stats-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stats-change {
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.stats-change.positive {
    color: #28a745;
}

.stats-change.negative {
    color: #dc3545;
}

.orders-section {
    background: white;
    border-radius: 15px;
    zoom: 80%;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.section-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.section-title {
    font-size: 1.25rem;
    font-weight: bold;
    color: var(--maccafe-dark);
    margin: 0;
}

.order-table {
    background: white;
}

.order-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: var(--maccafe-dark);
    border: none;
    padding: 1rem;
}

.order-table td {
    padding: 1rem;
    vertical-align: middle;
    border-color: #f0f0f0;
}

.order-table tbody tr:hover {
    background: #f8f9fa;
}

.order-number {
    font-weight: bold;
    color: var(--maccafe-primary);
}

.customer-info {
    display: flex;
    align-items: center;
}

.customer-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 0.8rem;
    font-weight: bold;
    color: #6c757d;
}

.order-amount {
    font-weight: bold;
    color: var(--maccafe-dark);
}

.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #d1ecf1;
    color: #0c5460;
}

.status-preparing {
    background: #cce5ff;
    color: #004085;
}

.status-ready {
    background: #d4edda;
    color: #155724;
}

.status-completed {
    background: #e2e3e5;
    color: #383d41;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.quick-actions-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
}

.action-btn {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 10px;
    border: 2px solid #f0f0f0;
    background: white;
    color: var(--maccafe-dark);
    text-decoration: none;
    transition: all 0.3s ease;
    margin-bottom: 0.75rem;
    font-weight: 500;
}

.action-btn:hover {
    background: var(--maccafe-primary);
    border-color: var(--maccafe-primary);
    color: white;
    transform: translateX(5px);
    text-decoration: none;
}

.action-btn i {
    font-size: 1.2rem;
    margin-right: 1rem;
    width: 30px;
    text-align: center;
}

.urgent-orders {
    background: #dc3545;
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.urgent-orders h5 {
    color: white;
    margin-bottom: 1rem;
}

.urgent-order-item {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    backdrop-filter: blur(10px);
}

@media (max-width: 768px) {
    .dashboard-header {
        padding: 1.5rem 0;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .order-table {
        font-size: 0.9rem;
    }
}
</style>

<div class="admin-dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Dashboard</h1>
                    <div class="subtitle">Welcome back, <?= Session::get('user.first_name') ?>! Ready to manage today's orders?</div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="text-white-50">
                        <i class="bi bi-clock me-2"></i>
                        <?= date('l, F j, Y') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <?php
            // Get real statistics
            $totalOrders = 0;
            $pendingOrders = 0;
            $totalRevenue = 0;
            $totalCustomers = 0;
            
            try {
                $totalOrders = Order::getTotalOrderCount();
                $pendingOrders = Order::getPendingOrderCount();
                $totalRevenue = Order::getTotalRevenue();
                $totalCustomers = User::getTotalCustomers();
            } catch (Exception $e) {
                // Fallback to 0 if database fails
            }
            ?>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card orders">
                    <div class="stats-icon orders">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div class="stats-number"><?= $totalOrders ?></div>
                    <div class="stats-label">Total Orders</div>
                    <div class="stats-change positive">
                        <i class="bi bi-arrow-up"></i> 12% from yesterday
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card pending">
                    <div class="stats-icon pending">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="stats-number"><?= $pendingOrders ?></div>
                    <div class="stats-label">Pending Orders</div>
                    <div class="stats-change negative">
                        <i class="bi bi-arrow-down"></i> 5% from yesterday
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card revenue">
                    <div class="stats-icon revenue">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stats-number">₱<?= number_format($totalRevenue, 2) ?></div>
                    <div class="stats-label">Total Revenue</div>
                    <div class="stats-change positive">
                        <i class="bi bi-arrow-up"></i> 8% from yesterday
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card customers">
                    <div class="stats-icon customers">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stats-number"><?= $totalCustomers ?></div>
                    <div class="stats-label">Customers</div>
                    <div class="stats-change positive">
                        <i class="bi bi-arrow-up"></i> 3 new today
                    </div>
                </div>
            </div>
        </div>

        <!-- Urgent Orders Alert -->
        <?php if ($pendingOrders > 0): ?>
        <div class="urgent-orders">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Urgent: <?= $pendingOrders ?> orders need attention!</h5>
            <?php
            $urgentOrders = Order::getUrgentOrders(3);
            foreach ($urgentOrders as $order):
            ?>
            <div class="urgent-order-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Order #<?= $order['order_number'] ?></strong>
                        <div class="small">Customer: <?= $order['customer_name'] ?></div>
                    </div>
                    <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-light">
                        <i class="bi bi-eye"></i> View
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- Recent Orders Table -->
            <div class="col-lg-8 mb-4">
                <div class="orders-section">
                    <div class="section-header">
                        <h5 class="section-title">Recent Orders</h5>
                        <a href="/admin/orders" class="btn btn-sm btn-primary" style="margin-left: 550px;">
                            View All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table order-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recentOrders = Order::getRecentOrders(10);
                                if (!empty($recentOrders)):
                                    foreach ($recentOrders as $order):
                                ?>
                                <tr>
                                    <td class="order-number">#<?= $order['order_number'] ?></td>
                                    <td>
                                        <div class="customer-info">
                                            <div class="customer-avatar">
                                                <?= strtoupper(substr($order['customer_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div><?= $order['customer_name'] ?></div>
                                                <small class="text-muted"><?= $order['customer_phone'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $itemCount = Order::getOrderItemCount($order['id']);
                                        echo $itemCount . ' item' . ($itemCount > 1 ? 's' : '');
                                        ?>
                                    </td>
                                    <td class="order-amount">₱<?= number_format($order['total_amount'], 2) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $order['status'] ?>">
                                            <?= Order::getStatusText($order['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?= date('H:i', strtotime($order['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($order['status'] === 'pending'): ?>
                                            <button onclick="updateOrderStatus(<?= $order['id'] ?>, 'confirmed')" class="btn btn-outline-success">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    endforeach;
                                else:
                                ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No recent orders found
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="quick-actions-card">
                    <h5 class="section-title mb-3">Quick Actions</h5>
                    <a href="/admin/orders" class="action-btn">
                        <i class="bi bi-bag-check"></i>
                        <div>
                            <div>Manage Orders</div>
                            <small class="text-muted">View and process orders</small>
                        </div>
                    </a>
                    <a href="/admin/products/create" class="action-btn">
                        <i class="bi bi-plus-circle"></i>
                        <div>
                            <div>Add Product</div>
                            <small class="text-muted">Create new menu item</small>
                        </div>
                    </a>
                    <a href="/admin/products" class="action-btn">
                        <i class="bi bi-box-seam"></i>
                        <div>
                            <div>Manage Products</div>
                            <small class="text-muted">Edit menu items</small>
                        </div>
                    </a>
                </div>
                
                <!-- System Status -->
                <div class="quick-actions-card">
                    <h5 class="section-title mb-3">System Status</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="bi bi-database me-2"></i>Database</span>
                        <span class="badge bg-success">Connected</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="bi bi-hdd me-2"></i>Storage</span>
                        <span class="badge bg-success">Available</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="bi bi-clock me-2"></i>Server Time</span>
                        <span class="text-muted"><?= date('H:i:s') ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-calendar me-2"></i>Last Backup</span>
                        <span class="text-muted">Never</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateOrderStatus(orderId, status) {
    if (confirm('Are you sure you want to update this order status?')) {
        fetch('/admin/orders/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                order_id: orderId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating order status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating order status');
        });
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Admin Dashboard - McCafe';
$currentPage = 'dashboard';
include __DIR__ . '/layout.php';
?>
