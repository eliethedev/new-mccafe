<?php ob_start(); ?>
<?php 
$baseUrl = rtrim(APP_URL, '/') . '/assets/images/';
?>
<style>
    
:root {
    --maccafe-primary: #d88911;
    --maccafe-secondary: #6c757d;
    --maccafe-accent: #d88911;
    --maccafe-dark: #343a40;
    --maccafe-light: #f8f9fa;
    --primary: #d88911;
}

/* Scroll Animations */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease-out;
}

.animate-on-scroll.animate-fade-in {
    opacity: 1;
    transform: translateY(0);
}

.animate-on-scroll.animate-slide-left {
    opacity: 1;
    transform: translateX(0);
}

.animate-on-scroll.animate-slide-right {
    opacity: 1;
    transform: translateX(0);
}

.animate-on-scroll.animate-scale {
    opacity: 1;
    transform: scale(1);
}

/* Initial states for different animations */
.animate-slide-left {
    transform: translateX(-50px);
}

.animate-slide-right {
    transform: translateX(50px);
}

.animate-scale {
    transform: scale(0.8);
}

/* Staggered animation delays */
.animate-delay-1 { transition-delay: 0.1s; }
.animate-delay-2 { transition-delay: 0.2s; }
.animate-delay-3 { transition-delay: 0.3s; }
.animate-delay-4 { transition-delay: 0.4s; }

/* Hero section animations */
.hero-content {
    opacity: 0;
    transform: translateX(-50px);
    animation: slideInLeft 1s ease-out forwards;
}

.hero-section .swiper {
    opacity: 0;
    transform: translateX(50px);
    animation: slideInRight 1s ease-out 0.3s forwards;
}

@keyframes slideInLeft {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Enhanced animations and transitions */
.hero-content {
    opacity: 0;
    transform: translateX(-50px);
    animation: slideInLeft 1s ease-out forwards;
}

.hero-section .swiper {
    opacity: 0;
    transform: translateX(50px);
    animation: slideInRight 1s ease-out 0.3s forwards;
}

/* Enhanced hero button styles */
.hero-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    padding: 15px 35px;
    border-radius: 50px;
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
}

.hero-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4);
    background: linear-gradient(45deg, #ffc107, #ffb300) !important;
}

.hero-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.hero-btn:hover::before {
    left: 100%;
}

/* Enhanced popular items */
.popular-item {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 15px;
    overflow: hidden;
    position: relative;
}

.popular-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}

.popular-item:hover::before {
    opacity: 1;
}

.popular-item:hover {
    transform: translateY(-15px) scale(1.03);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}

.popular-item .card-img-top {
    transition: transform 0.4s ease;
    height: 200px;
    object-fit: cover;
}

.popular-item:hover .card-img-top {
    transform: scale(1.1);
}

.popular-item .card-body {
    position: relative;
    z-index: 2;
}

.popular-item .btn-add-cart {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.popular-item:hover .btn-add-cart {
    opacity: 1;
    transform: translateY(0);
}

/* Statistics section */
.stats-section {
    background: linear-gradient(135deg, var(--maccafe-primary), var(--maccafe-accent));
    color: white;
    padding: 60px 0;
}

.stat-item {
    text-align: center;
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Enhanced service box */
.service-box {
    transition: all 0.3s ease;
    padding: 30px 20px;
    border-radius: 15px;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    height: 100%;
}

.service-box:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(255, 193, 7, 0.2);
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.05), rgba(255, 193, 7, 0.02));
}

