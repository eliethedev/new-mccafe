<?php ob_start(); ?>

<style>
.order-aside {
    max-height: calc(100vh - 120px);
    overflow-y: auto;
    position: sticky;
    top: 80px;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Order Details</h1>
    <div>
        <a href="/admin/orders/<?= $order['id'] ?>/receipt" class="btn btn-dark">
            <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
        </a>
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
                                <th style="width: 60px;">Image</th>
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
                                <td colspan="6" class="text-center text-muted">No items found</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($item['product_image'])): ?>
                                        <img src="/assets/images/products/<?= htmlspecialchars($item['product_image']) ?>"
                                             alt="<?= htmlspecialchars($item['product_name']) ?>"
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    <?php else: ?>
                                        <div style="width: 50px; height: 50px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-cup-hot text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
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
                            <?php if (!empty($order['discount_type']) && $order['discount_type'] !== 'none'): ?>
                            <tr>
                                <th colspan="4"><?= $order['discount_type'] === 'senior' ? 'Senior' : 'PWD' ?> Discount (20%, VAT-exempt):</th>
                                <th>−₱<?= number_format($order['discount_amount'] ?? 0, 2) ?> <span class="badge bg-dark"><?= ucfirst($order['discount_status'] ?? 'pending') ?></span></th>
                            </tr>
                            <?php endif; ?>
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

    <div class="col-lg-4 order-aside">
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

    

        <!-- Payment Verification -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment Verification</h5>
            </div>
            <div class="card-body">
                <?php $pm = strtolower($order['payment_method'] ?? 'cash'); ?>
                <?php $isManual = in_array($pm, ['gcash', 'paymaya', 'maya', 'bank_transfer'], true); ?>
                <p class="mb-1"><strong>Method:</strong> <?= ucfirst(htmlspecialchars($order['payment_method'] ?? 'N/A')) ?>
                    <?php if ($isManual): ?><span class="badge bg-warning text-dark ms-1">manual · verify within 24h</span><?php endif; ?>
                </p>
                <p class="mb-2"><strong>Amount due:</strong> ₱<?= number_format($order['total_amount'], 2) ?></p>
                <?php if ($isManual && ($order['payment_status'] ?? '') !== 'paid' && ($order['status'] ?? '') !== 'cancelled'): ?>
                    <div class="alert alert-warning small mb-2">Awaiting proof verification. Use Orders list → Verify Payment to approve/reject with reason.</div>
                    <button class="btn btn-sm btn-primary" onclick="window.location.href='/admin/orders'">Open verification queue</button>
                <?php elseif (($order['payment_status'] ?? '') === 'paid'): ?>
                    <div class="alert alert-success small mb-0">Payment verified / marked paid. Order may proceed to confirmed.</div>
                <?php else: ?>
                    <div class="alert alert-info small mb-0">Cash order — no proof verification required. Collect on delivery/pickup.</div>
                <?php endif; ?>
                <hr class="my-2">
                <small class="text-muted"><strong>Policy:</strong> Manual payments lock against self-cancellation once proof is submitted. Rejections require a reason; refunds are recorded with method, amount, staff and timestamp.</small>
            </div>
        </div>

        <!-- Rider Dispatch -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-bicycle me-2"></i>Rider Dispatch</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($delivery)): ?>
                    <p class="mb-1"><strong>Rider:</strong> <?= htmlspecialchars(trim(($delivery['rider_name'] ?? '')) ?: '—') ?></p>
                    <p class="mb-2"><strong>Status:</strong> <span class="badge bg-dark"><?= ucfirst(str_replace('_', ' ', $delivery['status'])) ?></span></p>
                <?php else: ?>
                    <p class="text-muted small mb-2">No rider assigned yet.</p>
                <?php endif; ?>
                <?php if (!in_array($order['status'] ?? '', ['completed', 'cancelled'], true)): ?>
                    <div class="d-flex gap-2">
                        <select id="assignRiderSelect" class="form-select form-select-sm">
                            <option value="">Choose rider…</option>
                            <?php foreach ($riders ?? [] as $r): ?>
                                <?php
                                $availability = $r['availability_status'] ?? 'available';
                                $statusLabel = match($availability) {
                                    'available' => '',
                                    'unavailable' => ' - Unavailable',
                                    'on_delivery' => ' - On delivery',
                                    default => ''
                                };
                                ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars(trim($r['first_name'] . ' ' . $r['last_name'])) ?> (<?= htmlspecialchars($r['email']) ?>)<?= $statusLabel ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-sm btn-dark text-nowrap" onclick="assignRider(<?= $order['id'] ?>)">Assign</button>
                    </div>
                    <?php if (empty($riders)): ?>
                        <small class="text-muted d-block mt-1">No rider accounts yet — create a user with the rider role first.</small>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Substitutions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-arrow-left-right me-2"></i>Substitutions</h5>
            </div>
            <div class="card-body">
                <?php if (empty($subs ?? [])): ?>
                    <p class="text-muted small mb-0">No replacement suggestions for out-of-stock items.</p>
                <?php else: ?>
                    <?php foreach ($subs as $s): ?>
                        <div class="border rounded p-2 mb-2 small">
                            <div><strong><?= htmlspecialchars($s['original_name']) ?></strong> (₱<?= number_format($s['original_total'], 2) ?>)
                                → <strong><?= htmlspecialchars($s['suggested_name']) ?></strong> × <?= (int)$s['suggested_qty'] ?> (₱<?= number_format($s['suggested_total'], 2) ?>)</div>
                            <div class="text-muted">By <?= htmlspecialchars(trim($s['suggested_by_name'] ?? '')) ?: 'staff' ?> · <span class="badge bg-dark"><?= ucfirst($s['status']) ?></span></div>
                            <?php if ($s['status'] === 'suggested'): ?>
                                <div class="d-flex gap-2 mt-2">
                                    <button class="btn btn-sm btn-success" onclick="decideSub(<?= $s['id'] ?>, 'approved')">Approve</button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="decideSub(<?= $s['id'] ?>, 'rejected')">Reject</button>
                                </div>
                            <?php elseif ($s['status'] === 'approved'): ?>
                                <button class="btn btn-sm btn-dark mt-2" onclick="applySubAdmin(<?= $s['id'] ?>)">Apply to order</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php $chatOrderId = $order['id']; include __DIR__ . '/../../components/order-chat.php'; ?>

        <!-- Senior / PWD Discount -->        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Senior / PWD Discount</h5>
            </div>
            <div class="card-body">
                <?php $dtype = $order['discount_type'] ?? 'none'; ?>
                <?php if ($dtype === 'none'): ?>
                    <p class="text-muted mb-0">No discount claimed on this order.</p>
                <?php else: ?>
                    <p class="mb-1"><strong>Type:</strong> <?= $dtype === 'senior' ? 'Senior Citizen (20% off)' : 'PWD (20% off)' ?></p>
                    <p class="mb-1"><strong>Amount:</strong> −₱<?= number_format($order['discount_amount'] ?? 0, 2) ?> <small class="text-muted">(VAT-exempt)</small></p>
                    <p class="mb-1"><strong>ID No.:</strong> <?= htmlspecialchars($order['discount_id_no'] ?? '—') ?></p>
                    <p class="mb-2"><strong>Status:</strong>
                        <?php $dsColor = ($order['discount_status'] ?? 'none') === 'verified' ? 'success' : ((($order['discount_status'] ?? '') === 'pending') ? 'warning' : 'secondary'); ?>
                        <span class="badge bg-<?= $dsColor ?>"><?= ucfirst($order['discount_status'] ?? 'none') ?></span>
                    </p>
                    <?php if (($order['discount_status'] ?? '') === 'pending'): ?>
                        <div class="mb-2">
                            <label for="discountNotes" class="form-label small">Verification note (optional)</label>
                            <input type="text" id="discountNotes" class="form-control form-control-sm" placeholder="e.g. ID presented at counter" maxlength="200">
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-success" onclick="verifyDiscount(<?= $order['id'] ?>, 'verified')">
                                <i class="bi bi-patch-check me-1"></i>Verify
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="verifyDiscount(<?= $order['id'] ?>, 'rejected')">
                                <i class="bi bi-x-circle me-1"></i>Reject
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">Rejecting reverts the total to regular price (subtotal + 12% VAT); collect the difference on pickup/delivery.</small>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Refunds -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Refunds</h5>
            </div>
            <div class="card-body">
                <?php if (empty($refunds ?? [])): ?>
                    <p class="text-muted mb-0">No refunds recorded for this order.</p>
                <?php else: ?>
                    <?php foreach ($refunds as $r): ?>
                        <?php
                            $rsColor = ($r['refund_status'] ?? '') === 'processed' ? 'success'
                                : ((($r['refund_status'] ?? '') === 'pending') ? 'warning' : 'danger');
                            $nextStatuses = ['pending' => ['processed', 'failed'], 'processed' => ['failed'], 'failed' => []];
                            $options = $nextStatuses[$r['refund_status'] ?? ''] ?? [];
                        ?>
                        <div class="border rounded p-2 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>₱<?= number_format($r['refund_amount'], 2) ?></strong>
                                    <small class="text-muted">via <?= ucfirst($r['refund_method']) ?></small>
                                    <span class="badge bg-<?= $rsColor ?> ms-1"><?= ucfirst($r['refund_status']) ?></span>
                                </div>
                                <small class="text-muted"><?= !empty($r['processed_at']) ? date('M j, g:i A', strtotime($r['processed_at'])) : '' ?></small>
                            </div>
                            <?php if (!empty($r['refund_notes'])): ?>
                                <small class="text-muted d-block"><?= htmlspecialchars($r['refund_notes']) ?></small>
                            <?php endif; ?>
                            <?php if (!empty($r['processed_by_name'])): ?>
                                <small class="text-muted d-block">By <?= htmlspecialchars(trim($r['processed_by_name'])) ?></small>
                            <?php endif; ?>
                            <?php if (!empty($options)): ?>
                                <div class="d-flex gap-2 mt-2">
                                    <?php foreach ($options as $opt): ?>
                                        <button class="btn btn-sm <?= $opt === 'failed' ? 'btn-outline-danger' : 'btn-outline-primary' ?>"
                                                onclick="updateRefundStatus(<?= $r['id'] ?>, '<?= $opt ?>')">
                                            Mark <?= ucfirst($opt) ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <small class="text-muted">Status changes notify the customer and staff automatically (in-app + email).</small>
                <?php endif; ?>
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

