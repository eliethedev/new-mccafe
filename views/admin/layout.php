<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel - CafeCat' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="/assets/css/admin.css" rel="stylesheet">
    <link href="/assets/css/bw-theme.css" rel="stylesheet">
    <style>
        :root {
            --cafecat-primary: #111111;
            --cafecat-secondary: #6c757d;
            --cafecat-accent: #000000;
            --cafecat-dark: #000000;
            --cafecat-light: #f8f9fa;
            --primary: #111111;
        }
        
        body {
            background: var(--cafecat-light);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 240px;
            background: var(--cafecat-dark);
            color: white;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.2);
        }
        
        .sidebar-brand {
            color: white;
            text-decoration: none;
            font-size: 1.25rem;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .sidebar-brand:hover {
            color: var(--cafecat-primary);
            text-decoration: none;
        }

        .sidebar-logo {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.6rem;
            border: 2px solid rgba(255,255,255,0.25);
            background: #fff;
            flex-shrink: 0;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.25);
            border-radius: 3px;
        }
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.45);
        }
        
        .sidebar-section {
            margin-bottom: 2px;
        }
        
        .sidebar-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-nav-item {
            margin-bottom: 0.25rem;
        }
        
        .sidebar-nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            text-decoration: none;
            border-left-color: var(--cafecat-primary);
        }
        
        .sidebar-nav-link.active {
            background: var(--cafecat-primary);
            color: white;
            border-left-color: var(--cafecat-accent);
        }
        
        .sidebar-nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-dropdown {
            background: rgba(0,0,0,0.2);
        }
        
        .sidebar-dropdown .sidebar-nav-link {
            padding-left: 3rem;
            font-size: 0.9rem;
        }
        
        .main-content {
            flex: 1;
            margin-left: 240px;
            min-height: 100vh;
        }
        
        .top-nav {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 0.45rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            min-height: 52px;
        }
        
        .top-nav-left {
            display: flex;
            align-items: center;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--cafecat-dark);
            margin-right: 1rem;
            cursor: pointer;
            display: none;
        }
        
        .page-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--cafecat-dark);
            margin: 0;
        }
        
        .top-nav-right {
            display: flex;
            align-items: center;
            gap: 0rem;
        }
        
        .user-dropdown {
            position: relative;
        }
        
        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            padding: 0.25rem 0.7rem;
            background: var(--cafecat-light);
            border: 1px solid #e9ecef;
            border-radius: 25px;
            color: var(--cafecat-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        
        .user-dropdown-toggle:hover {
            background: var(--cafecat-primary);
            color: white;
            text-decoration: none;
            border-color: var(--cafecat-primary);
        }
        
        .user-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--cafecat-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.45rem;
            font-weight: bold;
            font-size: 0.7rem;
        }
        
        .content-area {
            padding: 2rem;
        }
        
        .alert {
            margin-bottom: 1rem;
            border-radius: 10px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .content-area {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="/admin" class="sidebar-brand">
                    <img src="/assets/images/cafe-cat_logo-cropped.jpg" alt="caf&eacute;cat logo" class="sidebar-logo">
                    CafeCat Admin
                </a>
            </div>
            
            <nav class="sidebar-menu">
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Main</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-nav-item">
                            <a href="/admin" class="sidebar-nav-link <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-section-title">Café Service</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-nav-item">
                            <a href="/admin/orders" class="sidebar-nav-link <?= ($currentPage ?? '') === 'orders' ? 'active' : '' ?>">
                                <i class="bi bi-cart-check"></i>
                                Orders
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="/admin/users" class="sidebar-nav-link <?= ($currentPage ?? '') === 'users' ? 'active' : '' ?>">
                                <i class="bi bi-people"></i>
                                Customers
                            </a>
                            <a href="/rider" class="sidebar-nav-link <?= ($currentPage ?? '') === 'riders' ? 'active' : '' ?>">
                                <i class="bi bi-bicycle"></i>
                                Riders
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="/admin/support" class="sidebar-nav-link <?= ($currentPage ?? '') === 'support' ? 'active' : '' ?>">
                                <i class="bi bi-headset"></i>
                                Support Chats
                            </a>
                        </li>
                    </ul>
                </div>
                 
                 <div class="sidebar-section">
                     <div class="sidebar-section-title">Menu</div>
                     <ul class="sidebar-nav">
                         <li class="sidebar-nav-item">
                             <a href="/admin/menu" class="sidebar-nav-link <?= in_array(($currentPage ?? ''), ['products', 'menu']) ? 'active' : '' ?>">
                                 <i class="bi bi-book"></i>
                                 Menu Management
                             </a>
                         </li>
                         <li class="sidebar-nav-item">
                             <a href="/admin/products" class="sidebar-nav-link <?= ($currentPage ?? '') === 'products' ? 'active' : '' ?>">
                                 <i class="bi bi-box-seam"></i>
                                 All Products
                             </a>
                         </li>
                         <li class="sidebar-nav-item">
                             <a href="/admin/best-sellers" class="sidebar-nav-link <?= ($currentPage ?? '') === 'best-sellers' ? 'active' : '' ?>">
                                 <i class="bi bi-trophy"></i>
                                 Best Sellers
                             </a>
                         </li>
                         <li class="sidebar-nav-item">
                             <a href="/admin/categories" class="sidebar-nav-link <?= ($currentPage ?? '') === 'categories' ? 'active' : '' ?>">
                                 <i class="bi bi-tags"></i>
                                 Categories
                             </a>
                         </li>
                         <li class="sidebar-nav-item">
                             <a href="/admin/products/create" class="sidebar-nav-link <?= ($currentPage ?? '') === 'products-create' ? 'active' : '' ?>">
                                 <i class="bi bi-plus-circle"></i>
                                 Add Product
                             </a>
                         </li>
                     </ul>
                 </div>
                
                <div class="sidebar-section">
                    <div class="sidebar-section-title">System</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-nav-item">
                            <a href="/" target="_blank" class="sidebar-nav-link">
                                <i class="bi bi-shop"></i>
                                View Store
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="/logout" class="sidebar-nav-link">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <header class="top-nav" >
                <div class="top-nav-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title"><?= $title ? str_replace(' - CafeCat', '', $title) : 'Dashboard' ?></h1>
                </div>
                
                <div class="top-nav-right">
                    <div class="dropdown">
                        <a href="#" class="position-relative text-decoration-none text-dark me-3" id="adminNotifBell" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="adminNotifBadge" style="display:none;">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2" id="adminNotifList" style="min-width:340px;max-height:380px;overflow-y:auto;" aria-labelledby="adminNotifBell">
                            <li class="px-3 py-3 text-muted small text-center">Loading notifications…</li>
                        </ul>
                    </div>
                    <div class="user-dropdown">
                        <a href="#" class="user-dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <?php $adminUser = Session::get('user') ?? []; ?>
                                <?= strtoupper(substr($adminUser['first_name'] ?? 'A', 0, 1)) ?>
                            </div>
                            <span><?= htmlspecialchars(($adminUser['first_name'] ?? 'Admin') . ' ' . ($adminUser['last_name'] ?? '')) ?></span>
                            <i class="bi bi-chevron-down ms-2"></i>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/" target="_blank">
                                <i class="bi bi-shop me-2"></i>View Store
                            </a></li>
                            <li><a class="dropdown-item" href="/logout">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a></li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="content-area">
                <!-- Flash Messages -->
                <?php $flashSuccess = Session::getFlash('success'); ?>
                <?php if ($flashSuccess): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php $flashError = Session::getFlash('error'); ?>
                <?php if ($flashError): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($flashError) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>


                <?php 
                $errors = Session::getFlash('errors');
                if ($errors && is_array($errors)): 
            ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Please fix the following errors:</strong><br>
                        <?php foreach ($errors as $field => $fieldErrors): ?>
                            <?php foreach ($fieldErrors as $error): ?>
                                <small><?= $error ?></small><br>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Page Content -->
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/assets/js/admin.js"></script>
    <!-- Delivery notification bell (real-time polling) -->
    <script src="/assets/js/notifications.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.CafeCatNotifications && document.getElementById('adminNotifBadge')) {
                window.CafeCatNotifications.initBell({
                    badgeId: 'adminNotifBadge',
                    listId: 'adminNotifList',
                    bellId: 'adminNotifBell',
                    ordersUrl: '/admin/orders'
                });
            }
        });
    </script>
    
    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                            sidebar.classList.remove('show');
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
<?php
$layoutContent = ob_get_clean();
echo $layoutContent;
?>
