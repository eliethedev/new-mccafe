<link rel="stylesheet" href="/assets/css/style.css">
<style>/* CafeCat Custom Styles */

:root {
    --cafecat-primary: #111111;
    --cafecat-secondary: #6c757d;
    --cafecat-accent: #000000;
    --cafecat-dark: #000000;
    --cafecat-light: #f8f9fa;
    --primary: #111111;
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
    margin: 0 22px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
}

.navbar-nav .nav-link:hover {
    color: #f1f1f1 !important;
    transform: translateY(-2px);
}

.navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    width: 0;
    height: 2px;
    background-color: #f1f1f1;
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.navbar-nav .nav-link:hover::after {
    width: 100%;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--cafecat-primary), var(--cafecat-dark));
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
    color: var(--cafecat-primary);
}

.popular-item {
    background-color: transparent !important;
    border: 2px solid rgba(0, 0, 0, 0.25) !important;
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
    border-color: var(--cafecat-accent) !important;
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
    border: 2px solid var(--cafecat-accent);
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
    background-color: var(--cafecat-primary);
    border-color: var(--cafecat-primary);
}

.btn-primary {
    background-color: var(--cafecat-primary) !important;
    border-color: var(--cafecat-primary) !important;
}

.btn-success:hover {
    background-color: #000000;
    border-color: #000000;
}

/* Category Filter Button Hover Effects */
.btn-outline-success {
    color: var(--cafecat-primary);
    border-color: var(--cafecat-primary);
    transition: all 0.3s ease;
}

.btn-outline-success:hover {
    background-color: var(--cafecat-primary);
    border-color: var(--cafecat-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.btn-outline-success.active {
    background-color: var(--cafecat-primary);
    border-color: var(--cafecat-primary);
    color: white;
}

.btn-cafecat-accent {
    background-color: #f1f1f1;
    border-color: var(--cafecat-accent);
    color: #ffffff;
}

.btn-cafecat-accent:hover {
    background-color: #333333;
    border-color: #000000;
    color: #212529;
}

.text-primary {
    color: #111111 !important;
}

h1, h2, h3 {
    color: var(--cafecat-primary) !important;
}

/* Form Styles */
.form-control:focus {
    border-color: var(--cafecat-primary);
    box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.25);
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
    color: var(--cafecat-accent) !important;
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
    background: var(--cafecat-primary);
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
.status-pending { background-color: #111111; }
.status-confirmed { background-color: #343a40; }
.status-preparing { background-color: #3a3a3a; }
.status-ready { background-color: #000000; }
.status-completed { background-color: #6c757d; }
.status-cancelled { background-color: #000000; }

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
    background: #f1f1f1;
    color: #000000 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    border-color: var(--cafecat-primary);
}

.user-dropdown .nav-link i {
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.user-dropdown .nav-link:hover i {
    color: #000000 !important;
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
    background: linear-gradient(135deg, #f4f4f4, #e9ecef);
    color: var(--cafecat-dark) !important;
    transform: translateX(5px);
    border-left-color: var(--cafecat-primary);
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
    background: linear-gradient(135deg, #111111, #000000) !important;
    color: white !important;
    border: none;
    border-radius: 10px !important;
    margin-top: 5px;
}

.logout-btn:hover {
    background: linear-gradient(135deg, #000000, #1f1f1f) !important;
    transform: translateX(5px) scale(1.02);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
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
    background: #f1f1f1;
    color: #000000 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    border-color: #000000;
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
    color: #000000 !important;
    transform: scale(1.1);
}

#cart-count {
    font-size: 0.65rem;
    padding: 3px 7px;
    background: linear-gradient(135deg, #000000, #1f1f1f);
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    }
    50% {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.6);
        transform: scale(1.05);
    }
    100% {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
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
    background: linear-gradient(135deg, var(--cafecat-primary), #111111);
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    border-color: var(--cafecat-primary);
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
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: linear-gradient(135deg, #000000, #1f1f1f); box-shadow: 0 2px 10px rgba(0,0,0,0.35);">
    <div class="container">
<div class="logo"><a href="/" style="text-decoration:none;display:inline-block;line-height:1;"><span style="font-weight:800;font-size:1.8rem;letter-spacing:-1px;color:#ffffff;">caf&eacute;<span style="color:#d9d9d9;">cat</span></span><br><small style="letter-spacing:3px;font-size:0.55rem;color:#d9d9d9;">CRAFTED FOR YOUR CRAVINGS</small></a></div>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/menu">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/#services">Services</a>
                </li><li class="nav-item">
                    <a class="nav-link text-white" href="/#about-us">About Us</a>
                </li>
                <?php $navUser = Session::get('user'); ?>
                <?php if (!empty($navUser) && in_array($navUser['role'] ?? '', ['rider', 'admin'], true)): ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/rider"><i class="bi bi-bicycle me-1"></i>Deliveries</a>
                </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <?php if (Session::has('user')): ?>
                    <li class="nav-item user-dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
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
                                    <button type="submit" class="dropdown-item logout-btn" style="border: none; width: 100%; text-align: left; padding: 0.25rem 1rem; cursor: pointer;">
                                        <i class="bi bi-box-arrow-right"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-white auth-btn" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white auth-btn" href="/register">Register</a>
                    </li>
                <?php endif; ?>
                
                <?php if (Session::has('user')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative text-white" href="#" id="notifBell" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifBadge" style="display:none;">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2" id="notifList" style="min-width:320px;max-height:380px;overflow-y:auto;" aria-labelledby="notifBell">
                            <li class="px-3 py-3 text-muted small text-center">Loading notifications…</li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-item cart-icon-wrapper">
                    <a class="nav-link position-relative text-white" href="/cart">
                        <i class="bi bi-cart3" style="color:#ffffff !important;"></i>
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



