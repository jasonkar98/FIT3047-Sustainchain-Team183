<?php
// product_card.php
// Reusable product card for marketplace and favourite listings.
// Expected variables:
// - $product: 
// - $showSaveButton: true/false
// - $saveLabel: label for the save button
// - $saveAction: URL for the save action, if provided
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

</style>

<a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'view', $product->id]) ?>" class="product-card">

    <div class="product-img-wrap">
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
        <p class="product-price">$<?= h(number_format($product->price, 2)) ?></p>
        <p class="product-desc"><?= h(mb_strimwidth($product->description, 0, 90, '...')) ?></p>
    </div>
</a>
