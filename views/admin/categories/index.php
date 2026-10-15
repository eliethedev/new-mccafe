<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Categories</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <i class="bi bi-plus-circle me-2"></i>Add New Category
    </button>
</div>

<!-- Categories Grid -->
<div class="row">
    <?php if (empty($categories ?? [])): ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-tags fs-1 text-muted d-block mb-3"></i>
                <h5>No Categories Found</h5>
                <p class="text-muted">Start by adding your first category.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                    <i class="bi bi-plus-circle me-2"></i>Add Category
                </button>
            </div>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($categories as $category): ?>
    <div class="col-lg-4 col-md-6 mb-4" id="category-<?= $category['id'] ?>">
        <div class="card h-100">
            <?php if (!empty($category['image'])): ?>
            <img src="/assets/images/categories/<?= $category['image'] ?>" class="card-img-top" alt="<?= $category['name'] ?>" style="height: 200px; object-fit: cover;">
            <?php else: ?>
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                <i class="bi bi-tag fs-1 text-muted"></i>
            </div>
            <?php endif; ?>
            
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0"><?= $category['name'] ?></h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" 
                               id="active-<?= $category['id'] ?>" 
                               <?= $category['is_active'] ? 'checked' : '' ?>
                               onchange="toggleCategoryStatus(<?= $category['id'] ?>, this.checked)">
                        <label class="form-check-label" for="active-<?= $category['id'] ?>"></label>
                    </div>
                </div>
                
                <?php if (!empty($category['description'])): ?>
                <p class="card-text text-muted small"><?= $category['description'] ?></p>
                <?php endif; ?>
                
                <div class="mt-auto">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Sort Order: <?= $category['sort_order'] ?? 0 ?>
                        </small>
                        <div class="btn-group" role="group">
                            <a href="/admin/categories/<?= $category['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(<?= $category['id'] ?>, '<?= $category['name'] ?>')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="category_id" id="categoryId">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order (auto)</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= !empty($categories) ? (int) max(array_column($categories, 'sort_order')) + 1 : 1 ?>" min="0" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Category Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Recommended size: 800x400px. Max size: 5MB.</div>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentEditId = null;

// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail mt-2" style="max-height: 150px;">`;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Form submission
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const isEdit = formData.get('category_id') !== '';
    const url = isEdit ? `/admin/categories/${formData.get('category_id')}` : '/admin/categories';
    
    fetch(url, {
        method: isEdit ? 'PUT' : 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async (response) => {
        const raw = await response.text();
        try {
            return JSON.parse(raw);
        } catch (e) {
            console.error('Non-JSON response', response.status, raw);
            throw e;
        }
    })
    .then(data => {
        if (data.success) {
            console.log('Category saved successfully', data);
            try {
                const modalEl = document.getElementById('categoryModal');
                const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                if (inst) inst.hide();
            } catch (e) {}
            location.reload();
        } else {
            console.error('Save failed', data);
            alert(data.message || data.errors ? JSON.stringify(data.errors || data) : 'Failed to save. Check console.');
        }
    })
    .catch(error => {
        console.error(error);
        alert('Network error. Check console for details.');
    });
});

function editCategory(categoryId) {
    // Fetch category data and populate form
    fetch(`/admin/categories/${categoryId}/edit`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const category = data.category;
            document.getElementById('categoryId').value = category.id;
            document.getElementById('name').value = category.name;
            document.getElementById('description').value = category.description || '';
            document.getElementById('sort_order').value = category.sort_order || 0;
            document.getElementById('is_active').checked = category.is_active;
            document.getElementById('categoryModalTitle').textContent = 'Edit Category';
            
            // Show existing image if any
            if (category.image) {
                document.getElementById('imagePreview').innerHTML = 
                    `<img src="/assets/images/categories/${category.image}" class="img-thumbnail mt-2" style="max-height: 150px;">`;
            }
            
            new bootstrap.Modal(document.getElementById('categoryModal')).show();
        } else {
            console.error('Edit failed:', data.message || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error fetching category:', error);
    });
}

function deleteCategory(categoryId, categoryName) {
    if (confirm(`Are you sure you want to delete "${categoryName}"? This action cannot be undone.`)) {
        fetch(`/admin/categories/${categoryId}`, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                messageDiv.style.zIndex = '9999';
                messageDiv.style.minWidth = '300px';
                messageDiv.innerHTML = `
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Category "${categoryName}" deleted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(messageDiv);
                setTimeout(() => messageDiv.remove(), 3000);

                const el = document.getElementById('category-' + categoryId);
                if (el) {
                    el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'scale(0.95)';
                    setTimeout(() => el.remove(), 300);
                }
            } else {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                messageDiv.style.zIndex = '9999';
                messageDiv.style.minWidth = '300px';
                messageDiv.innerHTML = `
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    ${data.message || 'Failed to delete category.'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(messageDiv);
                setTimeout(() => messageDiv.remove(), 4000);
            }
        })
        .catch(error => {
            alert(error.message || 'Network error. Please try again.');
        });
    }
}

function toggleCategoryStatus(categoryId, isActive) {
    fetch(`/admin/categories/${categoryId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_active: isActive })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            // Revert the checkbox if failed
            document.getElementById(`active-${categoryId}`).checked = !isActive;
        }
    })
    .catch(error => {
        // Revert the checkbox if failed
        document.getElementById(`active-${categoryId}`).checked = !isActive;
    });
}

// Reset modal when closed
document.getElementById('categoryModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('categoryModalTitle').textContent = 'Add New Category';
});
</script>

<?php
$content = ob_get_clean();
$title = 'Manage Categories - CafeCat';
$currentPage = 'categories';
include __DIR__ . '/../layout.php';
?>
