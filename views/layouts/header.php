<link rel="stylesheet" href="assets/css/style.css">
<style>/* MacCafe Custom Styles */

:root {
    --maccafe-primary: #e09407;
    --maccafe-secondary: #6c757d;
    --maccafe-accent: #ffc107;
    --maccafe-dark: #343a40;
    --maccafe-light: #f8f9fa;
    --primary: #e09407;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
}

/* Navbar Styles */
.navbar-brand {
    font-size: 1.5rem;
    font-weight: bold;
}

.navbar-nav .nav-link {
    margin: 0 15px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
}

.navbar-nav .nav-link:hover {
    color: var(--maccafe-accent) !important;
    transform: translateY(-2px);
}

.navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    width: 0;
    height: 2px;
    background-color: var(--maccafe-accent);
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.navbar-nav .nav-link:hover::after {
    width: 100%;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--maccafe-primary), var(--maccafe-dark));
    color: white;
    padding: 80px 0;
    margin-bottom: 50px;
}

.hero-section h1 {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 20px;
}

.hero-section p {
    font-size: 1.2rem;
    margin-bottom: 30px;
}

/* Product Cards */
.product-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.product-card img {
    height: 200px;
    object-fit: cover;
}

.product-card .card-body {
    padding: 20px;
}

.product-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--maccafe-primary);
}

.popular-item {
    background-color: transparent !important;
    border: 2px solid rgba(248, 184, 8, 0.884) !important;
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
}

.popular-item img {
    height: 200px !important;
    width: 100% !important;
    object-fit: cover;
}

.popular-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-color: var(--maccafe-accent) !important;
}

/* Category Cards */
.category-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.menu-category-card {
    height: 350px;
    display: flex;
    flex-direction: column;
}

.menu-category-card .btn {
    padding: 3px 16px;
    font-size: 0.875rem;
    align-self: center;
}

.menu-category-card .card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.category-card .card-img-top {
    height: 200px !important;
    width: 100% !important;
    object-fit: cover;
}

.menu-category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    border: 2px solid var(--maccafe-accent);
}

.category-card img {
    height: 200px !important;
    width: 100% !important;
    object-fit: cover;
}

.col-lg-2-4 {
    flex: 0 0 auto;
    width: 20%;
    max-width: 20%;
}

/* Cart Badge */
#cart-count {
    font-size: 0.7rem;
    padding: 2px 6px;
}

/* Button Styles */
.btn-success {
    background-color: var(--maccafe-primary);
    border-color: var(--maccafe-primary);
}

