<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Product Variants - <?= $product['name'] ?></h1>
    <a href="/admin/products/<?= $product['id'] ?>/edit" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Product
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Existing Variants</h5>
            </div>
            <div class="card-body">
                <?php if (empty($variants)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-layers display-4"></i>
                        <p class="mt-2">No variants found for this product.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price Adjustment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($variants as $variant): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($variant['name']) ?></td>
                                        <td>
                                            <?php if ($variant['price_adjustment'] > 0): ?>
                                                <span class="text-success">+₱<?= number_format($variant['price_adjustment'], 2) ?></span>
                                            <?php elseif ($variant['price_adjustment'] < 0): ?>
                                                <span class="text-danger">₱<?= number_format($variant['price_adjustment'], 2) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">No change</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $variant['is_available'] ? 'success' : 'danger' ?>">
                                                <?= $variant['is_available'] ? 'Available' : 'Unavailable' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-<?= $variant['is_available'] ? 'warning' : 'success' ?>" 
                                                        onclick="toggleVariantStatus(<?= $variant['id'] ?>)">
                                                    <i class="bi bi-<?= $variant['is_available'] ? 'eye-slash' : 'eye' ?>"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteVariant(<?= $variant['id'] ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Add New Variant</h5>
            </div>
            <div class="card-body">
                <form id="addVariantForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="variant_name" class="form-label">Variant Name</label>
                                <input type="text" class="form-control" id="variant_name" name="name" required>
                                <div class="form-text">e.g., Small, Medium, Large or Vanilla, Chocolate</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price_adjustment" class="form-label">Price Adjustment</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" id="price_adjustment" name="price_adjustment" 
                                           step="0.01" placeholder="0.00">
                                </div>
                                <div class="form-text">Use positive for increase, negative for decrease</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="variant_available" name="is_available" checked>
                        <label class="form-check-label" for="variant_available">
                            Available for ordering
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Variant
                    </button>
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
                    <strong>Product:</strong> <?= $product['name'] ?>
                </div>
                <div class="mb-3">
                    <strong>Base Price:</strong> ₱<?= number_format($product['price'], 2) ?>
                </div>
                <div class="mb-3">
                    <strong>Category:</strong> <?= $product['category_name'] ?? 'N/A' ?>
                </div>
                <div class="mb-3">
                    <strong>Total Variants:</strong> <?= count($variants) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add variant form submission
document.getElementById('addVariantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        name: formData.get('name'),
        price_adjustment: parseFloat(formData.get('price_adjustment')) || 0,
        is_available: formData.get('is_available') ? 1 : 0
    };
    
    fetch('/admin/products/<?= $product['id'] ?>/variants', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to add variant: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the variant');
    });
});

// Toggle variant status
function toggleVariantStatus(variantId) {
    fetch('/admin/products/variants/' + variantId + '/status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_available: true })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to update variant status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the variant');
    });
}

// Delete variant
function deleteVariant(variantId) {
    if (confirm('Are you sure you want to delete this variant? This action cannot be undone.')) {
        fetch('/admin/products/variants/' + variantId, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete variant');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the variant');
        });
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Product Variants - MacCafe';
$currentPage = 'products';
include __DIR__ . '/../layout.php';
?>
