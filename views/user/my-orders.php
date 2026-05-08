<?php 
require_once __DIR__ . '/../../models/Order.php';
ob_start(); 
?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-bag-check me-2"></i>My Orders</h2>
                <a href="/dashboard" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
            
            <?php if (Session::getFlash('message')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= Session::getFlash('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (empty($orders)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-bag-x fs-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No orders yet</h4>
                    <p class="text-muted">You haven't placed any orders yet.</p>
                    <a href="/menu" class="btn btn-primary">
                        <i class="bi bi-cup-hot me-2"></i>Order Now
                    </a>
                </div>
            <?php else: ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Showing <span class="fw-bold"><?= count($orders) ?></span> of 
                            <span class="fw-bold"><?= $totalOrders ?></span> orders
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="/menu" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>New Order
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <?php foreach ($orders as $order): ?>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($order['order_number']) ?></h6>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            <?= date('M d, Y h:i A', strtotime($order['created_at'])) ?>
                                        </small>
                                    </div>
                                    <span class="badge <?= Order::getStatusBadgeClass($order['status']) ?>">
                                        <?= Order::getStatusText($order['status']) ?>
                                    </span>
                                </div>
                                
                                <div class="card-body">
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1">Items (<?= $order['item_count'] ?>):</small>
                                        <p class="mb-0 small"><?= htmlspecialchars($order['items_summary'] ?? 'No items') ?></p>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Total Amount:</small>
                                            <h5 class="mb-0 text-primary">₱<?= number_format($order['total_amount'], 2) ?></h5>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Payment:</small>
                                            <p class="mb-0 small text-capitalize"><?= htmlspecialchars($order['payment_method']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="/orders/<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                        
                                        <?php if (in_array($order['status'], ['pending', 'confirmed'])): ?>
                                            <button class="btn btn-outline-danger btn-sm cancel-order-btn" 
                                                    data-order-id="<?= $order['id'] ?>"
                                                    data-order-number="<?= htmlspecialchars($order['order_number']) ?>">
                                                <i class="bi bi-x-circle me-1"></i>Cancel
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Order pagination">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $currentPage - 1 ?>">Previous</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $currentPage + 1 ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
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
                    location.reload();
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

<?php
$content = ob_get_clean();
$title = 'My Orders - MacCafe';
include __DIR__ . '/../layouts/main.php';
?>
