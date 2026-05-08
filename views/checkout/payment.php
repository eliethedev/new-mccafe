<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Upload Payment Proof</h4>
                </div>
                <div class="card-body">
                    <?php if (Session::has('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (Session::get('errors') as $field => $errors): ?>
                                <?php foreach ($errors as $error): ?>
                                    <p class="mb-1"><?php echo $error; ?></p>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <h5>Order Details</h5>
                        <p><strong>Order Number:</strong> #<?php echo $order['order_number']; ?></p>
                        <p><strong>Total Amount:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?></p>
                        <p><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
                        <p><strong>Status:</strong> <span class="badge bg-warning"><?php echo ucfirst($order['payment_status']); ?></span></p>
                    </div>
                    
                    <form action="/checkout/upload-payment-proof" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <input type="hidden" name="payment_method" value="<?php echo $order['payment_method']; ?>">
                        
                        <div class="mb-4">
                            <h5>Payment Information</h5>
                            <?php if ($order['payment_method'] === 'gcash'): ?>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>GCash Details:</h6>
                                        <p><strong>Number:</strong> 0912-345-6789</p>
                                        <p><strong>Name:</strong> MacCafe Store</p>
                                        <div class="text-center mt-3">
                                            <img src="/assets/images/gcash-qr.png" alt="GCash QR Code" class="img-fluid" style="max-width: 200px;">
                                            <p class="mt-2">Scan this QR code with your GCash app</p>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif ($order['payment_method'] === 'paymaya'): ?>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>PayMaya Details:</h6>
                                        <p><strong>Number:</strong> 0912-345-6789</p>
                                        <p><strong>Name:</strong> MacCafe Store</p>
                                        <div class="text-center mt-3">
                                            <img src="/assets/images/paymaya-qr.png" alt="PayMaya QR Code" class="img-fluid" style="max-width: 200px;">
                                            <p class="mt-2">Scan this QR code with your PayMaya app</p>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif ($order['payment_method'] === 'bank_transfer'): ?>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Bank Transfer Details:</h6>
                                        <p><strong>Bank:</strong> BPI</p>
                                        <p><strong>Account Name:</strong> MacCafe Store</p>
                                        <p><strong>Account Number:</strong> 1234-5678-90</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="reference_number" class="form-label">Reference Number *</label>
                            <input type="text" name="reference_number" id="reference_number" class="form-control" 
                                   placeholder="Enter transaction reference number" required
                                   value="<?php echo Session::get('old')['reference_number'] ?? ''; ?>">
                            <small class="form-text text-muted">This is the transaction ID or reference number from your payment app.</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="proof_image" class="form-label">Payment Proof Screenshot *</label>
                            <input type="file" name="proof_image" id="proof_image" class="form-control" 
                                   accept="image/*" required>
                            <small class="form-text text-muted">Upload a screenshot of your payment confirmation (JPG, PNG, max 5MB).</small>
                        </div>
                        
                        <div id="image-preview" class="mb-3" style="display: none;">
                            <img id="preview-img" src="" alt="Payment Proof Preview" class="img-fluid" style="max-height: 300px;">
                        </div>
                        
                        <div class="alert alert-success">
                            <h6>Next Steps:</h6>
                            <ol class="mb-0">
                                <li>Complete your payment using the details above</li>
                                <li>Take a screenshot of the payment confirmation</li>
                                <li>Upload the screenshot and enter the reference number</li>
                                <li>We'll verify your payment within 24 hours</li>
                                <li>You'll receive an email once your payment is verified</li>
                            </ol>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="/order/<?php echo $order['id']; ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Order
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Payment Proof
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
