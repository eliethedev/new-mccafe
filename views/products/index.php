<?php ob_start(); ?>

<?php 
// Variables are passed from Controller->view() method and should be available directly
?>

<div class="container py-4" style="margin-top: 70px;">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><?= $title ?? 'Menu - McCafe' ?></h1>
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
                            <?php 
                            // Check if image exists in products folder first, then root images folder
                            $imagePath = '/assets/images/products/' . $product['image'];
                            $fallbackPath = '/assets/images/' . $product['image'];
                            ?>
                            <img src="<?= $imagePath ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['name']) ?>" 
                                 onerror="this.src='<?= $fallbackPath ?>'; this.onerror='this.src=\'/assets/images/logo.jpg\';'">
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
                                    <select class="form-select form-select-sm" onchange="updateVariant(this, <?= $product['id'] ?>)">
                                        <?php foreach ($product['variants'] as $variant): ?>
                                            <option value="<?= $variant['id'] ?>" data-price="<?= $variant['price'] + $product['price'] ?>">
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
                                    <button class="btn btn-outline-success" onclick="showProductModal('<?= htmlspecialchars($product['name']) ?>', '<?= $product['image'] ? '/assets/images/products/' . $product['image'] : '' ?>', '<?= htmlspecialchars($product['description']) ?>', '<?= htmlspecialchars($product['category_name']) ?>', '', <?= $product['price'] ?>, <?= $product['id'] ?>)">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <form method="POST" action="/cart/add" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <?php if (isset($product['variants']) && !empty($product['variants'])): ?>
                                            <input type="hidden" name="variant_id" value="">
                                            <input type="hidden" name="variant_price" value="<?= $product['price'] ?>">
                                        <?php endif; ?>
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-cart-plus"></i> Add to Cart
                                        </button>
                                    </form>
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

<!-- Login Required Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Please Log In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You need to be logged in to add items to your cart.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="/login" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</div>

<!-- Product Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Add to Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/cart/confirm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="confirmProductImage" src="" alt="" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h4 id="confirmProductName"></h4>
                            <p class="text-success h5" id="confirmProductPrice"></p>
                            
                            <?php if (isset($_SESSION['added_product']['variants']) && !empty($_SESSION['added_product']['variants'])): ?>
                            <div class="mb-3">
                                <label for="confirmSize" class="form-label">Size:</label>
                                <select name="variant_id" id="confirmSize" class="form-select" onchange="updateConfirmPrice()">
                                    <?php foreach ($_SESSION['added_product']['variants'] as $variant): ?>
                                        <option value="<?= $variant['id'] ?>" 
                                                data-price="<?= number_format($_SESSION['added_product']['price'] + $variant['price_adjustment'], 2) ?>"
                                                <?= $variant['id'] == ($_SESSION['added_variant_id'] ?? '') ? 'selected' : '' ?>>
                                            <?= $variant['name'] ?> - ₱<?= number_format($_SESSION['added_product']['price'] + $variant['price_adjustment'], 2) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="confirmQuantity" class="form-label">Quantity:</label>
                                <div class="input-group" style="width: 150px;">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">-</button>
                                    <input type="number" name="quantity" id="confirmQuantity" class="form-control text-center" value="<?= $_SESSION['added_quantity'] ?? 1 ?>" min="1" max="99">
                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">+</button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    This will bring your cart to <span id="cartItemCount">X</span> items
                                </small>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Subtotal: ₱<span id="confirmSubtotal">0.00</span></strong>
                            </div>
                            
                            <input type="hidden" name="product_id" value="<?= $_SESSION['added_product']['id'] ?? '' ?>">
                            <input type="hidden" name="variant_id" value="<?= $_SESSION['added_variant_id'] ?? '' ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Continue Shopping</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </form>
        </div>
    </div>
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
                        <div id="modalProductVariants"></div>
                        <h4 class="text-success" id="modalProductPrice"></h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
function updateVariant(select, productId) {
    const card = select.closest('.product-card');
    const priceElement = card.querySelector('.text-success');
    const selectedOption = select.options[select.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute('data-price'));
    const variantId = selectedOption.value;
    
    // Update displayed price
    priceElement.textContent = '₱' + price.toFixed(2);
    
    // Update form hidden fields
    const form = card.querySelector('form');
    const variantIdField = form.querySelector('input[name="variant_id"]');
    const variantPriceField = form.querySelector('input[name="variant_price"]');
    
    if (variantIdField) variantIdField.value = variantId;
    if (variantPriceField) variantPriceField.value = price;
}

