<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-1">
    <div>
        <h1 class="h3 mb-0">Menu Management</h1>
        <p class="text-muted mb-0">Manage products, availability, best sellers & featured items</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/admin/best-sellers" class="btn btn-outline-secondary">
            <i class="bi bi-trophy me-2"></i>Best Sellers
        </a>
        <a href="/admin/products/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Product
        </a>
    </div>
</div>

<?php $ms = $menuStats ?? ['total'=>0,'available'=>0,'unavailable'=>0,'bestsellers'=>0,'featured'=>0]; ?>
<div class="row g-3 my-3">
    <div class="col-md-2 col-6"><div class="card text-center py-3"><div class="h4 mb-0"><?= $ms['total'] ?></div><small class="text-muted text-uppercase">Total items</small></div></div>
    <div class="col-md-2 col-6"><div class="card text-center py-3"><div class="h4 mb-0"><?= $ms['available'] ?></div><small class="text-muted text-uppercase">Available</small></div></div>
    <div class="col-md-2 col-6"><div class="card text-center py-3"><div class="h4 mb-0"><?= $ms['unavailable'] ?></div><small class="text-muted text-uppercase">Unavailable</small></div></div>
    <div class="col-md-3 col-6"><div class="card text-center py-3"><div class="h4 mb-0"><i class="bi bi-trophy me-1"></i><?= $ms['bestsellers'] ?></div><small class="text-muted text-uppercase">Best sellers</small></div></div>
    <div class="col-md-3 col-6"><div class="card text-center py-3"><div class="h4 mb-0"><i class="bi bi-star me-1"></i><?= $ms['featured'] ?></div><small class="text-muted text-uppercase">Featured</small></div></div>
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
                    <option value="bestseller" <?= ($currentSort ?? '') === 'bestseller' ? 'selected' : '' ?>>Best sellers first</option>
                    <option value="availability" <?= ($currentSort ?? '') === 'availability' ? 'selected' : '' ?>>Availability</option>
                    <option value="price_low" <?= ($currentSort ?? '') === 'price_low' ? 'selected' : '' ?>>Price (Low to High)</option>
                    <option value="price_high" <?= ($currentSort ?? '') === 'price_high' ? 'selected' : '' ?>>Price (High to Low)</option>
                    <option value="created" <?= ($currentSort ?? '') === 'created' ? 'selected' : '' ?>>Date Created</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="flag" class="form-label">Show</label>
                <select class="form-select" id="flag" name="flag" onchange="this.form.submit()">
                    <option value="">All items</option>
                    <option value="bestseller" <?= ($currentFlag ?? '') === 'bestseller' ? 'selected' : '' ?>>Best sellers</option>
                    <option value="featured" <?= ($currentFlag ?? '') === 'featured' ? 'selected' : '' ?>>Featured</option>
                    <option value="unavailable" <?= ($currentFlag ?? '') === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="search" class="form-label">Search</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Name or description..." value="<?= htmlspecialchars($searchQuery ?? '') ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search me-1"></i>Go
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
                        <th>Flags</th>
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
                                <strong><?= htmlspecialchars($product['name']) ?></strong><br>
                                <?php if (!empty($product['is_bestseller'])): ?><span class="badge bg-dark me-1"><i class="bi bi-trophy me-1"></i>Best seller</span><?php endif; ?>
                                <?php if (!empty($product['is_featured'])): ?><span class="badge bg-secondary me-1"><i class="bi bi-star me-1"></i>Featured</span><?php endif; ?>
                                <?php if (empty($product['is_available'])): ?><span class="badge bg-light text-dark border">Unavailable</span><?php endif; ?>
                                <br><small class="text-muted"><?= htmlspecialchars(substr($product['description'] ?? '', 0, 50)) ?>...</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info"><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></span>
                        </td>
                        <td>
                            <strong>₱<?= number_format($product['price'], 2) ?></strong>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm <?= !empty($product['is_bestseller']) ? 'btn-dark' : 'btn-outline-dark' ?>" title="Toggle best seller" onclick="toggleFlag(<?= $product['id'] ?>, 'bestseller', this)">
                                    <i class="bi bi-trophy"></i>
                                </button>
                                <button class="btn btn-sm <?= !empty($product['is_featured']) ? 'btn-dark' : 'btn-outline-dark' ?>" title="Toggle featured" onclick="toggleFlag(<?= $product['id'] ?>, 'featured', this)">
                                    <i class="bi bi-star"></i>
                                </button>
                            </div>
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
                    <a class="page-link" href="?page=<?= $i ?><?= isset($_GET['category']) ? '&category=' . urlencode($_GET['category']) : '' ?><?= isset($_GET['availability']) ? '&availability=' . urlencode($_GET['availability']) : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?><?= isset($_GET['flag']) ? '&flag=' . urlencode($_GET['flag']) : '' ?>"><?= $i ?></a>
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

/* Shared helper: marks requests as AJAX so the backend returns JSON
   (401 + JSON when the session expired) instead of an HTML redirect. */
