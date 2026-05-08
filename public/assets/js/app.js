// MacCafe JavaScript Application

class MacCafeApp {
    constructor() {
        this.cart = [];
        this.init();
    }
    
    init() {
        this.loadCart();
        this.updateCartCount();
        this.initEventListeners();
    }
    
    initEventListeners() {
        // Cart toggle
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-cart-toggle]')) {
                this.toggleCartSidebar();
            }
        });
        
        // Add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-add-to-cart]')) {
                const productId = e.target.closest('[data-add-to-cart]').dataset.productId;
                const variantId = e.target.closest('[data-add-to-cart]').dataset.variantId || null;
                const quantity = parseInt(e.target.closest('[data-add-to-cart]').dataset.quantity || 1);
                this.addToCart(productId, variantId, quantity);
            }
        });
        
        // Remove from cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-remove-from-cart]')) {
                const productId = e.target.closest('[data-remove-from-cart]').dataset.productId;
                this.removeFromCart(productId);
            }
        });
        
        // Update quantity buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-quantity-increase]')) {
                const productId = e.target.closest('[data-quantity-increase]').dataset.productId;
                this.updateQuantity(productId, 1);
            }
            
            if (e.target.closest('[data-quantity-decrease]')) {
                const productId = e.target.closest('[data-quantity-decrease]').dataset.productId;
                this.updateQuantity(productId, -1);
            }
        });
        
        // Clear cart button
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-clear-cart]')) {
                this.clearCart();
            }
        });
    }
    
    loadCart() {
        const savedCart = localStorage.getItem('maccafe_cart');
        if (savedCart) {
            this.cart = JSON.parse(savedCart);
        }
    }
    
    saveCart() {
        localStorage.setItem('maccafe_cart', JSON.stringify(this.cart));
        this.updateCartCount();
        this.renderCart();
    }
    
    addToCart(productId, variantId = null, quantity = 1) {
        const existingItem = this.cart.find(item => 
            item.product_id == productId && item.variant_id == variantId
        );
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            // Get product details from server or from page data
            const productData = this.getProductData(productId, variantId);
            this.cart.push({
                product_id: productId,
                variant_id: variantId,
                quantity: quantity,
                name: productData.name,
                price: productData.price,
                image: productData.image
            });
        }
        
        this.saveCart();
        this.showNotification('Product added to cart!', 'success');
    }
    
    removeFromCart(productId, variantId = null) {
        this.cart = this.cart.filter(item => 
            !(item.product_id == productId && item.variant_id == variantId)
        );
        this.saveCart();
        this.showNotification('Product removed from cart!', 'info');
    }
    
    updateQuantity(productId, change) {
        const item = this.cart.find(item => item.product_id == productId);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                this.removeFromCart(productId);
            } else {
                this.saveCart();
            }
        }
    }
    
    clearCart() {
        if (confirm('Are you sure you want to clear your cart?')) {
            this.cart = [];
            this.saveCart();
            this.showNotification('Cart cleared!', 'info');
        }
    }
    
    updateCartCount() {
        const count = this.cart.reduce((total, item) => total + item.quantity, 0);
        const badge = document.getElementById('cart-count');
        if (badge) {
            badge.textContent = count;
        }
    }
    
    renderCart() {
        const cartBody = document.querySelector('.cart-sidebar-body');
        if (!cartBody) return;
        
        if (this.cart.length === 0) {
            cartBody.innerHTML = '<p class="text-center text-muted">Your cart is empty</p>';
            return;
        }
        
        let html = '';
        let total = 0;
        
        this.cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            
            html += `
                <div class="cart-item mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="${item.image || '/maccafe-mor-demo/public/assets/images/default-product.jpg'}" 
                                 alt="${item.name}" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">₱${item.price.toFixed(2)}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-outline-secondary" data-quantity-decrease data-product-id="${item.product_id}">-</button>
                            <span class="mx-3">${item.quantity}</span>
                            <button class="btn btn-sm btn-outline-secondary" data-quantity-increase data-product-id="${item.product_id}">+</button>
                            <button class="btn btn-sm btn-outline-danger ms-2" data-remove-from-cart data-product-id="${item.product_id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartBody.innerHTML = html;
        
        // Update total
        const cartFooter = document.querySelector('.cart-sidebar-footer');
        if (cartFooter) {
            cartFooter.innerHTML = `
                <div class="d-flex justify-content-between mb-3">
                    <h5>Total:</h5>
                    <h5>₱${total.toFixed(2)}</h5>
                </div>
                <a href="/maccafe-mor-demo/checkout" class="btn btn-success w-100">Proceed to Checkout</a>
                <button class="btn btn-outline-secondary w-100 mt-2" data-clear-cart>Clear Cart</button>
            `;
        }
    }
    
    toggleCartSidebar() {
        const sidebar = document.querySelector('.cart-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('show');
            if (sidebar.classList.contains('show')) {
                this.renderCart();
            }
        }
    }
    
    getProductData(productId, variantId) {
        // This should be implemented to get product data from the server
        // For now, return dummy data
        return {
            name: 'Product ' + productId,
            price: 100,
            image: '/maccafe-mor-demo/public/assets/images/default-product.jpg'
        };
    }
    
    showNotification(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
    
    // API methods
    async syncCartWithServer() {
        try {
            const response = await fetch('/maccafe-mor-demo/api/cart/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ cart: this.cart })
            });
            
            if (response.ok) {
                const data = await response.json();
                this.cart = data.cart;
                this.saveCart();
            }
        } catch (error) {
            console.error('Failed to sync cart:', error);
        }
    }
}

// Initialize the app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.macCafeApp = new MacCafeApp();
    
    // Initialize Swiper carousel for home page
    const homeSwiper = document.querySelector('.homeSwiper');
    if (homeSwiper) {
        new Swiper('.homeSwiper', {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            }
        });
    }
});

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
}

function showLoadingSpinner(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="loading-spinner"></span> Loading...';
    button.disabled = true;
    
    return () => {
        button.innerHTML = originalText;
        button.disabled = false;
    };
}

// Form validation
function validateForm(formElement) {
    const inputs = formElement.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}
