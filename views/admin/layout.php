<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel - MacCafe' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="/assets/css/admin.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Admin Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin">
                <i class="bi bi-cup-hot-fill me-2"></i>MacCafe Admin
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>" href="/admin">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['products', 'categories']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-box-seam me-1"></i> Products
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/admin/products">Manage Products</a></li>
                            <li><a class="dropdown-item" href="/admin/categories">Manage Categories</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/admin/products/create">Add New Product</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'orders' ? 'active' : '' ?>" href="/admin/orders">
                            <i class="bi bi-cart-check me-1"></i> Orders
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> 
                            <?= Session::get('user.first_name') ?> <?= Session::get('user.last_name') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile">
                                <i class="bi bi-person me-2"></i>My Profile
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/" target="_blank">
                                <i class="bi bi-shop me-2"></i>View Store
                            </a></li>
                            <li><a class="dropdown-item" href="/logout">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (Session::getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= Session::getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (Session::getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= Session::getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php 
    $errors = Session::getFlash('errors');
    if ($errors && is_array($errors)): 
?>
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
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

    <!-- Main Content -->
    <main class="container-fluid py-4">
        <?= $content ?? '' ?>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/assets/js/admin.js"></script>
</body>
</html>
<?php
$layoutContent = ob_get_clean();
echo $layoutContent;
?>
