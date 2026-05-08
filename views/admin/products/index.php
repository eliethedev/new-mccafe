<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Products</h1>
    <a href="/admin/products/create" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add New Product
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories ?? [] as $cat): ?>
                    <option value="<?= $cat['name'] ?>" <?= ($currentCategory ?? '') === $cat['name'] ? 'selected' : '' ?>>
                        <?= $cat['name'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="availability" class="form-label">Availability</label>
                <select class="form-select" id="availability" name="availability">
                    <option value="">All Products</option>
                    <option value="available" <?= ($currentAvailability ?? '') === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="unavailable" <?= ($currentAvailability ?? '') === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="sort" class="form-label">Sort By</label>
                <select class="form-select" id="sort" name="sort">
                    <option value="name" <?= ($currentSort ?? '') === 'name' ? 'selected' : '' ?>>Name</option>
                    <option value="price_low" <?= ($currentSort ?? '') === 'price_low' ? 'selected' : '' ?>>Price (Low to High)</option>
                    <option value="price_high" <?= ($currentSort ?? '') === 'price_high' ? 'selected' : '' ?>>Price (High to Low)</option>
                    <option value="created" <?= ($currentSort ?? '') === 'created' ? 'selected' : '' ?>>Date Created</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Product name or description..." value="<?= $searchQuery ?? '' ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search me-1"></i>Submit Selection
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products ?? [])): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                            No products found
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                            <img src="/assets/images/products/<?= $product['image'] ?>" 
                                 alt="<?= $product['name'] ?>" 
                                 class="img-thumbnail" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div>
                                <strong><?= $product['name'] ?></strong><br>
                                <small class="text-muted"><?= substr($product['description'] ?? '', 0, 50) ?>...</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info"><?= $product['category_name'] ?? 'N/A' ?></span>
                        </td>
                        <td>
                            <strong>₱<?= number_format($product['price'], 2) ?></strong>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="available-<?= $product['id'] ?>" 
                                       <?= $product['is_available'] ? 'checked' : '' ?>
                                       onchange="toggleProductStatus(<?= $product['id'] ?>, this.checked)">
                                <label class="form-check-label" for="available-<?= $product['id'] ?>"></label>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/admin/products/<?= $product['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-info" onclick="manageVariants(<?= $product['id'] ?>)">
                                    <i class="bi bi-layers"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(<?= $product['id'] ?>, '<?= $product['name'] ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if (($totalPages ?? 1) > 1): ?>
        <nav aria-label="Products pagination">
            <ul class="pagination justify-content-center mt-4">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= isset($_GET['category']) ? '&category=' . urlencode($_GET['category']) : '' ?><?= isset($_GET['availability']) ? '&availability=' . urlencode($_GET['availability']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<!-- Product Variants Modal -->
<div class="modal fade" id="variantsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Product Variants</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Product Variants</h6>
                    <button class="btn btn-sm btn-primary" onclick="addVariant()">
                        <i class="bi bi-plus-circle me-1"></i>Add Variant
                    </button>
                </div>
                
                <div id="variantsList">
                    <!-- Variants will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentProductId = null;

function toggleProductStatus(productId, isAvailable) {
    fetch(`/admin/products/${productId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_available: isAvailable })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            // Revert the checkbox if failed
            document.getElementById(`available-${productId}`).checked = !isAvailable;
            alert('Error: ' + (data.message || 'Failed to update status'));
        }
    })
    .catch(error => {
        // Revert the checkbox if failed
        document.getElementById(`available-${productId}`).checked = !isAvailable;
        alert('Error updating product status');
    });
}

function updateSortOrder(productId, sortOrder) {
    fetch(`/admin/products/${productId}/sort`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ sort_order: sortOrder })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Error: ' + (data.message || 'Failed to update sort order'));
            location.reload(); // Reload to show correct value
        }
    })
    .catch(error => {
        alert('Error updating sort order');
        location.reload();
    });
}

function deleteProduct(productId, productName) {
    if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
        fetch(`/admin/products/${productId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to delete product'));
            }
        })
        .catch(error => {
            alert('Error deleting product');
        });
    }
}

function manageVariants(productId) {
    currentProductId = productId;
    
    fetch(`/admin/products/${productId}/variants`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderVariants(data.variants);
            new bootstrap.Modal(document.getElementById('variantsModal')).show();
        }
    });
}

function renderVariants(variants) {
    const container = document.getElementById('variantsList');
    
    if (variants.length === 0) {
        container.innerHTML = '<p class="text-muted">No variants found. Add your first variant.</p>';
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table"><thead><tr><th>Name</th><th>Price Adjustment</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
    
    variants.forEach(variant => {
        html += `
            <tr>
                <td>${variant.name}</td>
                <td>₱${parseFloat(variant.price_adjustment).toFixed(2)}</td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" 
                               id="variant-${variant.id}" 
                               ${variant.is_available ? 'checked' : ''}
                               onchange="toggleVariantStatus(${variant.id}, this.checked)">
                        <label class="form-check-label" for="variant-${variant.id}"></label>
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteVariant(${variant.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
}

function addVariant() {
    const name = prompt('Enter variant name (e.g., Small, Medium, Large):');
    if (!name) return;
    
    const priceAdjustment = prompt('Enter price adjustment (e.g., 10.00):');
    if (!priceAdjustment || isNaN(priceAdjustment)) {
        alert('Please enter a valid price adjustment.');
        return;
    }
    
    fetch(`/admin/products/${currentProductId}/variants`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: name,
            price_adjustment: parseFloat(priceAdjustment)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            manageVariants(currentProductId); // Refresh variants list
        } else {
            alert('Error: ' + (data.message || 'Failed to add variant'));
        }
    });
}

function toggleVariantStatus(variantId, isAvailable) {
    fetch(`/admin/products/variants/${variantId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_available: isAvailable })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            document.getElementById(`variant-${variantId}`).checked = !isAvailable;
            alert('Error: ' + (data.message || 'Failed to update variant status'));
        }
    });
}

function deleteVariant(variantId) {
    if (confirm('Are you sure you want to delete this variant?')) {
        fetch(`/admin/products/variants/${variantId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                manageVariants(currentProductId); // Refresh variants list
            } else {
                alert('Error: ' + (data.message || 'Failed to delete variant'));
            }
        });
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Manage Products - MacCafe';
$currentPage = 'products';
include __DIR__ . '/../layout.php';
?>
