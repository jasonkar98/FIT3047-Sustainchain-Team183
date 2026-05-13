<?php
// product_card.php
// Reusable product card for marketplace and favourite listings.
// Expected variables:
// - $product:
// - $showSaveButton: true/false
// - $saveLabel: label for the save button
// - $saveAction: URL for the save action, if provided

// Unlisted-state lookup. Surfaces a "Delisted" badge on cards for products
// that have been pulled from the marketplace (deactivation cascade or admin
// moderation). Card is still clickable so the user can view & unsave.
$isUnlisted = isset($product->is_listed) && (int)$product->is_listed === 0;
$unlistLabel = null;
if ($isUnlisted) {
    $reason = $product->unlist_reason ?? null;
    if ($reason === 'admin') {
        $unlistLabel = 'Admin Unlisted';
    } elseif ($reason === 'deactivation') {
        $unlistLabel = 'Unlisted';
    } else {
        $unlistLabel = 'Unlisted';
    }
}

// Owners cannot favourite their own products. Hide the save button when
// the logged-in viewer is the product's owner.
$cardIdentity = $this->request->getAttribute('identity');
$viewerOwnsProduct = $cardIdentity
    && isset($product->user_id)
    && (int)$cardIdentity->getIdentifier() === (int)$product->user_id;
if ($viewerOwnsProduct) {
    $showSaveButton = false;
}
?>
<style>

    /* ── Image side ── */
.product-view-img-wrap {
    position: relative;
    background: var(--s1);
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.product-view-img {
    width: 100%;
    object-fit: cover;
    position: absolute;
    inset: 0;
}
.product-card {
    text-decoration: none;
    color: inherit;
    display: block;
}

/* Delisted badge + dimmed image for unlisted product cards */
.product-card.is-unlisted .product-img-wrap { position: relative; }
.product-card.is-unlisted .product-view-img,
.product-card.is-unlisted .product-img-wrap img {
    filter: grayscale(.6) brightness(.85);
    opacity: .8;
}
.product-card.is-unlisted .product-card-body .product-name,
.product-card.is-unlisted .product-card-body .product-price,
.product-card.is-unlisted .product-card-body .product-desc {
    color: #666;
}
.product-card-delisted-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #b00020;
    color: #fff;
    font-family: 'Cabinet Grotesk', 'DM Sans', sans-serif;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: .3rem .65rem;
    border-radius: 999px;
    z-index: 3;
    box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
}
.product-card-delisted-badge.is-admin {
    background: #6a4f1c;
}

</style>

<a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'view', $product->id]) ?>" class="product-card">

<div class="product-card<?= $isUnlisted ? ' is-unlisted' : '' ?>">
    <div class="product-img-wrap">
        <?php if ($isUnlisted): ?>
            <span class="product-card-delisted-badge<?= ($product->unlist_reason ?? null) === 'admin' ? ' is-admin' : '' ?>">
                <?= h($unlistLabel) ?>
            </span>
        <?php endif; ?>

        <?php if (!empty($product->image_url)): ?>
            <?= $this->Html->image('products/' . $product->image_url, [
                'class' => 'product-view-img',
                'alt'   => h($product->name),
            ]) ?>
        <?php else: ?>
            <img class="product-view-img" src="https://placehold.co/800x600/d9ede4/2e7d52?text=No+Image" alt="No image">
        <?php endif; ?>
        

        <?php if (!empty($showSaveButton)): ?>
        <?php $isSaved = !empty($isSaved) || (!empty($product->is_saved) && $product->is_saved); ?>
        <button
            class="product-save-btn<?= $isSaved ? ' is-saved' : '' ?>"
            aria-pressed="<?= $isSaved ? 'true' : 'false' ?>"
            aria-label="<?= h($saveLabel ?? 'Save ' . $product->name) ?>"
            data-product-id="<?= h($product->id) ?>"
            data-save-url="<?= h($saveAction ?? $this->Url->build(['controller' => 'Products', 'action' => 'toggleSave', $product->id])) ?>"
            onclick="event.preventDefault()"
        >
            <svg class="product-save-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        </button>
        <?php endif; ?>
    </div>

    <div class="product-card-body">
        <span class="product-category"><?= h($product->category) ?></span>
        <h3 class="product-name"><?= h($product->name) ?></h3>
        <p class="product-price">
            <?php if (!empty($product->discount) && $product->discount > 0): ?>
                <span class="product-price-original">$<?= h(number_format($product->price, 2)) ?></span>
                $<?= h(number_format($product->price * (1 - $product->discount / 100), 2)) ?>
                <span class="product-discount-badge">-<?= h($product->discount) ?>%</span>
            <?php else: ?>
                $<?= h(number_format($product->price, 2)) ?>
            <?php endif; ?>
        </p>
        <p class="product-desc"><?= h(mb_strimwidth($product->description, 0, 90, '...')) ?></p>
    </div>
</a>
