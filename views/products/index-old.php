<?php ob_start(); ?>

<?php 
// Variables are passed from Controller->view() method and should be available directly
?>

<div class="container py-4" style="margin-top: 70px;">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><?= $title ?? 'Menu - MacCafe' ?></h1>
        </div>
        <div class="col-md-4">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="<?= htmlspecialchars($searchQuery ?? '') ?>">
                <button type="submit" class="btn btn-outline-success">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Category Filter -->
    <?php if (!$searchQuery): ?>
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
    <?php endif; ?>

    <!-- Products Grid -->
    <div class="row g-4">
        <?php if (empty($products)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <?php if ($searchQuery): ?>
                        No products found for "<?= htmlspecialchars($searchQuery) ?>"
                    <?php else: ?>
                        No products available in this category.
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 product-card">
                        <?php if ($product['image']): ?>
                            <img src="/assets/images/products/<?= $product['image'] ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-image fs-1 text-muted"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                            
                            <?php if (isset($product['variants']) && !empty($product['variants'])): ?>
                                <div class="mb-2">
                                    <small class="text-muted">From:</small>
                                    <select class="form-select form-select-sm" onchange="updatePrice(this)">
                                        <?php foreach ($product['variants'] as $variant): ?>
                                            <option value="<?= $variant['price'] + $product['price'] ?>">
                                                <?= $variant['name'] ?> - ₱<?= number_format($variant['price'] + $product['price'], 2) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="h5 mb-0 text-success">₱<?= number_format($product['price'], 2) ?></span>
                                </div>
                                
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-outline-success" onclick="showProductModal('<?= htmlspecialchars($product['name']) ?>', '<?= $product['image'] ? 'assets/images/products/' . $product['image'] : '' ?>', '<?= htmlspecialchars($product['description']) ?>', '<?= htmlspecialchars($product['category_name']) ?>', '', <?= $product['price'] ?>, <?= $product['id'] ?>)">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-success add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Products pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage - 1 ?><?= $currentCategory ? "&category=$currentCategory" : '' ?><?= $searchQuery ? "&search=$searchQuery" : '' ?>">
                                Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?><?= $currentCategory ? "&category=$currentCategory" : '' ?><?= $searchQuery ? "&search=$searchQuery" : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage + 1 ?><?= $currentCategory ? "&category=$currentCategory" : '' ?><?= $searchQuery ? "&search=$searchQuery" : '' ?>">
                                Next
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Product Detail Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img id="modalProductImage" src="" alt="" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <h4 id="modalProductName"></h4>
                        <p id="modalProductDescription"></p>
                        <div class="mb-3">
                            <span class="badge bg-success" id="modalProductCategory"></span>
                        </div>
                        <div class="mb-3">
                            <label for="modalQuantity" class="form-label">Quantity:</label>
                            <input type="number" class="form-control" id="modalQuantity" value="1" min="1" max="99">
                        </div>
                        <div id="modalProductVariants"></div>
                        <h4 class="text-success" id="modalProductPrice"></h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="addModalProductToCart()">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>


<script src="/assets/js/menu-cart.js"></script>
<script>
function updatePrice(select) {
    const card = select.closest('.product-card');
    const priceElement = card.querySelector('.text-success');
    const basePrice = parseFloat(select.options[select.selectedIndex].value);
    priceElement.textContent = '₱' + basePrice.toFixed(2);
}

async function addToCart(productId, button) {
    // Check authentication first
    if (!menuCart.checkAuthentication()) {
        menuCart.showAuthModal();
        return;
    }
    
    // Get product details from the card
    const card = button.closest('.product-card');
    const priceText = card.querySelector('.text-success').textContent;
    const price = parseFloat(priceText.replace('₱', '').replace(',', ''));
    
    // Create form data for server request
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', 1);
    
    console.log('Sending add to cart request...');
    console.log('FormData:', Object.fromEntries(formData));
    
    try {
        const response = await fetch('/cart/add', {
            method: 'POST',
            body: formData
        });
        
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        const responseData = await response.json();
        console.log('Response data:', responseData);
        
        if (response.ok) {
            // Show success animation
            menuCart.createCartAnimation(button);
            menuCart.showNotification('Product added to cart!', 'success');
            
            // Update cart count
            updateCartCount();
        } else {
            console.error('Server error:', responseData);
            menuCart.showNotification(responseData.message || 'Failed to add product to cart', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        menuCart.showNotification('Error adding to cart', 'error');
    }
}

function updateCartCount() {
    fetch('/api/cart')
        .then(response => response.json())
        .then(data => {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                const totalItems = data.items ? data.items.reduce((sum, item) => sum + item.quantity, 0) : 0;
                cartCount.textContent = totalItems;
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}

// Add event listeners for add to cart buttons
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            addToCart(productId, this);
        });
    });
});
</script>

<?php
$content = ob_get_clean();
$title = $title ?? 'Menu - MacCafe';
include __DIR__ . '/../layouts/main.php';
?>
