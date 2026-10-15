<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-1">
    <div>
        <h1 class="h3 mb-0"><i class="bi bi-trophy me-2"></i>Best Sellers</h1>
        <p class="text-muted mb-0">Auto-ranked by units sold + your manual picks. Toggle flags to feature items storefront-wide.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/admin/menu" class="btn btn-outline-secondary"><i class="bi bi-book me-2"></i>Menu Management</a>
        <a href="/admin/products/create" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Add Product</a>
    </div>
</div>

<?php $ms = $menuStats ?? ['bestsellers'=>0,'featured'=>0]; ?>
<div class="row g-3 my-3">
    <div class="col-md-4"><div class="card text-center py-3"><div class="h4 mb-0"><i class="bi bi-trophy me-1"></i><?= $ms['bestsellers'] ?></div><small class="text-muted text-uppercase">Flagged best sellers</small></div></div>
    <div class="col-md-4"><div class="card text-center py-3"><div class="h4 mb-0"><i class="bi bi-star me-1"></i><?= $ms['featured'] ?></div><small class="text-muted text-uppercase">Featured items</small></div></div>
    <div class="col-md-4"><div class="card text-center py-3"><div class="h4 mb-0"><?= count($topSellers ?? []) ?></div><small class="text-muted text-uppercase">Ranked by sales</small></div></div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Top sellers by units sold</h5>
        <small class="text-muted">Completed + ready orders only</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead><tr><th>#</th><th>Product</th><th>Category</th><th>Price</th><th>Sold</th><th>Revenue</th><th>Flags</th></tr></thead>
                <tbody>
                <?php if (empty($topSellers)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No sales data yet — new orders will populate this ranking automatically.</td></tr>
                <?php else: ?>
                    <?php foreach ($topSellers as $i => $p): ?>
                    <tr>
                        <td><span class="badge <?= $i < 3 ? 'bg-dark' : 'bg-secondary' ?>"><?= $i + 1 ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <?php if (!empty($p['image'])): ?>
                                    <img src="/assets/images/products/<?= htmlspecialchars($p['image']) ?>" style="width:44px;height:44px;object-fit:cover;border-radius:8px;" alt="">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width:44px;height:44px;border-radius:8px;"><i class="bi bi-image text-muted"></i></div>
                                <?php endif; ?>
                                <div><strong><?= htmlspecialchars($p['name']) ?></strong><br>
                                <small class="text-muted"><?= empty($p['is_available']) ? 'Unavailable' : 'Available' ?></small></div>
                            </div>
                        </td>
                        <td><span class="badge bg-info"><?= htmlspecialchars($p['category_name'] ?? 'N/A') ?></span></td>
                        <td><strong>₱<?= number_format($p['price'], 2) ?></strong></td>
                        <td><span class="badge bg-dark"><?= (int)($p['total_sold'] ?? 0) ?> sold</span></td>
                        <td>₱<?= number_format($p['total_revenue'] ?? 0, 2) ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm <?= !empty($p['is_bestseller']) ? 'btn-dark' : 'btn-outline-dark' ?>" title="Best seller" onclick="toggleFlag(<?= $p['id'] ?>,'bestseller',this)"><i class="bi bi-trophy"></i></button>
                                <button class="btn btn-sm <?= !empty($p['is_featured']) ? 'btn-dark' : 'btn-outline-dark' ?>" title="Featured" onclick="toggleFlag(<?= $p['id'] ?>,'featured',this)"><i class="bi bi-star"></i></button>
                                <a class="btn btn-sm btn-outline-secondary" href="/admin/products/<?= $p['id'] ?>/edit"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Your manual picks (<?= count($flagged ?? []) ?>)</h5></div>
            <div class="card-body">
                <?php if (empty($flagged)): ?>
                    <p class="text-muted mb-0">No manual picks yet. Use the <i class="bi bi-trophy"></i> toggle on any product to pin it here and on the storefront.</p>
                <?php else: ?>
                    <div class="list-group">
                    <?php foreach ($flagged as $p): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div><strong><?= htmlspecialchars($p['name']) ?></strong><br><small class="text-muted"><?= (int)($p['total_sold'] ?? 0) ?> sold · <?= htmlspecialchars($p['category_name'] ?? '') ?></small></div>
                            <button class="btn btn-sm btn-outline-dark" onclick="toggleFlag(<?= $p['id'] ?>,'bestseller',this,true)"><i class="bi bi-x"></i> Unpin</button>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0"><i class="bi bi-star me-2"></i>Featured on storefront (<?= count($featured ?? []) ?>)</h5></div>
            <div class="card-body">
                <?php if (empty($featured)): ?>
                    <p class="text-muted mb-0">No featured items. Use the <i class="bi bi-star"></i> toggle to highlight seasonal or promo items.</p>
                <?php else: ?>
                    <div class="list-group">
                    <?php foreach ($featured as $p): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div><strong><?= htmlspecialchars($p['name']) ?></strong><br><small class="text-muted"><?= htmlspecialchars($p['category_name'] ?? '') ?> · ₱<?= number_format($p['price'], 2) ?></small></div>
                            <button class="btn btn-sm btn-outline-dark" onclick="toggleFlag(<?= $p['id'] ?>,'featured',this,true)"><i class="bi bi-x"></i> Remove</button>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
async function adminFetch(url, options = {}) {
    options.headers = Object.assign({ 'X-Requested-With': 'XMLHttpRequest' }, options.headers || {});
    let response;
    try {
        response = await fetch(url, options);
    } catch (e) {
        throw new Error('Network error — is the server still running?');
    }
    if (response.status === 401) {
        alert('Your session expired. Please log in again.');
        window.location.href = '/login';
        throw new Error('Session expired');
    }
    const text = await response.text();
    let data;
    try {
        data = text ? JSON.parse(text) : {};
    } catch (e) {
        console.error('Non-JSON response:', text);
        throw new Error('Unexpected server response. Please refresh and try again.');
    }
    if (!response.ok) {
        throw new Error((data && data.message) || `Request failed (HTTP ${response.status})`);
    }
    return data;
}

async function toggleFlag(productId, kind, btn, reload = false) {
    btn.disabled = true;
    try {
        const data = await adminFetch(`/admin/products/${productId}/${kind}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({})
        });
        if (!data.success) { alert('Error: ' + (data.message || 'Failed')); return; }
        if (reload) { location.reload(); return; }
        const on = !!data.value;
        btn.classList.remove('btn-dark', 'btn-outline-dark');
        btn.classList.add(on ? 'btn-dark' : 'btn-outline-dark');
    } catch (e) {
        if (e.message !== 'Session expired') alert(e.message);
    } finally {
        btn.disabled = false;
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Best Sellers - CafeCat';
$currentPage = 'best-sellers';
include __DIR__ . '/layout.php';
?>
