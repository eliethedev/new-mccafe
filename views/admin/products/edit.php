<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Product</h1>
    <a href="/admin/products" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Products
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/products/<?= $product['id'] ?>" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= Session::getFlash('old.name') ?? $product['name'] ?>" required>
                                <?php if (Session::getFlash('errors.name')): ?>
                                    <div class="text-danger small">
                                        <?= implode(', ', Session::getFlash('errors.name')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories ?? [] as $category): ?>
                                    <option value="<?= $category['id'] ?>" 
                                            <?= ((Session::getFlash('old.category_id') ?? $product['category_id']) == $category['id']) ? 'selected' : '' ?>>
                                        <?= $category['name'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (Session::getFlash('errors.category_id')): ?>
                                    <div class="text-danger small">
                                        <?= implode(', ', Session::getFlash('errors.category_id')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?= Session::getFlash('old.description') ?? $product['description'] ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" id="price" name="price" 
                                           step="0.01" min="0" 
                                           value="<?= Session::getFlash('old.price') ?? $product['price'] ?>" required>
                                </div>
                                <?php if (Session::getFlash('errors.price')): ?>
                                    <div class="text-danger small">
                                        <?= implode(', ', Session::getFlash('errors.price')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                       min="0" value="<?= Session::getFlash('old.sort_order') ?? $product['sort_order'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Leave empty to keep current image. New image will replace the current one.</div>
                        <div id="imagePreview" class="mt-2">
                            <?php if (!empty($product['image'])): ?>
                            <div class="mt-2">
                                <img src="/assets/images/products/<?= $product['image'] ?>" 
                                     class="img-thumbnail" style="max-height: 200px;">
                                <div class="small text-muted mt-1">Current image</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_available" name="is_available" 
                               <?= (Session::getFlash('old.is_available') ?? $product['is_available']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_available">
                            Available for ordering
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/admin/products" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Product ID:</strong> <?= $product['id'] ?>
                </div>
                <div class="mb-3">
                    <strong>Created:</strong> <?= date('F j, Y g:i A', strtotime($product['created_at'])) ?>
                </div>
                <div class="mb-3">
                    <strong>Last Updated:</strong> <?= date('F j, Y g:i A', strtotime($product['updated_at'])) ?>
                </div>
                <div class="mb-3">
                    <strong>Current Status:</strong> 
                    <span class="badge bg-<?= $product['is_available'] ? 'success' : 'danger' ?>">
                        <?= $product['is_available'] ? 'Available' : 'Unavailable' ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Variants</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-sm btn-outline-primary w-100 mb-2" onclick="manageVariants()">
                    <i class="bi bi-layers me-1"></i>Manage Variants
                </button>
                <div class="small text-muted">
                    Add size options, flavors, or other product variations.
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Danger Zone</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-sm btn-outline-danger w-100" onclick="deleteProduct()">
                    <i class="bi bi-trash me-1"></i>Delete Product
                </button>
                <div class="small text-muted mt-2">
                    This action cannot be undone. This will permanently delete the product and all associated data.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            this.value = '';
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Only JPG, PNG, and GIF files are allowed');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="mt-2">
                    <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">
                    <div class="small text-muted mt-1">New image preview</div>
                </div>
            `;
        }
        reader.readAsDataURL(file);
    } else {
        // Restore current image preview
        <?php if (!empty($product['image'])): ?>
        preview.innerHTML = `
            <div class="mt-2">
                <img src="/assets/images/products/<?= $product['image'] ?>" 
                     class="img-thumbnail" style="max-height: 200px;">
                <div class="small text-muted mt-1">Current image</div>
            </div>
        `;
        <?php else: ?>
        preview.innerHTML = '';
        <?php endif; ?>
    }
});

function manageVariants() {
    window.location.href = `/admin/products/<?= $product['id'] ?>/variants`;
}

function deleteProduct() {
    const productName = '<?= $product['name'] ?>';
    if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/products/<?= $product['id'] ?>';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Edit Product - MacCafe';
$currentPage = 'products';
include __DIR__ . '/../layout.php';
?>
