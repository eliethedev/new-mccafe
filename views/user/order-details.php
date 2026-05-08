<?php 
require_once __DIR__ . '/../../models/Order.php';
ob_start(); 
?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-receipt me-2"></i>Order Details</h2>
                <a href="/orders" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Orders
                </a>
            </div>
            
            <div class="row">
                <!-- Order Information -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-receipt-cutoff me-2"></i>
                                <?= htmlspecialchars($order['order_number']) ?>
                            </h5>
                            <span class="badge <?= Order::getStatusBadgeClass($order['status']) ?> fs-6">
                                <?= Order::getStatusText($order['status']) ?>
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted">Order Date:</small>
                                    <p class="mb-0"><?= date('F d, Y h:i A', strtotime($order['created_at'])) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Payment Method:</small>
                                    <p class="mb-0 text-capitalize"><?= htmlspecialchars($order['payment_method']) ?></p>
                                </div>
                            </div>
                            
                            <?php if ($order['notes']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Order Notes:</small>
                                    <p class="mb-0"><?= htmlspecialchars($order['notes']) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Order Items -->
                            <h6 class="fw-bold mb-3">Order Items:</h6>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderItems as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($item['image']): ?>
                                                            <img src="/public/assets/images/products/<?= htmlspecialchars($item['image']) ?>" 
                                                                 alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                                                 class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                        <?php else: ?>
                                                            <div class="me-3 bg-light d-flex align-items-center justify-content-center" 
                                                                 style="width: 50px; height: 50px; border-radius: 8px;">
                                                                <i class="bi bi-image text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                                            <?php if ($item['variant_name']): ?>
                                                                <br><small class="text-muted"><?= htmlspecialchars($item['variant_name']) ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?= $item['quantity'] ?></td>
                                                <td class="text-end">₱<?= number_format($item['unit_price'], 2) ?></td>
                                                <td class="text-end fw-bold">₱<?= number_format($item['total_price'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Subtotal:</th>
                                            <td class="text-end">₱<?= number_format($order['subtotal'], 2) ?></td>
                                        </tr>
                                        <?php if ($order['tax_amount'] > 0): ?>
                                            <tr>
                                                <th colspan="3">Tax:</th>
                                                <td class="text-end">₱<?= number_format($order['tax_amount'], 2) ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr class="table-primary">
                                            <th colspan="3">Total Amount:</th>
                                            <td class="text-end fw-bold">₱<?= number_format($order['total_amount'], 2) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status History -->
                    <?php if (!empty($statusHistory)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-clock-history me-2"></i>Order Status History
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <?php foreach ($statusHistory as $history): ?>
                                        <div class="d-flex mb-3">
                                            <div class="me-3">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-check-circle text-success"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong><?= Order::getStatusText($history['status']) ?></strong>
                                                        <?php if ($history['notes']): ?>
                                                            <br><small class="text-muted"><?= htmlspecialchars($history['notes']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= date('M d, Y h:i A', strtotime($history['created_at'])) ?>
                                                    </small>
                                                </div>
                                                <?php if ($history['first_name'] || $history['last_name']): ?>
                                                    <small class="text-muted">
                                                        by <?= htmlspecialchars($history['first_name'] . ' ' . $history['last_name']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Customer Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-person me-2"></i>Customer Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>Name:</strong><br>
                                <?= htmlspecialchars($order['customer_name']) ?>
                            </p>
                            <?php if ($order['customer_phone']): ?>
                                <p class="mb-2">
                                    <strong>Phone:</strong><br>
                                    <?= htmlspecialchars($order['customer_phone']) ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($order['customer_email']): ?>
                                <p class="mb-0">
                                    <strong>Email:</strong><br>
                                    <?= htmlspecialchars($order['customer_email']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-gear me-2"></i>Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (in_array($order['status'], ['pending', 'confirmed'])): ?>
                                <button class="btn btn-danger w-100 mb-2 cancel-order-btn" 
                                        data-order-id="<?= $order['id'] ?>"
                                        data-order-number="<?= htmlspecialchars($order['order_number']) ?>">
                                    <i class="bi bi-x-circle me-2"></i>Cancel Order
                                </button>
                            <?php endif; ?>
                            
                            <a href="/menu" class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-plus-circle me-2"></i>Order Again
                            </a>
                            
                            <button class="btn btn-outline-secondary w-100" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i>Print Receipt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel order <strong id="cancelOrderNumber"></strong>?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Order</button>
                <button type="button" class="btn btn-danger" id="confirmCancelOrder">Yes, Cancel Order</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cancelOrderModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
    let currentOrderId = null;
    
    // Handle cancel order button clicks
    document.querySelectorAll('.cancel-order-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentOrderId = this.dataset.orderId;
            document.getElementById('cancelOrderNumber').textContent = this.dataset.orderNumber;
            cancelOrderModal.show();
        });
    });
    
    // Handle confirm cancel
    document.getElementById('confirmCancelOrder').addEventListener('click', function() {
        if (currentOrderId) {
            fetch(`/orders/${currentOrderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/orders';
                } else {
                    alert(data.message || 'Failed to cancel order');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while cancelling the order');
            });
        }
    });
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    
    .btn {
        display: none !important;
    }
}
</style>

<?php
$content = ob_get_clean();
$title = 'Order Details - MacCafe';
include __DIR__ . '/../layouts/main.php';
?>
