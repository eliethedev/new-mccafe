<?php ob_start(); ?>

<style>
:root {
    --cafecat-primary: #111111;
    --cafecat-secondary: #6c757d;
    --cafecat-accent: #000000;
    --cafecat-dark: #000000;
    --cafecat-light: #f8f9fa;
    --primary: #111111;
}

.users-container {
    background: var(--cafecat-light);
    min-height: 100vh;
}

.users-header {
    background: var(--cafecat-primary);
    color: white;
    padding: 1rem 0;
    margin-bottom: 0.2rem;
    border-radius: 0 0 20px 20px;
}

.users-header h1 {
    font-weight: bold;
    margin: 0;
}

.users-header .subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
}

.users-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow: hidden;
}

.users-table {
    margin-bottom: 0;
}

.users-table th {
    background: var(--cafecat-light);
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
    color: var(--cafecat-dark);
    padding: 1rem;
    white-space: nowrap;
}

.users-table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.users-table tbody tr:hover {
    background: #fafafa;
}

.users-table tbody tr:last-child td {
    border-bottom: none;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--cafecat-primary);
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.user-name-cell {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.user-name-cell .name {
    font-weight: 600;
    color: var(--cafecat-dark);
}

.user-name-cell .email {
    font-size: 0.875rem;
    color: var(--cafecat-secondary);
}

.badge-role {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-role.admin { background: #e7eaf3; color: #2c3e6b; }
.badge-role.staff { background: #d6f5f5; color: #0d6e6e; }
.badge-role.customer { background: #d4edda; color: #155724; }
.badge-role.rider { background: #fff3cd; color: #856404; }

.badge-status {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.badge-status.verified { background: #d4edda; color: #155724; }
.badge-status.unverified { background: #f8d7da; color: #721c24; }

.amount-cell {
    font-weight: 600;
    color: var(--cafecat-primary);
}

.orders-cell {
    font-weight: 600;
    color: var(--cafecat-secondary);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-btn {
    padding: 0.5rem 0.875rem;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    white-space: nowrap;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.action-btn.view {
    background: #e9ecef;
    color: var(--cafecat-dark);
}

.action-btn.view:hover {
    background: var(--cafecat-primary);
    color: white;
}

.action-btn.edit {
    background: #d1ecf1;
    color: #0c5460;
}

.action-btn.edit:hover {
    background: #17a2b8;
    color: white;
}

.action-btn.delete {
    background: #f8d7da;
    color: #721c24;
}

.action-btn.delete:hover {
    background: #dc3545;
    color: white;
}

.join-date {
    font-size: 0.8rem;
    color: var(--cafecat-secondary);
}

@media (max-width: 991px) {
    .users-table-container {
        border-radius: 0;
    }
    
    .users-table th:nth-child(4),
    .users-table td:nth-child(4),
    .users-table th:nth-child(5),
    .users-table td:nth-child(5) {
        display: none;
    }
}

@media (max-width: 767px) {
    .users-table th:nth-child(3),
    .users-table td:nth-child(3) {
        display: none;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
    }
}

.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state i {
    font-size: 3rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: var(--cafecat-secondary);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--cafecat-secondary);
    margin-bottom: 1.5rem;
}

.table-responsive {
    overflow-x: auto;
}

.pagination-wrapper {
    background: white;
    border-radius: 0 0 15px 15px;
    padding: 1rem 1.5rem;
    border-top: 1px solid #f0f0f0;
}

.pagination .page-link {
    color: var(--cafecat-primary);
    border-color: #e9ecef;
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    border-radius: 8px;
}

.pagination .page-link:hover {
    background: var(--cafecat-primary);
    color: white;
    border-color: var(--cafecat-primary);
}

.pagination .page-item.active .page-link {
    background: var(--cafecat-primary);
    border-color: var(--cafecat-primary);
}

.pagination .page-item.disabled .page-link {
    color: var(--cafecat-secondary);
}
</style>

<?php
// Helper functions
function getStatusBadgeClass($status) {
    $classes = [
        'admin' => 'badge-role admin',
        'staff' => 'badge-role staff',
        'customer' => 'badge-role customer',
        'rider' => 'badge-role rider',
    ];
    return $classes[$status] ?? 'badge-role';
}

function getRoleDisplay($role) {
    $labels = [
        'admin' => 'Admin',
        'staff' => 'Staff',
        'customer' => 'Customer',
        'rider' => 'Rider',
    ];
    return $labels[$role] ?? ucfirst($role);
}
?>

<?php
// Get status filter from query
$statusFilter = $_GET['status'] ?? '';
$roleFilter = $_GET['role'] ?? '';
$searchQuery = $_GET['search'] ?? '';
?>

<div class="users-container">
    <!-- Header -->
    <div class="users-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="text-white">Manage Users</h1>
                    <div class="subtitle">Manage customer accounts and staff access</div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="/admin/users/create" class="btn btn-light">
                        <i class="bi bi-plus-circle me-1"></i> Add User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Name, email..." value="<?= htmlspecialchars($searchQuery) ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Role</label>
                        <select name="role" class="form-select">
                            <option value="">All Roles</option>
                            <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="staff" <?= $roleFilter === 'staff' ? 'selected' : '' ?>>Staff</option>
                            <option value="customer" <?= $roleFilter === 'customer' ? 'selected' : '' ?>>Customer</option>
                            <option value="rider" <?= $roleFilter === 'rider' ? 'selected' : '' ?>>Rider</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active (Verified)</option>
                            <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive (Unverified)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Filter
                            </button>
                            <a href="/admin/users" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <?php if (empty($users ?? [])): ?>
        <div class="users-table-container">
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h4 class="text-muted">No users found</h4>
                <p class="text-muted">No users match your current filters.</p>
                <a href="/admin/users" class="btn btn-primary">Clear Filters</a>
            </div>
        </div>
        <?php else: ?>
        <div class="users-table-container">
            <div class="table-responsive">
                <table class="table users-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="min-width: 200px;">User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="width: 120px;">Orders</th>
                            <th style="width: 140px;">Total Spent</th>
                            <th style="width: 140px;">Joined</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><span class="text-muted">#<?= $user['id'] ?></span></td>
                            <td>
                                <div class="user-name-cell">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="user-avatar"><?= strtoupper(substr($user['first_name'] ?? 'A', 0, 1)) ?></span>
                                        <span class="name"><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></span>
                                    </div>
                                    <span class="email"><?= htmlspecialchars($user['email'] ?? '') ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-role <?= $user['role'] ?? '' ?>">
                                    <?= getRoleDisplay($user['role'] ?? '') ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($user['email_verified_at'])): ?>
                                <span class="badge-status verified">
                                    <i class="bi bi-check-circle-fill me-1"></i>Verified
                                </span>
                                <?php else: ?>
                                <span class="badge-status unverified">
                                    <i class="bi bi-x-circle-fill me-1"></i>Unverified
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="orders-cell"><?= $user['orders_count'] ?? 0 ?></td>
                            <td class="amount-cell">₱<?= number_format($user['total_spent'] ?? 0, 2) ?></td>
                            <td class="join-date"><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/users/<?= $user['id'] ?>" class="action-btn view" title="View Details">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="/admin/users/<?= $user['id'] ?>/edit" class="action-btn edit" title="Edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" action="/admin/users/<?= $user['id'] ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        <button type="submit" class="action-btn delete" title="Delete">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (($totalPages ?? 1) > 1): ?>
            <div class="pagination-wrapper">
                <nav aria-label="Users pagination">
                    <ul class="pagination justify-content-center mb-0">
                        <?php if (($currentPage ?? 1) > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>">Previous</a>
                        </li>
                        <?php endif; ?>
                        
                        <?php 
                        $current = $currentPage ?? 1;
                        $total = $totalPages ?? 1;
                        $start = max(1, $current - 2);
                        $end = min($total, $current + 2);
                        
                        if ($start > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a>
                        </li>
                        <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= $i == $current ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($end < $total): ?>
                        <?php if ($end < $total - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $total])) ?>"><?= $total ?></a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($current < $total): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $current + 1])) ?>">Next</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = $title ?? 'Manage Users - CafeCat';
$currentPage = 'users';
include __DIR__ . '/../layout.php';
?>