.service-box i {
    transition: all 0.3s ease;
    font-size: 3.5rem;
    background: linear-gradient(135deg, var(--maccafe-primary), var(--maccafe-accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.service-box:hover i {
    transform: scale(1.1) rotate(5deg);
}

/* Enhanced menu category cards */
.menu-category-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 15px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.menu-category-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(255, 193, 7, 0.15);
    border-color: var(--maccafe-primary);
}

.menu-category-card .card-img-top {
    transition: transform 0.4s ease;
    height: 120px;
    object-fit: cover;
}

.menu-category-card:hover .card-img-top {
    transform: scale(1.1);
}

.menu-category-card .btn {
    transition: all 0.3s ease;
    border-radius: 25px;
}

.menu-category-card:hover .btn {
    background: var(--maccafe-primary) !important;
    border-color: var(--maccafe-primary) !important;
    transform: translateX(5px);
}

/* Testimonials section */
.testimonial-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
}

.testimonial-card::before {
    content: '"';
    position: absolute;
    top: 10px;
    left: 20px;
    font-size: 4rem;
    color: var(--maccafe-primary);
    opacity: 0.2;
    font-family: Georgia, serif;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(255, 193, 7, 0.15);
}

.testimonial-rating {
    color: #ffc107;
    margin-bottom: 15px;
}

.testimonial-author {
    display: flex;
    align-items: center;
    margin-top: 20px;
}

.testimonial-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
    background: linear-gradient(135deg, var(--maccafe-primary), var(--maccafe-accent));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
}

/* Enhanced contact form */
.contact-form {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.contact-form:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(255, 193, 7, 0.15);
}

.contact-form .form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.contact-form .form-control:focus {
    border-color: var(--maccafe-primary);
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.contact-form .btn-primary {
    background: linear-gradient(135deg, var(--maccafe-primary), var(--maccafe-accent));
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.contact-form .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(255, 193, 7, 0.3);
}

/* Floating action buttons */
.floating-buttons {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.fab {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--maccafe-primary), var(--maccafe-accent));
    color: white;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    font-size: 1.2rem;
}

.fab:hover {
    transform: scale(1.1);
    box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4);
    color: white;
    text-decoration: none;
}

.fab-whatsapp {
    background: #25D366;
}

.fab-cart {
    background: linear-gradient(135deg, var(--maccafe-primary), var(--maccafe-accent));
    position: relative;
}

.cart-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
}

/* Map animation */
.ratio {
    transition: all 0.3s ease;
}

