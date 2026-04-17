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
                <div class="product-card">

                    <!-- Image -->
                    <div class="product-img-wrap">
                        <?php
                        // Use the product image if set, otherwise a themed placeholder
                        $imageSrc = !empty($product->image_url)
                            ? $product->image_url
                            : 'https://placehold.co/400x300/d9ede4/2e7d52?text=No+Image';
                        ?>
                        <img
                            class="product-img"
                            src="<?= h($imageSrc) ?>"
                            alt="<?= h($product->name) ?>"
                        >

                        <!-- Save / bookmark icon — overlaid on the image -->
                        <button
                            class="product-save-btn"
                            aria-label="Save <?= h($product->name) ?>"
                            data-product-id="<?= h($product->id) ?>"
                            data-save-url="<?= $this->Url->build(['controller' => 'Products', 'action' => 'toggleSave', $product->id]) ?>"
                        >
                            <svg
                                class="product-save-icon"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Info -->
                    <div class="product-card-body">
                        <span class="product-category"><?= h($product->category) ?></span>
                        <h3 class="product-name"><?= h($product->name) ?></h3>
                        <p class="product-price">$<?= h(number_format($product->price, 2)) ?></p>
                        <p class="product-desc">
                            <?= h(mb_strimwidth($product->description, 0, 90, '...')) ?>
                        </p>
                    </div>

                    <!-- CTA -->
                    <div class="product-card-footer">
                        <a href="/products/<?= $product->id ?>" class="btn-product">
                            View Product →
                        </a>
                    </div>

                </div>
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
