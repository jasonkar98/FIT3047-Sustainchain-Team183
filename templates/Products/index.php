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

<!-- Search bar -->
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

<!-- Results heading — only shows when there is a keyword AND results exist -->
<?php if (!empty($this->request->getQuery('keyword')) && !$products->isEmpty()): ?>
    <div class="search-results-label">
        Here are our results for <strong><?= h($this->request->getQuery('keyword')) ?></strong>
    </div>
<?php endif; ?>

<!-- Body: sidebar + product grid -->
<div class="marketplace-body">
    <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index'], 'id' => 'filter-form']) ?>

    <!-- Keep keyword in filter form -->
    <?php if (!empty($this->request->getQuery('keyword'))): ?>
        <input type="hidden" name="keyword" value="<?= h($this->request->getQuery('keyword')) ?>">
    <?php endif; ?>

    <div class="marketplace-layout">

        <!-- Sidebar -->
        <aside class="filter-sidebar">
            <h3 class="filter-heading">Filters</h3>

            <!-- Category -->
            <div class="filter-group">
                <label class="filter-label">Category</label>
                <?php foreach ($categories as $cat): ?>
                    <label class="filter-option">
                        <input
                            type="checkbox"
                            name="category[]"
                            value="<?= h($cat) ?>"
                            <?= in_array($cat, (array)($search['category'] ?? [])) ? 'checked' : '' ?>
                        >
                        <?= h($cat) ?>
                    </label>
                <?php endforeach; ?>
                <?php if (!empty($search['category'])): ?>
                    <a href="<?= $this->Url->build(['action' => 'index'] + ['?' => ['keyword' => $search['keyword'] ?? '']]) ?>" class="filter-clear-link">Clear category</a>
                <?php endif; ?>
            </div>

            <!-- Price range -->
            <div class="filter-group">
                <label class="filter-label">Price range</label>
                <div class="price-inputs">
                    <input
                        type="number"
                        name="price_min"
                        placeholder="Min $"
                        min="0"
                        step="0.01"
                        value="<?= h($search['price_min'] ?? '') ?>"
                        class="price-input"
                    >
                    <span class="price-sep">–</span>
                    <input
                        type="number"
                        name="price_max"
                        placeholder="Max $"
                        min="0"
                        step="0.01"
                        value="<?= h($search['price_max'] ?? '') ?>"
                        class="price-input"
                    >
                </div>
                <button type="submit" class="btn-apply">Apply</button>
            </div>

            <!-- Clear all filters -->
            <?php if (!empty($search['category']) || !empty($search['price_min']) || !empty($search['price_max'])): ?>
                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn-clear-all">Clear all filters</a>
            <?php endif; ?>
        </aside>

        <!-- Products -->
        <div class="products-col">
            <!-- sorting -->
            <div class="sort-row">
                <select name="sort" class="sort-select" onchange="this.closest('form').submit()">
                    <option value="newest"    <?= ($search['sort'] ?? 'newest') === 'newest'    ? 'selected' : '' ?>>Newest arrivals</option>
                    <option value="price_asc" <?= ($search['sort'] ?? '') === 'price_asc'  ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc"<?= ($search['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="name_asc"  <?= ($search['sort'] ?? '') === 'name_asc'   ? 'selected' : '' ?>>A–Z by name</option>
                    <option value="name_desc" <?= ($search['sort'] ?? '') === 'name_desc'  ? 'selected' : '' ?>>Z–A by name</option>
                </select>
            </div>

            <?php if ($products->items()->isEmpty()): ?>
                <div class="marketplace-empty">
                    <?php if (!empty($search['category']) || !empty($search['price_min']) || !empty($search['price_max'])): ?>
                        <p>No results found for your filters.</p>
                        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-lime">Clear filters</a>
                    <?php elseif (!empty($this->request->getQuery('keyword'))): ?>
                        <p>No results found for your search.</p>
                        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="btn btn-lime">Back to All Products</a>
                    <?php else: ?>
                        <p>No products available yet. Check back soon!</p>
                        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="btn btn-lime">Back to All Products</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-img-wrap">
                                <?php
                                    $imageSrc = !empty($product->image_url)
                                        ? $product->image_url
                                        : 'https://placehold.co/400x300/d9ede4/2e7d52?text=No+Image';
                                ?>
                                <img class="product-img" src="<?= h($imageSrc) ?>" alt="<?= h($product->name) ?>">
                            </div>
                            <div class="product-card-body">
                                <span class="product-category"><?= h($product->category) ?></span>
                                <h3 class="product-name"><?= h($product->name) ?></h3>
                                <p class="product-price">$<?= h(number_format($product->price, 2)) ?></p>
                                <p class="product-desc"><?= h(mb_strimwidth($product->description, 0, 90, '...')) ?></p>
                            </div>
                            <div class="product-card-footer">
                                <a href="/products/<?= $product->id ?>" class="btn-product">View Product →</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <?= $this->Form->end() ?>
</div>

<script>
    document.querySelector('.search-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            this.closest('form').submit();
        }
    });
</script>