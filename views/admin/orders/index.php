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

.orders-container {
    background: var(--maccafe-light);
    min-height: 100vh;
}

.orders-header {
    background: var(--maccafe-primary);
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
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    position: relative;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.order-card.pending {
    border-left-color: var(--maccafe-accent);
}

.order-card.confirmed {
    border-left-color: #17a2b8;
}

.order-card.preparing {
    border-left-color: var(--maccafe-primary);
}

.order-card.ready {
    border-left-color: #28a745;
}

.order-card.completed {
    border-left-color: var(--maccafe-secondary);
}

.order-card.cancelled {
    border-left-color: #dc3545;
}

.order-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.order-status-right {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 10;
}

.order-number {
    font-size: 1.25rem;
    font-weight: bold;
    color: var(--maccafe-primary);
}

.order-status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
}

.order-content {
    display: grid;
    grid-template-columns: 1fr 200px;
    gap: 1.5rem;
}

.order-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.order-detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-detail-label {
    font-weight: 600;
    color: var(--maccafe-dark);
    min-width: 80px;
}

.order-detail-value {
    color: var(--maccafe-secondary);
}

.order-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-btn {
    padding: 0.75rem 1rem;
    border-radius: 10px;
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.action-btn.view {
    background: #e9ecef;
    color: var(--maccafe-dark);
}

.action-btn.view:hover {
    background: var(--maccafe-primary);
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
    background: var(--maccafe-primary);
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
    background: var(--maccafe-secondary);
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
    gap: 0.75rem;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--maccafe-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.payment-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
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
        min-width: 120px;
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
    <!-- Header -->
    <div class="orders-header">
        <div class="container">
            <h1>Manage Orders</h1>
            <div class="subtitle">Handle and track customer orders efficiently</div>
        </div>
    </div>

    <div class="container">

<!-- Filters -->
<div class="card mb-4">

</div>

<!-- Orders Cards -->
<?php if (empty($orders ?? [])): ?>
<div class="text-center py-5">
    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
    <h4 class="text-muted">No orders found</h4>
    <p class="text-muted">There are no orders matching your criteria.</p>
</div>
<?php else: ?>
<?php foreach ($orders as $order): ?>
<div class="order-card <?= $order['status'] ?>">
    <div class="order-header">
        <div>
            <div class="order-number">#<?= $order['order_number'] ?></div>
            <small class="text-muted"><?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></small>
        </div>
    </div>
    
    <div class="order-content">
        <div class="order-details">
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
                    <?php if (($order['payment_method'] ?? 'cash') === 'gcash' && $order['payment_status'] !== 'paid' && $order['status'] !== 'cancelled'): ?>
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
                    <?php elseif ($order['status'] === 'cancelled' && ($order['payment_method'] ?? 'cash') === 'gcash'): ?>
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
                View Details
            </a>
            
            <!-- Payment Verification for GCash -->
            <?php if (($order['payment_method'] ?? 'cash') === 'gcash' && $order['payment_status'] !== 'paid' && $order['status'] !== 'cancelled'): ?>
            <button class="action-btn confirm" 
                    onclick="showPaymentProofModal(<?= $order['id'] ?>, '<?= $order['order_number'] ?? '' ?>')" 
                    title="Verify GCash Payment">
                <i class="bi bi-credit-card"></i>
                Verify Payment
            </button>
            <?php endif; ?>
            
            <!-- Status Actions -->
            <?php 
            $validTransitions = getValidStatusTransitions($order['status']);
            foreach ($validTransitions as $nextStatus): 
                
            // Skip confirmation for GCash orders if payment not verified
            if ($nextStatus === 'confirmed' && ($order['payment_method'] ?? 'cash') === 'gcash' && $order['payment_status'] !== 'paid') {
                continue;
            }
            
            ?>
            <?php if ($nextStatus === 'cancelled' && ($order['payment_method'] ?? 'cash') === 'gcash'): ?>
                <!-- Special GCash cancellation button -->
                <button type="button" class="action-btn cancel" 
                        title="Cancel GCash Order"
                        onclick="showGcashCancelModal(<?= $order['id'] ?>, <?= $order['total_amount'] ?>)">
                    <i class="bi <?= getActionIcon($nextStatus) ?>"></i>
                    <?= ucfirst($nextStatus) ?>
                </button>
            <?php else: ?>
                <!-- Regular status change button -->
                <form method="POST" action="/admin/orders/<?= $order['id'] ?>/status" style="display: inline;" id="statusForm<?= $order['id'] ?><?= $nextStatus ?>">
                    <input type="hidden" name="status" value="<?= $nextStatus ?>">
                    <input type="hidden" name="notes" value="">
                    <button type="button" class="action-btn <?= getActionClass($nextStatus) ?>" 
                            title="Change to <?= ucfirst($nextStatus) ?>"
                            onclick="confirmStatusChange('<?= $order['id'] ?>', '<?= $nextStatus ?>', '<?= ucfirst($nextStatus) ?>')">
                        <i class="bi <?= getActionIcon($nextStatus) ?>"></i>
                        <?= ucfirst($nextStatus) ?>
                    </button>
                </form>
            <?php endif; ?>
            <?php endforeach; ?>
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
});

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
        // Create cancellation data
        const formData = new FormData();
        formData.append('order_id', currentGcashOrderId);
        formData.append('status', 'cancelled');
        formData.append('notes', `GCash Order Cancellation: ${reason}`);
        formData.append('refund_amount', refundAmount);
        formData.append('refund_method', refundMethod);
        formData.append('refund_notes', document.getElementById('refundNotes').value);
        formData.append('is_gcash_cancel', 'true');
        
        // Submit cancellation
        fetch('/admin/orders/' + currentGcashOrderId + '/status', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('gcashCancelModal')).hide();
                
                // Show success message
                const messageDiv = document.createElement('div');
                messageDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                messageDiv.style.zIndex = '9999';
                messageDiv.innerHTML = `
                    <i class="bi bi-check-circle-fill me-2"></i>
                    GCash order cancelled successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(messageDiv);
                
                // Remove alert and reload page
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
        // Create refund data
        const formData = new FormData();
        formData.append('order_id', currentGcashOrderId);
        formData.append('action', 'process_refund');
        formData.append('refund_amount', refundAmount);
        formData.append('refund_method', refundMethod);
        formData.append('refund_notes', refundNotes);
        
        // Submit refund
        fetch('/admin/orders/process-refund', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Refund processed successfully!');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('gcashCancelModal')).hide();
                
                // Reload page
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

// Status Change Confirmation Function
function confirmStatusChange(orderId, newStatus, statusDisplay) {
    const confirmMessage = `Are you sure you want to change this order status to ${statusDisplay}?`;
    
    if (confirm(confirmMessage)) {
        // Find and submit the form
        const formId = 'statusForm' + orderId + newStatus;
        const form = document.getElementById(formId);
        
        if (form) {
            // Show loading state
            const button = form.querySelector('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';
            button.disabled = true;
            
            // Submit the form
            form.submit();
        } else {
            alert('Error: Form not found. Please refresh the page and try again.');
        }
    }
    
    return false; // Prevent default behavior
}
</script>

<?php
$content = ob_get_clean();
$title = 'Manage Orders - McCafe';
$currentPage = 'orders';
include __DIR__ . '/../layout.php';
?>
