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

.orders-container {
    background: var(--cafecat-light);
    min-height: 100vh;
}

.orders-header {
    background: var(--cafecat-primary);
    color: white;
    padding: 1rem 0;
    margin-bottom: 0.2rem;
    border-radius: 0 0 20px 20px;
}

.orders-header h1 {
    font-weight: bold;
    margin: 0;
}

.orders-header .subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
}

.order-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    position: relative;
    overflow: hidden;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.order-card.pending {
    border-left-color: var(--cafecat-accent);
}

.order-card.confirmed {
    border-left-color: #17a2b8;
}

.order-card.preparing {
    border-left-color: var(--cafecat-primary);
}

.order-card.ready {
    border-left-color: #28a745;
}

.order-card.completed {
    border-left-color: var(--cafecat-secondary);
}

.order-card.cancelled {
    border-left-color: #dc3545;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f0f0f0;
}

.order-status-right {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    z-index: 10;
}

.order-number {
    font-size: 1rem;
    font-weight: bold;
    color: var(--cafecat-primary);
    padding-right: 80px;
}

.order-status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.order-content {
    display: grid;
    grid-template-columns: 1fr 140px;
    gap: 1rem;
}

.order-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.order-detail-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    flex-wrap: wrap;
}

.order-detail-label {
    font-weight: 600;
    color: var(--cafecat-dark);
    min-width: 60px;
    font-size: 0.8rem;
}

.order-detail-value {
    color: var(--cafecat-secondary);
    font-size: 0.8rem;
}

.order-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.action-btn {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    white-space: nowrap;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}

.action-btn.view {
    background: #e9ecef;
    color: var(--cafecat-dark);
}

.action-btn.view:hover {
    background: var(--cafecat-primary);
    color: white;
}

.action-btn.manage {
    background: #d1ecf1;
    color: #0c5460;
}

.action-btn.manage:hover {
    background: #17a2b8;
    color: white;
}

.action-btn.confirm {
    background: #d1ecf1;
    color: #0c5460;
}

.action-btn.confirm:hover {
    background: #17a2b8;
    color: white;
}

.action-btn.prepare {
    background: #cce5ff;
    color: #004085;
}

.action-btn.prepare:hover {
    background: var(--cafecat-primary);
    color: white;
}

.action-btn.ready {
    background: #d4edda;
    color: #155724;
}

.action-btn.ready:hover {
    background: #28a745;
    color: white;
}

.action-btn.complete {
    background: #e2e3e5;
    color: #383d41;
}

.action-btn.complete:hover {
    background: var(--cafecat-secondary);
    color: white;
}

.action-btn.cancel {
    background: #f8d7da;
    color: #721c24;
}

.action-btn.cancel:hover {
    background: #dc3545;
    color: white;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.customer-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--cafecat-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.75rem;
    flex-shrink: 0;
}

.payment-badge {
    padding: 0.25rem 0.625rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    white-space: nowrap;
}

.payment-paid {
    background: #d4edda;
    color: #155724;
}

.payment-pending {
    background: #fff3cd;
    color: #856404;
}

@media (max-width: 768px) {
    .order-content {
        grid-template-columns: 1fr;
    }

    .order-actions {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .action-btn {
        flex: 1;
        min-width: 100px;
    }
}

/* Table View Styles */
.orders-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}

.orders-table {
    margin-bottom: 0;
}

