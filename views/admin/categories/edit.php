<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Category</h1>
    <a href="/admin/categories" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Categories
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-tag me-2"></i>Category Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/categories/<?= $category['id'] ?>" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Category Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($category['name']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label fw-semibold">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                       value="<?= $category['sort_order'] ?? 0 ?>" min="0">
                                <div class="form-text">Lower numbers appear first</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Enter category description..."><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold">Category Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Recommended: 800x400px, Max: 5MB. Leave empty to keep current image.
                        </div>
                        
                        <?php if (!empty($category['image'])): ?>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-muted d-block mb-2">Current Image:</small>
                            <img src="/assets/images/categories/<?= $category['image'] ?>" 
                                 class="img-thumbnail shadow-sm" style="max-height: 120px;" alt="<?= htmlspecialchars($category['name']) ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-check mb-4 p-3 bg-light rounded">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                               <?= $category['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="is_active">
                            <i class="bi bi-eye me-1"></i>Active
                        </label>
                        <div class="form-text mt-1">Inactive categories won't be shown in the menu</div>
                    </div>
                    
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Update Category
                        </button>
                        <a href="/admin/categories" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Category Statistics
                </h6>
            </div>
            <div class="card-body">
                <?php $productCount = $productCount ?? 0; ?>
                
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            <i class="bi bi-box me-1"></i>Products
                        </span>
                        <span class="badge bg-primary rounded-pill"><?= $productCount ?></span>
                    </div>
                </div>
                
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            <i class="bi bi-toggle-on me-1"></i>Status
                        </span>
                        <span class="badge bg-<?= $category['is_active'] ? 'success' : 'secondary' ?> rounded-pill">
                            <?= $category['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                </div>
                
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            <i class="bi bi-calendar-plus me-1"></i>Created
                        </span>
                        <small class="text-muted"><?= date('M j, Y', strtotime($category['created_at'])) ?></small>
                    </div>
                </div>
                
                <?php if (!empty($category['updated_at'])): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            <i class="bi bi-calendar-check me-1"></i>Updated
                        </span>
                        <small class="text-muted"><?= date('M j, Y', strtotime($category['updated_at'])) ?></small>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($productCount > 0): ?>
        <div class="card shadow-sm mt-3 border-warning">
            <div class="card-header bg-warning bg-opacity-10 border-warning">
                <h6 class="card-title mb-0 text-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>Important Notice
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <i class="bi bi-info-circle text-warning me-3 fs-5"></i>
                    <div>
                        <p class="mb-0 text-muted">
                            This category contains <strong><?= $productCount ?></strong> product(s).
                        </p>
                        <small class="text-muted">
                            You must reassign these products before deleting this category.
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Edit Category - MacCafe';
$currentPage = 'categories';
include __DIR__ . '/../layout.php';
?>
