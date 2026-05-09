<?php 
require_once __DIR__ . '/../components/order-status-tracker.php';
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
                            <?php if (isset($_GET['filter']) && $_GET['filter'] !== 'all'): ?>
                                <span class="badge bg-secondary ms-2">
                                    Filter: <?= ucfirst($_GET['filter']) ?>
                                    <a href="?" class="text-white ms-1"><i class="bi bi-x"></i></a>
                                </span>
                            <?php endif; ?>
                            <?php if (isset($_GET['sort'])): ?>
                                <span class="badge bg-secondary ms-2">
                                    Sort: <?= ucfirst($_GET['sort']) ?>
                                    <a href="?<?php echo isset($_GET['filter']) ? 'filter=' . $_GET['filter'] : '' ?>" class="text-white ms-1"><i class="bi bi-x"></i></a>
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-2">
                            <!-- Sort Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                                    <i class="bi bi-sort-down me-1"></i>Sort
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item <?= (isset($_GET['sort']) && $_GET['sort'] === 'newest') || !isset($_GET['sort']) ? 'active' : '' ?>" href="?<?php echo isset($_GET['filter']) ? 'filter=' . $_GET['filter'] . '&' : '' ?>sort=newest">Newest First</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'active' : '' ?>" href="?<?php echo isset($_GET['filter']) ? 'filter=' . $_GET['filter'] . '&' : '' ?>sort=oldest">Oldest First</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['sort']) && $_GET['sort'] === 'highest' ? 'active' : '' ?>" href="?<?php echo isset($_GET['filter']) ? 'filter=' . $_GET['filter'] . '&' : '' ?>sort=highest">Highest Amount</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['sort']) && $_GET['sort'] === 'lowest' ? 'active' : '' ?>" href="?<?php echo isset($_GET['filter']) ? 'filter=' . $_GET['filter'] . '&' : '' ?>sort=lowest">Lowest Amount</a></li>
                                </ul>
                            </div>
                            
                            <!-- Filter Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item <?= (!isset($_GET['filter']) || $_GET['filter'] === 'all') ? 'active' : '' ?>" href="?<?php echo isset($_GET['sort']) ? 'sort=' . $_GET['sort'] : '' ?>">All Orders</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['filter']) && $_GET['filter'] === 'pending' ? 'active' : '' ?>" href="?<?php echo isset($_GET['sort']) ? 'sort=' . $_GET['sort'] . '&' : '' ?>filter=pending">Pending</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['filter']) && $_GET['filter'] === 'confirmed' ? 'active' : '' ?>" href="?<?php echo isset($_GET['sort']) ? 'sort=' . $_GET['sort'] . '&' : '' ?>filter=confirmed">Confirmed</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['filter']) && $_GET['filter'] === 'preparing' ? 'active' : '' ?>" href="?<?php echo isset($_GET['sort']) ? 'sort=' . $_GET['sort'] . '&' : '' ?>filter=preparing">Preparing</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['filter']) && $_GET['filter'] === 'ready' ? 'active' : '' ?>" href="?<?php echo isset($_GET['sort']) ? 'sort=' . $_GET['sort'] . '&' : '' ?>filter=ready">Ready</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['filter']) && $_GET['filter'] === 'completed' ? 'active' : '' ?>" href="?<?php echo isset($_GET['sort']) ? 'sort=' . $_GET['sort'] . '&' : '' ?>filter=completed">Completed</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['filter']) && $_GET['filter'] === 'cancelled' ? 'active' : '' ?>" href="?<?php echo isset($_GET['sort']) ? 'sort=' . $_GET['sort'] . '&' : '' ?>filter=cancelled">Cancelled</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item <?= isset($_GET['filter']) && $_GET['filter'] === 'gcash' ? 'active' : '' ?>" href="?<?php echo isset($_GET['sort']) ? 'sort=' . $_GET['sort'] . '&' : '' ?>filter=gcash">GCash Payment</a></li>
                                    <li><a class="dropdown-item <?= isset($_GET['filter']) && $_GET['filter'] === 'cash' ? 'active' : '' ?>" href="?<?php echo isset($_GET['sort']) ? 'sort=' . $_GET['sort'] . '&' : '' ?>filter=cash">Cash on Delivery</a></li>
                                </ul>
                            </div>
                            
                            <a href="/menu" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>New Order
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <?php foreach ($orders as $order): ?>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100">
                                <?php if (!empty($order['first_product_image'])): ?>
                                    <img src="/assets/images/products/<?= htmlspecialchars($order['first_product_image']) ?>" 
                                         class="card-img-top" 
                                         alt="Product Image"
                                         style="height: 150px; object-fit: cover;"
                                         onerror="this.src='/assets/images/placeholder-product.jpg';">
                                <?php endif; ?>
                                <div class="card-body p-3">
                                    <?php renderCompactStatusIndicator($order['status']); ?>
                                    
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1 fw-bold"><?= htmlspecialchars($order['order_number']) ?></h6>
                                            <?php if (!empty($order['first_product_name'])): ?>
                                                <small class="text-primary d-block">
                                                    <i class="bi bi-box me-1"></i>
                                                    <?= htmlspecialchars($order['first_product_name']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                        <span class="badge <?= Order::getStatusBadgeClass($order['status']) ?> mb-2">
                                            <?= Order::getStatusText($order['status']) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            <?= date('M d, Y h:i A', strtotime($order['created_at'])) ?>
                                        </small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <small class="text-muted d-block">Total:</small>
                                            <h6 class="mb-2 text-primary">₱<?= number_format($order['total_amount'], 2) ?></h6>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block">Payment:</small>
                                            <p class="mb-2 small text-capitalize"><?= htmlspecialchars($order['payment_method']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="/orders/<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                        
                                        <?php if (in_array($order['status'], ['pending', 'confirmed']) && $order['payment_method'] !== 'gcash'): ?>
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

// Load constants for the layout
require_once __DIR__ . '/../../config/constants.php';

include __DIR__ . '/../layouts/main.php';
?>
