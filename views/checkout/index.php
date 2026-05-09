<?php ob_start(); ?>
<div class="container mt-5">
    <div class="row" style="margin-top: 120px;">
        
        <!-- Left Column - Main Form -->
        <div class="col-lg-7">
            <h2 class="mb-4">Checkout</h2>
            
            <?php if (Session::has('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (Session::get('errors') as $field => $errors): ?>
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-1"><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form action="/checkout/process" method="POST" enctype="multipart/form-data">
                
                <!-- Order Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Variant</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($item['product_image'])): ?>
                                                        <img src="/assets/images/<?php echo $item['product_image']; ?>" 
                                                             alt="<?php echo $item['product_name']; ?>" 
                                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                    <?php endif; ?>
                                                    <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo $item['variant_name'] ? htmlspecialchars($item['variant_name']) : '-'; ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td>₱<?php echo number_format($item['unit_price'], 2); ?></td>
                                            <td>₱<?php echo number_format($item['total_price'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <p>Subtotal: <strong>₱<?php echo number_format($subtotal, 2); ?></strong></p>
                                <p>Tax (12%): <strong>₱<?php echo number_format($tax, 2); ?></strong></p>
                                <h4>Total: <strong class="text-primary">₱<?php echo number_format($total, 2); ?></strong></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_name" class="form-label">Full Name *</label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control" 
                                           value="<?php echo Session::get('old')['customer_name'] ?? htmlspecialchars($user['first_name'] . ' ' . ($user['last_name'] ?? '')); ?>" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer_phone" class="form-label">Phone Number *</label>
                                    <input type="tel" name="customer_phone" id="customer_phone" class="form-control" 
                                           value="<?php echo Session::get('old')['customer_phone'] ?? htmlspecialchars($user['phone'] ?? ''); ?>" 
                                           required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="customer_address" class="form-label">Delivery Address *</label>
                                    <textarea name="customer_address" id="customer_address" class="form-control" rows="3" 
                                              placeholder="Enter your complete delivery address" required><?php echo Session::get('old')['customer_address'] ?? htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="customer_email" class="form-label">Email Address *</label>
                                    <input type="email" name="customer_email" id="customer_email" class="form-control" 
                                           value="<?php echo Session::get('old')['customer_email'] ?? htmlspecialchars($user['email'] ?? ''); ?>" 
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="gcash" value="gcash" checked>
                            <label class="form-check-label" for="gcash">
                                <strong>GCash</strong> - Send payment via GCash and upload proof
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                            <label class="form-check-label" for="cash">
                                <strong>Cash on Delivery</strong> - Pay when you receive your order
                            </label>
                        </div>

                        <div class="alert alert-info mt-4">
                            <strong>Important:</strong> For GCash payments, you will need to upload payment proof below.
                        </div>
                    </div>
                </div>

                <!-- Payment Proof Upload -->
                <div class="card mb-4" id="payment-proof-section">
                    <div class="card-header">
                        <h5 class="mb-0">Upload Payment Proof (GCash Only)</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" name="reference_number" id="reference_number" class="form-control" 
                                   placeholder="GCash Transaction ID">
                        </div>
                        
                        <div class="form-group">
                            <label for="proof_image" class="form-label">Payment Screenshot</label>
                            <input type="file" name="proof_image" id="proof_image" class="form-control" 
                                   accept="image/*">
                            <small class="text-muted">JPG or PNG only, max 5MB</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="/cart" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Cart
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg" id="placeOrderBtn">
                        <i class="fas fa-check"></i> <span id="btnText">Place Order</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column - Payment Information (Fixed Sidebar) -->
        <div class="col-lg-5">
            <div class="card border-primary sticky-top" style="top: 100px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-credit-card me-2"></i>Payment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Available Payment Methods</strong>
                    </div>
                    
                    <h6 class="text-success mt-3">
                        <i class="bi bi-phone me-2"></i>GCash
                    </h6>
                    <p><strong>Number:</strong> 09708228108</p>
                    <p><strong>Account Name:</strong> MCCafe Store</p>
                    
                    <hr>
                    
                    <h6 class="text-warning">
                        <i class="bi bi-cash me-2"></i>Cash on Delivery (COD)
                    </h6>
                    <p>Pay when your order arrives.</p>
                    
                    <div class="alert alert-warning mt-4 small">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        For GCash payments, please upload your payment screenshot after placing the order.
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-danger small">
                        <i class="bi bi-exclamation-octagon me-2"></i>
                        <strong>No Cancellation Policy</strong>
                        <p class="mb-1 mt-2">Orders cannot be canceled once payment is submitted via GCash.</p>
                        <p class="mb-0">This ensures commitment and reduces invalid orders.</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay" style="display: none;">
    <div class="loading-content">
        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5>Processing Your Order</h5>
        <p class="text-muted">Please wait while we process your order...</p>
        <div class="progress mt-3" style="width: 300px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
        </div>
    </div>
</div>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.95);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(5px);
}

.loading-content {
    text-align: center;
    background: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border: 1px solid #dee2e6;
}

.loading-content h5 {
    color: var(--maccafe-primary);
    font-weight: bold;
    margin-bottom: 10px;
}

.loading-content p {
    font-size: 0.9rem;
    margin-bottom: 20px;
}

.progress {
    height: 8px;
    background-color: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    background-color: var(--maccafe-primary);
    transition: width 0.3s ease;
}
</style>

<script>
// Show/hide payment proof section based on payment method
function togglePaymentProofSection() {
    const paymentMethods = document.getElementsByName('payment_method');
    const proofSection = document.getElementById('payment-proof-section');
    const referenceNumber = document.getElementById('reference_number');
    const proofImage = document.getElementById('proof_image');
    
    let selectedMethod = '';
    for (const method of paymentMethods) {
        if (method.checked) {
            selectedMethod = method.value;
            break;
        }
    }
    
    // Show payment proof section for GCash payments only
    const manualPayments = ['gcash'];
    if (manualPayments.includes(selectedMethod)) {
        proofSection.style.display = 'block';
        referenceNumber.required = true;
        proofImage.required = true;
    } else {
        proofSection.style.display = 'none';
        referenceNumber.required = false;
        proofImage.required = false;
    }
}

// Add event listeners to payment method radios
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.getElementsByName('payment_method');
    paymentMethods.forEach(method => {
        method.addEventListener('change', togglePaymentProofSection);
    });
    
    // Initial check
    togglePaymentProofSection();
});

// Handle image preview
document.getElementById('proof_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// Handle form submission with loading overlay
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('placeOrderBtn');
    const btnText = document.getElementById('btnText');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Disable the button and change text
    submitBtn.disabled = true;
    btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
    
    // Show loading overlay
    loadingOverlay.style.display = 'flex';
    
    // Prevent multiple submissions
    submitBtn.style.pointerEvents = 'none';
    submitBtn.style.opacity = '0.7';
});
</script>

<?php
$content = ob_get_clean();
$title = $title ?? 'Checkout - MacCafe';
include __DIR__ . '/../layouts/main.php';
?>
