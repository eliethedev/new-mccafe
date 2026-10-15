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
    padding: 0.75rem 0;
    margin-bottom: 0.2rem;
    border-radius: 0 0 16px 16px;
}

.users-header h1 {
    font-weight: bold;
    margin: 0;
}

.users-header .subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
}

.users-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}

.detail-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border: none;
    margin-bottom: 1rem;
}

.detail-card .card-header {
    background: var(--cafecat-light);
    border-bottom: 2px solid #e9ecef;
    border-radius: 12px 12px 0 0 !important;
    padding: 0.75rem 1rem;
}

.detail-card .card-body {
    padding: 1rem;
}

.detail-row {
    display: flex;
    align-items: flex-start;
    padding: 0.625rem 0;
    border-bottom: 1px solid #f0f0f0;
    flex-wrap: wrap;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: var(--cafecat-dark);
    min-width: 140px;
    flex-shrink: 0;
    font-size: 0.8rem;
}

.detail-value {
    color: var(--cafecat-secondary);
    flex: 1;
    word-break: break-word;
    min-width: 0;
    font-size: 0.8rem;
}

.badge-role {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.badge-role.admin { background: #e7eaf3; color: #2c3e6b; }
.badge-role.staff { background: #d6f5f5; color: #0d6e6e; }
.badge-role.customer { background: #d4edda; color: #155724; }
.badge-role.rider { background: #fff3cd; color: #856404; }

.badge-status {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.badge-status.verified { background: #d4edda; color: #155724; }
.badge-status.unverified { background: #f8d7da; color: #721c24; }

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    text-align: center;
    transition: all 0.2s ease;
    border: 1px solid #f0f0f0;
}

.stat-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.stat-icon.orders { background: #e7eaf3; color: #2c3e6b; }
.stat-icon.spent { background: #d4edda; color: #155724; }
.stat-icon.joined { background: #d6f5f5; color: #0d6e6e; }

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--cafecat-dark);
    margin-bottom: 0.125rem;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--cafecat-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.orders-table th {
    background: var(--cafecat-light);
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
    color: var(--cafecat-dark);
    padding: 0.75rem 0.5rem;
    white-space: nowrap;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.orders-table td {
    padding: 0.75rem 0.5rem;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
    font-size: 0.85rem;
}

.orders-table tbody tr:hover {
    background: #fafafa;
}

.orders-table tbody tr:last-child td {
    border-bottom: none;
}

.order-status-badge {
    padding: 0.25rem 0.625rem;
    border-radius: 16px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: capitalize;
    white-space: nowrap;
}

.delivery-badge {
    padding: 0.25rem 0.625rem;
    border-radius: 16px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    white-space: nowrap;
}

.delivery-badge.assigned { background: #cfe2ff; color: #084298; }
.delivery-badge.pending { background: #fff3cd; color: #856404; }
.delivery-badge.none { background: #e9ecef; color: #6c757d; }

.action-btn {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
    white-space: nowrap;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
}

.order-status-pending { background: #fff3cd; color: #856404; }
.order-status-processing { background: #cfe2ff; color: #084298; }
.order-status-ready { background: #d1ecf1; color: #0c5460; }
.order-status-out_for_delivery { background: #e7eaf3; color: #2c3e6b; }
.order-status-delivered { background: #d4edda; color: #155724; }
.order-status-completed { background: #d4edda; color: #155724; }
.order-status-cancelled { background: #f8d7da; color: #721c24; }
.order-status-refunded { background: #e2e3e5; color: #383d41; }

.action-btn {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    white-space: nowrap;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.action-btn.edit { background: #d1ecf1; color: #0c5460; }
.action-btn.edit:hover { background: #17a2b8; color: white; }
.action-btn.delete { background: #f8d7da; color: #721c24; }
.action-btn.delete:hover { background: #dc3545; color: white; }
.action-btn.view { background: #e9ecef; color: var(--cafecat-dark); }
.action-btn.view:hover { background: var(--cafecat-primary); color: white; }
.action-btn.back { background: white; border: 1px solid #dee2e6; color: var(--cafecat-dark); }
.action-btn.back:hover { background: var(--cafecat-light); border-color: var(--cafecat-primary); color: var(--cafecat-primary); }

.btn-back {
    background: white;
    border: 1px solid #dee2e6;
    color: var(--cafecat-dark);
    padding: 0.625rem 1.25rem;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-back:hover {
    background: var(--cafecat-light);
    border-color: var(--cafecat-primary);
    color: var(--cafecat-primary);
}

.table-responsive {
    overflow-x: auto;
}

.empty-state {
    padding: 3rem 2rem;
    text-align: center;
}

.empty-state i {
    font-size: 2.5rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state h5 {
    color: var(--cafecat-secondary);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--cafecat-secondary);
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--cafecat-dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: var(--cafecat-primary);
}

.user-avatar-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: white;
    color: var(--cafecat-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

@media (max-width: 767px) {
    .user-avatar-large {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .detail-label {
        min-width: 120px;
        font-size: 0.875rem;
    }
    
    .detail-value {
        font-size: 0.875rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
    }
}
</style>

<?php
function getRoleBadge($role) {
    $classes = [
        'admin' => 'badge-role admin',
        'staff' => 'badge-role staff',
        'customer' => 'badge-role customer',
        'rider' => 'badge-role rider',
    ];
    $labels = [
        'admin' => 'Admin',
        'staff' => 'Staff',
        'customer' => 'Customer',
        'rider' => 'Rider',
    ];
    return '<span class="' . ($classes[$role] ?? 'badge-role') . '">' . ($labels[$role] ?? ucfirst($role)) . '</span>';
}

function getStatusBadge($verifiedAt) {
    if (!empty($verifiedAt)) {
        return '<span class="badge-status verified"><i class="bi bi-check-circle-fill"></i>Verified</span>';
    }
    return '<span class="badge-status unverified"><i class="bi bi-x-circle-fill"></i>Unverified</span>';
}

function getOrderStatusBadge($status) {
    $classes = [
        'pending' => 'order-status-pending',
        'processing' => 'order-status-processing',
        'ready' => 'order-status-ready',
        'out_for_delivery' => 'order-status-out_for_delivery',
        'delivered' => 'order-status-delivered',
        'completed' => 'order-status-completed',
        'cancelled' => 'order-status-cancelled',
        'refunded' => 'order-status-refunded',
    ];
    $class = $classes[$status] ?? 'order-status-pending';
    return '<span class="order-status-badge ' . $class . '">' . ucfirst(str_replace('_', ' ', $status)) . '</span>';
}
?>

<div class="users-container">
    <!-- Header -->
    <div class="users-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-avatar-large"><?= strtoupper(substr($user['first_name'] ?? 'A', 0, 1)) ?></div>
                        <div>
                            <h1 class="mb-1" style="font-size: 1.25rem;"><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></h1>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <?= getRoleBadge($user['role'] ?? '') ?>
                                <?= getStatusBadge($user['email_verified_at'] ?? null) ?>
                                <small class="text-white-50">ID: #<?= $user['id'] ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="/admin/users" class="action-btn back">
                        <i class="bi bi-arrow-left me-1"></i> Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Row -->
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon orders"><i class="bi bi-bag"></i></div>
                    <div class="stat-value"><?= $user['orders_count'] ?? 0 ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon spent"><i class="bi bi-currency-peso"></i></div>
                    <div class="stat-value">₱<?= number_format($user['total_spent'] ?? 0, 2) ?></div>
                    <div class="stat-label">Total Spent</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon joined"><i class="bi bi-calendar-plus"></i></div>
                    <div class="stat-value small"><?= date('M j, Y', strtotime($user['created_at'])) ?></div>
                    <div class="stat-label">Member Since</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Details -->
            <div class="col-lg-5 mb-4">
                <div class="detail-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-person-circle me-2"></i>Profile Information</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="detail-row px-4">
                            <span class="detail-label">First Name</span>
                            <span class="detail-value"><?= htmlspecialchars($user['first_name'] ?? '—') ?></span>
                        </div>
                        <div class="detail-row px-4">
                            <span class="detail-label">Last Name</span>
                            <span class="detail-value"><?= htmlspecialchars($user['last_name'] ?? '—') ?></span>
                        </div>
                        <div class="detail-row px-4">
                            <span class="detail-label">Email</span>
                            <span class="detail-value"><?= htmlspecialchars($user['email'] ?? '—') ?></span>
                        </div>
                        <div class="detail-row px-4">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value"><?= htmlspecialchars($user['phone'] ?? '—') ?></span>
                        </div>
                        <div class="detail-row px-4">
                            <span class="detail-label">Address</span>
                            <span class="detail-value"><?= htmlspecialchars($user['address'] ?? '—') ?></span>
                        </div>
                        <div class="detail-row px-4">
                            <span class="detail-label">Role</span>
                            <span class="detail-value"><?= getRoleBadge($user['role'] ?? '') ?></span>
                        </div>
                        <div class="detail-row px-4">
                            <span class="detail-label">Email Verified</span>
                            <span class="detail-value">
                                <?= getStatusBadge($user['email_verified_at'] ?? null) ?>
                                <?php if (!empty($user['email_verified_at'])): ?>
                                    <small class="text-muted ms-2">(<?= date('M j, Y H:i', strtotime($user['email_verified_at'])) ?>)</small>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="detail-row px-4">
                            <span class="detail-label">Created</span>
                            <span class="detail-value"><?= date('M j, Y H:i', strtotime($user['created_at'])) ?></span>
                        </div>
                        <div class="detail-row px-4">
                            <span class="detail-label">Updated</span>
                            <span class="detail-value"><?= date('M j, Y H:i', strtotime($user['updated_at'])) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="detail-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-gear me-2"></i>Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <a href="/admin/users/<?= $user['id'] ?>/edit" class="action-btn edit">
                                <i class="bi bi-pencil"></i> Edit User
                            </a>
                            <form method="POST" action="/admin/users/<?= $user['id'] ?>" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                <button type="submit" class="action-btn delete w-100">
                                    <i class="bi bi-trash"></i> Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders History -->
            <div class="col-lg-7">
                <div class="detail-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="bi bi-receipt me-2"></i>Order History</h5>
                        <span class="badge bg-dark"><?= $user['orders_count'] ?? 0 ?> orders</span>
                    </div>
                    <div class="card-body p-0">
                        <?php
                        // Fetch user orders
                        try {
                            $pdo = new PDO(
                                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                                DB_USER,
                                DB_PASS,
                                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
                            );
                            $stmt = $pdo->prepare("
                                SELECT id, order_number, status, total_amount, payment_method, created_at
                                FROM orders
                                WHERE user_id = ?
                                ORDER BY created_at DESC
                                LIMIT 20
                            ");
                            $stmt->execute([$user['id']]);
                            $orders = $stmt->fetchAll();
                        } catch (Exception $e) {
                            $orders = [];
                        }
                        ?>

                        <?php if (empty($orders)): ?>
                        <div class="empty-state" style="padding: 2rem;">
                            <i class="bi bi-receipt" style="font-size: 1.5rem;"></i>
                            <h6 class="text-muted mt-2 mb-1">No orders yet</h6>
                            <p class="text-muted small mb-0">This user hasn't placed any orders.</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table orders-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 90px;">Order #</th>
                                        <th style="width: 110px;">Date</th>
                                        <th style="width: 110px;">Status</th>
                                        <th style="width: 110px;">Delivery</th>
                                        <th style="width: 100px;">Total</th>
                                        <th style="width: 100px;">Payment</th>
                                        <th style="width: 70px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong class="small">#<?= htmlspecialchars($order['order_number'] ?? $order['id']) ?></strong></td>
                                        <td class="small"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                        <td><?= getOrderStatusBadge($order['status'] ?? '') ?></td>
                                        <td>
                                            <?php
                                            // Get delivery info for this order
                                            try {
                                                $dstmt = $pdo->prepare("
                                                    SELECT d.status, d.rider_id, CONCAT(u.first_name, ' ', u.last_name) as rider_name
                                                    FROM deliveries d
                                                    LEFT JOIN users u ON u.id = d.rider_id
                                                    WHERE d.order_id = ?
                                                    LIMIT 1
                                                ");
                                                $dstmt->execute([$order['id']]);
                                                $delivery = $dstmt->fetch();
                                            } catch (Exception $e) {
                                                $delivery = null;
                                            }
                                            ?>
                                            <?php if ($delivery): ?>
                                                <?php if ($delivery['rider_id']): ?>
                                                <span class="delivery-badge assigned">
                                                    <i class="bi bi-person-check"></i> <?= htmlspecialchars($delivery['rider_name'] ?? 'Rider') ?>
                                                </span>
                                                <?php else: ?>
                                                <span class="delivery-badge pending">
                                                    <i class="bi bi-clock"></i> Pending
                                                </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="delivery-badge none">
                                                    <i class="bi bi-dash"></i> None
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-primary small">₱<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                                        <td class="small"><?= htmlspecialchars(ucfirst($order['payment_method'] ?? '—')) ?></td>
                                        <td>
                                            <a href="/admin/orders/<?= $order['id'] ?>" class="action-btn view" title="View Order">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = $title ?? 'User Details - CafeCat';
$currentPage = 'users';
include __DIR__ . '/../layout.php';
?>