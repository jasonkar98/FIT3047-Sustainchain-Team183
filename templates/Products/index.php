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

        <?= $this->Form->button('Search', ['class' => 'btn btn-lime', 'type' => 'submit']) ?>

        <?php if ($this->request->getQuery('keyword')): ?>
            <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="btn btn-outline">Clear</a>
        <?php endif; ?>

        <?php // Preserve any active filters/sort when re-searching ?>
        <?php foreach ((array)($search['category'] ?? []) as $cat): ?>
            <input type="hidden" name="category[]" value="<?= h($cat) ?>">
        <?php endforeach; ?>
        <?php if (!empty($search['price_min'])): ?>
            <input type="hidden" name="price_min" value="<?= h($search['price_min']) ?>">
        <?php endif; ?>
        <?php if (!empty($search['price_max'])): ?>
            <input type="hidden" name="price_max" value="<?= h($search['price_max']) ?>">
        <?php endif; ?>
        <?php if (!empty($search['sort'])): ?>
            <input type="hidden" name="sort" value="<?= h($search['sort']) ?>">
        <?php endif; ?>

    </div>

    <?= $this->Form->end() ?>
</div>

<!-- Results heading — only shows when there is a keyword AND results exist -->
<?php if (!empty($this->request->getQuery('keyword')) && !$products->items()->isEmpty()): ?>
    <div class="search-results-label">
        Here are your results for "<strong><?= h($this->request->getQuery('keyword')) ?></strong>"
    </div>
<?php endif; ?>

<!-- Body: sidebar + product grid -->

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

    <!-- Keep keyword in filter form -->
    <?php if (!empty($this->request->getQuery('keyword'))): ?>
        <input type="hidden" name="keyword" value="<?= h($this->request->getQuery('keyword')) ?>">
    <?php endif; ?>

<div class="marketplace-layout">

    <!-- Sidebar: Filters form -->
    <aside class="filter-sidebar">
        <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index']]) ?>

        <?php // Preserve keyword and sort across filter changes ?>
        <?php if (!empty($search['keyword'])): ?>
            <input type="hidden" name="keyword" value="<?= h($search['keyword']) ?>">
        <?php endif; ?>
        <?php if (!empty($search['sort'])): ?>
            <input type="hidden" name="sort" value="<?= h($search['sort']) ?>">
        <?php endif; ?>

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
        </div>

        <button type="submit" class="btn-apply">Apply</button>

        <!-- Clear all filters -->
        <?php if (!empty($search['category']) || !empty($search['price_min']) || !empty($search['price_max'])): ?>
            <a href="<?= $this->Url->build(['action' => 'index'] + ['?' => array_filter([
                'keyword' => $search['keyword'] ?? null,
                'sort' => $search['sort'] ?? null,
            ])]) ?>" class="btn-clear-all">Clear all filters</a>
        <?php endif; ?>

        <?= $this->Form->end() ?>
    </aside>

    <!-- Products column -->
    <div class="products-col">

        <!-- Sort form -->
        <div class="sort-row">
            <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index']]) ?>
                <?php if (!empty($search['keyword'])): ?>
                    <input type="hidden" name="keyword" value="<?= h($search['keyword']) ?>">
                <?php endif; ?>
                <?php foreach ((array)($search['category'] ?? []) as $cat): ?>
                    <input type="hidden" name="category[]" value="<?= h($cat) ?>">
                <?php endforeach; ?>
                <?php if (!empty($search['price_min'])): ?>
                    <input type="hidden" name="price_min" value="<?= h($search['price_min']) ?>">
                <?php endif; ?>
                <?php if (!empty($search['price_max'])): ?>
                    <input type="hidden" name="price_max" value="<?= h($search['price_max']) ?>">
                <?php endif; ?>
                <select name="sort" class="sort-select" onchange="this.closest('form').submit()">
                    <option value="newest"    <?= ($search['sort'] ?? 'newest') === 'newest'    ? 'selected' : '' ?>>Newest arrivals</option>
                    <option value="price_asc" <?= ($search['sort'] ?? '') === 'price_asc'  ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc"<?= ($search['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="name_asc"  <?= ($search['sort'] ?? '') === 'name_asc'   ? 'selected' : '' ?>>A–Z by name</option>
                    <option value="name_desc" <?= ($search['sort'] ?? '') === 'name_desc'  ? 'selected' : '' ?>>Z–A by name</option>
                </select>
            <?= $this->Form->end() ?>
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
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <?= $this->element('product_card', [
                        'product' => $product,
                        'showSaveButton' => !empty($this->request->getAttribute('identity')),
                        'isSaved' => in_array($product->id, $savedProductIds),
                    ]) ?>
                <?php endforeach; ?>
            </div>

            <div class="marketplace-pagination">
                <?= $this->Paginator->prev('← Prev') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next('Next →') ?>
            </div>
        <?php endif; ?>

    </div>

</div>

<script>
    document.querySelector('.search-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            this.closest('form').submit();
        }
    });
</script>