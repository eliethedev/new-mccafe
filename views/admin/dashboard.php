<?php ob_start(); ?>
<?php
$stats = $stats ?? ['totalOrders'=>0,'pendingOrders'=>0,'totalRevenue'=>0,'todayRevenue'=>0,'weekRevenue'=>0,'ordersToday'=>0,'customers'=>0,'menu'=>['total'=>0,'available'=>0,'unavailable'=>0,'bestsellers'=>0,'featured'=>0]];
$menu = $stats['menu'] ?? ['total'=>0,'available'=>0,'unavailable'=>0,'bestsellers'=>0,'featured'=>0];
$trend = $salesTrend ?? [];
$byStatus = $ordersByStatus ?? [];
$catRev = $revenueByCategory ?? [];
$best = $bestSellers ?? [];
$recent = $recentOrders ?? [];
$urgent = $urgentOrders ?? [];
$unavail = $unavailableItems ?? [];
$adminUser = Session::get('user') ?? [];
?>
<style>
.admin-dashboard { background: #f4f4f4; min-height: 100vh; }
.dashboard-header { background: #000; color: #fff; padding: 1.5rem 0; margin-bottom: 1.5rem; border-radius: 0 0 20px 20px; }
.dashboard-header h1 { font-weight: 800; margin: 0; color: #fff !important; }
.dashboard-header .subtitle { opacity: .75; }
.kpi { background: #fff; border-radius: 14px; padding: 1.1rem 1.2rem; box-shadow: 0 4px 15px rgba(0,0,0,.08); border-left: 5px solid #000; height: 100%; }
.kpi .kpi-top { display: flex; justify-content: space-between; align-items: center; }
.kpi .kpi-icon { width: 46px; height: 46px; border-radius: 12px; background: #000; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
.kpi .kpi-icon.light { background: #e9ecef; color: #000; }
.kpi .kpi-num { font-size: 1.7rem; font-weight: 800; }
.kpi .kpi-label { text-transform: uppercase; font-size: .75rem; letter-spacing: .5px; color: #6c757d; }
.panel { background: #fff; border-radius: 14px; box-shadow: 0 4px 15px rgba(0,0,0,.08); }
.panel-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; border-bottom: 2px solid #f0f0f0; }
.panel-header h5 { margin: 0; font-weight: 700; }
.urgent-banner { background: #000; color: #fff; border-radius: 14px; padding: 1.25rem; }
.urgent-item { background: rgba(255,255,255,.12); border-radius: 10px; padding: .8rem 1rem; margin-top: .6rem; display: flex; justify-content: space-between; align-items: center; }
.status-badge { padding: .35rem .75rem; border-radius: 20px; font-size: .75rem; font-weight: 600; text-transform: uppercase; background: #000; color: #fff; }
.action-btn { display: flex; align-items: center; gap: .8rem; padding: .85rem 1rem; border-radius: 10px; border: 2px solid #eee; color: #111; text-decoration: none; font-weight: 600; margin-bottom: .6rem; transition: all .25s ease; }
.action-btn:hover { background: #000; border-color: #000; color: #fff; transform: translateX(4px); }
.action-btn small { display: block; font-weight: 400; opacity: .65; }
.rank-badge { width: 30px; height: 30px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; background: #e9ecef; }
.rank-1 { background: #000; color: #fff; }
.chart-fallback { padding: 2rem 1rem; text-align: center; color: #6c757d; }
@media print {
    .dashboard-header .btn, .panel-header .btn, .action-btn, .urgent-banner .btn { display: none !important; }
    .admin-dashboard { background: #fff; }
    .kpi, .panel { box-shadow: none !important; border: 1px solid #dee2e6; break-inside: avoid; }
    .dashboard-header { border-radius: 0; }
    #printReportBtn { display: none !important; }
}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<div class="admin-dashboard">
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Dashboard</h1>
                    <div class="subtitle">Welcome back, <?= htmlspecialchars($adminUser['first_name'] ?? 'Admin') ?>! Here's what's happening at Caf&eacute;Cat today — <?= date('l, F j, Y') ?></div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <button type="button" id="printReportBtn" class="btn btn-light me-2" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print report</button>
                    <a href="/admin/products/create" class="btn btn-light me-2"><i class="bi bi-plus-circle me-1"></i> Add product</a>
                    <a href="/" target="_blank" class="btn btn-outline-light"><i class="bi bi-shop me-1"></i> View store</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-4">
        <!-- KPI row -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="kpi"><div class="kpi-top"><div><div class="kpi-num">₱<?= number_format($stats['todayRevenue'] ?? 0, 2) ?></div><div class="kpi-label">Revenue today</div></div><div class="kpi-icon"><i class="bi bi-cash-stack"></i></div></div>
                <small class="text-muted">₱<?= number_format($stats['weekRevenue'] ?? 0, 2) ?> last 7 days · ₱<?= number_format($stats['totalRevenue'] ?? 0, 2) ?> total</small></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="kpi"><div class="kpi-top"><div><div class="kpi-num"><?= $stats['ordersToday'] ?? 0 ?></div><div class="kpi-label">Orders today</div></div><div class="kpi-icon light"><i class="bi bi-bag-check"></i></div></div>
                <small class="text-muted"><?= $stats['totalOrders'] ?? 0 ?> total orders</small></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="kpi" style="border-left-color:#6c757d;"><div class="kpi-top"><div><div class="kpi-num"><?= $stats['pendingOrders'] ?? 0 ?></div><div class="kpi-label">Needs attention</div></div><div class="kpi-icon light"><i class="bi bi-clock-history"></i></div></div>
                <small class="text-muted">Pending orders awaiting confirmation</small></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="kpi"><div class="kpi-top"><div><div class="kpi-num"><?= $stats['customers'] ?? 0 ?></div><div class="kpi-label">Customers</div></div><div class="kpi-icon light"><i class="bi bi-people"></i></div></div>
                <small class="text-muted"><?= $menu['total'] ?? 0 ?> menu items · <?= $menu['available'] ?? 0 ?> available</small></div>
            </div>
        </div>

        <?php if (!empty($urgent)): ?>
        <div class="urgent-banner mb-4">
            <h5 class="mb-1 text-white"><i class="bi bi-exclamation-triangle me-2"></i><?= count($urgent) ?> order<?= count($urgent) > 1 ? 's' : '' ?> need<?= count($urgent) === 1 ? 's' : '' ?> attention</h5>
            <small style="opacity:.7">Oldest pending orders first — confirm or start preparing.</small>
            <?php foreach ($urgent as $o): ?>
            <div class="urgent-item">
                <div><a class="order-number-link" href="/admin/orders/<?= $o['id'] ?>">#<?= htmlspecialchars($o['order_number']) ?></a> · <?= htmlspecialchars($o['customer_name']) ?> · ₱<?= number_format($o['total_amount'], 2) ?></div>
                <a href="/admin/orders/<?= $o['id'] ?>" class="btn btn-sm btn-light">Review</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Charts -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="panel h-100">
                    <div class="panel-header"><h5><i class="bi bi-graph-up me-2"></i>Sales — last 14 days</h5><a href="/admin/orders" class="btn btn-sm btn-outline-secondary">All orders</a></div>
                    <div class="p-3"><canvas id="salesChart" height="120"></canvas>
                    <div id="salesChartEmpty" class="chart-fallback" style="display:none;"><i class="bi bi-bar-chart fs-3 d-block mb-2"></i>No sales recorded in this period.</div></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel h-100">
                    <div class="panel-header"><h5><i class="bi bi-pie-chart me-2"></i>Orders by status</h5></div>
                    <div class="p-3"><canvas id="statusChart" height="200"></canvas>
                    <div id="statusChartEmpty" class="chart-fallback" style="display:none;"><i class="bi bi-pie-chart fs-3 d-block mb-2"></i>No orders yet.</div>
                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <?php foreach (($byStatus ?? []) as $st => $n): ?>
                            <span class="badge bg-dark"><?= htmlspecialchars($st) ?>: <?= $n ?></span>
                        <?php endforeach; ?>
                        <?php if (empty($byStatus)): ?><small class="text-muted">No orders yet.</small><?php endif; ?>
                    </div></div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-8">
                <!-- Best sellers -->
                <div class="panel mb-3">
                    <div class="panel-header"><h5><i class="bi bi-trophy me-2"></i>Best sellers</h5><a href="/admin/best-sellers" class="btn btn-sm btn-dark">Manage <i class="bi bi-arrow-right"></i></a></div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead><tr><th>#</th><th>Product</th><th>Sold</th><th>Revenue</th><th>Status</th></tr></thead>
                            <tbody>
                            <?php if (empty($best)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No products yet.</td></tr>
                            <?php else: ?>
                                <?php foreach (array_slice($best, 0, 6) as $i => $p): ?>
                                <tr>
                                    <td><span class="rank-badge <?= $i === 0 ? 'rank-1' : '' ?>"><?= $i + 1 ?></span></td>
                                    <td><strong><?= htmlspecialchars($p['name']) ?></strong><br><small class="text-muted"><?= htmlspecialchars($p['category_name'] ?? '') ?></small>
                                        <?php if (!empty($p['is_bestseller'])): ?><span class="badge bg-dark ms-1">Pinned</span><?php endif; ?></td>
                                    <td><span class="badge bg-dark"><?= (int)($p['total_sold'] ?? 0) ?></span></td>
                                    <td>₱<?= number_format($p['total_revenue'] ?? 0, 2) ?></td>
                                    <td><?= empty($p['is_available']) ? '<span class="badge bg-secondary">Unavailable</span>' : '<span class="badge bg-dark">Available</span>' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Recent orders -->
                <div class="panel">
                    <div class="panel-header"><h5><i class="bi bi-receipt me-2"></i>Recent orders</h5><a href="/admin/orders" class="btn btn-sm btn-outline-secondary">View all</a></div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead><tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Status</th><th>Time</th><th></th></tr></thead>
                            <tbody>
                            <?php if (empty($recent)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No orders yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recent as $o): ?>
                                <tr>
                                    <td><a class="order-number-link" href="/admin/orders/<?= $o['id'] ?>">#<?= htmlspecialchars($o['order_number']) ?></a></td>
                                    <td><?= htmlspecialchars($o['customer_name']) ?><br><small class="text-muted"><?= htmlspecialchars($o['customer_phone'] ?? '') ?></small></td>
                                    <td><strong>₱<?= number_format($o['total_amount'], 2) ?></strong></td>
                                    <td><span class="status-badge"><?= htmlspecialchars($o['status']) ?></span></td>
                                    <td><small><?= date('M j H:i', strtotime($o['created_at'])) ?></small></td>
                                    <td><a href="/admin/orders/<?= $o['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Menu health -->
                <div class="panel mb-3">
                    <div class="panel-header"><h5><i class="bi bi-book me-2"></i>Menu health</h5><a href="/admin/menu" class="btn btn-sm btn-outline-secondary">Manage</a></div>
                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-2"><span><i class="bi bi-trophy me-2"></i>Best sellers pinned</span><strong><?= $menu['bestsellers'] ?? 0 ?></strong></div>
                        <div class="d-flex justify-content-between mb-2"><span><i class="bi bi-star me-2"></i>Featured</span><strong><?= $menu['featured'] ?? 0 ?></strong></div>
                        <div class="d-flex justify-content-between mb-2"><span><i class="bi bi-check-circle me-2"></i>Available</span><strong><?= $menu['available'] ?? 0 ?></strong></div>
                        <div class="d-flex justify-content-between"><span><i class="bi bi-x-circle me-2"></i>Unavailable</span><strong><?= $menu['unavailable'] ?? 0 ?></strong></div>
                        <hr>
                        <h6 class="text-uppercase text-muted" style="font-size:.75rem;">Revenue by category</h6>
                        <?php if (empty($catRev)): ?><small class="text-muted">No completed sales yet.</small>
                        <?php else: foreach ($catRev as $c): ?>
                            <div class="d-flex justify-content-between mb-1"><span><?= htmlspecialchars($c['category']) ?> <small class="text-muted">(<?= (int)$c['qty'] ?> sold)</small></span><strong>₱<?= number_format($c['revenue'], 2) ?></strong></div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
                <!-- Unavailable -->
                <div class="panel mb-3">
                    <div class="panel-header"><h5><i class="bi bi-eye-slash me-2"></i>Unavailable items</h5></div>
                    <div class="p-3">
                        <?php if (empty($unavail)): ?><small class="text-success"><i class="bi bi-check-circle me-1"></i>Everything is available — nice.</small>
                        <?php else: foreach ($unavail as $p): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><strong><?= htmlspecialchars($p['name']) ?></strong><br><small class="text-muted"><?= htmlspecialchars($p['category_name'] ?? '') ?></small></div>
                                <a href="/admin/products/<?= $p['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">Fix</a>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
                <!-- Quick actions -->
                <div class="panel">
                    <div class="p-3">
                        <h5 class="mb-3">Quick actions</h5>
                        <a href="/admin/orders" class="action-btn"><i class="bi bi-bag-check"></i><div>Manage orders<small>Confirm, prepare & complete</small></div></a>
                        <a href="/admin/menu" class="action-btn"><i class="bi bi-book"></i><div>Menu management<small>Edit prices & availability</small></div></a>
                        <a href="/admin/best-sellers" class="action-btn"><i class="bi bi-trophy"></i><div>Best sellers<small>Pin & feature top items</small></div></a>
                        <a href="/admin/categories" class="action-btn"><i class="bi bi-tags"></i><div>Categories<small>Organize the menu</small></div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var trendLabels = <?= json_encode(array_column($trend, 'label')) ?>;
    var trendRevenue = <?= json_encode(array_column($trend, 'revenue')) ?>;
    var trendOrders = <?= json_encode(array_column($trend, 'orders')) ?>;
    var statusLabels = <?= json_encode(array_keys($byStatus)) ?>;
    var statusData = <?= json_encode(array_values($byStatus)) ?>;

    function showFallback(canvasId, emptyId) {
        var canvas = document.getElementById(canvasId);
        var empty = document.getElementById(emptyId);
        if (canvas) canvas.style.display = 'none';
        if (empty) empty.style.display = 'block';
    }

    // If the Chart.js CDN failed (offline/blocked), the tables below still
    // constitute the report — show a notice instead of throwing.
    if (typeof Chart === 'undefined') {
        showFallback('salesChart', 'salesChartEmpty');
        showFallback('statusChart', 'statusChartEmpty');
        var note = document.createElement('div');
        note.className = 'alert alert-warning small mt-2 mb-0';
        note.textContent = 'Charts unavailable (chart library failed to load). Tabular data below is unaffected.';
        var salesCanvas = document.getElementById('salesChart');
        if (salesCanvas && salesCanvas.parentNode) salesCanvas.parentNode.appendChild(note);
        return;
    }

    try {
        var salesCanvas = document.getElementById('salesChart');
        var hasSales = trendRevenue.some(function (v) { return Number(v) > 0; }) ||
                       trendOrders.some(function (v) { return Number(v) > 0; });
        if (!salesCanvas) {
            showFallback('salesChart', 'salesChartEmpty');
        } else if (!hasSales) {
            showFallback('salesChart', 'salesChartEmpty');
        } else {
            new Chart(salesCanvas, {
                type: 'bar',
                data: { labels: trendLabels, datasets: [
                    { label: 'Revenue (₱)', data: trendRevenue, backgroundColor: '#111111', borderRadius: 6 },
                    { label: 'Orders', data: trendOrders, type: 'line', borderColor: '#6c757d', backgroundColor: '#6c757d', tension: .35, yAxisID: 'y1' }
                ]},
                options: { responsive: true, plugins: { legend: { position: 'bottom' } },
                    scales: { y: { beginAtZero: true }, y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } } } }
            });
        }
    } catch (e) {
        console.error('Sales chart failed:', e);
        showFallback('salesChart', 'salesChartEmpty');
    }

    try {
        var statusCanvas = document.getElementById('statusChart');
        if (!statusCanvas || !statusData.length) {
            showFallback('statusChart', 'statusChartEmpty');
        } else {
            new Chart(statusCanvas, {
                type: 'doughnut',
                data: { labels: statusLabels,
                    datasets: [{ data: statusData,
                    backgroundColor: ['#111111','#343a40','#6c757d','#adb5bd','#dee2e6','#000000'] }] },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        }
    } catch (e) {
        console.error('Status chart failed:', e);
        showFallback('statusChart', 'statusChartEmpty');
    }
})();
</script>

<?php
$content = ob_get_clean();
$title = 'Admin Dashboard - CafeCat';
$currentPage = 'dashboard';
include __DIR__ . '/layout.php';
?>
