<?php ob_start(); ?>
<div class="container py-5 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-bicycle me-2"></i>Delivery #<?= htmlspecialchars($delivery['order_number']) ?></h2>
            <p class="text-muted small mb-0">
                <span class="badge bg-dark"><?= ucfirst(str_replace('_', ' ', $delivery['status'])) ?></span>
                <span class="badge bg-secondary ms-1">Order: <?= ucfirst($delivery['order_status']) ?></span>
            </p>
        </div>
        <a href="/rider" class="btn btn-outline-dark"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Drop-off</h5></div>
                <div class="card-body">
                    <p class="mb-1"><strong><?= htmlspecialchars($delivery['customer_name']) ?></strong></p>
                    <p class="mb-1"><?= htmlspecialchars($delivery['customer_address'] ?? '—') ?></p>
                    <p class="mb-1"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($delivery['customer_phone'] ?? '—') ?></p>
                    <p class="mb-0"><strong>Total to collect:</strong> ₱<?= number_format($delivery['total_amount'], 2) ?>
                        <small class="text-muted">(<?= ucfirst($delivery['payment_method']) ?> · <?= ucfirst($delivery['payment_status']) ?>)</small></p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Items</h5></div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead><tr><th>Item</th><th class="text-center">Qty</th><th class="text-end">Total</th></tr></thead>
                        <tbody>
                        <?php foreach ($items as $it): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($it['product_name']) ?></strong>
                                    <?php if (!empty($it['variant_name'])): ?><br><small class="text-muted"><?= htmlspecialchars($it['variant_name']) ?></small><?php endif; ?>
                                </td>
                                <td class="text-center"><?= (int)$it['quantity'] ?></td>
                                <td class="text-end">₱<?= number_format($it['total_price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Substitutions</h5></div>
                <div class="card-body">
                    <?php if (!empty($needsSub)): ?>
                        <div class="alert alert-warning small"><i class="bi bi-exclamation-triangle me-2"></i><strong><?= count($needsSub) ?> item(s) out of stock</strong> with no replacement yet — suggest one below.</div>
                    <?php endif; ?>
                    <?php if (empty($subs)): ?>
                        <p class="text-muted small mb-0">No substitutions suggested for this order.</p>
                    <?php else: ?>
                        <?php foreach ($subs as $s): ?>
                            <div class="border rounded p-2 mb-2 small">
                                <div><strong><?= htmlspecialchars($s['original_name']) ?></strong> (₱<?= number_format($s['original_total'], 2) ?>)
                                    → <strong><?= htmlspecialchars($s['suggested_name']) ?></strong><?= !empty($s['suggested_variant']) ? ' (' . htmlspecialchars($s['suggested_variant']) . ')' : '' ?>
                                    × <?= (int)$s['suggested_qty'] ?> (₱<?= number_format($s['suggested_total'], 2) ?>)</div>
                                <div class="text-muted">Diff: <?= $s['price_diff'] >= 0 ? '+' : '−' ?>₱<?= number_format(abs($s['price_diff']), 2) ?> · <span class="badge bg-dark"><?= ucfirst($s['status']) ?></span>
                                    <?php if (!empty($s['note'])): ?> · <?= htmlspecialchars($s['note']) ?><?php endif; ?></div>
                                <?php if ($s['status'] === 'approved'): ?>
                                    <button class="btn btn-sm btn-dark mt-2" onclick="applySub(<?= $s['id'] ?>)">Apply to order</button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <hr>
                    <h6>Suggest a replacement</h6>
                    <form id="suggestForm" class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small">Out-of-stock item</label>
                            <select class="form-select form-select-sm" name="order_item_id" required>
                                <option value="">Choose item…</option>
                                <?php foreach ($items as $it): ?>
                                    <option value="<?= $it['id'] ?>"><?= htmlspecialchars(mb_strimwidth($it['product_name'], 0, 40, '…')) ?> × <?= (int)$it['quantity'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Replacement (available only)</label>
                            <select class="form-select form-select-sm" name="suggested_product_id" id="suggProduct" required>
                                <option value="">Choose replacement…</option>
                                <?php foreach ($products as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>"><?= htmlspecialchars($p['name']) ?> — ₱<?= number_format($p['price'], 2) ?><?= !empty($p['category_name']) ? ' (' . htmlspecialchars($p['category_name']) . ')' : '' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Qty</label>
                            <input type="number" class="form-control form-control-sm" name="qty" value="1" min="1" max="99">
                        </div>
                        <div class="col-md-9">
                            <label class="form-label small">Note (optional)</label>
                            <input type="text" class="form-control form-control-sm" name="note" placeholder="e.g. customer asked for closest flavor" maxlength="200">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-send me-1"></i>Suggest replacement</button>
                            <small class="text-muted ms-2">The customer is notified for approval.</small>
                        </div>
                    </form>
                </div>
            </div>

            <?php $chatOrderId = $delivery['order_id']; include __DIR__ . '/../components/order-chat.php'; ?>
        </div>

        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Update status</h5></div>
                <div class="card-body">
                    <?php if (empty($transitions)): ?>
                        <p class="text-muted small mb-0">This delivery is <?= htmlspecialchars($delivery['status']) ?> — no further actions.</p>
                    <?php else: ?>
                        <div class="mb-2">
                            <label class="form-label small" for="statusNote">Note (optional)</label>
                            <input type="text" id="statusNote" class="form-control form-control-sm" placeholder="e.g. gate code, landmark" maxlength="200">
                        </div>
                        <div class="d-grid gap-2">
                        <?php foreach ($transitions as $t): ?>
                            <?php $danger = in_array($t, ['failed', 'cancelled'], true); ?>
                            <button class="btn <?= $danger ? 'btn-outline-danger' : 'btn-dark' ?>" onclick="setStatus('<?= $t ?>')">
                                Mark <?= ucfirst(str_replace('_', ' ', $t)) ?>
                            </button>
                        <?php endforeach; ?>
                        </div>
                        <small class="text-muted d-block mt-2">The customer and staff are notified automatically on every update.</small>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($delivery['delivery_notes'])): ?>
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Notes</h5></div>
                <div class="card-body"><small style="white-space:pre-line;"><?= htmlspecialchars($delivery['delivery_notes']) ?></small></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
var DELIVERY_ID = <?= (int)$delivery['id'] ?>;
function setStatus(status) {
    var label = status.replace(/_/g, ' ');
    if (status === 'delivered' && !confirm('Confirm order delivered? This completes the order.')) return;
    if (status !== 'delivered' && !confirm('Mark delivery as ' + label + '?')) return;
    var fd = new FormData();
    fd.append('status', status);
    var note = document.getElementById('statusNote');
    if (note && note.value.trim()) fd.append('notes', note.value.trim());
    fetch('/rider/deliveries/' + DELIVERY_ID + '/status', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            alert(d.message || (d.success ? 'Updated.' : 'Failed.'));
            if (d.success) location.reload();
        })
        .catch(function () { alert('Network error. Please try again.'); });
}
document.getElementById('suggestForm').addEventListener('submit', function (e) {
    e.preventDefault();
    var fd = new FormData(this);
    fetch('/rider/deliveries/' + DELIVERY_ID + '/substitutions', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            alert(d.message || (d.success ? 'Suggested.' : 'Failed.'));
            if (d.success) location.reload();
        })
        .catch(function () { alert('Network error. Please try again.'); });
});
function applySub(subId) {
    if (!confirm('Apply this replacement to the order? Totals will be recalculated.')) return;
    var fd = new FormData();
    fetch('/substitutions/' + subId + '/apply', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            alert(d.message || (d.success ? 'Applied.' : 'Failed.'));
            if (d.success) location.reload();
        })
        .catch(function () { alert('Network error. Please try again.'); });
}
</script>
<?php
$content = ob_get_clean();
$title = $title ?? 'Delivery - CafeCat';
if (($isAdmin ?? false) === true) {
    $currentPage = 'riders';
    include __DIR__ . '/../admin/layout.php';
} else {
    include __DIR__ . '/../layouts/main.php';
}
?>

