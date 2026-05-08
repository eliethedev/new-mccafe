<?php ob_start(); ?>
<?php 
$baseUrl = rtrim(APP_URL, '/') . '/assets/images/';
?>
<style>
    
:root {
    --maccafe-primary: #ffc107;
    --maccafe-secondary: #6c757d;
    --maccafe-accent: #ffc107;
    --maccafe-dark: #343a40;
    --maccafe-light: #f8f9fa;
    --primary: #ffc107;
}
</style>
<!-- Hero Section -->
<section class="hero-section bg-image text-white position-relative" style="background-image:url('<?= $baseUrl ?>BG.jpg'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 100vh; padding-top:50px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4 text-dark">Cravings?<br>Come get it at MC Coffee n' Tea</h1>
                    <p class="lead mb-4">Savor the Moment: Treat Yourself</p>
                    <a href="/menu" class="btn btn-warning btn-lg me-3">Explore Food <i class="fas fa-arrow-right ms-2"></i></a>
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

    <div class="container">
        <h2 class="text-center mb-5 mt-5 text-dark">Popular Order</h2>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 popular-item">
                    <img src="<?= $baseUrl ?>Cookiesandcream.jpg" class="card-img-top" alt="Cookies and Cream">
                    <div class="card-body text-center">
                        <h5 class="card-title text-white">Cookies and Cream</h5>
                        <p class="card-text text-white fw-bold">₱99 16oz ┃ ₱119 22oz</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 popular-item">
                    <img src="<?= $baseUrl ?>matcha.jpg" class="card-img-top" alt="Matcha Espresso">
                    <div class="card-body text-center">
                        <h5 class="card-title text-white">Matcha Espresso</h5>
                        <p class="card-text text-white fw-bold">₱79 16oz ┃ ₱99 22oz</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 popular-item">
                    <img src="<?= $baseUrl ?>nachos.jpg" class="card-img-top" alt="Nachos">
                    <div class="card-body text-center">
                        <h5 class="card-title text-white">Nachos</h5>
                        <p class="card-text text-white fw-bold">₱89</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 popular-item">
                    <img src="<?= $baseUrl ?>cheesybacon.jpg" class="card-img-top" alt="Cheesy Bacon">
                    <div class="card-body text-center">
                        <h5 class="card-title text-white">Cheesy Bacon</h5>
                        <p class="card-text text-white fw-bold">₱150</p>
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
            <h2 class="display-5 fw-bold">Our <span class="text-primary">Services</span></h2>
            <p class="lead">We provide quality service with love and care</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="service-box">
                    <div class="mb-3">
                        <i class="fas fa-truck fa-3x text-primary"></i>
                    </div>
                    <h4>Fast Delivery</h4>
                    <p class="text-muted">Quick and reliable delivery service</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="service-box">
                    <div class="mb-3">
                        <i class="fas fa-utensils fa-3x text-primary"></i>
                    </div>
                    <h4>Quality Food</h4>
                    <p class="text-muted">Fresh ingredients and great taste</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="service-box">
                    <div class="mb-3">
                        <i class="fas fa-headset fa-3x text-primary"></i>
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
            <h2 class="display-5 fw-bold">Browse Our Hottest <br><span class="text-primary">Menu</span></h2>
            <a href="/menu" class="btn btn-primary btn-lg mt-3">See All <i class="fas fa-arrow-right ms-2"></i></a>
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
            'coffee' => 'spanish latte.jpg',
            'frappe' => 'javaF.jpg',
            'milk tea' => 'milk tea.jpg',
            'fruit tea' => 'fruit tea.jpg',
            'non coffee' => 'noncoffe.jpg',
            'non coffee frappe' => 'frappe.jpg',
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
            <?php foreach ($categories as $category): ?>
                <div class="col-lg-2-4 col-md-3 col-sm-4 col-6">
                    <?php 
                    // Check if this category has special routing
                    $routePath = isset($subcategories[$category['name']]) 
                        ? "/menu/{$subcategories[$category['name']]}" 
                        : "/menu/{$category['name']}";
                    ?>
                    <div class="card menu-category-card h-10" onclick="window.location.href='<?= $routePath ?>'">
                        <img src="<?= $baseUrl ?><?= $categoryImages[$category['name']] ?? 'default.jpg' ?>" class="card-img-top h-50 object-fit-cover" alt="<?= htmlspecialchars($category['name']) ?>">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="card-title"><?= ucfirst($category['name']) ?></h4>
                                <p class="text-muted"><?= $category['product_count'] ?> Items</p>
                            </div>
                            <button class="btn btn-outline-primary align-self-start">
                                <i class="fas fa-arrow-right"></i>
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
            <h2 class="display-5 fw-bold">Contact <span class="text-primary">Us</span></h2>
            <p class="lead">Get in touch with us for any inquiries</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="/contact" method="POST">
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
                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5" id="about-us">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">About <span class="text-primary">Us</span></h2>
            <p class="lead">Learn more about our story</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center">
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
            <h2 class="display-5 fw-bold">LOCATION</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="ratio ratio-16x9">
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
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/main.php';
?>
