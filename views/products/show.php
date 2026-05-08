<?php ob_start(); ?>

<div class="container py-4">
    <!-- Category Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="/menu" class="btn btn-outline-success <?= !$currentCategory ? 'active' : '' ?>">
                    All
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="/menu/<?= $category['name'] ?>" class="btn btn-outline-success <?= $currentCategory === $category['name'] ? 'active' : '' ?>">
                        <?= ucfirst($category['name']) ?> (<?= $category['product_count'] ?>)
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <?php if ($product['image']): ?>
                    <img src="/public/assets/images/products/<?= $product['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title h2 mb-3"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <div class="mb-3">
                        <span class="badge bg-success text-white"><?= htmlspecialchars($product['category_name']) ?></span>
                    </div>
                    
                    <p class="card-text"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    
                    <?php if (isset($product['variants']) && !empty($product['variants'])): ?>
                        <div class="mb-3">
                            <h5>Choose Variant:</h5>
                            <div class="row g-2">
                                <?php foreach ($product['variants'] as $variant): ?>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="variant" value="<?= $variant['id'] ?>" id="variant_<?= $variant['id'] ?>" 
                                                   onchange="updateVariantPrice(<?= $variant['price'] + $product['price'] ?>)">
                                            <label class="form-check-label d-flex justify-content-between align-items-center" for="variant_<?= $variant['id'] ?>">
                                                <span><?= htmlspecialchars($variant['name']) ?></span>
                                                <span class="text-success fw-bold">+₱<?= number_format($variant['price'], 2) ?></span>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="text-success mb-0">₱<span id="product-price"><?= number_format($product['price'], 2) ?></span></h3>
                        <div class="input-group" style="max-width: 150px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(-1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="99">
                            <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg" onclick="addToCart()">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                        <a href="/menu" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Menu
                        </a>
                        <?php if (Session::has('user')): ?>
                            <form action="/logout" method="POST" style="display: inline;">
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!empty($relatedProducts)): ?>
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row g-4">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card h-100">
                            <?php if ($relatedProduct['image']): ?>
                                <img src="assets/images/products/<?= $relatedProduct['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($relatedProduct['name']) ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="bi bi-image fs-3 text-muted"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <h6 class="card-title"><?= htmlspecialchars($relatedProduct['name']) ?></h6>
                                <p class="card-text text-success fw-bold">₱<?= number_format($relatedProduct['price'], 2) ?></p>
                                <a href="/product/<?= $relatedProduct['id'] ?>" class="btn btn-outline-success btn-sm w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
let basePrice = <?= $product['price'] ?>;

function updateQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    const newQuantity = parseInt(quantityInput.value) + change;
    
    if (newQuantity >= 1 && newQuantity <= 99) {
        quantityInput.value = newQuantity;
    }
}

function updateVariantPrice(variantPrice) {
    const priceElement = document.getElementById('product-price');
    const quantity = parseInt(document.getElementById('quantity').value);
    const totalPrice = (basePrice + variantPrice) * quantity;
    priceElement.textContent = totalPrice.toFixed(2);
}

async function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const variantInput = document.querySelector('input[name="variant"]:checked');
    const variantId = variantInput ? variantInput.value : null;
    
    // Get product ID from the page
    const productId = <?= $product['id'] ?>;
    
    try {
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        });

        const data = await response.json();
        
        if (data.success) {
            // Update cart count
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                const currentCount = parseInt(cartCount.textContent) || 0;
                cartCount.textContent = currentCount + quantity;
            }
            
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                Product added to cart!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // Remove alert after 3 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        } else {
            alert(data.message || 'Failed to add to cart');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        alert('Error adding to cart');
    }
}
</script>

<?php
$content = ob_get_clean();
$title = $product['name'] . ' - MacCafe';
include __DIR__ . '/../layouts/main.php';
?>