async function adminFetch(url, options = {}) {
    options.headers = Object.assign({ 'X-Requested-With': 'XMLHttpRequest' }, options.headers || {});
    let response;
    try {
        response = await fetch(url, options);
    } catch (e) {
        throw new Error('Network error — is the server still running?');
    }
    if (response.status === 401) {
        alert('Your session expired. Please log in again.');
        window.location.href = '/login';
        throw new Error('Session expired');
    }
    const text = await response.text();
    let data;
    try {
        data = text ? JSON.parse(text) : {};
    } catch (e) {
        console.error('Non-JSON response:', text);
        throw new Error('Unexpected server response. Please refresh and try again.');
    }
    if (!response.ok) {
        throw new Error((data && data.message) || `Request failed (HTTP ${response.status})`);
    }
    return data;
}

async function toggleFlag(productId, kind, btn) {
    btn.disabled = true;
    try {
        const data = await adminFetch(`/admin/products/${productId}/${kind}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({})
        });
        if (data.success) {
            // Flip button style without reload
            const on = !!data.value;
            btn.classList.remove('btn-dark', 'btn-outline-dark');
            btn.classList.add(on ? 'btn-dark' : 'btn-outline-dark');
        } else {
            alert('Error: ' + (data.message || 'Failed to update'));
        }
    } catch (e) {
        if (e.message !== 'Session expired') alert(e.message);
    } finally {
        btn.disabled = false;
    }
}

async function toggleProductStatus(productId, isAvailable) {
    try {
        const data = await adminFetch(`/admin/products/${productId}/status`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ is_available: isAvailable })
        });
        if (!data.success) {
            document.getElementById(`available-${productId}`).checked = !isAvailable;
            alert('Error: ' + (data.message || 'Failed to update status'));
        }
    } catch (e) {
        document.getElementById(`available-${productId}`).checked = !isAvailable;
        if (e.message !== 'Session expired') alert(e.message);
    }
}

function updateSortOrder(productId, sortOrder) {
    adminFetch(`/admin/products/${productId}/sort`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sort_order: sortOrder })
    })
    .then(data => {
        if (!data.success) {
            alert('Error: ' + (data.message || 'Failed to update sort order'));
            location.reload();
        }
    })
    .catch(error => {
        if (error.message !== 'Session expired') alert(error.message);
    });
}

function deleteProduct(productId, productName) {
    if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
        adminFetch(`/admin/products/${productId}`, { method: 'DELETE' })
        .then(data => {
            if (data.success) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                messageDiv.style.zIndex = '9999';
                messageDiv.style.minWidth = '300px';
                messageDiv.innerHTML = `
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Product "${productName}" deleted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(messageDiv);
                setTimeout(() => {
                    messageDiv.remove();
                    location.reload();
                }, 2000);
            } else {
                alert('Error: ' + (data.message || 'Failed to delete product'));
            }
        })
        .catch(error => {
            if (error.message !== 'Session expired') alert(error.message);
        });
    }
}

function manageVariants(productId) {
    currentProductId = productId;
    
    adminFetch(`/admin/products/${productId}/variants`)
    .then(data => {
        if (data.success) {
            renderVariants(data.variants);
            new bootstrap.Modal(document.getElementById('variantsModal')).show();
        } else {
            alert('Error: ' + (data.message || 'Failed to load variants'));
        }
    })
    .catch(e => { if (e.message !== 'Session expired') alert(e.message); });
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
    
    adminFetch(`/admin/products/${currentProductId}/variants`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: name,
            price_adjustment: parseFloat(priceAdjustment)
        })
    })
    .then(data => {
        if (data.success) {
            manageVariants(currentProductId); // Refresh variants list
        } else {
            alert('Error: ' + (data.message || 'Failed to add variant'));
        }
    })
    .catch(e => { if (e.message !== 'Session expired') alert(e.message); });
}

function toggleVariantStatus(variantId, isAvailable) {
    adminFetch(`/admin/products/variants/${variantId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_available: isAvailable })
    })
    .then(data => {
        if (!data.success) {
            document.getElementById(`variant-${variantId}`).checked = !isAvailable;
            alert('Error: ' + (data.message || 'Failed to update variant status'));
        }
    })
    .catch(e => {
        document.getElementById(`variant-${variantId}`).checked = !isAvailable;
        if (e.message !== 'Session expired') alert(e.message);
    });
}

function deleteVariant(variantId) {
    if (confirm('Are you sure you want to delete this variant?')) {
        adminFetch(`/admin/products/variants/${variantId}`, {
            method: 'DELETE'
        })
        .then(data => {
            if (data.success) {
                manageVariants(currentProductId); // Refresh variants list
            } else {
                alert('Error: ' + (data.message || 'Failed to delete variant'));
            }
        })
        .catch(e => { if (e.message !== 'Session expired') alert(e.message); });
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Manage Products - CafeCat';
$currentPage = 'products';
include __DIR__ . '/../layout.php';
?>
