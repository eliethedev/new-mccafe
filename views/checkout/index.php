<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
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
            
            <form action="/checkout/process" method="POST">
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
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($item['product_image']): ?>
                                                        <img src="/assets/images/<?php echo $item['product_image']; ?>" 
                                                             alt="<?php echo $item['product_name']; ?>" 
                                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes" class="form-label">Order Notes (Optional)</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" 
                                              placeholder="Special instructions..."><?php echo Session::get('old')['notes'] ?? ''; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-end">
                                    <p>Subtotal: <strong>₱<?php echo number_format($subtotal, 2); ?></strong></p>
                                    <p>Tax (12%): <strong>₱<?php echo number_format($tax, 2); ?></strong></p>
                                    <h4>Total: <strong>₱<?php echo number_format($total, 2); ?></strong></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_name" class="form-label">Full Name *</label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control" 
                                           value="<?php echo Session::get('old')['customer_name'] ?? htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_phone" class="form-label">Phone Number *</label>
                                    <input type="tel" name="customer_phone" id="customer_phone" class="form-control" 
                                           value="<?php echo Session::get('old')['customer_phone'] ?? htmlspecialchars($user['phone'] ?? ''); ?>" 
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="customer_email" class="form-label">Email Address *</label>
                                    <input type="email" name="customer_email" id="customer_email" class="form-control" 
                                           value="<?php echo Session::get('old')['customer_email'] ?? htmlspecialchars($user['email']); ?>" 
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="paymaya" value="paymaya">
                            <label class="form-check-label" for="paymaya">
                                <strong>PayMaya</strong> - Send payment via PayMaya and upload proof
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                            <label class="form-check-label" for="bank_transfer">
                                <strong>Bank Transfer</strong> - Send payment via bank transfer and upload proof
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                            <label class="form-check-label" for="cash">
                                <strong>Cash on Pickup</strong> - Pay when you pick up your order
                            </label>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <strong>Important:</strong> For manual payments (GCash, PayMaya, Bank Transfer), you will need to upload a payment proof after placing the order.
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="/cart" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Cart
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Place Order
                    </button>
                </div>
            </form>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <h6>GCash Details:</h6>
                    <p><strong>Number:</strong> 0912-345-6789</p>
                    <p><strong>Name:</strong> MacCafe Store</p>
                    
                    <hr>
                    
                    <h6>PayMaya Details:</h6>
                    <p><strong>Number:</strong> 0912-345-6789</p>
                    <p><strong>Name:</strong> MacCafe Store</p>
                    
                    <hr>
                    
                    <h6>Bank Transfer:</h6>
                    <p><strong>Bank:</strong> BPI</p>
                    <p><strong>Account Name:</strong> MacCafe Store</p>
                    <p><strong>Account Number:</strong> 1234-5678-90</p>
                    
                    <div class="alert alert-warning mt-3">
                        <small><strong>Note:</strong> Please take a screenshot of your payment transaction as proof.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
