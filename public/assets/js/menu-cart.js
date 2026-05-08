// Menu Cart Integration
class MenuCart {
    constructor() {
        this.cart = [];
        this.init();
    }

    init() {
        // Load cart from localStorage if exists
        const savedCart = localStorage.getItem('mccafe_cart');
        if (savedCart) {
            this.cart = JSON.parse(savedCart);
        }
        this.updateCartDisplay();
    }

    addToCart(productName, quantity, price, coffeeType = '', button = null) {
        // Check authentication first
        if (!this.checkAuthentication()) {
            this.showAuthModal();
            return false;
        }

        // Create animation if button is provided
        if (button) {
            this.createCartAnimation(button);
        }

        const item = {
            id: Date.now(), // Simple ID for now
            name: productName,
            quantity: parseInt(quantity),
            price: parseFloat(price),
            type: coffeeType,
            total: parseFloat(price) * parseInt(quantity)
        };

        this.cart.push(item);
        this.saveCart();
        this.updateCartDisplay();
        
        // Show success notification
        const displayName = coffeeType ? `${productName} (${coffeeType})` : productName;
        this.showNotification(`${displayName} added to cart!`, 'success');
        
        return true;
    }

    removeFromCart(itemId) {
        this.cart = this.cart.filter(item => item.id !== itemId);
        this.saveCart();
        this.updateCartDisplay();
    }

    updateQuantity(itemId, newQuantity) {
        const item = this.cart.find(item => item.id === itemId);
        if (item) {
            item.quantity = parseInt(newQuantity);
            item.total = item.price * item.quantity;
            this.saveCart();
            this.updateCartDisplay();
        }
    }

    getTotalItems() {
        return this.cart.reduce((total, item) => total + item.quantity, 0);
    }

    getTotalPrice() {
        return this.cart.reduce((total, item) => total + item.total, 0);
    }

    saveCart() {
        localStorage.setItem('mccafe_cart', JSON.stringify(this.cart));
    }

    updateCartDisplay() {
        const cartCount = document.getElementById('cart-count');
        if (cartCount) {
            cartCount.textContent = this.getTotalItems();
        }
    }

