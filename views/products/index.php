<?php
ob_start();

$menuCategories = isset($menuCategories) && is_array($menuCategories) ? $menuCategories : [];


$escape = static function ($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};

$jsValue = static function ($value): string {
    return htmlspecialchars(
        json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
};

$categoryNavLabels = [
    'menu10' => '2PC Chicken',
];
?>

<style>
    .cc-menu {
        --cc-ink: #111111;
        --cc-paper: #ffffff;
        --cc-canvas: #f4f4f1;
        --cc-muted: #73736e;
        --cc-line: #deded9;
        --cc-soft: #eeeeeb;
        --cc-serif: "DM Serif Display", Georgia, serif;
        --cc-sans: Manrope, Arial, sans-serif;
        color: var(--cc-ink);
        font-family: var(--cc-sans);
        background: var(--cc-canvas);
        margin: 0;
        padding: 0 0 56px;
        -webkit-font-smoothing: antialiased;
    }

    .cc-menu *,
    .cc-menu *::before,
    .cc-menu *::after {
        box-sizing: border-box;
    }

    .cc-menu button,
    .cc-menu input,
    .cc-menu select {
        font: inherit;
    }

    .cc-menu button:focus-visible,
    .cc-menu a:focus-visible,
    .cc-menu input:focus-visible,
    .cc-menu select:focus-visible {
        outline: 3px solid #777772;
        outline-offset: 3px;
    }

    .cc-menu__intro {
        max-width: 1320px;
        margin: 0 auto;
        padding: 50px 28px 30px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(245px, 380px);
        gap: 38px;
        align-items: end;
    }

    .cc-menu__eyebrow {
        margin: 0 0 14px;
        color: var(--cc-muted);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .cc-menu__title {
        max-width: 740px;
        margin: 0;
        font: 400 clamp(40px, 5.7vw, 72px)/.98 var(--cc-serif);
        letter-spacing: -.035em;
    }

    .cc-menu__lead {
        max-width: 620px;
        margin: 17px 0 0;
        color: #5f5f5a;
        font-size: 14px;
        line-height: 1.75;
    }

    .cc-menu__aside {
        padding: 4px 0 4px 20px;
        border-left: 1px solid var(--cc-line);
        color: var(--cc-muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .cc-menu__aside strong {
        display: block;
        margin-bottom: 5px;
        color: var(--cc-ink);
        font-size: 13px;
    }

    .cc-menu__tools {
        max-width: 1320px;
        margin: 0 auto;
        padding: 0 28px 20px;
    }

    .cc-menu__search-wrap {
        position: relative;
        max-width: 560px;
    }

    .cc-menu__search-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        width: 18px;
        height: 18px;
        color: #777772;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .cc-menu__search {
        display: block;
        width: 100%;
        height: 50px;
        padding: 0 44px 0 44px;
        border: 1px solid #cacac4;
        border-radius: 2px;
        background: var(--cc-paper);
        color: var(--cc-ink);
        font-size: 14px;
    }

    .cc-menu__search::placeholder {
        color: #888883;
    }

    .cc-menu__clear-search {
        position: absolute;
        top: 8px;
        right: 8px;
        display: none;
        width: 34px;
        height: 34px;
        padding: 0;
        border: 0;
        background: transparent;
        color: #555550;
        cursor: pointer;
        font-size: 22px;
        line-height: 1;
    }

    .cc-menu__search-wrap.has-query .cc-menu__clear-search {
        display: block;
    }

    .cc-menu__search-hint {
        margin: 8px 0 0;
        color: var(--cc-muted);
        font-size: 11px;
    }

    .cc-menu__category-bar {
        position: sticky;
        top: 0;
        z-index: 15;
        border-top: 1px solid var(--cc-line);
        border-bottom: 1px solid var(--cc-line);
        background: rgba(244, 244, 241, .96);
        backdrop-filter: blur(14px);
    }

    .cc-menu__category-scroll {
        display: flex;
        max-width: 1320px;
        gap: 8px;
        margin: 0 auto;
        padding: 10px 28px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .cc-menu__category-scroll::-webkit-scrollbar {
        display: none;
    }

    .cc-menu__category-link {
        flex: 0 0 auto;
        padding: 10px 13px;
        border: 1px solid transparent;
        color: #666660;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: color .16s ease, border-color .16s ease, background .16s ease;
    }

    .cc-menu__category-link:hover {
        border-color: #c9c9c3;
        color: var(--cc-ink);
    }

    .cc-menu__category-link.is-active {
        border-color: var(--cc-ink);
        background: var(--cc-ink);
        color: #fff;
    }

    .cc-menu__category-link[hidden] {
        display: none !important;
    }

    .cc-menu__content {
        max-width: 1320px;
        margin: 0 auto;
        padding: 18px 28px 28px;
    }

    .cc-menu__section {
        padding: 32px 0 40px;
        border-bottom: 1px solid var(--cc-line);
        scroll-margin-top: 120px;
    }

    .cc-menu__section[hidden],
    .cc-menu__card[hidden] {
        display: none !important;
    }

    .cc-menu__section-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .cc-menu__section-title {
        margin: 0;
        font: 400 clamp(27px, 3vw, 38px)/1.08 var(--cc-serif);
        letter-spacing: -.02em;
    }

    .cc-menu__section-count {
        flex: 0 0 auto;
        color: var(--cc-muted);
        font-size: 11px;
        font-weight: 700;
    }

    .cc-menu__grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
    }

    .cc-menu__card {
        display: flex;
        min-width: 0;
        flex-direction: column;
        border: 1px solid var(--cc-line);
        background: var(--cc-paper);
        text-align: left;
        transition: box-shadow .2s ease, transform .2s ease, border-color .2s ease;
    }

    .cc-menu__card:hover {
        transform: translateY(-2px);
        border-color: #c8c8c2;
        box-shadow: 0 10px 30px rgba(17, 17, 17, .06);
    }

    .cc-menu__image {
        display: block;
        width: 100%;
        height: 180px;
        border-bottom: 1px solid #e3e3df;
        background: #f5f5f2;
        object-fit: contain;
        padding: 8px;
    }

    .cc-menu__image-wrap {
        position: relative;
    }

    .cc-menu__image-btn {
        position: absolute;
        bottom: 8px;
        right: 8px;
        min-height: 30px;
        padding: 0 10px;
        border: 1px solid #c7c7c1;
        background: rgba(255,255,255,.92);
        color: var(--cc-ink);
        font-size: 10px;
        font-weight: 700;
        cursor: pointer;
        backdrop-filter: blur(4px);
    }

    .cc-menu__image-btn:hover {
        background: var(--cc-ink);
        color: #fff;
    }

    .cc-menu__card-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 10px;
    }

    .cc-menu__product-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
    }

    .cc-menu__product-name {
        margin: 0;
        font-size: 13px;
        font-weight: 800;
        line-height: 1.3;
    }

    .cc-menu__from-price {
        flex: 0 0 auto;
        color: #fff;
        background: var(--cc-ink);
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
        padding: 3px 8px;
        border: 1px solid var(--cc-ink);
    }

    .cc-menu__price-options {
        min-height: 14px;
        margin: 4px 0 8px;
        color: var(--cc-muted);
        font-size: 10px;
        line-height: 1.45;
    }

    .cc-menu__field-label {
        display: block;
        margin: 0 0 4px;
        color: #4e4e49;
        font-size: 10px;
        font-weight: 800;
    }

    .cc-menu__size-select {
        display: block;
        width: 100%;
        height: 34px;
        padding: 0 8px;
        border: 1px solid #d0d0ca;
        border-radius: 2px;
        background: #fff;
        color: var(--cc-ink);
        font-size: 11px;
    }

    .cc-menu .coffee-choice {
        margin: 8px 0 0;
        padding: 0;
        border: 0;
        background: transparent;
    }

    .cc-menu .coffee-choice__legend {
        margin-bottom: 4px;
        color: #4e4e49;
        font-size: 10px;
        font-weight: 800;
    }

    .cc-menu .coffee-options {
        display: flex;
        gap: 5px;
    }

    .cc-menu .coffee-options label {
        position: relative;
        display: flex;
        flex: 1;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        margin: 0;
        padding: 5px 7px;
        border: 1px solid #d0d0ca;
        background: #fff;
        color: var(--cc-ink);
        cursor: pointer;
        font-size: 10px;
        font-weight: 700;
        transition: border-color .16s ease, background .16s ease, color .16s ease;
    }

    .cc-menu .coffee-options label:has(input:checked) {
        border-color: var(--cc-ink);
        background: var(--cc-ink);
        color: #fff;
    }

    .cc-menu .coffee-options input[type="radio"] {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        margin: 0;
    }

    .cc-menu .coffee-options label:has(input:focus-visible) {
        outline: 3px solid #777772;
        outline-offset: 2px;
    }

    .cc-menu .coffee-choice.needs-choice .coffee-options label {
        border-color: #8d8d85;
    }

    .cc-menu__quantity-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin: 8px 0 6px;
    }

    .cc-menu__quantity-label {
        color: #4e4e49;
        font-size: 10px;
        font-weight: 800;
    }

    .cc-menu__quantity-control {
        display: flex;
        height: 30px;
        border: 1px solid #d0d0ca;
    }

    .cc-menu__quantity-control button {
        width: 28px;
        padding: 0;
        border: 0;
        background: #fff;
        color: var(--cc-ink);
        cursor: pointer;
        font-size: 15px;
    }

    .cc-menu__quantity-control input.quantity-input {
        width: 34px;
        min-width: 0;
        margin: 0;
        padding: 0;
        border: 0;
        border-right: 1px solid #e3e3df;
        border-left: 1px solid #e3e3df;
        border-radius: 0;
        background: #fff;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        appearance: textfield;
        -moz-appearance: textfield;
    }

    .cc-menu__quantity-control input.quantity-input::-webkit-inner-spin-button,
    .cc-menu__quantity-control input.quantity-input::-webkit-outer-spin-button {
        margin: 0;
        -webkit-appearance: none;
    }

    .cc-menu .order-btn {
        display: flex;
        width: 100%;
        min-height: 30px;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 0;
        padding: 0 8px;
        border: 1px solid var(--cc-ink);
        border-radius: 0;
        background: var(--cc-ink);
        color: #fff;
        cursor: pointer;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .02em;
        transition: border-color .16s ease, background .16s ease, transform .16s ease;
        flex: 1 1 0;
    }

    .cc-menu .order-btn:hover {
        border-color: #363633;
        background: #363633;
    }

    .cc-menu .order-btn:active {
        transform: scale(.99);
    }

    .cc-menu__button-price {
        font-weight: 600;
        opacity: .78;
        white-space: nowrap;
    }

    .cc-menu__actions {
        display: flex;
        gap: 6px;
        margin-top: 6px;
    }

    .cc-menu__detail-btn {
        min-height: 32px;
        border: 1px solid #c7c7c1;
        border-radius: 0;
        background: #fff;
        color: var(--cc-ink);
        cursor: pointer;
        font-size: 10px;
        font-weight: 700;
        padding: 0 8px;
        flex: 0 0 auto;
    }

    .cc-menu__detail-btn:hover {
        background: var(--cc-soft);
    }

    .cc-menu__feedback {
        min-height: 0;
        margin: 4px 0 0;
        color: #555550;
        font-size: 10px;
        line-height: 1.4;
    }

    .cc-menu__empty {
        display: none;
        padding: 68px 20px;
        text-align: center;
    }

    .cc-menu__empty.is-visible {
        display: block;
    }

    .cc-menu__empty h2 {
        margin: 0 0 8px;
        font: 400 32px/1.15 var(--cc-serif);
    }

    .cc-menu__empty p {
        margin: 0 0 18px;
        color: var(--cc-muted);
        font-size: 13px;
    }

    .cc-menu__reset {
        min-height: 42px;
        padding: 0 17px;
        border: 0;
        background: var(--cc-ink);
        color: #fff;
        cursor: pointer;
        font-size: 12px;
        font-weight: 800;
    }

    .cc-visually-hidden {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    .cartAmount {
        display: inline-flex;
        min-width: 22px;
        height: 22px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--cc-ink);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
    }

    #productModal .modal-content {
        border: 1px solid var(--cc-line);
        border-radius: 2px;
        box-shadow: 0 18px 48px rgba(17, 17, 17, .18);
    }

    #productModal .modal-header,
    #productModal .modal-footer {
        border-color: var(--cc-line);
    }

    #productModal .modal-title,
    #productModal #modalProductName {
        font-family: var(--cc-serif);
        font-weight: 400;
    }

    #productModal #modalProductPrice {
        color: var(--cc-ink) !important;
    }

    @media (max-width: 760px) {
        .cc-menu__intro {
            display: block;
            padding: 39px 18px 24px;
        }

        .cc-menu__title {
            max-width: 560px;
            font-size: clamp(42px, 11vw, 60px);
        }

        .cc-menu__lead {
            margin-top: 13px;
        }

        .cc-menu__aside {
            display: none;
        }

        .cc-menu__tools {
            padding: 0 18px 16px;
        }

        .cc-menu__category-scroll {
            padding: 10px 18px;
        }

        .cc-menu__content {
            padding: 8px 18px 24px;
        }

        .cc-menu__section {
            padding: 26px 0 30px;
            scroll-margin-top: 116px;
        }

        .cc-menu__section-head {
            margin-bottom: 14px;
        }

        .cc-menu__grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .cc-menu__image {
            height: 130px;
        }

        .cc-menu__card-body {
            padding: 12px;
        }

        .cc-menu__product-name {
            font-size: 13px;
        }

        .cc-menu__from-price {
            font-size: 12px;
        }

        .cc-menu__price-options {
            margin: 6px 0 12px;
            font-size: 10px;
        }

        .cc-menu__field-label,
        .cc-menu .coffee-choice__legend,
        .cc-menu__quantity-label {
            font-size: 10px;
        }

        .cc-menu__size-select {
            height: 39px;
            font-size: 11px;
        }

        .cc-menu .coffee-options label {
            min-height: 36px;
            padding: 7px 5px;
            font-size: 10px;
        }

        .cc-menu__quantity-control button {
            width: 30px;
        }

        .cc-menu__quantity-control input.quantity-input {
            width: 36px;
        }

        .cc-menu .order-btn {
            min-height: 42px;
            padding: 0 10px;
            font-size: 11px;
        }
    }

    @media (max-width: 390px) {
        .cc-menu__grid {
            grid-template-columns: 1fr;
        }

        .cc-menu__image {
            height: 178px;
        }

        .cc-menu__card-body {
            padding: 15px;
        }

        .cc-menu__product-name {
            font-size: 15px;
        }

        .cc-menu__price-options {
            font-size: 11px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .cc-menu *,
        .cc-menu *::before,
        .cc-menu *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>

<div class="cc-menu mt-5" id="customerMenu">
    <header class="cc-menu__intro">
        <div>
            <p class="cc-menu__eyebrow">The CafeCat menu</p>
            <h1 class="cc-menu__title">Find your new<br>favorite.</h1>
            <p class="cc-menu__lead">Browse something to sip, share, or settle in with. Pick your size, make it yours, and add it to your order.</p>
        </div>
        <aside class="cc-menu__aside">
            <strong>Choose at your own pace</strong>
            Search for an item or jump to a category. Your size, temperature, and quantity are clear before you add anything.
        </aside>
    </header>

    <div class="cc-menu__tools">
        <label class="cc-visually-hidden" for="customerMenuSearch">Search the menu</label>
        <div class="cc-menu__search-wrap" id="customerMenuSearchWrap">
            <svg class="cc-menu__search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <circle cx="10.8" cy="10.8" r="6.8"></circle>
                <path d="m16 16 5 5"></path>
            </svg>
            <input class="cc-menu__search" id="customerMenuSearch" type="search" placeholder="Search drinks, snacks, meals…" autocomplete="off" aria-describedby="customerMenuSearchHint">
            <button class="cc-menu__clear-search" id="customerMenuClear" type="button" aria-label="Clear search">×</button>
        </div>
        <p class="cc-menu__search-hint" id="customerMenuSearchHint">Search by item or category. Prices are shown in Philippine pesos.</p>
    </div>

    <nav class="cc-menu__category-bar" aria-label="Menu categories">
        <div class="cc-menu__category-scroll" id="customerMenuCategories">
            <?php foreach ($menuCategories as $category): ?>
                <a class="cc-menu__category-link" href="#<?= $escape($category['id']) ?>" data-target="<?= $escape($category['id']) ?>">
                    <?= $escape($categoryNavLabels[$category['id']] ?? $category['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <div class="cc-menu__content" role="region" aria-label="CafeCat menu items">
        <p class="cc-visually-hidden" id="customerMenuResultCount" aria-live="polite" aria-atomic="true"></p>
        <div id="customerMenuSections">
            <?php foreach (['Featured' => ($featuredItems ?? []), 'Best sellers' => ($bestSellerItems ?? [])] as $sectionTitle => $featuredSectionItems): ?>
                <?php if (empty($featuredSectionItems)) continue; ?>
                <section class="cc-menu__section box" id="<?= $escape(strtolower(str_replace(' ', '-', $sectionTitle))) ?>" aria-labelledby="<?= $escape(strtolower(str_replace(' ', '-', $sectionTitle))) ?>-title">
                    <div class="cc-menu__section-head">
                        <h2 class="cc-menu__section-title" id="<?= $escape(strtolower(str_replace(' ', '-', $sectionTitle))) ?>-title"><?= $escape($sectionTitle) ?></h2>
                        <span class="cc-menu__section-count" data-section-count><?= count($featuredSectionItems) ?> <?= count($featuredSectionItems) === 1 ? 'item' : 'items' ?></span>
                    </div>
                    <div class="cc-menu__grid menu-details">
                        <?php foreach ($featuredSectionItems as $item): ?>
                            <?php
                            $cartName = $item['cartCall']['name'];
                            $quantityId = $item['quantityId'];
                            $sizeId = $item['sizeId'];
                            $fixedPrice = $item['cartCall']['fixedPrice'];
                            $coffeeGroup = $item['coffeeOptions'][0]['name'] ?? '';
                            $startPrice = $item['options'][0]['value'] ?? $fixedPrice;
                            $searchText = strtolower($item['displayName'] . ' ' . $sectionTitle);
                            $visiblePrices = preg_replace('/^Price:\\s*/u', '', $item['priceText']);
                            $cardSection = $sectionTitle;
                            ?>
                            <?php include __DIR__ . '/../partials/cc-menu-card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
            <?php foreach ($menuCategories as $category): ?>
                <section class="cc-menu__section box" id="<?= $escape($category['id']) ?>" aria-labelledby="<?= $escape($category['id']) ?>-title">
                    <div class="cc-menu__section-head">
                        <h2 class="cc-menu__section-title" id="<?= $escape($category['id']) ?>-title"><?= $escape($category['name']) ?></h2>
                        <span class="cc-menu__section-count" data-section-count><?= count($category['items']) ?> <?= count($category['items']) === 1 ? 'item' : 'items' ?></span>
                    </div>
                    <div class="cc-menu__grid menu-details">
                        <?php foreach ($category['items'] as $item): ?>
                            <?php
                            $cartName = $item['cartCall']['name'];
                            $quantityId = $item['quantityId'];
                            $sizeId = $item['sizeId'];
                            $fixedPrice = $item['cartCall']['fixedPrice'];
                            $coffeeGroup = $item['coffeeOptions'][0]['name'] ?? '';
                            $startPrice = $item['options'][0]['value'] ?? $fixedPrice;
                            $searchText = strtolower($item['displayName'] . ' ' . $category['name']);
                            $cardSection = $category['name'];
                            $visiblePrices = preg_replace('/^Price:\\s*/u', '', $item['priceText']);
                            ?>
                            <?php include __DIR__ . '/../partials/cc-menu-card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
        <div class="cc-menu__empty" id="customerMenuEmpty" role="status" aria-live="polite">
            <h2>No matches yet</h2>
            <p>Try another item name or browse all categories.</p>
            <button class="cc-menu__reset" id="customerMenuReset" type="button">Show the full menu</button>
        </div>
    </div>
</div>

<!-- Product image preview modal -->
<div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 0;">
            <div class="modal-header" style="border-color: #deded9;">
                <h5 class="modal-title" id="productImageModalLabel" style="font-family: var(--cc-serif);">Product image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="productImageModalImg" src="" alt="" style="width: 100%; max-height: 70vh; object-fit: contain; background: #f5f5f2;">
            </div>
        </div>
    </div>
</div>

<script>
function showProductImage(src, name) {
    var img = document.getElementById('productImageModalImg');
    if (!img) return;
    img.src = src;
    img.alt = name || '';
    document.getElementById('productImageModalLabel').textContent = name || 'Product image';
    var modal = new bootstrap.Modal(document.getElementById('productImageModal'));
    modal.show();
}
</script>

<!-- Existing product-detail modal contract retained. -->
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
                    <i class="bi bi-cart-plus" aria-hidden="true"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/menu-cart.js"></script>
<script>
(function () {
    'use strict';

    const root = document.getElementById('customerMenu');
    if (!root) return;

    const search = document.getElementById('customerMenuSearch');
    const searchWrap = document.getElementById('customerMenuSearchWrap');
    const clearButton = document.getElementById('customerMenuClear');
    const emptyState = document.getElementById('customerMenuEmpty');
    const resultCount = document.getElementById('customerMenuResultCount');
    const categoryNav = document.getElementById('customerMenuCategories');
    let sectionObserver = null;

    root.querySelectorAll('[data-size-select]').forEach(function (select) {
        select.addEventListener('change', function () {
            const card = select.closest('.cc-menu__card');
            const fromPrice = card.querySelector('[data-from-price]');
            const buttonPrice = card.querySelector('[data-button-price]');
            const formatted = '₱' + Number(select.value).toLocaleString('en-PH');
            fromPrice.textContent = formatted;
            buttonPrice.textContent = formatted;
        });
    });

    root.querySelectorAll('[data-quantity-step]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = button.parentElement.querySelector('.quantity-input');
            const step = Number(button.dataset.quantityStep);
            const current = Math.max(1, Math.floor(Number(input.value) || 1));
            input.value = String(Math.max(1, current + step));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });

    root.querySelectorAll('[data-coffee-choice] input[type="radio"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            const choice = radio.closest('[data-coffee-choice]');
            choice.classList.remove('needs-choice');
            const feedback = radio.closest('.cc-menu__card').querySelector('[data-item-feedback]');
        if (button && typeof menuCart.createCartAnimation === 'function') menuCart.createCartAnimation(button);
        feedback.textContent = '';
        });
    });

    window.ccCustomerAddToCart = async function (productName, quantityId, priceIdOrFixed, coffeeGroup, event) {
        const quantityInput = document.getElementById(quantityId);
        const card = quantityInput ? quantityInput.closest('.cc-menu__card') : null;
        const button = event ? event.currentTarget : null;
        if (!quantityInput || !card) return false;

        if (!quantityInput.checkValidity()) {
            quantityInput.reportValidity();
            quantityInput.focus();
            return false;
        }

        const feedback = card.querySelector('[data-item-feedback]');
        let coffeeValue = '';
        if (coffeeGroup) {
            const selected = Array.from(document.getElementsByName(coffeeGroup)).find(function (input) {
                return input.checked;
            });
            coffeeValue = selected ? selected.value : '';
            if (!selected) {
                const choice = card.querySelector('[data-coffee-choice]');
                choice.classList.add('needs-choice');
                feedback.textContent = 'Choose hot or iced before adding this coffee.';
                const firstOption = choice.querySelector('input[type="radio"]');
                if (firstOption) firstOption.focus();
                return false;
            }
        }

        const price = /^size-/.test(String(priceIdOrFixed))
            ? (document.getElementById(priceIdOrFixed) || {}).value
            : priceIdOrFixed;

        const productId = card ? card.dataset.productId : '';
        if (!productId) {
            feedback.textContent = 'Product ID is missing. Please refresh the page.';
            return false;
        }

        if (!menuCart || typeof menuCart.checkAuthentication !== 'function' || !menuCart.checkAuthentication()) {
            if (typeof menuCart.showAuthModal === 'function') menuCart.showAuthModal();
            return false;
        }

        feedback.textContent = '';

        try {
            const response = await fetch('/api/cart/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantityInput.value)}`
            });
            const data = await response.json();
            if (data.success) {
                if (typeof menuCart.updateCartCount === 'function') menuCart.updateCartCount();
                const displayName = coffeeValue ? `${productName} (${coffeeValue})` : productName;
                if (typeof menuCart.showNotification === 'function') menuCart.showNotification(`${displayName} added to cart!`, 'success');
                return true;
            }
            feedback.textContent = data.message || 'Failed to add to cart.';
            return false;
        } catch (error) {
            console.error('Error adding to cart:', error);
            feedback.textContent = 'Error adding to cart.';
            return false;
        }
    };

    function filterMenu() {
        const query = search.value.trim().toLocaleLowerCase();
        const sections = root.querySelectorAll('.cc-menu__section');
        let visibleCount = 0;
        searchWrap.classList.toggle('has-query', Boolean(query));

        sections.forEach(function (section) {
            const cards = section.querySelectorAll('.cc-menu__card');
            let sectionCount = 0;
            cards.forEach(function (card) {
                const visible = !query || card.dataset.search.includes(query);
                card.hidden = !visible;
                if (visible) sectionCount += 1;
            });
            section.hidden = sectionCount === 0;
            const count = section.querySelector('[data-section-count]');
            count.textContent = sectionCount + ' ' + (sectionCount === 1 ? 'item' : 'items');
            visibleCount += sectionCount;
        });

        emptyState.classList.toggle('is-visible', visibleCount === 0);
        resultCount.textContent = visibleCount + ' ' + (visibleCount === 1 ? 'menu item' : 'menu items') + ' found.';
        categoryNav.querySelectorAll('.cc-menu__category-link').forEach(function (link) {
            const section = document.getElementById(link.dataset.target);
            link.hidden = Boolean(query) && (!section || section.hidden);
        });
    }

    function setActiveCategory(id) {
        categoryNav.querySelectorAll('.cc-menu__category-link').forEach(function (link) {
            const active = link.dataset.target === id;
            link.classList.toggle('is-active', active);
            if (active) link.setAttribute('aria-current', 'location');
            else link.removeAttribute('aria-current');
        });
    }

    function observeSections() {
        if (!('IntersectionObserver' in window)) return;
        if (sectionObserver) sectionObserver.disconnect();
        sectionObserver = new IntersectionObserver(function (entries) {
            const visible = entries.filter(function (entry) {
                return entry.isIntersecting;
            }).sort(function (a, b) {
                return b.intersectionRatio - a.intersectionRatio;
            })[0];
            if (visible) setActiveCategory(visible.target.id);
        }, { rootMargin: '-25% 0px -62% 0px', threshold: [0, .1, .25, .5] });

        root.querySelectorAll('.cc-menu__section').forEach(function (section) {
            sectionObserver.observe(section);
        });
    }

    search.addEventListener('input', filterMenu);
    clearButton.addEventListener('click', function () {
        search.value = '';
        filterMenu();
        search.focus();
    });
    document.getElementById('customerMenuReset').addEventListener('click', function () {
        search.value = '';
        filterMenu();
        search.focus();
    });
    categoryNav.addEventListener('click', function (event) {
        const link = event.target.closest('a[data-target]');
        if (link) setActiveCategory(link.dataset.target);
    });

    observeSections();
})();
</script>

<?php
$content = ob_get_clean();
$title = 'Menu - CafeCat';
include __DIR__ . '/../layouts/main.php';
?>
