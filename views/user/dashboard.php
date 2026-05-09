<?php ob_start(); ?>

<?php
// Simple time ago function
function timeAgo($timestamp) {
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $timestamp);
    }
}
?>

<style>
/* Dashboard Custom Styles */
.dashboard-hero {
    background: linear-gradient(135deg, var(--maccafe-primary), #e48d0a);
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(255, 193, 7, 0.3);
    position: relative;
    overflow: hidden;
}

.dashboard-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.dashboard-hero h1 {
    color: white;
    font-weight: bold;
    margin-bottom: 10px;
    position: relative;
    z-index: 1;
}

.dashboard-hero p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0;
    position: relative;
    z-index: 1;
}

.stats-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, var(--maccafe-primary), #ffb300);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.stats-icon.orders {
    background: black;
    color: white;
}

.stats-icon.cart {
    background: black;
    color: white;
}

.stats-icon.favorite {
    background: black;
    color: white;
}

.stats-icon.spent {
    background: black;
    color: white;
}

.stats-number {
    font-size: 2rem;
    font-weight: bold;
    color: var(--maccafe-dark);
    margin-bottom: 5px;
}

.stats-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

.user-profile-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.user-profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(135deg, var(--maccafe-primary), #ffb300);
}

.user-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    position: relative;
    z-index: 1;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: 4px solid white;
}

.user-avatar i {
    font-size: 3rem;
    color: var(--maccafe-primary);
}

.user-name {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--maccafe-dark);
    margin-bottom: 5px;
}

.user-email {
    color: #6c757d;
    margin-bottom: 15px;
}

.user-role {
    display: inline-block;
    padding: 6px 16px;
    background: green;
    color: white;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.quick-actions-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.quick-actions-card h5 {
    color: var(--maccafe-dark);
    font-weight: bold;
    margin-bottom: 25px;
    position: relative;
    padding-left: 15px;
}

.quick-actions-card h5::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 20px;
    background: linear-gradient(135deg, var(--maccafe-primary), #ffb300);
}

.action-btn {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border-radius: 15px;
    border: 2px solid #f0f0f0;
    background: white;
    color: var(--maccafe-dark);
    text-decoration: none;
    transition: all 0.3s ease;
    margin-bottom: 15px;
    font-weight: 500;
}

.action-btn:hover {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    border-color: var(--maccafe-primary);
    color: var(--maccafe-dark);
    transform: translateX(5px);
    text-decoration: none;
}

.action-btn i {
    font-size: 1.2rem;
    margin-right: 15px;
    width: 30px;
    text-align: center;
}

.action-btn.orders i { color: #667eea; }
.action-btn.menu i { color: #28a745; }
.action-btn.profile i { color: #17a2b8; }
.action-btn.cart i { color: #ffc107; }

.recent-activity {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-top: 30px;
}

.recent-activity h5 {
    color: var(--maccafe-dark);
    font-weight: bold;
    margin-bottom: 20px;
    position: relative;
    padding-left: 15px;
}

.recent-activity h5::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 20px;
    background: linear-gradient(135deg, var(--maccafe-primary), #ffb300);
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 1.1rem;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 500;
    color: var(--maccafe-dark);
    margin-bottom: 2px;
}

.activity-time {
    font-size: 0.85rem;
    color: #6c757d;
}

.back-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .dashboard-hero {
        padding: 25px;
    }
    
    .stats-card {
        margin-bottom: 20px;
    }
    
    .user-profile-card,
    .quick-actions-card {
        margin-bottom: 20px;
    }
}
</style>

<div class="container py-5 mt-5">
    <!-- Dashboard Hero Section -->
    <div class="dashboard-hero">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="text-white">Welcome back, <?= Session::get('user')['first_name'] ?? 'User' ?>! </h1>
                <p>Ready to enjoy your favorite coffee and delicious meals?</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="/" class="back-btn">
                    <i class="bi bi-house me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 col-lg-12">
        <?php
        $user = Session::get('user');
        $totalOrders = 0;
        $cartItems = 0;
        $totalSpent = 0.00;
        
        if ($user) {
            // Get total orders count
            try {
                $totalOrders = Order::getUserOrderCount($user['id']);
            } catch (Exception $e) {
                $totalOrders = 0;
            }
            
            // Get cart items count
            try {
                $cartModel = new Cart();
                $cartItems = $cartModel->getCartItemCount($user['id']);
            } catch (Exception $e) {
                $cartItems = 0;
            }
            
            // Get total spent amount
            try {
                $totalSpent = Order::getUserTotalSpent($user['id']);
            } catch (Exception $e) {
                $totalSpent = 0.00;
            }
        }
        ?>
        
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon orders">
                    <i class="bi bi-bag-check"></i>
                </div>
                <div class="stats-number"><?= $totalOrders ?></div>
                <div class="stats-label">Total Orders</div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon spent">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-number">$<?= number_format($totalSpent, 2) ?></div>
                <div class="stats-label">Total Spent</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="user-profile-card">
                <div class="user-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="user-name">
                    <?= Session::get('user')['first_name'] ?? 'First' ?> <?= Session::get('user')['last_name'] ?? 'Name' ?>
                </div>
                <div class="user-email">
                    <?= Session::get('user')['email'] ?? 'email@example.com' ?>
                </div>
                <span class="user-role">
                    <i class="bi bi-patch-check me-1"></i><?= ucfirst(Session::get('user')['role'] ?? 'customer') ?>
                </span>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="col-lg-8 mb-4">
            <div class="quick-actions-card">
                <h5>Quick Actions</h5>
                <div class="row">
                    <div class="col-md-6">
                        <a href="/orders" class="action-btn orders">
                            <i class="bi bi-bag-check"></i>
                            <div>
                                <div>My Orders</div>
                                <small class="text-muted">View order history</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/menu" class="action-btn menu">
                            <i class="bi bi-cup-hot"></i>
                            <div>
                                <div>View Menu</div>
                                <small class="text-muted">Browse our menu</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/profile" class="action-btn profile">
                            <i class="bi bi-gear"></i>
                            <div>
                                <div>Edit Profile</div>
                                <small class="text-muted">Update your info</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/cart" class="action-btn cart">
                            <i class="bi bi-cart3"></i>
                            <div>
                                <div>View Cart</div>
                                <small class="text-muted"><?= $cartItems ?> items waiting</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
        <h5>Recent Activity</h5>
        <?php
        // Get recent user activities
        try {
            $recentOrders = [];
            $recentOrders = Order::getUserRecentOrders($user['id'], 3);
            
            if (!empty($recentOrders)) {
                foreach ($recentOrders as $order) {
                    $orderTime = strtotime($order['created_at']);
                    $timeAgo = timeAgo($orderTime);
                    ?>
                    <div class="activity-item">
                        <div class="activity-icon" style="background: black; color: white;">
                            <i class="bi bi-bag-check"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Order #<?= $order['id'] ?> <?= ucfirst($order['status']) ?></div>
                            <div class="activity-time"><?= $timeAgo ?></div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Show placeholder if no recent activity
                ?>
                <div class="activity-item">
                    <div class="activity-icon" style="background: black; color: white;">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">No recent activity</div>
                        <div class="activity-time">Start ordering to see your activity here</div>
                    </div>
                </div>
                <?php
            }
        } catch (Exception $e) {
            // Fallback to static content if database fails
            ?>
            <div class="activity-item">
                <div class="activity-icon" style="background: black; color: white;">
                    <i class="bi bi-info-circle"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Activity loading...</div>
                    <div class="activity-time">Please refresh the page</div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Dashboard - MacCafe';
include __DIR__ . '/../layouts/main.php';
?>
