<?php ob_start(); ?>

<style>
    .cc-cart {
        --cc-ink: #111111;
        --cc-white: #ffffff;
        --cc-mist: #f3f3f0;
        --cc-gray: #74746e;
        --cc-line: #deded9;
        --cc-soft: #ecece8;
        --cc-serif: "DM Serif Display", Georgia, serif;
        --cc-sans: Manrope, Arial, sans-serif;
        min-height: 70vh;
        padding: 32px 0 64px;
        background: var(--cc-mist);
        color: var(--cc-ink);
        font-family: var(--cc-sans);
        -webkit-font-smoothing: antialiased;
    }

    .cc-cart *,
    .cc-cart *::before,
    .cc-cart *::after {
        box-sizing: border-box;
    }

    .cc-cart button,
    .cc-cart input {
        font: inherit;
    }

    .cc-cart a:focus-visible,
    .cc-cart button:focus-visible,
    .cc-cart input:focus-visible {
        outline: 3px solid #777772;
        outline-offset: 3px;
    }

    .cc-cart__inner {
        width: min(1160px, 100%);
        margin: 0 auto;
        padding: 0 26px;
    }

    .cc-cart__header {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .cc-cart__eyebrow {
        margin: 0 0 10px;
        color: var(--cc-gray);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .cc-cart__title {
        margin: 0;
        font: 400 clamp(36px, 5vw, 56px)/1 var(--cc-serif);
        letter-spacing: -.025em;
    }

    .cc-cart__subtitle {
        margin: 11px 0 0;
        color: #62625d;
        font-size: 12px;
        line-height: 1.7;
    }

    .cc-cart__continue {
        display: inline-flex;
        min-height: 44px;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 14px;
        border: 1px solid #c9c9c3;
        background: transparent;
        color: var(--cc-ink);
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        transition: background .16s ease, border-color .16s ease;
    }

    .cc-cart__continue:hover {
        border-color: var(--cc-ink);
        background: #fff;
        color: var(--cc-ink);
    }

    .cc-cart__loading,
    .cc-cart__error,
    .cc-cart__empty {
        padding: 48px 24px;
        border: 1px solid var(--cc-line);
        background: var(--cc-white);
        text-align: center;
    }

    .cc-cart__loading {
        display: flex;
        min-height: 170px;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: var(--cc-gray);
        font-size: 12px;
    }

    .cc-cart__spinner {
        width: 18px;
        height: 18px;
        border: 2px solid #d3d3cd;
        border-top-color: var(--cc-ink);
        border-radius: 50%;
        animation: cc-cart-spin .8s linear infinite;
    }

    @keyframes cc-cart-spin {
        to { transform: rotate(360deg); }
    }

    .cc-cart__error[hidden],
    .cc-cart__empty[hidden],
    .cc-cart__loading[hidden],
    .cc-cart__layout[hidden],
    .cc-cart__summary[hidden] {
        display: none !important;
    }

    .cc-cart__error h2,
    .cc-cart__empty h2 {
        margin: 0 0 8px;
        font: 400 30px/1.15 var(--cc-serif);
    }

    .cc-cart__error p,
    .cc-cart__empty p {
        max-width: 510px;
        margin: 0 auto 18px;
        color: var(--cc-gray);
        font-size: 12px;
        line-height: 1.7;
    }

    .cc-cart__layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 330px;
        align-items: start;
        gap: 22px;
    }

    .cc-cart__items-panel,
    .cc-cart__summary {
        border: 1px solid var(--cc-line);
        background: var(--cc-white);
    }

    .cc-cart__items-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 20px;
        border-bottom: 1px solid var(--cc-line);
    }

    .cc-cart__items-head h2,
    .cc-cart__summary h2 {
        margin: 0;
        font: 400 23px/1.1 var(--cc-serif);
    }

    .cc-cart__item-count {
        color: var(--cc-gray);
        font-size: 11px;
        font-weight: 700;
    }

    .cc-cart__item-list {
        padding: 0 20px;
    }

    .cc-cart__item {
        display: grid;
        grid-template-columns: 112px minmax(0, 1fr) auto;
        gap: 16px;
        align-items: center;
        padding: 18px 0;
        border-bottom: 1px solid var(--cc-line);
    }

    .cc-cart__item:last-child {
        border-bottom: 0;
    }

    .cc-cart__image {
        display: block;
        width: 112px;
        height: 104px;
        border: 1px solid #e5e5e0;
        background: var(--cc-soft);
        object-fit: cover;
    }

    .cc-cart__item-main {
        min-width: 0;
    }

    .cc-cart__item-name {
        margin: 0;
        color: var(--cc-ink);
        font-size: 14px;
        font-weight: 800;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .cc-cart__item-description {
        margin: 5px 0 0;
        color: var(--cc-gray);
        font-size: 11px;
        line-height: 1.55;
        overflow-wrap: anywhere;
    }

    .cc-cart__item-variant {
        display: inline-flex;
        margin-top: 8px;
        padding: 4px 7px;
        border: 1px solid var(--cc-line);
        color: #5c5c56;
        font-size: 10px;
        font-weight: 700;
    }

    .cc-cart__item-controls {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 12px;
    }

    .cc-cart__stepper {
        display: inline-flex;
        height: 34px;
        align-items: stretch;
        border: 1px solid #cecec8;
    }

    .cc-cart__stepper form {
        display: flex;
        margin: 0;
    }

    .cc-cart__stepper button {
        width: 33px;
        padding: 0;
        border: 0;
        background: #fff;
        color: var(--cc-ink);
        cursor: pointer;
        font-size: 16px;
        transition: background .15s ease;
    }

    .cc-cart__stepper button:hover:not(:disabled) {
        background: var(--cc-soft);
    }

    .cc-cart__stepper button:disabled {
        color: #a3a39d;
        cursor: not-allowed;
    }

    .cc-cart__quantity {
        display: inline-flex;
        min-width: 34px;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        border-right: 1px solid #e3e3df;
        border-left: 1px solid #e3e3df;
        color: var(--cc-ink);
        font-size: 11px;
        font-weight: 800;
    }

    .cc-cart__remove-form {
        margin: 0;
    }

    .cc-cart__remove {
        padding: 4px 0;
        border: 0;
        background: transparent;
        color: #696963;
        cursor: pointer;
        font-size: 10px;
        font-weight: 700;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .cc-cart__remove:hover {
        color: #111;
    }

    .cc-cart__line-total {
        align-self: start;
        padding-top: 4px;
        color: var(--cc-ink);
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .cc-cart__summary {
        position: sticky;
        top: var(--cafecat-header-height, 86px);
        padding: 20px;
    }

    .cc-cart__summary h2 {
        margin-bottom: 19px;
    }

    .cc-cart__summary-row {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        margin: 0 0 13px;
        color: #5d5d58;
        font-size: 12px;
    }

    .cc-cart__summary-row span:last-child {
        color: var(--cc-ink);
        font-weight: 700;
        white-space: nowrap;
    }

    .cc-cart__summary-divider {
        margin: 17px 0;
        border: 0;
        border-top: 1px solid var(--cc-line);
    }

    .cc-cart__summary-total {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
        color: var(--cc-ink);
        font-size: 14px;
        font-weight: 800;
    }

    .cc-cart__summary-total strong {
        font-size: 19px;
        white-space: nowrap;
    }

    .cc-cart__checkout {
        display: inline-flex;
        width: 100%;
        min-height: 48px;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border: 1px solid var(--cc-ink);
        background: var(--cc-ink);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        transition: background .16s ease, transform .16s ease;
    }

    .cc-cart__checkout:hover {
        background: #383835;
        color: #fff;
    }

    .cc-cart__checkout:active {
        transform: scale(.99);
    }

    .cc-cart__clear-form {
        margin: 12px 0 0;
    }

    .cc-cart__clear {
        width: 100%;
        min-height: 42px;
        border: 1px solid #c9c9c3;
        background: #fff;
        color: #555550;
        cursor: pointer;
        font-size: 11px;
        font-weight: 800;
        transition: border-color .16s ease, color .16s ease;
    }

    .cc-cart__clear:hover {
        border-color: #7c7c75;
        color: var(--cc-ink);
    }

    .cc-cart__empty-icon {
        width: 44px;
        height: 44px;
        margin: 0 auto 16px;
        color: #777772;
    }

    .cc-cart__empty .cc-cart__continue-empty,
    .cc-cart__error button {
        display: inline-flex;
        min-height: 43px;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 15px;
        border: 1px solid var(--cc-ink);
        background: var(--cc-ink);
        color: #fff;
        cursor: pointer;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
    }

    .cc-cart__error button {
        background: #fff;
        color: var(--cc-ink);
    }

    .cc-cart__error button:hover {
        background: var(--cc-soft);
    }

    @media (max-width: 900px) {
        .cc-cart__layout {
            grid-template-columns: minmax(0, 1fr) 290px;
            gap: 14px;
        }

        .cc-cart__item {
            grid-template-columns: 92px minmax(0, 1fr) auto;
            gap: 12px;
        }

        .cc-cart__image {
            width: 92px;
            height: 92px;
        }
    }

    @media (max-width: 720px) {
        .cc-cart {
            padding-top: 22px;
        }

        .cc-cart__inner {
            padding: 0 16px;
        }

        .cc-cart__header {
            align-items: flex-start;
            flex-direction: column;
            gap: 17px;
            margin-bottom: 22px;
        }

        .cc-cart__continue {
            min-height: 41px;
        }

        .cc-cart__layout {
            grid-template-columns: 1fr;
        }

        .cc-cart__summary {
            position: static;
            order: -1;
        }

        .cc-cart__item {
            grid-template-columns: 82px minmax(0, 1fr);
            align-items: start;
        }

        .cc-cart__image {
            width: 82px;
            height: 82px;
        }

        .cc-cart__line-total {
            grid-column: 2;
            grid-row: 2;
            padding-top: 0;
        }

        .cc-cart__item-controls {
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .cc-cart__items-head,
        .cc-cart__item-list {
            padding-right: 14px;
            padding-left: 14px;
        }
    }

    @media (max-width: 390px) {
        .cc-cart__inner {
            padding: 0 12px;
        }

        .cc-cart__item {
            grid-template-columns: 70px minmax(0, 1fr);
            gap: 10px;
        }

        .cc-cart__image {
            width: 70px;
            height: 70px;
        }

        .cc-cart__summary {
            padding: 16px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .cc-cart *,
        .cc-cart *::before,
        .cc-cart *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            transition-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>

<div class="cc-cart mt-5" id="customerCart">
    <div class="cc-cart__inner">
        <header class="cc-cart__header">
            <div>
                <p class="cc-cart__eyebrow">CafeCat · Your order</p>
                <h1 class="cc-cart__title">Your cart</h1>
                <p class="cc-cart__subtitle">Review your items and quantities before you continue.</p>
            </div>
            <a href="/menu" class="cc-cart__continue">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M19 12H5m7 7-7-7 7-7"></path></svg>
                Continue browsing
            </a>
        </header>

        <div class="cc-cart__loading" id="cart-loading" role="status" aria-live="polite">
            <span class="cc-cart__spinner" aria-hidden="true"></span>
            <span>Loading your cart…</span>
        </div>

        <section class="cc-cart__error" id="cart-error" role="alert" hidden>
            <h2>We couldn’t load your cart</h2>
            <p>Your items haven’t been removed. Check your connection and try loading the cart again.</p>
            <button type="button" id="cart-retry">Try again</button>
        </section>

        <section class="cc-cart__empty" id="cart-empty" aria-labelledby="cart-empty-title" hidden>
            <svg class="cc-cart__empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 1.9-1.4L21 8H6"></path><circle cx="10" cy="20" r="1"></circle><circle cx="18" cy="20" r="1"></circle><path d="M10 8v4m4-4v4m4-4v4"></path></svg>
            <h2 id="cart-empty-title">Your cart is empty</h2>
            <p>There’s nothing in your order yet. Browse the menu and add something you’d love.</p>
            <a href="/menu" class="cc-cart__continue-empty">Browse the menu</a>
        </section>

        <div class="cc-cart__layout" id="cart-content" hidden>
            <section class="cc-cart__items-panel" aria-labelledby="cart-items-heading">
                <div class="cc-cart__items-head">
                    <h2 id="cart-items-heading">Your items</h2>
                    <span class="cc-cart__item-count" id="cart-item-count" aria-live="polite"></span>
                </div>
                <div class="cc-cart__item-list" id="cart-items" aria-live="polite" aria-busy="false"></div>
            </section>

            <aside class="cc-cart__summary" id="cart-summary" aria-labelledby="cart-summary-heading" hidden>
                <h2 id="cart-summary-heading">Order summary</h2>
                <div class="cc-cart__summary-row">
                    <span>Subtotal</span>
                    <span id="cart-subtotal">₱0.00</span>
                </div>
                <div class="cc-cart__summary-row">
                    <span>Delivery fee</span>
                    <span id="cart-delivery">₱0.00</span>
                </div>
                <hr class="cc-cart__summary-divider">
                <div class="cc-cart__summary-total">
                    <span>Total</span>
                    <strong id="cart-total">₱0.00</strong>
                </div>
                <a href="/checkout" class="cc-cart__checkout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M3 10h18"></path></svg>
                    Proceed to checkout
                </a>
                <form action="/cart/clear" method="POST" class="cc-cart__clear-form" id="clearCartForm">
                    <button type="submit" class="cc-cart__clear">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 7h16m-10 4v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3"></path></svg>
                        Clear cart
                    </button>
                </form>
            </aside>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const loading = document.getElementById('cart-loading');
    const errorPanel = document.getElementById('cart-error');
    const emptyPanel = document.getElementById('cart-empty');
    const cartContent = document.getElementById('cart-content');
    const cartItems = document.getElementById('cart-items');
    const cartSummary = document.getElementById('cart-summary');
    const itemCount = document.getElementById('cart-item-count');

    function amount(value) {
        const number = Number(value);
        const safeNumber = Number.isFinite(number) ? number : 0;
        return '₱' + safeNumber.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function normalizedQuantity(value) {
        const number = Number.parseInt(value, 10);
        return Number.isFinite(number) ? Math.max(1, number) : 1;
    }

    function safeImagePath(image) {
        let path = String(image || 'default.jpg').replace(/\\/g, '/').replace(/^\/+/, '');
        path = path.replace(/^assets\/images\//i, '');
        const parts = path.split('/').filter(function (part) {
            return part && part !== '.' && part !== '..';
        }).map(function (part) {
            return encodeURIComponent(part);
        });
        return '/assets/images/' + (parts.length ? parts.join('/') : 'default.jpg');
    }

    function hiddenInput(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = String(value);
        return input;
    }

    function makeUpdateForm(item, quantity, label, disabled) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/cart/update';
        form.className = 'cc-cart__stepper-form';
        const button = document.createElement('button');
        button.type = 'submit';
        button.disabled = Boolean(disabled);
        button.setAttribute('aria-label', label);
        button.textContent = label.startsWith('Increase') ? '+' : '−';
        form.append(hiddenInput('cart_id', item.id), hiddenInput('quantity', quantity), button);
        return form;
    }

    function makeRemoveForm(item) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/cart/remove';
        form.className = 'cc-cart__remove-form';
        form.appendChild(hiddenInput('cart_id', item.id));
        const button = document.createElement('button');
        button.type = 'submit';
        button.className = 'cc-cart__remove';
        button.textContent = 'Remove';
        button.setAttribute('aria-label', 'Remove ' + String(item.name || 'item') + ' from cart');
        form.addEventListener('submit', function (event) {
            if (!window.confirm('Remove ' + String(item.name || 'this item') + ' from your cart?')) {
                event.preventDefault();
            }
        });
        form.appendChild(button);
        return form;
    }

    function renderItem(item) {
        if (!item || item.id === undefined || item.id === null) {
            throw new Error('Cart item is missing its identifier.');
        }
        const quantity = normalizedQuantity(item.quantity);
        const card = document.createElement('article');
        card.className = 'cc-cart__item';
        card.dataset.id = String(item.id);

        const image = document.createElement('img');
        image.className = 'cc-cart__image';
        image.src = safeImagePath(item.image);
        image.alt = String(item.name || 'CafeCat menu item');
        image.loading = 'lazy';
        image.onerror = function () {
            if (image.dataset.fallbackTried !== '1') {
                image.dataset.fallbackTried = '1';
                image.src = '/assets/images/logo.jpg';
            } else {
                image.onerror = null;
            }
        };

        const main = document.createElement('div');
        main.className = 'cc-cart__item-main';
        const name = document.createElement('h3');
        name.className = 'cc-cart__item-name';
        name.textContent = String(item.name || 'CafeCat menu item');
        main.appendChild(name);

        const descriptionText = String(item.description || '').trim();
        if (descriptionText) {
            const description = document.createElement('p');
            description.className = 'cc-cart__item-description';
            description.textContent = descriptionText;
            main.appendChild(description);
        }
        const variantText = String(item.variant_name || item.variant || '').trim();
        if (variantText) {
            const variant = document.createElement('span');
            variant.className = 'cc-cart__item-variant';
            variant.textContent = variantText;
            main.appendChild(variant);
        }

        const controls = document.createElement('div');
        controls.className = 'cc-cart__item-controls';
        const stepper = document.createElement('div');
        stepper.className = 'cc-cart__stepper';
        stepper.setAttribute('role', 'group');
        stepper.setAttribute('aria-label', 'Quantity for ' + String(item.name || 'item'));
        stepper.appendChild(makeUpdateForm(item, quantity - 1, 'Decrease ' + String(item.name || 'item') + ' quantity', quantity <= 1));
        const quantityLabel = document.createElement('span');
        quantityLabel.className = 'cc-cart__quantity';
        quantityLabel.textContent = String(quantity);
        quantityLabel.setAttribute('aria-label', 'Quantity ' + String(quantity));
        stepper.appendChild(quantityLabel);
        stepper.appendChild(makeUpdateForm(item, quantity + 1, 'Increase ' + String(item.name || 'item') + ' quantity', false));
        controls.appendChild(stepper);
        controls.appendChild(makeRemoveForm(item));
        main.appendChild(controls);

        const lineTotal = document.createElement('strong');
        lineTotal.className = 'cc-cart__line-total';
        const calculatedTotal = Number(item.total);
        const fallbackTotal = Number(item.price || item.unit_price || 0) * quantity;
        lineTotal.textContent = amount(Number.isFinite(calculatedTotal) ? calculatedTotal : fallbackTotal);

        card.append(image, main, lineTotal);
        return card;
    }

    function displayCartItems(items) {
        const fragment = document.createDocumentFragment();
        items.forEach(function (item) {
            fragment.appendChild(renderItem(item));
        });
        cartItems.replaceChildren(fragment);
        const totalUnits = items.reduce(function (sum, item) {
            return sum + normalizedQuantity(item.quantity);
        }, 0);
        itemCount.textContent = totalUnits + ' ' + (totalUnits === 1 ? 'item' : 'items');
    }

    function updateCartSummary(cart) {
        const items = Array.isArray(cart.items) ? cart.items : [];
        const calculatedSubtotal = items.reduce(function (sum, item) {
            const line = Number(item.total);
            const fallback = Number(item.price || item.unit_price || 0) * normalizedQuantity(item.quantity);
            return sum + (Number.isFinite(line) ? line : fallback);
        }, 0);
        const subtotal = Number(cart.subtotal);
        const delivery = Number(cart.delivery_fee);
        const total = Number(cart.total);
        document.getElementById('cart-subtotal').textContent = amount(Number.isFinite(subtotal) ? subtotal : calculatedSubtotal);
        document.getElementById('cart-delivery').textContent = amount(Number.isFinite(delivery) ? delivery : 0);
        document.getElementById('cart-total').textContent = amount(Number.isFinite(total) ? total : (Number.isFinite(subtotal) ? subtotal : calculatedSubtotal) + (Number.isFinite(delivery) ? delivery : 0));
    }

    function showEmptyCart() {
        loading.hidden = true;
        errorPanel.hidden = true;
        cartContent.hidden = true;
        emptyPanel.hidden = false;
        cartSummary.hidden = true;
        cartItems.replaceChildren();
    }

    function showCartError() {
        loading.hidden = true;
        emptyPanel.hidden = true;
        cartContent.hidden = true;
        errorPanel.hidden = false;
    }

    async function loadCart() {
        loading.hidden = false;
        errorPanel.hidden = true;
        emptyPanel.hidden = true;
        cartContent.hidden = true;

        try {
            const response = await fetch('/api/cart', {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('Cart request failed with status ' + response.status);
            const data = await response.json();
            if (!data || !Array.isArray(data.items)) throw new Error('Cart response is not in the expected format.');

            if (data.items.length === 0) {
                showEmptyCart();
                return;
            }

            displayCartItems(data.items);
            updateCartSummary(data);
            loading.hidden = true;
            errorPanel.hidden = true;
            emptyPanel.hidden = true;
            cartContent.hidden = false;
            cartSummary.hidden = false;
        } catch (error) {
            console.error('Error loading cart:', error);
            showCartError();
        }
    }

    document.getElementById('cart-retry').addEventListener('click', loadCart);
    document.getElementById('clearCartForm').addEventListener('submit', function (event) {
        if (!window.confirm('Clear all items from your cart?')) event.preventDefault();
    });

    window.loadCart = loadCart;
    window.displayCartItems = displayCartItems;
    window.updateCartSummary = updateCartSummary;
    window.showEmptyCart = showEmptyCart;
    document.addEventListener('DOMContentLoaded', loadCart);
})();
</script>

<?php
$content = ob_get_clean();
$title = 'Shopping Cart - CafeCat';
include __DIR__ . '/../layouts/main.php';
?>