    async updateCartCount() {
        try {
            const response = await fetch('/api/cart');
            const data = await response.json();
            
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                const totalItems = data.items ? data.items.reduce((sum, item) => sum + item.quantity, 0) : 0;
                cartCount.textContent = totalItems;
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }

    showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

    checkAuthentication() {
        // Check if user is logged in by looking for user session indicator
        const userDropdown = document.querySelector('[data-user-logged-in="true"]');
        const userNav = document.querySelector('a[href="/dashboard"]');
        
        return userDropdown || userNav || document.body.innerHTML.includes('dashboard');
    }

    showAuthModal() {
        // Create authentication modal
        const modalHtml = `
            <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="authModalLabel">
                                <i class="bi bi-lock-fill me-2"></i>Login Required
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-4">
                                <i class="bi bi-cart-x" style="font-size: 4rem; color: #ff6b35;"></i>
                            </div>
                            <h5>Please login to add items to cart</h5>
                            <p class="text-muted">You need to be logged in to add items to your cart and place orders.</p>
                            
                            <div class="d-grid gap-2 mt-4">
                                <a href="/login" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </a>
                                <a href="/register" class="btn btn-outline-primary">
                                    <i class="bi bi-person-plus me-2"></i>Create Account
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Browsing</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if present
        const existingModal = document.getElementById('authModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('authModal'));
        modal.show();
    }

    createCartAnimation(button) {
        // Create flying cart animation
        const cartIcon = document.querySelector('a[href="/cart"] i');
        if (!cartIcon) return;

        const flyingCart = document.createElement('div');
        flyingCart.innerHTML = '<i class="bi bi-cart-fill" style="color: #ff6b35; font-size: 1.5rem;"></i>';
        flyingCart.style.position = 'fixed';
        flyingCart.style.zIndex = '10000';
        flyingCart.style.pointerEvents = 'none';
        flyingCart.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';

        // Get button position
        const buttonRect = button.getBoundingClientRect();
        flyingCart.style.left = buttonRect.left + buttonRect.width / 2 + 'px';
        flyingCart.style.top = buttonRect.top + buttonRect.height / 2 + 'px';

        document.body.appendChild(flyingCart);

        // Get cart icon position
        const cartRect = cartIcon.getBoundingClientRect();

        // Animate to cart
        setTimeout(() => {
            flyingCart.style.left = cartRect.left + cartRect.width / 2 + 'px';
            flyingCart.style.top = cartRect.top + cartRect.height / 2 + 'px';
            flyingCart.style.transform = 'scale(0.5)';
            flyingCart.style.opacity = '0';
        }, 10);

        // Remove animation element
        setTimeout(() => {
            if (flyingCart.parentNode) {
                flyingCart.remove();
            }
        }, 800);

        // Pulse cart icon
        cartIcon.style.transform = 'scale(1.3)';
        cartIcon.style.transition = 'transform 0.3s ease';
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1)';
        }, 300);
    }

    // Sync with server cart
    async syncWithServer() {
        try {
            const response = await fetch('/api/cart/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ cart: this.cart })
            });
            
            if (response.ok) {
                const data = await response.json();
                // Update local cart with server response
                this.cart = data.cart || [];
                this.saveCart();
                this.updateCartDisplay();
            }
        } catch (error) {
            console.error('Error syncing cart with server:', error);
        }
    }

    clearCart() {
        this.cart = [];
        this.saveCart();
        this.updateCartDisplay();
    }
}

// Global cart instance
const menuCart = new MenuCart();

// Product name to ID mapping (temporary solution)
const productMapping = {
    'Americano': 1,
    'Spanish Latte': 2,
    'Matcha Espresso': 3,
    'Caramel Macchiato': 4,
    'Java Chip': 5,
    'Butterscotch': 6,
    'Almond Frappe': 7,
    'Okinawa': 8,
    'Wintermelon': 9,
    'Green Apple Tea': 10,
    'Lychee Tea': 11,
    'Strawberries and Cream': 12,
    'Matcha Milk': 13,
    'Garlic Parmesan Chicken': 14,
    'Teriyaki Chicken': 15,
    'Honey Butter Chicken': 16
};

// Global function for onclick handlers
async function addToCart(productName, quantity, price, event) {
    // Get the button that was clicked
    const button = event ? event.currentTarget : null;
    
    // Check authentication first
    if (!menuCart.checkAuthentication()) {
        menuCart.showAuthModal();
        return false;
    }

    // Get product ID from mapping
    const productId = productMapping[productName];
    if (!productId) {
        menuCart.showNotification('Product not found', 'error');
        return false;
    }

    // Create animation if button is provided
    if (button) {
        menuCart.createCartAnimation(button);
    }

    try {
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        });

        const data = await response.json();
        
        if (data.success) {
            // Update cart count display
            menuCart.updateCartCount();
            
            // Get coffee type if applicable for notification
            let coffeeType = '';
            const radios = document.querySelectorAll    (`input[name^="coffee-type"]:checked`);
            if (radios.length > 0) {
                coffeeType = radios[radios.length - 1].value;
            }
            
            const displayName = coffeeType ? `${productName} (${coffeeType})` : productName;
            menuCart.showNotification(`${displayName} added to cart!`, 'success');
            
            return true;
        } else {
            menuCart.showNotification(data.message || 'Failed to add to cart', 'error');
            return false;
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        menuCart.showNotification('Error adding to cart', 'error');
        return false;
    }
}

// Smooth scroll to menu sections
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Load cart count from API on page load
    menuCart.updateCartCount();
});

// Modal functionality
let currentModalProduct = null;

function showProductModal(name, image, description, category, time, basePrice) {
    currentModalProduct = {
        name: name,
        image: image,
        description: description,
        category: category,
        time: time,
        basePrice: basePrice
    };
    
    // Populate modal
    document.getElementById('modalProductName').textContent = name;
    document.getElementById('modalProductImage').src = image;
    document.getElementById('modalProductImage').alt = name;
    document.getElementById('modalProductDescription').textContent = description;
    document.getElementById('modalProductCategory').textContent = category;
    document.getElementById('modalProductTime').textContent = time;
    document.getElementById('modalProductPrice').textContent = `₱${basePrice}`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
}

async function addModalProductToCart() {
    if (!currentModalProduct) return;
    
    // Check authentication first
    if (!menuCart.checkAuthentication()) {
        menuCart.showAuthModal();
        return;
    }
    
    const quantity = parseInt(document.getElementById('modalQuantity').value);
    const sizeSelect = document.getElementById('modalSizeSelect');
    const price = sizeSelect ? parseFloat(sizeSelect.value) : currentModalProduct.basePrice;
    
    // Get the add to cart button in modal for animation
    const modalButton = document.querySelector('#productModal .btn-success');
    
    // Create animation
    if (modalButton) {
        menuCart.createCartAnimation(modalButton);
    }

    try {
        // Get product ID from mapping
        const productId = productMapping[currentModalProduct.name];
        if (!productId) {
            menuCart.showNotification('Product not found', 'error');
            return;
        }

        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        });

        const data = await response.json();
        
        if (data.success) {
            // Update cart count display
            await menuCart.updateCartCount();
            
            menuCart.showNotification(`${currentModalProduct.name} added to cart!`, 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
            modal.hide();
        } else {
            menuCart.showNotification(data.message || 'Failed to add to cart', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        menuCart.showNotification('Error adding to cart', 'error');
    }
}

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MenuCart;
}
