<?php ob_start(); ?>

<!-- Hero Section with Swiper -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4">Cravings?<br>Come get it at MC Coffee n' Tea</h1>
                    <p class="lead mb-4">Savor the Moment: Treat Yourself</p>
                    <a href="/maccafe-mor-demo/menu" class="btn btn-warning btn-lg">
                        Explore Food <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="swiper heroSwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="/maccafe-mor-demo/public/assets/images/milk_teanobg.png" class="img-fluid rounded" alt="Milk Tea">
                        </div>
                        <div class="swiper-slide">
                            <img src="/maccafe-mor-demo/public/assets/images/fruit_teanobg.png" class="img-fluid rounded" alt="Fruit Tea">
                        </div>
                        <div class="swiper-slide">
                            <img src="/maccafe-mor-demo/public/assets/images/frappenobg.png" class="img-fluid rounded" alt="Frappe">
                        </div>
                        <div class="swiper-slide">
                            <img src="/maccafe-mor-demo/public/assets/images/matchanobg.png" class="img-fluid rounded" alt="Matcha">
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

<!-- Popular Orders Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Popular Orders</h2>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <img src="/maccafe-mor-demo/public/assets/images/Cookiesandcream.jpg" class="card-img-top" alt="Cookies and Cream">
                    <div class="card-body">
                        <h5 class="card-title">Cookies and Cream</h5>
                        <div class="price-tag mb-3">
                            <span class="fw-bold text-warning">₱99 16oz ┃ ₱119 22oz</span>
                        </div>
                        <button class="btn btn-success w-100" data-add-to-cart data-product-id="1" data-quantity="1">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <img src="/maccafe-mor-demo/public/assets/images/matcha.jpg" class="card-img-top" alt="Matcha Espresso">
                    <div class="card-body">
                        <h5 class="card-title">Matcha Espresso</h5>
                        <div class="price-tag mb-3">
                            <span class="fw-bold text-warning">₱79 16oz ┃ ₱99 22oz</span>
                        </div>
                        <button class="btn btn-success w-100" data-add-to-cart data-product-id="2" data-quantity="1">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <img src="/maccafe-mor-demo/public/assets/images/nachos.jpg" class="card-img-top" alt="Nachos">
                    <div class="card-body">
                        <h5 class="card-title">Nachos</h5>
                        <div class="price-tag mb-3">
                            <span class="fw-bold text-warning">₱89</span>
                        </div>
                        <button class="btn btn-success w-100" data-add-to-cart data-product-id="3" data-quantity="1">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <img src="/maccafe-mor-demo/public/assets/images/cheesybacon.jpg" class="card-img-top" alt="Cheesy Bacon">
                    <div class="card-body">
                        <h5 class="card-title">Cheesy Bacon</h5>
                        <div class="price-tag mb-3">
                            <span class="fw-bold text-warning">₱150</span>
                        </div>
                        <button class="btn btn-success w-100" data-add-to-cart data-product-id="4" data-quantity="1">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Menu Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="display-5 fw-bold">Browse Our Hottest <span class="text-warning">Menu</span></h2>
            </div>
            <a href="/maccafe-mor-demo/menu" class="btn btn-warning">
                See All <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/coffee'">
                    <img src="/maccafe-mor-demo/public/assets/images/spanish latte.jpg" class="card-img-top" alt="Coffee">
                    <div class="card-body text-center">
                        <h5 class="card-title">Coffee</h5>
                        <span class="badge bg-warning text-dark">8 Items</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/coffee-frappe'">
                    <img src="/maccafe-mor-demo/public/assets/images/frappe.jpg" class="card-img-top" alt="Coffee Frappe">
                    <div class="card-body text-center">
                        <h5 class="card-title">Coffee Frappe</h5>
                        <span class="badge bg-warning text-dark">6 Items</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/milk-tea'">
                    <img src="/maccafe-mor-demo/public/assets/images/milk tea.jpg" class="card-img-top" alt="Milk Tea">
                    <div class="card-body text-center">
                        <h5 class="card-title">Milk Tea</h5>
                        <span class="badge bg-warning text-dark">10 Items</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/fruit-tea'">
                    <img src="/maccafe-mor-demo/public/assets/images/fruit tea.jpg" class="card-img-top" alt="Fruit Tea">
                    <div class="card-body text-center">
                        <h5 class="card-title">Fruit Tea</h5>
                        <span class="badge bg-warning text-dark">4 Items</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/non-coffee'">
                    <img src="/maccafe-mor-demo/public/assets/images/noncoffe.jpg" class="card-img-top" alt="Non Coffee">
                    <div class="card-body text-center">
                        <h5 class="card-title">Non Coffee</h5>
                        <span class="badge bg-warning text-dark">4 Items</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/non-coffee-frappe'">
                    <img src="/maccafe-mor-demo/public/assets/images/MGFrappe.jpg" class="card-img-top" alt="Non Coffee Frappe">
                    <div class="card-body text-center">
                        <h5 class="card-title">Non Coffee Frappe</h5>
                        <span class="badge bg-warning text-dark">8 Items</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/snacks'">
                    <img src="/maccafe-mor-demo/public/assets/images/foods.jpg" class="card-img-top" alt="Snacks">
                    <div class="card-body text-center">
                        <h5 class="card-title">Snacks</h5>
                        <span class="badge bg-warning text-dark">6 Items</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/rice-bowl'">
                    <img src="/maccafe-mor-demo/public/assets/images/ricemeal.jpg" class="card-img-top" alt="Rice Bowl">
                    <div class="card-body text-center">
                        <h5 class="card-title">Rice Bowl</h5>
                        <span class="badge bg-warning text-dark">2 Items</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/pasta'">
                    <img src="/maccafe-mor-demo/public/assets/images/garlicTparme.jpg" class="card-img-top" alt="Pasta">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pasta</h5>
                        <span class="badge bg-warning text-dark">2 Items</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card category-card h-100" onclick="window.location.href='/maccafe-mor-demo/menu/chicken'">
                    <img src="/maccafe-mor-demo/public/assets/images/2pcchick.jpg" class="card-img-top" alt="2PC Chicken">
                    <div class="card-body text-center">
                        <h5 class="card-title">2PC Chicken With Java Rice</h5>
                        <span class="badge bg-warning text-dark">8 Items</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Our <span class="text-warning">Services</span></h2>
            <p class="lead">We provide quality service with love and care</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-truck fs-1 text-warning"></i>
                        </div>
                        <h5 class="card-title">Fast Delivery</h5>
                        <p class="card-text">Quick and reliable delivery service</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-egg-fried fs-1 text-warning"></i>
                        </div>
                        <h5 class="card-title">Quality Food</h5>
                        <p class="card-text">Fresh ingredients and great taste</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-headset fs-1 text-warning"></i>
                        </div>
                        <h5 class="card-title">24/7 Support</h5>
                        <p class="card-text">Always here to help you</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Us Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">About <span class="text-warning">Us</span></h2>
            <p class="lead">Learn more about our story</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Welcome to MC Coffee n' Tea</h3>
                        <p class="mb-3">We are passionate about serving quality coffee, tea, and delicious food to our valued customers. Our mission is to provide a cozy atmosphere where you can enjoy your favorite beverages and meals with friends and family.</p>
                        <p>From our carefully selected coffee beans to our freshly prepared meals, everything we serve is made with love and attention to detail. Come visit us and experience the warmth and hospitality that makes MC Coffee n' Tea special.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Contact <span class="text-warning">Us</span></h2>
            <p class="lead">Get in touch with us for any inquiries</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form id="contactForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Your Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Location Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Location</h2>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="ratio ratio-16x9">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.1381809766654!2d121.06588807423888!3d14.873549770337553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397a9004df977b1%3A0x587167ec9bc05294!2sMC%20COFFEE%20N&#39;%20TEA!5e0!3m2!1sen!2sph!4v1730438094817!5m2!1sen!2sph" 
                        class="border-0 rounded" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom CSS for this page -->
<style>
.hero-section {
    background: linear-gradient(135deg, #d8772a, #8B4513);
    color: white;
    padding: 80px 0;
}

.heroSwiper {
    max-width: 500px;
}

.category-card {
    cursor: pointer;
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
}

.product-card {
    border: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}

.price-tag {
    font-size: 1.1rem;
}

.swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}
</style>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
const swiper = new Swiper('.heroSwiper', {
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
});

// Contact form handler
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simple form validation and submission
    const formData = new FormData(this);
    
    // Show success message (in real app, this would submit to server)
    alert('Thank you for your message! We will get back to you soon.');
    this.reset();
});
</script>

<?php
$content = ob_get_clean();
$title = 'MC Coffee n\' Tea - Home';
include __DIR__ . '/layouts/main.php';
?>