function incrementQuantity() {
    const input = document.getElementById('confirmQuantity');
    const currentValue = parseInt(input.value) || 1;
    if (currentValue < 99) {
        input.value = currentValue + 1;
        updateSubtotal();
    }
}

function decrementQuantity() {
    const input = document.getElementById('confirmQuantity');
    const currentValue = parseInt(input.value) || 1;
    if (currentValue > 1) {
        input.value = currentValue - 1;
        updateSubtotal();
    }
}

function updateConfirmPrice() {
    const select = document.getElementById('confirmSize');
    if (select) {
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        if (price) {
            document.getElementById('confirmProductPrice').textContent = '₱' + price;
            updateSubtotal();
        }
    }
}

function updateSubtotal() {
    const quantity = parseInt(document.getElementById('confirmQuantity').value) || 1;
    const unitPrice = parseFloat(document.getElementById('confirmProductPrice').textContent.replace('₱', '').replace(',', '')) || 0;
    const subtotal = quantity * unitPrice;
    document.getElementById('confirmSubtotal').textContent = subtotal.toFixed(2);
}

// Show modals based on session flags
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['show_login_modal']) && $_SESSION['show_login_modal']): ?>
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
        <?php unset($_SESSION['show_login_modal']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['show_confirmation_modal']) && $_SESSION['show_confirmation_modal']): ?>
        // Populate confirmation modal with product data
        <?php if (isset($_SESSION['added_product'])): ?>
            document.getElementById('confirmProductName').textContent = '<?= htmlspecialchars($_SESSION['added_product']['name']) ?>';
            document.getElementById('confirmProductPrice').textContent = '₱<?= number_format($_SESSION['added_unit_price'] ?? $_SESSION['added_product']['price'], 2) ?>';
            <?php if ($_SESSION['added_product']['image']): ?>
                document.getElementById('confirmProductImage').src = '/assets/images/products/<?= $_SESSION['added_product']['image'] ?>';
                document.getElementById('confirmProductImage').onerror = function() {
                    this.src = '/assets/images/<?= $_SESSION['added_product']['image'] ?>';
                    this.onerror = function() {
                        this.src = '/assets/images/logo.jpg';
                    };
                };
            <?php endif; ?>
        <?php endif; ?>
        
        // Update cart item count (this would need to be fetched from server)
        updateCartItemCount();
        updateSubtotal();
        
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
        <?php unset($_SESSION['show_confirmation_modal']); ?>
    <?php endif; ?>
});

function updateCartItemCount() {
    // This would ideally fetch current cart count from server
    // For now, we'll use a placeholder
    document.getElementById('cartItemCount').textContent = 'X';
}

// Update subtotal when quantity changes
document.getElementById('confirmQuantity')?.addEventListener('input', updateSubtotal);

// Function to handle image loading with fallback
function loadImageWithFallback(imgElement, imagePath, fallbackPath, finalFallback) {
    imgElement.src = imagePath;
    imgElement.onerror = function() {
        this.src = fallbackPath;
        this.onerror = function() {
            this.src = finalFallback;
        };
    };
}

// Show Product Modal function
function showProductModal(name, image, description, category, variants, price, productId) {
    const modal = document.getElementById('productModal');
    const modalImage = document.getElementById('modalProductImage');
    const modalName = document.getElementById('modalProductName');
    const modalDescription = document.getElementById('modalProductDescription');
    const modalCategory = document.getElementById('modalProductCategory');
    const modalPrice = document.getElementById('modalProductPrice');
    
    // Set product details
    modalName.textContent = name;
    modalDescription.textContent = description;
    modalCategory.textContent = category;
    modalPrice.textContent = '₱' + parseFloat(price).toFixed(2);
    
    // Handle image with fallback
    if (image) {
        const imagePath = image.startsWith('/') ? image : '/assets/images/products/' + image;
        const fallbackPath = '/assets/images/' + image.split('/').pop();
        const finalFallback = '/assets/images/logo.jpg';
        
        loadImageWithFallback(modalImage, imagePath, fallbackPath, finalFallback);
    } else {
        modalImage.src = '/assets/images/logo.jpg';
    }
    
    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

// Add to cart from modal
function addModalProductToCart() {
    const modal = document.getElementById('productModal');
    const quantity = document.getElementById('modalQuantity').value;
    const productId = modal.dataset.productId;
    
    // Add to cart logic here
    const bsModal = bootstrap.Modal.getInstance(modal);
    bsModal.hide();
}
</script>

<?php
$content = ob_get_clean();
$title = $title ?? 'Menu - McCafe';
include __DIR__ . '/../layouts/main.php';
?>
