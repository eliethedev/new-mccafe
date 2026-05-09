<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Order Details</h1>
    <div>
        <button class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Print
        </button>
        <a href="/admin/orders" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Orders
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Order Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Order Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Order Number:</strong> #<?= $order['order_number'] ?? 'N/A' ?></p>
                        <p><strong>Order Date:</strong> <?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></p>
                        <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method'] ?? 'N/A') ?></p>
                        <p><strong>Payment Status:</strong> 
                            <span class="badge bg-<?= $order['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                                <?= ucfirst($order['payment_status'] ?? 'N/A') ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Order Status:</strong> 
                            <?php
                            $statusColors = [
                                'pending' => 'warning',
                                'confirmed' => 'info',
                                'preparing' => 'primary',
                                'ready' => 'success',
                                'completed' => 'secondary',
                                'cancelled' => 'danger'
                            ];
                            $color = $statusColors[$order['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $color ?>">
                                <?= ucfirst($order['status'] ?? 'N/A') ?>
                            </span>
                        </p>
                        <p><strong>Total Amount:</strong> <strong>₱<?= number_format($order['total_amount'], 2) ?></strong></p>
                        <p><strong>Prepared By:</strong> <?= $order['prepared_by_name'] ?? 'Not assigned' ?></p>
                    </div>
                </div>
                
                <?php if (!empty($order['notes'])): ?>
                <div class="mt-3">
                    <strong>Order Notes:</strong>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orderItems ?? [])): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No items found</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td>
                                    <strong><?= $item['product_name'] ?></strong>
                                </td>
                                <td><?= $item['variant_name'] ?? '-' ?></td>
                                <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><strong>₱<?= number_format($item['total_price'], 2) ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">Subtotal:</th>
                                <th>₱<?= number_format($order['subtotal'], 2) ?></th>
                            </tr>
                            <?php if ($order['tax_amount'] > 0): ?>
                            <tr>
                                <th colspan="4">Tax:</th>
                                <th>₱<?= number_format($order['tax_amount'], 2) ?></th>
                            </tr>
                            <?php endif; ?>
                            <tr class="table-primary">
                                <th colspan="4">Total:</th>
                                <th>₱<?= number_format($order['total_amount'], 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?= $order['customer_name'] ?? 'N/A' ?></p>
                <p><strong>Email:</strong> <?= $order['customer_email'] ?? 'N/A' ?></p>
                <p><strong>Phone:</strong> <?= $order['customer_phone'] ?? 'N/A' ?></p>
            </div>
        </div>

    

        <!-- Status History -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Status History</h5>
            </div>
            <div class="card-body">
                <?php if (empty($statusHistory ?? [])): ?>
                <p class="text-muted">No status history available</p>
                <?php else: ?>
                <div class="timeline">
                    <?php foreach ($statusHistory as $history): ?>
                    <div class="timeline-item mb-3">
                        <div class="d-flex justify-content-between">
                            <strong><?= ucfirst($history['status']) ?></strong>
                            <small class="text-muted"><?= date('M j, g:i A', strtotime($history['created_at'])) ?></small>
                        </div>
                        <?php if (!empty($history['notes'])): ?>
                        <small class="text-muted"><?= $history['notes'] ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-item {
    border-left: 3px solid #007bff;
    padding-left: 15px;
    margin-left: 5px;
}
.timeline-item:last-child {
    border-left-color: #28a745;
}
</style>

<?php
$content = ob_get_clean();
$title = 'Order Details - McCafe';
$currentPage = 'orders';
include __DIR__ . '/../layout.php';
?>
