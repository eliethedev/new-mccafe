<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Orders</h1>
    <div>
        <button class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Print
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="date_from" class="form-label">Date From</label>
                <input type="date" class="form-control" id="date_from" name="date_from">
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">Date To</label>
                <input type="date" class="form-control" id="date_to" name="date_to">
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" placeholder="Order # or Customer...">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders ?? [])): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No orders found
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <strong><?= $order['order_number'] ?></strong>
                        </td>
                        <td>
                            <div>
                                <strong><?= $order['customer_name'] ?></strong><br>
                                <small class="text-muted"><?= $order['customer_email'] ?></small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?= $order['items_count'] ?? 0 ?> items</span>
                        </td>
                        <td>
                            <strong>₱<?= number_format($order['total_amount'], 2) ?></strong>
                        </td>
                        <td>
                            <span class="badge bg-<?= $order['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                                <?= ucfirst($order['payment_status']) ?>
                            </span>
                        </td>
                        <td>
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
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <small><?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                                <button class="btn btn-sm btn-outline-success" onclick="updateOrderStatus(<?= $order['id'] ?>, 'confirmed')">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
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

<script>
function updateOrderStatus(orderId, currentStatus) {
    document.getElementById('modalOrderId').value = orderId;
    document.getElementById('modalStatus').value = currentStatus;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('/admin/orders/' + formData.get('order_id') + '/status', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error updating order status');
    });
});
</script>

<?php
$content = ob_get_clean();
$title = 'Manage Orders - MacCafe';
$currentPage = 'orders';
include __DIR__ . '/../layout.php';
?>
