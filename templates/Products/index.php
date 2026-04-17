<?php
// Load marketplace.css only on this page — injected into <head> by the layout
$this->Html->css('marketplace', ['block' => true]);
?>

<!-- Page header — dark green banner, same style as the landing page hero -->
<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">SustainChain</span>
        <h1 class="marketplace-title t-display">
            The <em>sustainable</em> marketplace
        </h1>
        <p class="marketplace-subtitle">
            Browse products from verified eco-friendly sellers, farmers, and manufacturers.
        </p>
    </div>
</div>

<!-- Product grid -->
<div class="marketplace-body">

    <?php if ($products->isEmpty()): ?>
        <div class="marketplace-empty">
            <p>No products available yet. Check back soon!</p>
            <a href="/" class="btn btn-lime">Back to Home</a>
        </div>

    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?= $this->element('product_card', [
                    'product' => $product,
                    'showSaveButton' => true,
                    'isSaved' => isset($savedProductIds[$product->id]),
                ]) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php $this->Html->scriptStart(['block' => true]); ?>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.product-save-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.productId;
            let url = btn.dataset.saveUrl || '';

            if (url.includes(':id')) {
                url = url.replace(':id', productId);
            }

            if (!url) {
                const appBase = window.location.pathname.split('/').filter(Boolean).slice(0, 1).join('/');
                url = `${window.location.origin}${appBase ? '/' + appBase : ''}/products/toggle-save/${productId}`;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': <?= json_encode($this->request->getAttribute('csrfToken')) ?>,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    btn.classList.toggle('is-saved');
                    const saved = btn.classList.contains('is-saved');
                    btn.setAttribute('aria-pressed', saved);
                }
            });
        });
    });
});
<?php $this->Html->scriptEnd(); ?>