.ratio:hover {
    transform: scale(1.02);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
</style>
<!-- Hero Section -->
<section class="hero-section bg-image text-white position-relative" style="background-image:url('<?= $baseUrl ?>BG.jpg'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 100vh; padding-top:50px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="badge bg-warning text-dark mb-3 animate-fade-in">Hot Deals Available</div>
                    <h1 class="display-3 fw-bold mb-4 text-dark animate-fade-in">Cravings?<br>Come get it at <span class="text-warning">MC Coffee n' Tea</span></h1>
                    <p class="lead mb-4 text-dark animate-fade-in animate-delay-1">Savor the Moment: Treat Yourself to Premium Coffee, Tea & Delicious Food</p>
                    <div class="d-flex flex-wrap gap-3 animate-fade-in animate-delay-2">
                        <a href="/menu" class="btn hero-btn" style="background-color: var(--maccafe-accent);">Explore Food <i class="fas fa-utensils ms-2"></i></a>
                        <a href="/menu" class="btn btn-outline hero-btn text-dark">Order Now <i class="fas fa-shopping-bag ms-2"></i></a>
                    </div>
                   
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Swiper Carousel -->
                <div class="swiper homeSwiper">
                    <div class="swiper-wrapper">
                       <div class="swiper-slide container">
                        <img src="<?= $baseUrl ?>milk_teanobg.png" alt="">
                    </div>
                    <div class="swiper-slide container">
                        <img src="<?= $baseUrl ?>fruit_teanobg.png" alt="">
                    </div>
                    <div class="swiper-slide container">
                        <img src="<?= $baseUrl ?>frappenobg.png" alt="">
                    </div>
                    <div class="swiper-slide container">
                        <img src="<?= $baseUrl ?>matchanobg.png" alt="">
                    </div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>  
    <section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge bg-warning text-dark mb-3">Customer Favorites</div>
            <h2 class="display-5 fw-bold text-primary animate-on-scroll animate-fade-in">Popular Orders</h2>
            <p class="lead text-muted animate-on-scroll animate-fade-in animate-delay-1">Handpicked by our customers, loved by everyone</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 popular-item animate-on-scroll animate-scale animate-delay-1">
                    <div class="position-relative">
                        <img src="<?= $baseUrl ?>Cookiesandcream.jpg" class="card-img-top" alt="Cookies and Cream">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-danger">Bestseller</span>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title text-dark">Cookies and Cream</h5>
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="card-text text-dark fw-bold">₱99 16oz ┃ ₱119 22oz</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 popular-item animate-on-scroll animate-scale animate-delay-2">
                    <div class="position-relative">
                        <img src="<?= $baseUrl ?>matcha.jpg" class="card-img-top" alt="Matcha Espresso">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-success">Trending</span>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title text-dark">Matcha Espresso</h5>
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text text-dark fw-bold">₱79 16oz ┃ ₱99 22oz</p>
                       
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 popular-item animate-on-scroll animate-scale animate-delay-3">
                    <div class="position-relative">
                        <img src="<?= $baseUrl ?>nachos.jpg" class="card-img-top" alt="Nachos">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-warning text-dark">Popular</span>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title text-dark">Loaded Nachos</h5>
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <p class="card-text text-dark fw-bold">₱89</p>
                       
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 popular-item animate-on-scroll animate-scale animate-delay-4">
                    <div class="position-relative">
                        <img src="<?= $baseUrl ?>cheesybacon.jpg" class="card-img-top" alt="Cheesy Bacon">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-danger">Hot</span>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title text-dark">Cheesy Bacon</h5>
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="card-text text-dark fw-bold">₱150</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge bg-warning text-dark mb-3"> Why Choose Us</div>
            <h2 class="display-5 fw-bold animate-on-scroll animate-fade-in">Our <span class="text-primary">Services</span></h2>
            <p class="lead animate-on-scroll animate-fade-in animate-delay-1">We provide quality service with love and care</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="service-box animate-on-scroll animate-slide-left animate-delay-1">
                    <div class="mb-3">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h4>Fast Delivery</h4>
                    <p class="text-muted">Quick and reliable delivery service</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="service-box animate-on-scroll animate-fade-in animate-delay-2">
                    <div class="mb-3">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                    <h4>Quality Food</h4>
                    <p class="text-muted">Fresh ingredients and great taste</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="service-box animate-on-scroll animate-slide-right animate-delay-3">
                    <div class="mb-3">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h4>24/7 Support</h4>
                    <p class="text-muted">Always here to help you</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Menu Categories Section -->
<section class="py-5" id="menu">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge bg-warning text-dark mb-3">Explore Our Menu</div>
            <h2 class="display-5 fw-bold animate-on-scroll animate-fade-in">Browse Our Hottest <br><span class="text-primary">Menu</span></h2>
            <p class="lead text-muted animate-on-scroll animate-fade-in animate-delay-1">From premium coffee to delicious meals, we have it all</p>
            <a href="/menu" class="btn btn-warning btn-lg mt-3 animate-on-scroll animate-fade-in animate-delay-2 hero-btn">View Full Menu <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        
        <?php
        // Custom categories for home page (with subcategories)
        $categories = [
            [
                'name' => 'coffee',
                'product_count' => 6
            ],
            [
                'name' => 'frappe',
                'product_count' => 2
            ],
            [
                'name' => 'milk tea',
                'product_count' => 2
            ],
            [
                'name' => 'fruit tea',
                'product_count' => 2
            ],
            [
                'name' => 'non coffee',
                'product_count' => 2
            ],
            [
                'name' => 'non coffee frappe',
                'product_count' => 2
            ],
            
            [
                'name' => 'snacks',
                'product_count' => 2
            ],
            [
                'name' => 'rice bowl',
                'product_count' => 2
            ],
            [
                'name' => 'pasta',
                'product_count' => 2
            ],
        ];
        
        // Category icons mapping
        $categoryIcons = [
            'coffee' => 'bi-cup-hot-fill',
            'frappe' => 'bi-cup-straw',
            'milk tea' => 'bi-cup-straw',
            'fruit tea' => 'bi-cup-straw',
            'non coffee' => 'bi-cup-straw',
            'non coffee frappe' => 'bi-cup-straw',
            'food' => 'bi-egg-fried',
            'snacks' => 'bi-egg-fried',
            'rice bowl' => 'bi-egg-fried',
            'pasta' => 'bi-egg-fried',
        ];
        
        // Category images mapping
        $categoryImages = [
            'coffee' => 'americano.webp',
            'frappe' => 'frappenobg.png',
            'milk tea' => 'milk_teanobg.png',
            'fruit tea' => 'fruit_teanobg.png',
            'non coffee' => 'noncoffe.jpg',
            'non coffee frappe' => 'frappenobg.png',
            'food' => '2pcchick.jpg',
            'snacks' => 'foods.jpg',
            'rice bowl' => 'cheesyhungarian.jpg',
            'pasta' => 'garlicTparme.jpg',
            
        ];
        
        // Subcategory mapping (for specific items)
        $subcategories = [
            'coffee-frappe' => 'coffee/frappe',
            'non-coffee-frappe' => 'non-coffee/frappe',
            'milk-tea' => 'beverage/milk-tea',
            'fruit-tea' => 'beverage/fruit-tea'
        ];
        ?>
        
        <div class="row g-1">
            <?php foreach ($categories as $index => $category): ?>
                <div class="col-lg-2-4 col-md-3 col-sm-4 col-6">
                    <?php 
                    // Check if this category has special routing
                    $routePath = isset($subcategories[$category['name']]) 
                        ? "/menu/{$subcategories[$category['name']]}" 
                        : "/menu/{$category['name']}";
                    $delayClass = 'animate-delay-' . (($index % 4) + 1);
                    ?>
                    <div class="card menu-category-card h-9 0 animate-on-scroll animate-scale <?= $delayClass ?>" onclick="window.location.href='<?= $routePath ?>'">
                        <div class="position-relative overflow-hidden">
                            <img src="<?= $baseUrl ?><?= $categoryImages[$category['name']] ?? 'default.jpg' ?>" class="card-img-top h-75 object-fit-cover" alt="<?= htmlspecialchars($category['name']) ?>">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning text-dark">Hot</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="card-title fw-bold"><?= ucfirst($category['name']) ?></h4>
                                <p class="text-muted mb-2"><?= $category['product_count'] ?> Items Available</p>
                                <div class="text-warning small mb-3">
                                </div>
                            </div>
                            <button class="btn btn-outline-warning align-self-start">
                                <i class="fas fa-arrow-right me-1"></i> Explore
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5 bg-light" id="contact">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge bg-warning text-dark mb-3">Get In Touch</div>
            <h2 class="display-5 fw-bold animate-on-scroll animate-fade-in">Contact <span class="text-primary">Us</span></h2>
            <p class="lead text-muted animate-on-scroll animate-fade-in animate-delay-1">We'd love to hear from you! Send us a message.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="contact-form animate-on-scroll animate-scale animate-delay-2">
                    <form id="contactForm" action="/home" method="POST" onsubmit="return handleContactSubmit(event);">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Your Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-dark"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Your Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-dark"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="form-label fw-bold">Your Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Tell us what's on your mind..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5" id="about-us">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold animate-on-scroll animate-fade-in">About <span class="text-primary">Us</span></h2>
            <p class="lead animate-on-scroll animate-fade-in animate-delay-1">Learn more about our story</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center animate-on-scroll animate-fade-in animate-delay-2">
                    <h3 class="mb-4">Welcome to MC Coffee n' Tea</h3>
                    <p class="lead">We are passionate about serving quality coffee, tea, and delicious food to our valued customers. Our mission is to provide a cozy atmosphere where you can enjoy your favorite beverages and meals with friends and family.</p>
                    <p class="lead">From our carefully selected coffee beans to our freshly prepared meals, everything we serve is made with love and attention to detail. Come visit us and experience the warmth and hospitality that makes MC Coffee n' Tea special.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Location Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold animate-on-scroll animate-fade-in">LOCATION</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="ratio ratio-16x9 animate-on-scroll animate-scale animate-delay-1">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.1381809766654!2d121.06588807423888!3d14.873549770337553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397a9004df977b1%3A0x587167ec9bc05294!2sMC%20COFFEE%20N&#39;%20TEA!5e0!3m2!1sen!2sph!4v1730438094817!5m2!1sen!2sph" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.homeSwiper', {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        }
    });

    // Scroll Animation using Intersection Observer
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                
                // Add the appropriate animation class based on current classes
                if (element.classList.contains('animate-fade-in')) {
                    element.classList.add('animate-fade-in');
                } else if (element.classList.contains('animate-slide-left')) {
                    element.classList.add('animate-slide-left');
                } else if (element.classList.contains('animate-slide-right')) {
                    element.classList.add('animate-slide-right');
                } else if (element.classList.contains('animate-scale')) {
                    element.classList.add('animate-scale');
                } else {
                    // Default fade-in animation
                    element.classList.add('animate-fade-in');
                }
                
                // Stop observing the element after animation
                observer.unobserve(element);
            }
        });
    }, observerOptions);

    // Observe all elements with animate-on-scroll class
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    animatedElements.forEach(element => {
        observer.observe(element);
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });
});

