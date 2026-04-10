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

<!-- Search & Filter bar -->
<div class="marketplace-search">
    <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index']]) ?>

    <div class="search-filter-row">

        <?= $this->Form->text('keyword', [
            'placeholder' => 'Search products...',
            'value'       => $this->request->getQuery('keyword') ?? '',
            'class'       => 'search-input',
        ]) ?>

        <?= $this->Form->button('Search', ['class' => 'btn btn-lime']) ?>

        <?php if ($this->request->getQuery('keyword')): ?>
    <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="btn-outline">Clear</a>
<?php endif; ?>

    </div>

    <?= $this->Form->end() ?>
</div>


<?php if (!empty($this->request->getQuery('keyword'))): ?>
    <div class="search-results-label">
        Here are our results for "<strong><?= h($this->request->getQuery('keyword')) ?></strong>"
    </div>
<?php endif; ?>

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

<script>
    document.querySelector('.search-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            this.closest('form').submit();
        }
    });
</script>