<script>
function verifyDiscount(orderId, decision) {
    var notesEl = document.getElementById('discountNotes');
    var notes = notesEl ? notesEl.value.trim() : '';
    var msg = decision === 'verified'
        ? 'Mark this Senior/PWD discount as verified?'
        : 'Reject this discount? The order total will revert to regular price.';
    if (!confirm(msg)) return;
    var formData = new FormData();
    formData.append('decision', decision);
    formData.append('notes', notes);
    fetch('/admin/orders/' + orderId + '/discount', { method: 'POST', body: formData })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                alert(data.message || 'Discount updated.');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to update discount'));
            }
        })
        .catch(function (err) {
            console.error('Error:', err);
            alert('Error updating discount. Please try again.');
        });
}

function updateRefundStatus(refundId, status) {
    if (!confirm('Mark this refund as ' + status + '? The customer and staff will be notified.')) return;
    var formData = new FormData();
    formData.append('refund_id', refundId);
    formData.append('status', status);
    fetch('/admin/orders/update-refund-status', { method: 'POST', body: formData })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                alert(data.message || 'Refund updated.');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to update refund'));
            }
        })
        .catch(function (err) {
            console.error('Error:', err);
            alert('Error updating refund. Please try again.');
        });
}

function assignRider(orderId) {
    var sel = document.getElementById('assignRiderSelect');
    if (!sel || !sel.value) { alert('Please choose a rider first.'); return; }
    if (!confirm('Assign this rider? They and the customer will be notified.')) return;
    var formData = new FormData();
    formData.append('rider_id', sel.value);
    fetch('/admin/orders/' + orderId + '/assign-rider', { method: 'POST', body: formData })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            alert(data.message || (data.success ? 'Rider assigned.' : 'Failed.'));
            if (data.success) location.reload();
        })
        .catch(function (err) {
            console.error('Error:', err);
            alert('Error assigning rider. Please try again.');
        });
}

function decideSub(subId, decision) {
    if (!confirm('Mark this suggestion as ' + decision + '?')) return;
    var formData = new FormData();
    formData.append('decision', decision);
    fetch('/substitutions/' + subId + '/decide', { method: 'POST', body: formData })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            alert(data.message || (data.success ? 'Updated.' : 'Failed.'));
            if (data.success) location.reload();
        })
        .catch(function (err) {
            console.error('Error:', err);
            alert('Error updating suggestion. Please try again.');
        });
}

function applySubAdmin(subId) {
    if (!confirm('Apply this replacement? Order totals will be recalculated.')) return;
    fetch('/substitutions/' + subId + '/apply', { method: 'POST', body: new FormData() })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            alert(data.message || (data.success ? 'Applied.' : 'Failed.'));
            if (data.success) location.reload();
        })
        .catch(function (err) {
            console.error('Error:', err);
            alert('Error applying substitution. Please try again.');
        });
}
</script>

<?php
$content = ob_get_clean();
$title = 'Order Details - CafeCat';
$currentPage = 'orders';
include __DIR__ . '/../layout.php';
?>
