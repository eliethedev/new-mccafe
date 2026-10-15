<?php ob_start(); ?>
<?php
$riderAvailability = 'available';
if (!$isAdmin) {
    $riderAvailability = $_SESSION['user']['availability_status'] ?? 'available';
}
?>
<div class="container py-5 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-bicycle me-2"></i><?= $isAdmin ? 'All Active Deliveries' : 'My Deliveries' ?></h2>
            <p class="text-muted small mb-0">Accept assignments, update delivery status, and suggest replacements for out-of-stock items.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <?php if (!$isAdmin): ?>
                <span class="badge <?= $riderAvailability === 'available' ? 'bg-success' : ($riderAvailability === 'on_delivery' ? 'bg-warning text-dark' : 'bg-secondary') ?> fs-6">
                    <?= ucfirst(str_replace('_', ' ', $riderAvailability)) ?>
                </span>
            <?php endif; ?>
            <span class="badge bg-dark fs-6"><?= (int)($stats['active'] ?? 0) ?> active</span>
        </div>
    </div>

    <?php $flashOk = Session::getFlash('success'); ?>
    <?php if ($flashOk): ?><div class="alert alert-success"><?= htmlspecialchars($flashOk) ?></div><?php endif; ?>
    <?php $flashErr = Session::getFlash('error'); ?>
    <?php if ($flashErr): ?><div class="alert alert-danger"><?= htmlspecialchars($flashErr) ?></div><?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-center py-3"><div class="h4 mb-0"><?= (int)($stats['active'] ?? 0) ?></div><small class="text-muted text-uppercase">Active</small></div>
        </div>
        <div class="col-md-4">
            <div class="card text-center py-3"><div class="h4 mb-0"><?= (int)($stats['delivered'] ?? 0) ?></div><small class="text-muted text-uppercase">Delivered</small></div>
        </div>
        <div class="col-md-4">
            <div class="card text-center py-3"><div class="h4 mb-0"><?= (int)($stats['total'] ?? 0) ?></div><small class="text-muted text-uppercase">Total assigned</small></div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Active deliveries</h5></div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead><tr><th>Order</th><th>Customer</th><th>Drop-off</th><th>Total</th><th>Delivery</th><th>Order</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($deliveries)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-bicycle fs-3 d-block mb-2"></i>No active deliveries. New assignments will appear here with a notification.</td></tr>
                <?php else: ?>
                    <?php foreach ($deliveries as $d): ?>
                    <tr>
                        <td><a class="order-number-link" href="/rider/deliveries/<?= $d['id'] ?>">#<?= htmlspecialchars($d['order_number']) ?></a><br><small class="text-muted"><?= date('M j H:i', strtotime($d['assigned_at'] ?? $d['created_at'])) ?></small></td>
                        <td><?= htmlspecialchars($d['customer_name']) ?><br><small class="text-muted"><?= htmlspecialchars($d['customer_phone'] ?? '') ?></small></td>
                        <td><small><?= htmlspecialchars(mb_strimwidth($d['customer_address'] ?? '—', 0, 60, '…')) ?></small></td>
                        <td><strong>₱<?= number_format($d['total_amount'], 2) ?></strong></td>
                        <td><span class="badge bg-dark"><?= ucfirst(str_replace('_', ' ', $d['status'])) ?></span></td>
                        <td><span class="badge bg-secondary"><?= ucfirst($d['order_status']) ?></span></td>
                        <td>
                            <?php if ($d['status'] === 'assigned'): ?>
                                <div class="btn-group">
                                    <button onclick="acceptDelivery(<?= $d['id'] ?>)" class="btn btn-sm btn-success"><i class="bi bi-check"></i> Accept</button>
                                    <button onclick="rejectDelivery(<?= $d['id'] ?>)" class="btn btn-sm btn-danger"><i class="bi bi-x"></i> Reject</button>
                                </div>
                            <?php else: ?>
                                <a href="/rider/deliveries/<?= $d['id'] ?>" class="btn btn-sm btn-outline-dark">Open</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (!$isAdmin): ?>
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Recent history</h5></div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead><tr><th>Order</th><th>Customer</th><th>Status</th><th>Updated</th></tr></thead>
                <tbody>
                <?php if (empty($history)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">Nothing here yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($history as $d): ?>
                    <tr>
                        <td><a href="/rider/deliveries/<?= $d['id'] ?>"><strong>#<?= htmlspecialchars($d['order_number']) ?></strong></a></td>
                        <td><?= htmlspecialchars($d['customer_name']) ?></td>
                        <td><span class="badge bg-secondary"><?= ucfirst($d['status']) ?></span></td>
                        <td><small><?= date('M j H:i', strtotime($d['updated_at'])) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function acceptDelivery(deliveryId) {
    if (!confirm('Accept this delivery assignment?')) return;
    fetch('/rider/deliveries/' + deliveryId + '/status', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'status=accepted'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to accept delivery');
        }
    })
    .catch(e => alert('Error: ' + e.message));
}

function rejectDelivery(deliveryId) {
    const reason = prompt('Reason for rejecting this delivery (optional):');
    if (reason === null) return;
    fetch('/rider/deliveries/' + deliveryId + '/status', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'status=cancelled&notes=' + encodeURIComponent(reason || 'Rejected by rider')
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to reject delivery');
        }
    })
    .catch(e => alert('Error: ' + e.message));
}
</script>

<?php
$content = ob_get_clean();
$title = $title ?? 'Rider Dashboard - CafeCat';
if (($isAdmin ?? false) === true) {
    $currentPage = 'riders';
    include __DIR__ . '/../admin/layout.php';
} else {
    include __DIR__ . '/../layouts/main.php';
}
?>