// Contact Form Handler
function handleContactSubmit(event) {
    event.preventDefault();
    
    // Get form values
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;
    
    // Create demo alert notification
    showContactAlert(name, email, message);
    
    // Reset form
    document.getElementById('contactForm').reset();
    
    return false;
}

function showContactAlert(name, email, message) {
    // Create alert container if it doesn't exist
    let alertContainer = document.getElementById('contactAlertContainer');
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'contactAlertContainer';
        alertContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(alertContainer);
    }
    
    // Create alert element
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show';
    alert.style.cssText = `
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: slideInRight 0.3s ease-out;
    `;
    
    alert.innerHTML = `
        <div class="d-flex align-items-start">
            <i class="bi bi-check-circle-fill me-2" style="color: #28a745; font-size: 1.2rem;"></i>
            <div class="flex-grow-1">
                <strong>Message Sent Successfully!</strong><br>
                <small class="text-muted">
                    Thank you <strong>${name}</strong> for contacting us!<br>
                    We'll respond to <strong>${email}</strong> within 24 hours.<br>
                    <em>Your message: "${message.substring(0, 50)}${message.length > 50 ? '...' : ''}"</em>
                </small>
            </div>
            <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    // Add alert to container
    alertContainer.appendChild(alert);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alert.parentElement) {
            alert.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => alert.remove(), 300);
        }
    }, 5000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

<!-- Floating Action Buttons -->
<div class="floating-buttons">
    <button class="fab" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Back to Top">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>

<script>
// Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Update cart count from localStorage
    updateCartCount();
    
    // Add to cart functionality
    document.querySelectorAll('.btn-add-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const card = this.closest('.popular-item');
            const productName = card.querySelector('.card-title').textContent;
            const price = card.querySelector('.card-text').textContent;
            
            // Add to cart animation
            this.innerHTML = '<i class="fas fa-check me-1"></i> Added!';
            this.classList.add('btn-success');
            this.classList.remove('btn-warning');
            
            // Update cart count
            let cartCount = parseInt(localStorage.getItem('cartCount') || '0');
            cartCount++;
            localStorage.setItem('cartCount', cartCount.toString());
            updateCartCount();
            
            // Show notification
            showNotification(`${productName} added to cart!`);
            
            // Reset button after 2 seconds
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-plus me-1"></i> Add to Cart';
                this.classList.remove('btn-success');
                this.classList.add('btn-warning');
            }, 2000);
        });
    });
});

function updateCartCount() {
    const cartCount = localStorage.getItem('cartCount') || '0';
    const cartElement = document.getElementById('cartCount');
    if (cartElement) {
        cartElement.textContent = cartCount;
        cartElement.style.display = cartCount === '0' ? 'none' : 'flex';
    }
}

function showNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 250px;
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: slideInRight 0.3s ease-out;
    `;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2" style="color: #28a745; font-size: 1.2rem;"></i>
            <div class="flex-grow-1">${message}</div>
            <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }
    }, 3000);
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/main.php';
?>
