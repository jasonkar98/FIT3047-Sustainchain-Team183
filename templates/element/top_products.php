<?php
/**
 * Reusable "Top 3 most-sold products (last 30 days)" section.
 *
 * Used on:
 *   - Innovators::view  — public manufacturer detail page
 *   - Auth::view        — manufacturer's own "My account" page
 *
 * Expected:
 *   - $topProducts : array<\App\Model\Entity\Product>
 *   - $heading     : optional override (default: "Most-sold products this month")
 */

$heading = $heading ?? 'Most-sold products this month';
$topProducts = $topProducts ?? [];
?>

<style>
.top-products {
    margin-top: 1.5rem;
}
.top-products h3 {
    font-family: 'Fraunces', serif;
    font-size: 1.15rem;
    font-weight: 700;
    margin: 0 0 .85rem;
    color: var(--g0, #0d1f14);
}
.top-products-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: .85rem;
}
.top-products-card {
    border: 1px solid var(--s2, #cfd7cf);
    border-radius: var(--r16, 16px);
    background: var(--white, #fff);
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    transition: transform .15s, box-shadow .15s, border-color .15s;
}
.top-products-card:hover {
    transform: translateY(-2px);
    border-color: var(--g3, #2e7d52);
    box-shadow: 0 8px 22px rgba(13,31,20,.12);
}
.top-products-img {
    aspect-ratio: 4 / 3;
    background: linear-gradient(160deg, #e8f0ec 0%, #b9d4c4 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.top-products-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.top-products-img .ph {
    font-family: 'Fraunces', serif;
    color: rgba(13,31,20,.4);
    font-weight: 700;
    font-size: 1.2rem;
}
.top-products-body {
    padding: .65rem .8rem .8rem;
    display: flex;
    flex-direction: column;
    gap: .25rem;
}
.top-products-category {
    font-size: .6rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-weight: 700;
    color: var(--g3, #2e7d52);
}
.top-products-name {
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: .95rem;
    line-height: 1.2;
    color: var(--g0, #0d1f14);
    overflow-wrap: anywhere;
}
.top-products-meta {
    font-size: .75rem;
    color: var(--ink, #2a3a32);
    display: flex;
    justify-content: space-between;
    gap: .5rem;
    margin-top: .2rem;
}
.top-products-meta .price {
    font-weight: 700;
}
.top-products-meta .units {
    color: var(--g3, #2e7d52);
    font-weight: 600;
}
.top-products-empty {
    border: 1px dashed var(--s2, #cfd7cf);
    border-radius: var(--r16, 16px);
    padding: 1.25rem;
    text-align: center;
    color: var(--g3, #2e7d52);
    font-size: .9rem;
}

@media (max-width: 720px) {
    .top-products-grid { grid-template-columns: 1fr; }
}
</style>

<section class="top-products">
    <h3><?= h($heading) ?></h3>

    <?php if (empty($topProducts)): ?>
        <div class="top-products-empty">
            No sales recorded in the last 30 days.
        </div>
    <?php else: ?>
        <div class="top-products-grid">
            <?php foreach ($topProducts as $p): ?>
                <a class="top-products-card" href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'view', $p->id]) ?>">
                    <div class="top-products-img">
                        <?php if (!empty($p->image_url)): ?>
                            <?= $this->Html->image('products/' . $p->image_url, ['alt' => h($p->name)]) ?>
                        <?php else: ?>
                            <span class="ph">No image</span>
                        <?php endif; ?>
                    </div>
                    <div class="top-products-body">
                        <span class="top-products-category"><?= h($p->category) ?></span>
                        <span class="top-products-name"><?= h($p->name) ?></span>
                        <span class="top-products-meta">
                            <span class="price">$<?= h(number_format((float)$p->price, 2)) ?></span>
                            <?php if (isset($p->units_sold_30d)): ?>
                                <span class="units"><?= (int)$p->units_sold_30d ?> sold</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