.btn-primary {
    background-color: var(--maccafe-primary) !important;
    border-color: var(--maccafe-primary) !important;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

/* Category Filter Button Hover Effects */
.btn-outline-success {
    color: var(--maccafe-primary);
    border-color: var(--maccafe-primary);
    transition: all 0.3s ease;
}

.btn-outline-success:hover {
    background-color: var(--maccafe-primary);
    border-color: var(--maccafe-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.btn-outline-success.active {
    background-color: var(--maccafe-primary);
    border-color: var(--maccafe-primary);
    color: white;
}

.btn-maccafe-accent {
    background-color: var(--maccafe-accent);
    border-color: var(--maccafe-accent);
    color: #fab011;
}

.btn-maccafe-accent:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    color: #212529;
}

.text-primary {
    color: #ffc107 !important;
}

h1, h2, h3 {
    color: var(--maccafe-primary) !important;
}

/* Form Styles */
.form-control:focus {
    border-color: var(--maccafe-primary);
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

/* Alert Styles */
.alert {
    border-radius: 10px;
    border: none;
}

/* Footer Styles */
footer {
    margin-top: auto;
}

footer a:hover {
    color: var(--maccafe-accent) !important;
}

/* Loading Spinner */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Cart Sidebar */
.cart-sidebar {
    position: fixed;
    top: 0;
    right: -400px;
    width: 400px;
    height: 100vh;
    background: white;
    box-shadow: -5px 0 15px rgba(0,0,0,0.1);
    transition: right 0.3s ease;
    z-index: 1050;
    overflow-y: auto;
}

.cart-sidebar.show {
    right: 0;
}

.cart-sidebar-header {
    background: var(--maccafe-primary);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-sidebar-body {
    padding: 20px;
}

.cart-sidebar-footer {
    padding: 20px;
    border-top: 1px solid #dee2e6;
}

/* Order Status Badges */
.status-pending { background-color: #ffc107; }
.status-confirmed { background-color: #17a2b8; }
.status-preparing { background-color: #6f42c1; }
.status-ready { background-color: #28a745; }
.status-completed { background-color: #6c757d; }
.status-cancelled { background-color: #dc3545; }

/* Enhanced User Dropdown Styles */
.user-dropdown {
    position: relative;
}

.user-dropdown .nav-link {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 25px;
    padding: 8px 16px !important;
    margin: 0 5px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.user-dropdown .nav-link:hover {
    background: linear-gradient(135deg, var(--maccafe-primary), #ffb300);
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
    border-color: var(--maccafe-primary);
}

.user-dropdown .nav-link i {
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.user-dropdown .nav-link:hover i {
    transform: scale(1.1);
}

.user-dropdown .dropdown-menu {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    background: white;
    padding: 10px;
    margin-top: 10px;
    min-width: 220px;
    animation: dropdownSlide 0.3s ease;
}

@keyframes dropdownSlide {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.user-dropdown .dropdown-item {
    border-radius: 10px;
    padding: 12px 16px;
    margin: 2px 0;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    font-weight: 500;
}

.user-dropdown .dropdown-item:hover {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: var(--maccafe-dark) !important;
    transform: translateX(5px);
    border-left-color: var(--maccafe-primary);
}

.user-dropdown .dropdown-item i {
    margin-right: 8px;
    font-size: 1.1rem;
}

.dropdown-divider {
    margin: 8px 0;
    border-color: #e9ecef;
}

.logout-btn {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
    color: white !important;
    border: none;
    border-radius: 10px !important;
    margin-top: 5px;
}

.logout-btn:hover {
    background: linear-gradient(135deg, #c82333, #bd2130) !important;
    transform: translateX(5px) scale(1.02);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Enhanced Cart Icon Styles */
.cart-icon-wrapper {
    position: relative;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 25px;
    padding: 8px 16px !important;
    margin: 0 5px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.cart-icon-wrapper:hover {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
    border-color: #28a745;
}

.cart-icon-wrapper .nav-link {
    color: inherit !important;
    padding: 0 !important;
    margin: 0 !important;
    background: none !important;
    border: none !important;
    box-shadow: none !important;
}

.cart-icon-wrapper i {
    font-size: 1.3rem;
    transition: transform 0.3s ease;
}

.cart-icon-wrapper:hover i {
    transform: scale(1.1);
}

#cart-count {
    font-size: 0.65rem;
    padding: 3px 7px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
    animation: pulse 2s infinite;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

@keyframes pulse {
    0% {
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
    }
    50% {
        box-shadow: 0 2px 12px rgba(220, 53, 69, 0.6);
        transform: scale(1.05);
    }
    100% {
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
    }
}

/* Auth Buttons Styles */
.auth-btn {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px solid transparent;
    border-radius: 25px;
    padding: 8px 20px !important;
    margin: 0 5px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    font-weight: 500;
}

.auth-btn:hover {
    background: linear-gradient(135deg, var(--maccafe-primary), #ffb300);
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
    border-color: var(--maccafe-primary);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 2rem;
    }
    
    .hero-section {
        padding: 60px 0;
    }
    
    .cart-sidebar {
        width: 100%;
        right: -100%;
    }
    
    .user-dropdown .nav-link,
    .cart-icon-wrapper,
    .auth-btn {
        margin: 2px;
        padding: 6px 12px !important;
    }
    
    .user-dropdown .dropdown-menu {
        min-width: 200px;
    }
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.slide-in-right {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}
</style>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div class="container">
<div class="logo"><h1 class="text-dark">MC<b style="color: orange;">Caffe</b></h1></div>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/menu">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/#services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/#contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/#about-us">About Us</a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <?php if (Session::has('user')): ?>
                    <li class="nav-item user-dropdown">
                        <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= Session::get('user.first_name') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item text-dark" href="/dashboard"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item text-dark" href="/orders"><i class="bi bi-bag-check"></i>My Orders</a></li>
                            <li><a class="dropdown-item text-dark" href="/profile"><i class="bi bi-person-gear"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout" method="POST" style="display: inline;">
                                    <button type="submit" class="dropdown-item text-dark logout-btn" style="border: none; background: none; width: 100%; text-align: left; padding: 0.25rem 1rem; cursor: pointer;">
                                        <i class="bi bi-box-arrow-right"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-dark auth-btn" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark auth-btn" href="/register">Register</a>
                    </li>
                <?php endif; ?>
                
                <li class="nav-item cart-icon-wrapper">
                    <a class="nav-link position-relative text-dark" href="/cart">
                        <i class="bi bi-cart3"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                            <?php 
                            $user = Session::get('user');
                            if ($user) {
                                $cartModel = new Cart();
                                $cartCount = $cartModel->getCartItemCount($user['id']);
                                echo $cartCount > 0 ? $cartCount : '0';
                            } else {
                                echo '0';
                            }
                            ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
