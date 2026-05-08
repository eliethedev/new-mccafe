<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Add New Product</h1>
    <a href="/admin/products" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Products
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/products" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= Session::getFlash('old.name') ?? '' ?>" required>
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
                                            <?= (Session::getFlash('old.category_id') ?? '') == $category['id'] ? 'selected' : '' ?>>
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
                        <textarea class="form-control" id="description" name="description" rows="4"><?= Session::getFlash('old.description') ?? '' ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" id="price" name="price" 
                                           step="0.01" min="0" 
                                           value="<?= Session::getFlash('old.price') ?? '' ?>" required>
                                </div>
                                <?php if (Session::getFlash('errors.price')): ?>
                                    <div class="text-danger small">
                                        <?= implode(', ', Session::getFlash('errors.price')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Recommended size: 400x400px. Max size: 5MB. JPG, PNG, GIF formats.</div>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_available" name="is_available" 
                               <?= (Session::getFlash('old.is_available') ?? '1') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_available">
                            Available for ordering
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/admin/products" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Guidelines</h5>
            </div>
            <div class="card-body">
                <h6>Image Requirements</h6>
                <ul class="small">
                    <li>Recommended size: 400x400 pixels</li>
                    <li>Maximum file size: 5MB</li>
                    <li>Accepted formats: JPG, PNG, GIF</li>
                    <li>Use high-quality, clear images</li>
                </ul>

                <h6 class="mt-3">Pricing Tips</h6>
                <ul class="small">
                    <li>Set competitive prices</li>
                    <li>Consider ingredient costs</li>
                    <li>Include preparation costs</li>
                    <li>Research competitor pricing</li>
                </ul>

                <h6 class="mt-3">Best Practices</h6>
                <ul class="small">
                    <li>Write clear, concise descriptions</li>
                    <li>Highlight unique features</li>
                    <li>Set appropriate preparation times</li>
                    <li>Use sort order for menu display</li>
                </ul>
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
                    <div class="small text-muted mt-1">Image preview</div>
                </div>
            `;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Auto-save draft (optional enhancement)
let autoSaveTimer;
const form = document.querySelector('form');

form.addEventListener('input', function() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        // Save form data to localStorage
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        localStorage.setItem('productDraft', JSON.stringify(data));
    }, 2000);
});

// Load draft on page load
window.addEventListener('load', function() {
    const draft = localStorage.getItem('productDraft');
    if (draft) {
        const data = JSON.parse(draft);
        // Populate form fields with draft data
        Object.keys(data).forEach(key => {
            const field = document.querySelector(`[name="${key}"]`);
            if (field) {
                if (field.type === 'checkbox') {
                    field.checked = data[key] === 'on';
                } else {
                    field.value = data[key];
                }
            }
        });
    }
});

// Clear draft on successful submission
form.addEventListener('submit', function() {
    localStorage.removeItem('productDraft');
});
</script>

<?php
$content = ob_get_clean();
$title = 'Add New Product - MacCafe';
$currentPage = 'products';
include __DIR__ . '/../layout.php';
?>
