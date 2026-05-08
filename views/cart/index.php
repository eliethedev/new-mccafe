<?php ob_start(); ?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Your Cart</h2>
                <a href="/menu" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Continue Select Order
                </a>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div id="cart-items">
                        <!-- Cart items will be loaded here via JavaScript -->
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                            <h4 class="text-muted">Your cart is empty</h4>
                            <p class="text-muted">Add some delicious items from our menu!</p>
                            <a href="/menu" class="btn btn-primary">
                                <i class="bi bi-cup-hot me-2"></i>Browse Menu
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-4" id="cart-summary" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Order Summary</h5>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span id="cart-subtotal">₱0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Delivery Fee:</span>
                                            <span id="cart-delivery">₱0.00</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total:</span>
                                            <span id="cart-total">₱0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-grid gap-2">
                                    <a href="/checkout" class="btn btn-success btn-lg">
                                        <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                                    </a>
                                    <form action="/cart/clear" method="POST" onsubmit="return confirm('Are you sure you want to clear your entire cart?')">
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash me-2"></i>Clear Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadCart() {
    fetch('/api/cart')
        .then(response => response.json())
        .then(data => {
            if (data.items && data.items.length > 0) {
                displayCartItems(data.items);
                updateCartSummary(data);
            } else {
                showEmptyCart();
            }
        })
        .catch(error => {
            console.error('Error loading cart:', error);
            showEmptyCart();
        });
}

function displayCartItems(items) {
    const cartItems = document.getElementById('cart-items');
    const itemsHtml = items.map(item => `
        <div class="cart-item border-bottom pb-3 mb-3" data-id="${item.id}">
            <div class="row align-items-center">
                <div class="col-md-2">
                 <img src="assets/images/${item.image || 'default.jpg'}" 
                         alt="${item.name}" 
                         class="img-fluid rounded">
                </div>
                <div class="col-md-4">
                    <h5>${item.name}</h5>
                    <p class="text-muted mb-0">${item.description || ''}</p>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <form action="/cart/update" method="POST" style="display: inline;">
                            <input type="hidden" name="cart_id" value="${item.id}">
                            <input type="hidden" name="quantity" value="${item.quantity - 1}">
                            <button type="submit" class="btn btn-outline-secondary">-</button>
                        </form>
                        <input type="number" class="form-control text-center" value="${item.quantity}" min="1" readonly>
                        <form action="/cart/update" method="POST" style="display: inline;">
                            <input type="hidden" name="cart_id" value="${item.id}">
                            <input type="hidden" name="quantity" value="${item.quantity + 1}">
                            <button type="submit" class="btn btn-outline-secondary">+</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <strong>₱${item.total.toFixed(2)}</strong>
                </div>
                <div class="col-md-2 text-center">
                    <form action="/cart/remove" method="POST" style="display: inline;">
                        <input type="hidden" name="cart_id" value="${item.id}">
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this item from your cart?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    `).join('');
    
    cartItems.innerHTML = itemsHtml;
    document.getElementById('cart-summary').style.display = 'block';
}

function updateCartSummary(cart) {
    document.getElementById('cart-subtotal').textContent = `₱${cart.subtotal.toFixed(2)}`;
    document.getElementById('cart-delivery').textContent = `₱${cart.delivery_fee.toFixed(2)}`;
    document.getElementById('cart-total').textContent = `₱${cart.total.toFixed(2)}`;
}

function showEmptyCart() {
    const cartItems = document.getElementById('cart-items');
    cartItems.innerHTML = `
        <div class="text-center py-5">
            <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
            <h4 class="text-muted">Your cart is empty</h4>
            <p class="text-muted">Add some delicious items from our menu!</p>
            <a href="/menu" class="btn btn-primary">
                <i class="bi bi-cup-hot me-2"></i>Browse Menu
            </a>
        </div>
    `;
    document.getElementById('cart-summary').style.display = 'none';
}

// Remove unused JavaScript functions since we're using server-side redirects

// Load cart when page loads
document.addEventListener('DOMContentLoaded', loadCart);
</script>

<?php
$content = ob_get_clean();
$title = 'Shopping Cart - MacCafe';
include __DIR__ . '/../layouts/main.php';
?>
