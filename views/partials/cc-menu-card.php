<?php
// Partial: renders one menu card. Expects:
//   $item, $cardSection, $escape, $jsValue, $quantityId, $sizeId,
//   $fixedPrice, $coffeeGroup, $startPrice, $searchText, $visiblePrices, $cartName
?>
<article class="cc-menu__card item-box" data-product-id="<?= (int) $item['id'] ?>" data-search="<?= $escape($searchText) ?>" data-category="<?= $escape(strtolower($cardSection)) ?>">
    <div class="cc-menu__image-wrap">
        <img class="cc-menu__image" src="<?= $escape($item['image']) ?>" alt="<?= $escape($item['displayName']) ?>" loading="lazy" decoding="async" onclick="showProductImage(<?= $jsValue($item['image']) ?>, <?= $jsValue($item['displayName']) ?>)" style="cursor: pointer;">
        <button type="button" class="cc-menu__image-btn" onclick="showProductImage(<?= $jsValue($item['image']) ?>, <?= $jsValue($item['displayName']) ?>)">
            <i class="bi bi-arrows-fullscreen me-1"></i>Show image
        </button>
    </div>
    <div class="cc-menu__card-body">
        <div class="cc-menu__product-top">
            <h3 class="cc-menu__product-name"><?= $escape($item['displayName']) ?></h3>
            <span class="cc-menu__from-price" data-from-price><?= $item['options'] ? 'From ' : '' ?><?= $escape('₱' . $startPrice) ?></span>
        </div>
        <p class="cc-menu__price-options"><?= $escape($visiblePrices) ?></p>

        <?php if ($item['options']): ?>
            <div class="cc-menu__option-group">
                <label class="cc-menu__field-label" for="<?= $escape($sizeId) ?>">Choose size</label>
                <select class="cc-menu__size-select" id="<?= $escape($sizeId) ?>" aria-label="<?= $escape($item['displayName']) ?> size" data-size-select>
                    <?php foreach ($item['options'] as $option): ?>
                        <option value="<?= $escape($option['value']) ?>"><?= $escape($option['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <?php if ($item['coffeeOptions']): ?>
            <fieldset class="coffee-choice" data-coffee-choice>
                <legend class="coffee-choice__legend">Hot or iced?</legend>
                <div class="coffee-options">
                    <?php foreach ($item['coffeeOptions'] as $coffeeOption): ?>
                        <label for="<?= $escape($coffeeOption['id']) ?>">
                            <input type="radio" id="<?= $escape($coffeeOption['id']) ?>" name="<?= $escape($coffeeOption['name']) ?>" value="<?= $escape($coffeeOption['value']) ?>">
                            <?= $escape($coffeeOption['value'] === 'hot' ? 'Hot Coffee' : 'Iced Coffee') ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>
        <?php endif; ?>

        <div class="cc-menu__quantity-row">
            <label class="cc-menu__quantity-label" for="<?= $escape($quantityId) ?>">Quantity</label>
            <div class="cc-menu__quantity-control">
                <button type="button" aria-label="Decrease <?= $escape($item['displayName']) ?> quantity" data-quantity-step="-1">−</button>
                <input type="number" value="1" min="1" step="1" class="quantity-input" id="<?= $escape($quantityId) ?>" inputmode="numeric" aria-label="<?= $escape($item['displayName']) ?> quantity">
                <button type="button" aria-label="Increase <?= $escape($item['displayName']) ?> quantity" data-quantity-step="1">+</button>
            </div>
        </div>

        <div class="cc-menu__actions">
            <button class="order-btn" type="button" onclick="return ccCustomerAddToCart(<?= $jsValue($cartName) ?>, <?= $jsValue($quantityId) ?>, <?= $jsValue($sizeId !== '' ? $sizeId : $fixedPrice) ?>, <?= $jsValue($coffeeGroup) ?>, event)">
                <i class="bi bi-cart-plus"></i><span>Add to cart</span>
                <span class="cc-menu__button-price" data-button-price>₱<?= $escape($startPrice) ?></span>
            </button>
            <?php if ($item['detail']): ?>
                <?php $detail = $item['detail']; ?>
                <button class="cc-menu__detail-btn btn btn-outline-primary btn-sm" type="button" onclick="showProductModal(<?= $jsValue($item['id']) ?>, <?= $jsValue($detail['name']) ?>, <?= $jsValue($detail['image']) ?>, <?= $jsValue($detail['description']) ?>, <?= $jsValue($detail['category']) ?>, <?= $jsValue($detail['time']) ?>, <?= $jsValue($detail['price']) ?>)">
                    View details
                </button>
            <?php endif; ?>
        </div>
        <p class="cc-menu__feedback" data-item-feedback role="status" aria-live="polite"></p>
    </div>
</article>
