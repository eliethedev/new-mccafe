<?php ob_start(); ?>

<style>
/* Menu Styles */
.menu-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.box {
    margin-bottom: 40px;
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.box h2 {
    color: #ff6b35;
    font-size: 2rem;
    margin-bottom: 30px;
    text-align: center;
    font-weight: bold;
}

.menu-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.item-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.item-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.item-box img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 15px;
}

.item-box h3 {
    color: #333;
    font-size: 1.2rem;
    margin-bottom: 10px;
    font-weight: 600;
}

.item-box p {
    color: #666;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

.item-box label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: 500;
}

.item-box select,
.item-box input[type="number"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.order-btn {
    background: #ff6b35;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s ease;
    width: 100%;
}

.order-btn:hover {
    background: #e55a2b;
}

.coffee-choice {
    margin-top: 15px;
    padding: 15px;
    background: #fff3e0;
    border-radius: 5px;
    border: 1px solid #ffe0b2;
}

.coffee-choice h3 {
    color: #ff6b35;
    font-size: 1rem;
    margin-bottom: 10px;
}

.coffee-options {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.coffee-options label {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    margin-bottom: 0;
}

.coffee-options input[type="radio"] {
    margin-right: 5px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-details {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .box {
        padding: 15px;
        margin-bottom: 30px;
    }
    
    .box h2 {
        font-size: 1.5rem;
    }
    
    .coffee-options {
        flex-direction: column;
        gap: 10px;
    }
}

/* Cart Badge */
.cartAmount {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

/* Navigation Menu Active State */
.nav-link.home-active {
    color: #ff6b35 !important;
    font-weight: bold;
}

/* Section spacing */
section.menu {
    margin-bottom: 40px;
}

/* Authentication Modal Styles */
#authModal .modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

#authModal .modal-header {
    background: linear-gradient(135deg, #ff6b35, #ff8f65);
    color: white;
    border-radius: 15px 15px 0 0;
}

#authModal .modal-body {
    padding: 2rem;
}

#authModal .btn-primary {
    background: #ff6b35;
    border-color: #ff6b35;
    transition: all 0.3s ease;
}

#authModal .btn-primary:hover {
    background: #e55a2b;
    border-color: #e55a2b;
    transform: translateY(-2px);
}

#authModal .btn-outline-primary {
    border-color: #ff6b35;
    color: #ff6b35;
    transition: all 0.3s ease;
}

#authModal .btn-outline-primary:hover {
    background: #ff6b35;
    border-color: #ff6b35;
    color: white;
    transform: translateY(-2px);
}

/* Cart Animation Enhancement */
.cart-icon-pulse {
    animation: cartPulse 0.6s ease-in-out;
}

@keyframes cartPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1); }
}