.orders-table th {
    background: var(--cafecat-primary);
    color: white;
    font-weight: 600;
    padding: 0.625rem 0.5rem;
    white-space: nowrap;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.orders-table td {
    padding: 0.625rem 0.5rem;
    vertical-align: middle;
    font-size: 0.8rem;
}

.orders-table tbody tr:hover {
    background: #fafafa;
}

.delivery-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.delivery-rider {
    font-weight: 600;
    color: var(--cafecat-dark);
    font-size: 0.8rem;
}

.delivery-status {
    font-size: 0.7rem;
    padding: 0.125rem 0.5rem;
    border-radius: 10px;
    display: inline-block;
    width: fit-content;
}

.delivery-status.pending { background: #fff3cd; color: #856404; }
.delivery-status.assigned { background: #cfe2ff; color: #084298; }
.delivery-status.picked_up { background: #d1ecf1; color: #0c5460; }
.delivery-status.delivered { background: #d4edda; color: #155724; }
.delivery-status.cancelled { background: #f8d7da; color: #721c24; }

.view-btn.active {
    background: var(--cafecat-primary) !important;
    color: white !important;
}

#tableViewContainer {
    display: none;
}

@media (max-width: 1199px) {
    .orders-table th:nth-child(5),
    .orders-table td:nth-child(5) {
        display: none;
    }
}

@media (max-width: 991px) {
    .orders-table th:nth-child(8),
    .orders-table td:nth-child(8) {
        display: none;
    }
}

@media (max-width: 767px) {
    .orders-table th:nth-child(4),
    .orders-table td:nth-child(4),
    .orders-table th:nth-child(7),
    .orders-table td:nth-child(7) {
        display: none;
    }
}

/* 5-column grid for card view */
.orders-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.75rem;
}

@media (max-width: 1400px) {
    .orders-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 1200px) {
    .orders-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .orders-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .orders-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
// Helper function to get valid status transitions
function getValidStatusTransitions($currentStatus) {
    $statusFlow = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['preparing', 'cancelled'],
        'preparing' => ['ready', 'cancelled'],
        'ready' => ['completed', 'cancelled'],
        'completed' => [],
        'cancelled' => []
    ];
    return $statusFlow[$currentStatus] ?? [];
}

function getStatusButtonClass($status) {
    $classes = [
        'pending' => 'warning',
        'confirmed' => 'info',
        'preparing' => 'primary',
        'ready' => 'success',
        'completed' => 'secondary',
        'cancelled' => 'danger'
    ];
    return $classes[$status] ?? 'secondary';
}

function getActionClass($status) {
    $classes = [
        'confirmed' => 'confirm',
        'preparing' => 'prepare',
        'ready' => 'ready',
        'completed' => 'complete',
        'cancelled' => 'cancel'
    ];
    return $classes[$status] ?? 'confirm';
}

function getActionIcon($status) {
    $icons = [
        'confirmed' => 'bi-check-circle',
        'preparing' => 'bi-clock',
        'ready' => 'bi-check2',
        'completed' => 'bi-check-all',
        'cancelled' => 'bi-x-circle'
    ];
    return $icons[$status] ?? 'bi-check-circle';
}
?>

<div class="orders-container">
    <div class="container">

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Order #, customer..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="confirmed" <?= ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="preparing" <?= ($_GET['status'] ?? '') === 'preparing' ? 'selected' : '' ?>>Preparing</option>
                    <option value="ready" <?= ($_GET['status'] ?? '') === 'ready' ? 'selected' : '' ?>>Ready</option>
                    <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Payment</label>
                <select name="payment_status" class="form-select">
                    <option value="">All Payment</option>
                    <option value="paid" <?= ($_GET['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="pending" <?= ($_GET['payment_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="/admin/orders" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small">View</label>
                <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-outline-secondary" id="cardViewBtn" onclick="switchView('card')">
                        <i class="bi bi-grid-3x3-gap"></i> Cards
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="tableViewBtn" onclick="switchView('table')">
                        <i class="bi bi-table"></i> Table
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Cards -->
<?php if (empty($orders ?? [])): ?>
<div class="text-center py-5">
    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
    <h4 class="text-muted">No orders found</h4>
    <p class="text-muted">There are no orders matching your criteria.</p>
</div>
<?php else: ?>

<!-- Card View -->
<div id="cardViewContainer">
    <div class="orders-grid">
        <?php foreach ($orders as $order): ?>
        <div class="order-card <?= $order['status'] ?>">
    <div class="order-header">
        <div>
            <div class="order-number"><a class="order-number-link" href="/admin/orders/<?= $order['id'] ?>">#<?= $order['order_number'] ?></a></div>
            <small class="text-muted"><?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></small>
        </div>
    </div>

    <div class="order-content">
        <div class="order-details">
            <!-- Product Preview -->
            <?php if (!empty($order['first_product_image'])): ?>
            <div style="margin-bottom: 0.5rem;">
                <img src="/assets/images/products/<?= htmlspecialchars($order['first_product_image']) ?>"
                     alt="Product"
                     style="width: 100%; height: 80px; object-fit: cover; border-radius: 8px;">
            </div>
            <?php endif; ?>

            <!-- Customer Info -->
            <div class="customer-info">
                <div class="customer-avatar">
                    <?= strtoupper(substr($order['customer_name'] ?? 'A', 0, 1)) ?>
                </div>
                <div>
                    <div class="fw-bold"><?= $order['customer_name'] ?? 'N/A' ?></div>
                    <small class="text-muted"><?= $order['customer_email'] ?? 'N/A' ?></small>
                </div>
            </div>

            <!-- Order Details -->
            <div class="order-detail-item">
                <span class="order-detail-label">Items:</span>
                <span class="order-detail-value"><?= $order['items_count'] ?? 0 ?> items</span>
            </div>

            <div class="order-detail-item">
                <span class="order-detail-label">Total:</span>
                <span class="order-detail-value fw-bold">₱<?= number_format($order['total_amount'], 2) ?></span>
                <?php if (!empty($order['discount_type']) && $order['discount_type'] !== 'none'): ?>
                    <span class="badge <?= ($order['discount_status'] ?? '') === 'pending' ? 'bg-warning' : 'bg-dark' ?>" title="ID: <?= htmlspecialchars($order['discount_id_no'] ?? '—') ?>">
                        <i class="bi bi-badge-ad"></i> <?= $order['discount_type'] === 'senior' ? 'Senior' : 'PWD' ?> −20% · <?= ucfirst($order['discount_status'] ?? 'pending') ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="order-detail-item">
                <span class="order-detail-label">Delivery:</span>
                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <?php if (!empty($order['rider_first_name'])): ?>
                        <span class="badge bg-dark" title="Delivery: <?= ucfirst(str_replace('_', ' ', $order['delivery_status'] ?? '')) ?>">
                            <i class="bi bi-bicycle"></i> <?= htmlspecialchars(trim($order['rider_first_name'] . ' ' . ($order['rider_last_name'] ?? ''))) ?> · <?= ucfirst(str_replace('_', ' ', $order['delivery_status'] ?? '')) ?>
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary">No rider</span>
                    <?php endif; ?>
                    <?php if (!empty($order['pending_subs'])): ?>
                        <span class="badge bg-warning">
                            <i class="bi bi-arrow-left-right"></i> <?= (int)$order['pending_subs'] ?> substitution<?= (int)$order['pending_subs'] > 1 ? 's' : '' ?> to review
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="order-detail-item">
                <span class="order-detail-label">Payment:</span>
                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <span class="payment-badge <?= $order['payment_status'] === 'paid' ? 'payment-paid' : 'payment-pending' ?>">
                        <?= ucfirst($order['payment_status']) ?>
                    </span>
                    <span class="badge bg-info">
                        <?= ucfirst($order['payment_method'] ?? 'cash') ?>
                    </span>
                    <?php if (in_array(strtolower($order['payment_method'] ?? 'cash'), ['gcash', 'paymaya', 'maya', 'bank_transfer'], true) && $order['payment_status'] !== 'paid' && $order['status'] !== 'cancelled'): ?>
                    <span class="badge bg-warning">
                        <i class="bi bi-exclamation-triangle"></i> Awaiting Proof
                    </span>
                    <?php endif; ?>

                    <?php if ($order['status'] === 'cancelled' && $order['refund_info']): ?>
                        <?php
                        $refund = $order['refund_info'];
                        $refundStatus = $refund['refund_status'];
                        $refundMethod = $refund['refund_method'];
                        $refundAmount = $refund['refund_amount'];
                        ?>
                        <?php if ($refundStatus === 'processed'): ?>
                            <span class="badge bg-success" title="Refunded: ₱<?= number_format($refundAmount, 2) ?> via <?= ucfirst($refundMethod) ?>">
                                <i class="bi bi-arrow-clockwise"></i> Refunded
                            </span>
                        <?php elseif ($refundStatus === 'pending'): ?>
                            <span class="badge bg-warning" title="Refund Pending: ₱<?= number_format($refundAmount, 2) ?> via <?= ucfirst($refundMethod) ?>">
                                <i class="bi bi-clock"></i> Refund Pending
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger" title="Refund Failed: ₱<?= number_format($refundAmount, 2) ?>">
                                <i class="bi bi-x-circle"></i> Refund Failed
                            </span>
                        <?php endif; ?>
                    <?php elseif ($order['status'] === 'cancelled' && in_array(strtolower($order['payment_method'] ?? 'cash'), ['gcash', 'paymaya', 'maya', 'bank_transfer'], true)): ?>
                        <span class="badge bg-secondary" title="No refund processed">
                            <i class="bi bi-dash-circle"></i> No Refund
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="order-actions">
            <!-- View Details -->
            <a href="/admin/orders/<?= $order['id'] ?>" class="action-btn view">
                <i class="bi bi-eye"></i>
                View
            </a>

            <!-- Manage Order -->
            <button type="button" class="action-btn manage" onclick="showManageOrderModal(<?= $order['id'] ?>, '<?= htmlspecialchars($order['order_number'] ?? '') ?>')">
                <i class="bi bi-gear"></i>
                Manage Order
            </button>

            <!-- Cancel Order -->
            <?php if ($order['status'] !== 'cancelled' && $order['status'] !== 'completed'): ?>
            <button type="button" class="action-btn cancel" onclick="showCancelOrderModal(<?= $order['id'] ?>, '<?= htmlspecialchars($order['order_number'] ?? '') ?>')">
                <i class="bi bi-x-circle"></i>
                Cancel
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Status Badge at Right Side -->
    <div class="order-status-right">
        <div class="order-status-badge bg-<?= getStatusButtonClass($order['status']) ?>">
            <?= ucfirst($order['status']) ?>
        </div>
    </div>
</div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Table View -->
<div id="tableViewContainer">
    <div class="orders-table-container">
        <div class="table-responsive">
            <table class="table table-hover table-bordered orders-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">Image</th>
                        <th style="width: 80px;">Order #</th>
                        <th style="min-width: 140px;">Customer</th>
                        <th style="width: 90px;">Date</th>
                        <th style="width: 90px;">Status</th>
                        <th style="width: 120px;">Delivery</th>
                        <th style="width: 50px;">Items</th>
                        <th style="width: 90px;">Total</th>
                        <th style="width: 100px;">Payment</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <?php if (!empty($order['first_product_image'])): ?>
                                <img src="/assets/images/products/<?= htmlspecialchars($order['first_product_image']) ?>"
                                     alt="Product"
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                                <div style="width: 40px; height: 40px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-cup-hot text-muted small"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/orders/<?= $order['id'] ?>" class="fw-bold text-decoration-none small">
                                #<?= $order['order_number'] ?>
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="customer-avatar" style="width: 28px; height: 28px; font-size: 0.7rem;">
                                    <?= strtoupper(substr($order['customer_name'] ?? 'A', 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold small"><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></div>
                                    <small class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($order['customer_email'] ?? 'N/A') ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small">
                            <?= date('M j', strtotime($order['created_at'])) ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= getStatusButtonClass($order['status']) ?>" style="font-size: 0.7rem;">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="delivery-info">
                                <?php if (!empty($order['rider_first_name'])): ?>
                                    <span class="delivery-rider">
                                        <i class="bi bi-bicycle"></i>
                                        <?= htmlspecialchars(trim($order['rider_first_name'] . ' ' . ($order['rider_last_name'] ?? ''))) ?>
                                    </span>
                                    <span class="delivery-status <?= htmlspecialchars($order['delivery_status'] ?? 'pending') ?>">
                                        <?= ucfirst(str_replace('_', ' ', $order['delivery_status'] ?? 'pending')) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">No rider</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="fw-bold small"><?= $order['items_count'] ?? 0 ?></td>
                        <td class="fw-bold text-primary small">
                            ₱<?= number_format($order['total_amount'], 2) ?>
                            <?php if (!empty($order['discount_type']) && $order['discount_type'] !== 'none'): ?>
                                <br><span class="badge bg-warning" style="font-size: 0.65rem;"><?= $order['discount_type'] === 'senior' ? 'Senior' : 'PWD' ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $order['payment_status'] === 'paid' ? 'bg-success' : 'bg-warning' ?>" style="font-size: 0.7rem;">
                                <?= ucfirst($order['payment_status']) ?>
                            </span>
                            <br>
                            <span class="badge bg-info" style="font-size: 0.7rem;">
                                <?= ucfirst($order['payment_method'] ?? 'cash') ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-outline-primary" title="View Details" style="padding: 0.25rem 0.5rem;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if (in_array(strtolower($order['payment_method'] ?? 'cash'), ['gcash', 'paymaya', 'maya', 'bank_transfer'], true) && $order['payment_status'] !== 'paid' && $order['status'] !== 'cancelled'): ?>
                                <button class="btn btn-outline-info"
                                        onclick="showPaymentProofModal(<?= $order['id'] ?>, '<?= $order['order_number'] ?? '' ?>')"
                                        title="Verify Payment" style="padding: 0.25rem 0.5rem;">
                                    <i class="bi bi-credit-card"></i>
                                </button>
                                <?php endif; ?>
                                <?php
                                $validTransitions = getValidStatusTransitions($order['status']);
                                foreach ($validTransitions as $nextStatus):
                                    if ($nextStatus === 'confirmed' && in_array(strtolower($order['payment_method'] ?? 'cash'), ['gcash', 'paymaya', 'maya', 'bank_transfer'], true) && $order['payment_status'] !== 'paid') {
                                        continue;
                                    }
                                ?>
                                <?php if ($nextStatus === 'cancelled' && in_array(strtolower($order['payment_method'] ?? 'cash'), ['gcash', 'paymaya', 'maya', 'bank_transfer'], true)): ?>
                                    <button type="button" class="btn btn-outline-danger"
                                            title="Cancel Order"
                                            onclick="showGcashCancelModal(<?= $order['id'] ?>, <?= $order['total_amount'] ?>)"
                                            style="padding: 0.25rem 0.5rem;">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                <?php else: ?>
                                    <form method="POST" action="/admin/orders/<?= $order['id'] ?>" style="display: inline;">
                                        <input type="hidden" name="status" value="<?= $nextStatus ?>">
                                        <input type="hidden" name="notes" value="">
                                        <button type="button" class="btn btn-outline-<?= $nextStatus === 'cancelled' ? 'danger' : ($nextStatus === 'completed' ? 'secondary' : 'primary') ?>"
                                                title="Change to <?= ucfirst($nextStatus) ?>"
                                                onclick="confirmStatusChange('<?= $order['id'] ?>', '<?= $nextStatus ?>', '<?= ucfirst($nextStatus) ?>')"
                                                style="padding: 0.25rem 0.5rem;">
                                            <i class="bi <?= getActionIcon($nextStatus) ?>"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
        
        <!-- Pagination -->
        <?php if (($totalPages ?? 1) > 1): ?>
        <nav aria-label="Orders pagination">
            <ul class="pagination justify-content-center mt-4">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<!-- Payment Proof Verification Modal -->
<div class="modal fade" id="paymentProofModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Payment - Order #<span id="paymentOrderNumber"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Payment Proof</h6>
                        <div id="paymentProofContainer" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading payment proof...</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Verification Actions</h6>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" onclick="verifyPayment(true)">
                                <i class="bi bi-check-circle me-2"></i>Payment Verified
                            </button>
                        </div>
                        
                        <div class="mt-3">
                            <label for="rejectionReason" class="form-label">Note</label>
                            <textarea class="form-control" id="rejectionReason" rows="3" placeholder="Enter note..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="modalOrderId">
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-select" name="status" id="modalStatus" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="preparing">Preparing</option>
                            <option value="ready">Ready</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" id="modalNotes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- GCash Cancellation Modal -->
<div class="modal fade" id="gcashCancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Cancel GCash Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Important:</strong> This order was paid via GCash. Please provide a reason for cancellation and process any refunds if necessary.
                </div>
                
                <div class="mb-3">
                    <label for="cancelReason" class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="cancelReason" rows="3" 
                              placeholder="Please specify why this GCash order needs to be cancelled..." required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="refundAmount" class="form-label">Refund Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" class="form-control" id="refundAmount" 
                               placeholder="0.00" step="0.01" min="0">
                        <span class="input-group-text">Full Refund</span>
                    </div>
                    <small class="text-muted">Enter the amount to refund to the customer.</small>
                </div>
                
                <div class="mb-3">
                    <label for="refundMethod" class="form-label">Refund Method</label>
                    <select class="form-select" id="refundMethod">
                        <option value="">Select refund method...</option>
                        <option value="gcash">GCash (Original Payment Method)</option>
                        <option value="cash">Cash Refund</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="store_credit">Store Credit</option>
                        <option value="no_refund">No Refund</option>
                    </select>
                </div>
                
                <div class="mb-3" id="refundNotesGroup" style="display: none;">
                    <label for="refundNotes" class="form-label">Refund Notes</label>
                    <textarea class="form-control" id="refundNotes" rows="2" 
                              placeholder="Additional details about the refund process..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
                <button type="button" class="btn btn-danger" onclick="processGcashCancellation()">
                    <i class="bi bi-x-circle me-2"></i>Cancel Order
                </button>
                <button type="button" class="btn btn-success" id="refundBtn" onclick="processRefund()" style="display: none;">
                    <i class="bi bi-arrow-clockwise me-2"></i>Process Refund
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentOrderId = null;

function showPaymentProofModal(orderId, orderNumber) {
    currentOrderId = orderId;
    document.getElementById('paymentOrderNumber').textContent = orderNumber;
    document.getElementById('rejectionReason').value = '';
    
    // Show loading state
    const container = document.getElementById('paymentProofContainer');
    container.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading payment proof...</p>
    `;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('paymentProofModal'));
    modal.show();
    
    // Load payment proof
    loadPaymentProof(orderId);
}

function loadPaymentProof(orderId) {
    // Fetch actual payment proof from server
    fetch(`/admin/orders/${orderId}/payment-proof`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('paymentProofContainer');
            
            if (data.success && data.proof_image) {
                container.innerHTML = `
                    <div class="payment-proof-wrapper">
                        <img src="${data.proof_image}" 
                             class="img-fluid rounded" 
                             alt="Payment Proof"
                             style="max-width: 100%; height: auto; cursor: pointer;"
                             onclick="window.open('${data.proof_image}', '_blank')">
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="window.open('${data.proof_image}', '_blank')">
                                <i class="bi bi-box-arrow-up-right me-1"></i>View in New Tab
                            </button>
                        </div>
                        <p class="mt-2 text-muted">
                            <small>Payment proof for Order #${orderId}</small><br>
                            <small>Amount: ₱${data.amount || 'N/A'} | Method: ${data.payment_method || 'GCash'}</small>
                        </p>
                    </div>
                `;
            } else {
                // Fallback to placeholder if no proof found
                container.innerHTML = `
                    <div class="payment-proof-wrapper">
                        <img src="https://via.placeholder.com/400x300/e09407/ffffff?text=No+Payment+Proof+Found" 
                             class="img-fluid rounded" 
                             alt="No Payment Proof"
                             style="max-width: 100%; height: auto;">
                        <p class="mt-2 text-muted">
                            <small>No payment proof uploaded for Order #${orderId}</small><br>
                            <small>Amount: ₱${data.amount || 'N/A'} | Method: ${data.payment_method || 'GCash'}</small>
                        </p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading payment proof:', error);
            const container = document.getElementById('paymentProofContainer');
            container.innerHTML = `
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Unable to load payment proof
                </div>
            `;
        });
}

function verifyPayment(approved) {
    if (!currentOrderId) return;
    
    if (approved) {
        if (confirm('Are you sure you want to verify this payment?')) {
            // Submit verification approval
            submitPaymentVerification(currentOrderId, true, '');
        }
    } else {
        const reason = document.getElementById('rejectionReason').value.trim();
        if (!reason) {
            alert('Please provide a reason for rejection.');
            return;
        }
        
        if (confirm('Are you sure you want to reject this payment?')) {
            // Submit verification rejection
            submitPaymentVerification(currentOrderId, false, reason);
        }
    }
}

function submitPaymentVerification(orderId, approved, reason) {
    const formData = new FormData();
    formData.append('order_id', orderId);
    formData.append('approved', approved);
    formData.append('reason', reason);
    
    fetch('/admin/orders/verify-payment', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('paymentProofModal')).hide();
            
            // Show success message
            const messageDiv = document.createElement('div');
            messageDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            messageDiv.style.zIndex = '9999';
            messageDiv.innerHTML = `
                <i class="bi bi-check-circle-fill me-2"></i>
                Payment ${approved ? 'verified' : 'rejected'} successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(messageDiv);
            
            // Remove alert and reload page
            setTimeout(() => {
                messageDiv.remove();
                location.reload();
            }, 2000);
        } else {
            alert('Error: ' + (data.message || 'Failed to verify payment'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error verifying payment. Please try again.');
    });
}

// GCash Cancellation Functions
let currentGcashOrderId = null;
let currentGcashAmount = 0;

function showGcashCancelModal(orderId, totalAmount) {
    currentGcashOrderId = orderId;
    currentGcashAmount = totalAmount;
    
    // Reset form
    document.getElementById('cancelReason').value = '';
    document.getElementById('refundAmount').value = totalAmount.toFixed(2);
    document.getElementById('refundMethod').value = '';
    document.getElementById('refundNotes').value = '';
    document.getElementById('refundNotesGroup').style.display = 'none';
    document.getElementById('refundBtn').style.display = 'none';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('gcashCancelModal'));
    modal.show();
}

// Handle refund method change
document.addEventListener('DOMContentLoaded', function() {
    const refundMethod = document.getElementById('refundMethod');
    if (refundMethod) {
        refundMethod.addEventListener('change', function() {
            const refundNotesGroup = document.getElementById('refundNotesGroup');
            const refundBtn = document.getElementById('refundBtn');

            if (this.value && this.value !== 'no_refund') {
                refundNotesGroup.style.display = 'block';
                refundBtn.style.display = 'inline-block';
            } else {
                refundNotesGroup.style.display = 'none';
                refundBtn.style.display = 'none';
            }
        });
    }

    // Initialize view preference
    initializeViewPreference();
});

// View Switching Functions
function switchView(view) {
    const cardContainer = document.getElementById('cardViewContainer');
    const tableContainer = document.getElementById('tableViewContainer');
    const cardBtn = document.getElementById('cardViewBtn');
    const tableBtn = document.getElementById('tableViewBtn');

    if (view === 'card') {
        cardContainer.style.display = 'block';
        tableContainer.style.display = 'none';
        cardBtn.classList.add('active');
        tableBtn.classList.remove('active');
    } else {
        cardContainer.style.display = 'none';
        tableContainer.style.display = 'block';
        cardBtn.classList.remove('active');
        tableBtn.classList.add('active');
    }

    // Save preference to localStorage
    localStorage.setItem('ordersViewPreference', view);
}

function initializeViewPreference() {
    const savedView = localStorage.getItem('ordersViewPreference') || 'table';
    switchView(savedView);
}

function processGcashCancellation() {
    const reason = document.getElementById('cancelReason').value.trim();
    const refundAmount = document.getElementById('refundAmount').value;
    const refundMethod = document.getElementById('refundMethod').value;
    
    if (!reason) {
        alert('Please provide a cancellation reason.');
        return;
    }
    
    if (!refundAmount || parseFloat(refundAmount) < 0) {
        alert('Please enter a valid refund amount.');
        return;
    }
    
    const confirmMessage = refundMethod && refundMethod !== 'no_refund' 
        ? `Are you sure you want to cancel this GCash order and process a ₱${refundAmount} refund?`
        : 'Are you sure you want to cancel this GCash order?';
    
    if (confirm(confirmMessage)) {
        const formData = new FormData();
        formData.append('order_id', currentGcashOrderId);
        formData.append('status', 'cancelled');
        formData.append('notes', `GCash Order Cancellation: ${reason}`);
        formData.append('refund_amount', refundAmount);
        formData.append('refund_method', refundMethod);
        formData.append('refund_notes', document.getElementById('refundNotes').value);
        formData.append('is_gcash_cancel', 'true');
        
        fetch('/admin/orders/' + currentGcashOrderId + '/status', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('gcashCancelModal')).hide();
                const messageDiv = document.createElement('div');
                messageDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                messageDiv.style.zIndex = '9999';
                messageDiv.innerHTML = `
                    <i class="bi bi-check-circle-fill me-2"></i>
                    GCash order cancelled successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(messageDiv);
                setTimeout(() => {
                    messageDiv.remove();
                    location.reload();
                }, 2000);
            } else {
                alert('Error: ' + (data.message || 'Failed to cancel order'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error cancelling order. Please try again.');
        });
    }
}

function processRefund() {
    const refundAmount = document.getElementById('refundAmount').value;
    const refundMethod = document.getElementById('refundMethod').value;
    const refundNotes = document.getElementById('refundNotes').value;
    
    if (!refundAmount || parseFloat(refundAmount) <= 0) {
        alert('Please enter a valid refund amount.');
        return;
    }
    
    if (!refundMethod) {
        alert('Please select a refund method.');
        return;
    }
    
    const confirmMessage = `Process ₱${refundAmount} refund via ${refundMethod}?`;
    
    if (confirm(confirmMessage)) {
        const formData = new FormData();
        formData.append('order_id', currentGcashOrderId);
        formData.append('action', 'process_refund');
        formData.append('refund_amount', refundAmount);
        formData.append('refund_method', refundMethod);
        formData.append('refund_notes', refundNotes);
        
        fetch('/admin/orders/process-refund', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Refund processed successfully!');
                bootstrap.Modal.getInstance(document.getElementById('gcashCancelModal')).hide();
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('Error: ' + (data.message || 'Failed to process refund'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error processing refund. Please try again.');
        });
    }
}

function showManageOrderModal(orderId, orderNumber) {
    if (confirm(`Manage order #${orderNumber}? You will be redirected to the order details page.`)) {
        window.location.href = '/admin/orders/' + orderId;
    }
}

function showCancelOrderModal(orderId, orderNumber) {
    if (confirm(`Are you sure you want to cancel order #${orderNumber}? This action cannot be undone.`)) {
        window.location.href = '/admin/orders/' + orderId + '/cancel';
    }
}

function confirmStatusChange(orderId, newStatus, statusDisplay) {
    const confirmMessage = `Are you sure you want to change this order status to ${statusDisplay}?`;
    
    if (confirm(confirmMessage)) {
        const formId = 'statusForm' + orderId + newStatus;
        const form = document.getElementById(formId);
        
        if (form) {
            const button = form.querySelector('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';
            button.disabled = true;
            
            form.submit();
        } else {
            alert('Error: Form not found. Please refresh the page and try again.');
        }
    }
    
    return false;
}
</script>

<?php
$content = ob_get_clean();
$title = 'Manage Orders - CafeCat';
$currentPage = 'orders';
include __DIR__ . '/../layout.php';
?>