/* Flying Cart Animation */
.flying-cart {
    position: fixed;
    z-index: 10000;
    pointer-events: none;
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
</style>

<div class="container-fluid py-4">
    <div class="menu-container">
        
        <!-- Quick Navigation -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Quick Navigation</h5>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="#menu1" class="btn btn-outline-primary btn-sm">Coffee</a>
                            <a href="#menu2" class="btn btn-outline-primary btn-sm">Coffee Frappe</a>
                            <a href="#menu3" class="btn btn-outline-primary btn-sm">Milk Tea</a>
                            <a href="#menu4" class="btn btn-outline-primary btn-sm">Fruit Tea</a>
                            <a href="#menu5" class="btn btn-outline-primary btn-sm">Non Coffee</a>
                            <a href="#menu6" class="btn btn-outline-primary btn-sm">Non Coffee Frappe</a>
                            <a href="#menu7" class="btn btn-outline-primary btn-sm">Snacks</a>
                            <a href="#menu8" class="btn btn-outline-primary btn-sm">Rice Bowl</a>
                            <a href="#menu9" class="btn btn-outline-primary btn-sm">Pasta</a>
                            <a href="#menu10" class="btn btn-outline-primary btn-sm">2PC Chicken</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Coffee Section -->
        <section class="box" id="menu1">
            <h2>Coffee</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/americano.webp" alt="Americano">
                    <h3>Americano</h3>
                    <p>Price: ₱55 12oz ┃ ₱65 16oz ┃ ₱85 22oz</p>
                    <label for="size-americano">Choose Size:</label>
                    <select id="size-americano">
                        <option value="55">12 oz - ₱55</option>
                        <option value="65">16 oz - ₱65</option>
                        <option value="85">22 oz - ₱85</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-americano">
                    <div class="d-grid gap-2">
                        <button class="order-btn" onclick="addToCart('Americano', document.getElementById('quantity-americano').value, document.getElementById('size-americano').value, event)">Order</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="showProductModal('Americano', 'assets/images/products/americano.webp', 'Classic Americano coffee made with espresso and hot water. Available in multiple sizes.', 'Coffee', '5min', 55)">View</button>
                    </div>
                    <section class="coffee-choice">
                        <h3>Would you like your coffee Hot or Iced?</h3>
                        <div class="coffee-options">
                            <label for="hot-americano">
                                <input type="radio" id="hot-americano" name="coffee-type-americano" value="hot">
                                Hot Coffee
                            </label>
                            <label for="iced-americano">
                                <input type="radio" id="iced-americano" name="coffee-type-americano" value="iced">
                                Iced Coffee
                            </label>
                        </div>
                    </section>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/spanish-latte.jpg" alt="Spanish Latte">
                    <h3>Spanish Latte</h3>
                    <p>Price: ₱65 12oz ┃ ₱70 16oz ┃ ₱90 22oz</p>
                    <label for="size-spanish-latte">Choose Size:</label>
                    <select id="size-spanish-latte">
                        <option value="65">12 oz - ₱65</option>
                        <option value="70">16 oz - ₱70</option>
                        <option value="90">22 oz - ₱90</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-spanish-latte">
                    <div class="d-grid gap-2">
                        <button class="order-btn" onclick="addToCart('Spanish Latte', document.getElementById('quantity-spanish-latte').value, document.getElementById('size-spanish-latte').value, event)">Order</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="showProductModal('Spanish Latte', 'assets/images/products/spanish-latte.jpg', 'Rich and creamy Spanish latte with condensed milk. A perfect blend of espresso and sweetened milk.', 'Coffee', '5min', 65)">View</button>
                    </div>
                    <section class="coffee-choice">
                        <h3>Would you like your coffee Hot or Iced?</h3>
                        <div class="coffee-options">
                            <label for="hot-spanish-latte">
                                <input type="radio" id="hot-spanish-latte" name="coffee-type-spanish-latte" value="hot">
                                Hot Coffee
                            </label>
                            <label for="iced-spanish-latte">
                                <input type="radio" id="iced-spanish-latte" name="coffee-type-spanish-latte" value="iced">
                                Iced Coffee
                            </label>
                        </div>
                    </section>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/matcha-espresso.jpg" alt="Matcha Espresso">
                    <h3>Matcha Espresso</h3>
                    <p>Price: ₱60 12oz ┃ ₱70 16oz ┃ ₱90 22oz</p>
                    <label for="size-matcha-espresso">Choose Size:</label>
                    <select id="size-matcha-espresso">
                        <option value="60">12 oz - ₱60</option>
                        <option value="70">16 oz - ₱70</option>
                        <option value="90">22 oz - ₱90</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-matcha-espresso">
                    <button class="order-btn" onclick="addToCart('Matcha Espresso', document.getElementById('quantity-matcha-espresso').value, document.getElementById('size-matcha-espresso').value, event)">Order</button>
                    <section class="coffee-choice">
                        <h3>Would you like your coffee Hot or Iced?</h3>
                        <div class="coffee-options">
                            <label for="hot-matcha-espresso">
                                <input type="radio" id="hot-matcha-espresso" name="coffee-type-matcha-espresso" value="hot">
                                Hot Coffee
                            </label>
                            <label for="iced-matcha-espresso">
                                <input type="radio" id="iced-matcha-espresso" name="coffee-type-matcha-espresso" value="iced">
                                Iced Coffee
                            </label>
                        </div>
                    </section>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/caramel-macchiato.jpg" alt="Caramel Macchiato">
                    <h3>Caramel Macchiato</h3>
                    <p>Price: ₱65 12oz ┃ ₱75 16oz ┃ ₱95 22oz</p>
                    <label for="size-caramel-macchiato">Choose Size:</label>
                    <select id="size-caramel-macchiato">
                        <option value="65">12 oz - ₱65</option>
                        <option value="75">16 oz - ₱75</option>
                        <option value="95">22 oz - ₱95</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-caramel-macchiato">
                    <button class="order-btn" onclick="addToCart('Caramel Macchiato', document.getElementById('quantity-caramel-macchiato').value, document.getElementById('size-caramel-macchiato').value, event)">Order</button>
                    <section class="coffee-choice">
                        <h3>Would you like your coffee Hot or Iced?</h3>
                        <div class="coffee-options">
                            <label for="hot-caramel-macchiato">
                                <input type="radio" id="hot-caramel-macchiato" name="coffee-type-caramel-macchiato" value="hot">
                                Hot Coffee
                            </label>
                            <label for="iced-caramel-macchiato">
                                <input type="radio" id="iced-caramel-macchiato" name="coffee-type-caramel-macchiato" value="iced">
                                Iced Coffee
                            </label>
                        </div>
                    </section>
                </div>
            </div>
        </section>

        <!-- Coffee Frappe Section -->
        <section class="box" id="menu2">
            <h2>Coffee Frappe</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/java-chip.jpg" alt="Java Chip">
                    <h3>Java Chip</h3>
                    <p>Price: ₱89 16oz ┃ ₱109 22oz</p>
                    <label for="size-java-chip">Choose Size:</label>
                    <select id="size-java-chip">
                        <option value="89">16 oz - ₱89</option>
                        <option value="109">22 oz - ₱109</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-java-chip">
                    <button class="order-btn" onclick="addToCart('Java Chip', document.getElementById('quantity-java-chip').value, document.getElementById('size-java-chip').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/butterscotch-frappe.jpg" alt="Butterscotch">
                    <h3>Butterscotch</h3>
                    <p>Price: ₱79 16oz ┃ ₱99 22oz</p>
                    <label for="size-butterscotch">Choose Size:</label>
                    <select id="size-butterscotch">
                        <option value="79">16 oz - ₱79</option>
                        <option value="99">22 oz - ₱99</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-butterscotch">
                    <button class="order-btn" onclick="addToCart('Butterscotch', document.getElementById('quantity-butterscotch').value, document.getElementById('size-butterscotch').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/almond-frappe.jpg" alt="Almond Frappe">
                    <h3>Almond Frappe</h3>
                    <p>Price: ₱79 16oz ┃ ₱99 22oz</p>
                    <label for="size-almond-frappe">Choose Size:</label>
                    <select id="size-almond-frappe">
                        <option value="79">16 oz - ₱79</option>
                        <option value="99">22 oz - ₱99</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-almond-frappe">
                    <button class="order-btn" onclick="addToCart('Almond Frappe', document.getElementById('quantity-almond-frappe').value, document.getElementById('size-almond-frappe').value, event)">Order</button>
                </div>
            </div>
        </section>

        <!-- Milk Tea Section -->
        <section class="box" id="menu3">
            <h2>Milk Tea</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/okinawa.jpg" alt="Okinawa">
                    <h3>Okinawa</h3>
                    <p>Price: ₱39 16oz ┃ ₱49 22oz</p>
                    <label for="size-okinawa">Choose Size:</label>
                    <select id="size-okinawa">
                        <option value="39">16 oz - ₱39</option>
                        <option value="49">22 oz - ₱49</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-okinawa">
                    <button class="order-btn" onclick="addToCart('Okinawa', document.getElementById('quantity-okinawa').value, document.getElementById('size-okinawa').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/wintermelon.jpg" alt="Wintermelon">
                    <h3>Wintermelon</h3>
                    <p>Price: ₱39 16oz ┃ ₱49 22oz</p>
                    <label for="size-wintermelon">Choose Size:</label>
                    <select id="size-wintermelon">
                        <option value="39">16 oz - ₱39</option>
                        <option value="49">22 oz - ₱49</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-wintermelon">
                    <button class="order-btn" onclick="addToCart('Wintermelon', document.getElementById('quantity-wintermelon').value, document.getElementById('size-wintermelon').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/salted-caramel.jpg" alt="Salted Caramel">
                    <h3>Salted Caramel</h3>
                    <p>Price: ₱39 16oz ┃ ₱49 22oz</p>
                    <label for="size-salted-caramel">Choose Size:</label>
                    <select id="size-salted-caramel">
                        <option value="39">16 oz - ₱39</option>
                        <option value="49">22 oz - ₱49</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-salted-caramel">
                    <button class="order-btn" onclick="addToCart('Salted Caramel', document.getElementById('quantity-salted-caramel').value, document.getElementById('size-salted-caramel').value, event)">Order</button>
                </div>
            </div>
        </section>

        <!-- Fruit Tea Section -->
        <section class="box" id="menu4">
            <h2>Fruit Tea</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/strawberry-tea.jpg" alt="Strawberry">
                    <h3>Strawberry</h3>
                    <p>Price: ₱45 16oz ┃ ₱55 22oz</p>
                    <label for="size-strawberry-tea">Choose Size:</label>
                    <select id="size-strawberry-tea">
                        <option value="45">16 oz - ₱45</option>
                        <option value="55">22 oz - ₱55</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-strawberry-tea">
                    <button class="order-btn" onclick="addToCart('Strawberry Tea', document.getElementById('quantity-strawberry-tea').value, document.getElementById('size-strawberry-tea').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/green-apple-tea.jpg" alt="Green Apple">
                    <h3>Green Apple</h3>
                    <p>Price: ₱45 16oz ┃ ₱55 22oz</p>
                    <label for="size-green-apple">Choose Size:</label>
                    <select id="size-green-apple">
                        <option value="45">16 oz - ₱45</option>
                        <option value="55">22 oz - ₱55</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-green-apple">
                    <button class="order-btn" onclick="addToCart('Green Apple Tea', document.getElementById('quantity-green-apple').value, document.getElementById('size-green-apple').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/lychee-tea.jpg" alt="Lychee">
                    <h3>Lychee</h3>
                    <p>Price: ₱45 16oz ┃ ₱55 22oz</p>
                    <label for="size-lychee">Choose Size:</label>
                    <select id="size-lychee">
                        <option value="45">16 oz - ₱45</option>
                        <option value="55">22 oz - ₱55</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-lychee">
                    <button class="order-btn" onclick="addToCart('Lychee Tea', document.getElementById('quantity-lychee').value, document.getElementById('size-lychee').value, event)">Order</button>
                </div>
            </div>
        </section>

        <!-- Non Coffee Section -->
        <section class="box" id="menu5">
            <h2>Non Coffee</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/strawberries-cream.jpg" alt="Strawberries and Cream">
                    <h3>Strawberries and Cream</h3>
                    <p>Price: ₱65 16oz ┃ ₱80 22oz</p>
                    <label for="size-strawberries-cream">Choose Size:</label>
                    <select id="size-strawberries-cream">
                        <option value="65">16 oz - ₱65</option>
                        <option value="80">22 oz - ₱80</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-strawberries-cream">
                    <button class="order-btn" onclick="addToCart('Strawberries and Cream', document.getElementById('quantity-strawberries-cream').value, document.getElementById('size-strawberries-cream').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/matcha-milk.jpg" alt="Matcha Milk">
                    <h3>Matcha Milk</h3>
                    <p>Price: ₱70 16oz ┃ ₱85 22oz</p>
                    <label for="size-matcha-milk">Choose Size:</label>
                    <select id="size-matcha-milk">
                        <option value="70">16 oz - ₱70</option>
                        <option value="85">22 oz - ₱85</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-matcha-milk">
                    <button class="order-btn" onclick="addToCart('Matcha Milk', document.getElementById('quantity-matcha-milk').value, document.getElementById('size-matcha-milk').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/iced-chocolate.jpg" alt="Iced Chocolate">
                    <h3>Iced Chocolate</h3>
                    <p>Price: ₱65 16oz ┃ ₱80 22oz</p>
                    <label for="size-iced-chocolate">Choose Size:</label>
                    <select id="size-iced-chocolate">
                        <option value="65">16 oz - ₱65</option>
                        <option value="80">22 oz - ₱80</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-iced-chocolate">
                    <button class="order-btn" onclick="addToCart('Iced Chocolate', document.getElementById('quantity-iced-chocolate').value, document.getElementById('size-iced-chocolate').value, event)">Order</button>
                </div>
            </div>
        </section>

        <!-- Non Coffee Frappe Section -->
        <section class="box" id="menu6">
            <h2>Non Coffee Frappe</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/dark-choco.jpg" alt="Dark Choco">
                    <h3>Dark Choco</h3>
                    <p>Price: ₱69 16oz ┃ ₱89 22oz</p>
                    <label for="size-dark-choco">Choose Size:</label>
                    <select id="size-dark-choco">
                        <option value="69">16 oz - ₱69</option>
                        <option value="89">22 oz - ₱89</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-dark-choco">
                    <button class="order-btn" onclick="addToCart('Dark Choco', document.getElementById('quantity-dark-choco').value, document.getElementById('size-dark-choco').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/caramel-frappe.jpg" alt="Caramel">
                    <h3>Caramel</h3>
                    <p>Price: ₱69 16oz ┃ ₱89 22oz</p>
                    <label for="size-caramel-frappe">Choose Size:</label>
                    <select id="size-caramel-frappe">
                        <option value="69">16 oz - ₱69</option>
                        <option value="89">22 oz - ₱89</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-caramel-frappe">
                    <button class="order-btn" onclick="addToCart('Caramel Frappe', document.getElementById('quantity-caramel-frappe').value, document.getElementById('size-caramel-frappe').value, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/strawberry-frappe.jpg" alt="Strawberry">
                    <h3>Strawberry</h3>
                    <p>Price: ₱69 16oz ┃ ₱89 22oz</p>
                    <label for="size-strawberry-frappe">Choose Size:</label>
                    <select id="size-strawberry-frappe">
                        <option value="69">16 oz - ₱69</option>
                        <option value="89">22 oz - ₱89</option>
                    </select>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-strawberry-frappe">
                    <button class="order-btn" onclick="addToCart('Strawberry Frappe', document.getElementById('quantity-strawberry-frappe').value, document.getElementById('size-strawberry-frappe').value, event)">Order</button>
                </div>
            </div>
        </section>

        <!-- Snacks Section -->
        <section class="box" id="menu7">
            <h2>Snacks</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/regular-fries.jpg" alt="Regular Fries">
                    <h3>Regular Fries</h3>
                    <p>Price: ₱50</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-regular-fries">
                    <button class="order-btn" onclick="addToCart('Regular Fries', document.getElementById('quantity-regular-fries').value, 50, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/cheesy-fries.jpg" alt="Cheesy Fries">
                    <h3>Cheesy Fries</h3>
                    <p>Price: ₱79</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-cheesy-fries">
                    <button class="order-btn" onclick="addToCart('Cheesy Fries', document.getElementById('quantity-cheesy-fries').value, 79, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/nachos.jpg" alt="Nachos">
                    <h3>Nachos</h3>
                    <p>Price: ₱89</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-nachos">
                    <button class="order-btn" onclick="addToCart('Nachos', document.getElementById('quantity-nachos').value, 89, event)">Order</button>
                </div>
            </div>
        </section>

        <!-- Rice Bowl Section -->
        <section class="box" id="menu8">
            <h2>Rice Bowl</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/cheesy-hungarian.jpg" alt="Cheesy Hungarian">
                    <h3>Cheesy Hungarian</h3>
                    <p>Price: ₱89</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-cheesy-hungarian">
                    <button class="order-btn" onclick="addToCart('Cheesy Hungarian', document.getElementById('quantity-cheesy-hungarian').value, 89, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/bacon-cheese.jpg" alt="Bacon & Cheese">
                    <h3>Bacon & Cheese</h3>
                    <p>Price: ₱89</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-bacon-cheese">
                    <button class="order-btn" onclick="addToCart('Bacon & Cheese', document.getElementById('quantity-bacon-cheese').value, 89, event)">Order</button>
                </div>
            </div>
        </section>

        <!-- Pasta Section -->
        <section class="box" id="menu9">
            <h2>Pasta</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/garlic-tuna-parmesan.jpg" alt="Garlic Tuna Parmesan">
                    <h3>Garlic Tuna Parmesan</h3>
                    <p>Price: ₱109</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-garlic-tuna-parmesan">
                    <button class="order-btn" onclick="addToCart('Garlic Tuna Parmesan', document.getElementById('quantity-garlic-tuna-parmesan').value, 109, event)">Order</button>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/meaty-spaghetti.jpg" alt="Meaty Spaghetti">
                    <h3>Meaty Spaghetti</h3>
                    <p>Price: ₱109</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-meaty-spaghetti">
                    <button class="order-btn" onclick="addToCart('Meaty Spaghetti', document.getElementById('quantity-meaty-spaghetti').value, 109, event)">Order</button>
                </div>
            </div>
        </section>

        <!-- 2PC Chicken Section -->
        <section class="box" id="menu10">
            <h2>2PC Chicken With Java Rice</h2>
            <div class="menu-details">
                <div class="item-box">
                    <img src="assets/images/products/garlic-parmesan-chicken.jpg" alt="Garlic Parmesan">
                    <h3>Garlic Parmesan</h3>
                    <p>Price: ₱200</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-garlic-parmesan">
                    <div class="d-grid gap-2">
                        <button class="order-btn" onclick="addToCart('Garlic Parmesan Chicken', document.getElementById('quantity-garlic-parmesan').value, 200, event)">Order</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="showProductModal('Garlic Parmesan Chicken', 'assets/images/products/garlic-parmesan-chicken.jpg', 'Crispy 2-piece chicken served with savory garlic parmesan sauce and java rice.', '2PC Chicken', '15min', 200)">View</button>
                    </div>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/teriyaki-chicken.jpg" alt="Teriyaki">
                    <h3>Teriyaki</h3>
                    <p>Price: ₱210</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-teriyaki">
                    <div class="d-grid gap-2">
                        <button class="order-btn" onclick="addToCart('Teriyaki Chicken', document.getElementById('quantity-teriyaki').value, 210, event)">Order</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="showProductModal('Teriyaki Chicken', 'assets/images/products/teriyaki-chicken.jpg', 'Tender 2-piece chicken glazed with sweet and savory teriyaki sauce, served with java rice.', '2PC Chicken', '15min', 210)">View</button>
                    </div>
                </div>

                <div class="item-box">
                    <img src="assets/images/products/honey-butter-chicken.jpg" alt="Honey Butter">
                    <h3>Honey Butter</h3>
                    <p>Price: ₱220</p>
                    <input type="number" value="1" min="1" class="quantity-input" id="quantity-honey-butter">
                    <button class="order-btn" onclick="addToCart('Honey Butter Chicken', document.getElementById('quantity-honey-butter').value, 220, event)">Order</button>
                </div>
            </div>
        </section>

    </div>
</div>

<!-- Product Detail Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img id="modalProductImage" src="" alt="" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <h4 id="modalProductName"></h4>
                        <p id="modalProductDescription"></p>
                        <div class="mb-3">
                            <span class="badge bg-success" id="modalProductCategory"></span>
                            <span class="badge bg-info" id="modalProductTime"></span>
                        </div>
                        <div class="mb-3">
                            <label for="modalQuantity" class="form-label">Quantity:</label>
                            <input type="number" class="form-control" id="modalQuantity" value="1" min="1" max="99">
                        </div>
                        <div id="modalProductVariants"></div>
                        <h4 class="text-success" id="modalProductPrice"></h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="addModalProductToCart()">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<script src="/public/assets/js/menu-cart.js"></script>

<?php
$content = ob_get_clean();
$title = 'Menu - MacCafe';
include __DIR__ . '/../layouts/main.php';
?>
