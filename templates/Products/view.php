<?php
/**
 * SustainChain — View Product
 * templates/Products/view.php
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */

use Cake\Core\Configure;

$this->assign('title', h($product->name) . ' — SustainChain');
$this->Html->css('marketplace', ['block' => true]);
?>

<style>
/* ── Product view layout ── */
.product-view-wrap {
    background: var(--s0);
    padding: 4rem 2.5rem 6rem;
}
.product-view-inner {
    max-width: 1100px;
    margin: 0 auto;
}

.product-view-card {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    box-shadow: 0 8px 40px rgba(13,31,20,.07);
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr 1fr;
    animation: reveal .6s .1s ease both;
}

@keyframes reveal {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

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
    height: 100%;
    object-fit: cover;
    position: absolute;
    inset: 0;
}

/* ── Save button on image ── */
.product-view-save {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    border: 1px solid var(--s2);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s, transform 0.18s var(--ease-spring);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 2;
}
.product-view-save:hover {
    background: var(--white);
    transform: scale(1.1);
}
.product-view-save svg {
    width: 20px;
    height: 20px;
    stroke: var(--g3);
    fill: none;
    transition: fill 0.15s, stroke 0.15s;
}
.product-view-save.is-saved svg {
    fill: var(--g3);
    stroke: var(--g3);
}

/* ── Details side ── */
.product-view-details {
    padding: 3rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.product-view-category {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(200,232,64,.12);
    border: 1px solid rgba(200,232,64,.22);
    color: var(--g2);
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 0.3rem 1rem;
    border-radius: var(--r999);
}

.product-view-name {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 3vw, 2.8rem);
    font-weight: 700;
    line-height: 1.05;
    letter-spacing: -0.03em;
    color: var(--g0);
}

.product-view-seller {
    font-family: 'Fraunces', serif;
    font-size: 1rem;
    font-style: italic;
    color: var(--g3);
}

.product-view-price {
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--g0);
    letter-spacing: -0.03em;
}

.product-view-divider {
    height: 1px;
    background: var(--s2);
}

.product-view-desc {
    font-size: 0.95rem;
    line-height: 1.8;
    color: var(--ink);
}

/* ── Filter tags ── */
.product-view-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.product-view-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: var(--s1);
    border: 1px solid var(--s2);
    color: var(--g2);
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.3rem 0.85rem;
    border-radius: var(--r999);
}
.product-view-tag-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--g3);
}

/* ── Actions ── */
.product-view-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: auto;
    padding-top: 0.5rem;
}

.btn-cart {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: var(--g0);
    color: var(--white);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 0.9rem 2rem;
    border: none;
    border-radius: var(--r16);
    cursor: pointer;
    transition: background 0.2s, transform 0.18s var(--ease-spring), box-shadow 0.2s;
    box-shadow: 0 4px 20px rgba(13,31,20,.2);
    letter-spacing: -0.01em;
}
.btn-cart:hover {
    background: var(--g2);
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(13,31,20,.3);
}

.btn-cart-icon {
    width: 18px;
    height: 18px;
}

/* ── Back button ── */
.product-view-back {
    margin-bottom: 2rem;
}
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--white);
    border: 1px solid var(--s2);
    color: var(--g2);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.75rem 1.25rem;
    border-radius: var(--r16);
    cursor: pointer;
    transition: background 0.2s, color 0.2s, transform 0.18s var(--ease-spring);
    text-decoration: none;
}
.btn-back:hover {
    background: var(--s1);
    color: var(--g0);
    transform: translateY(-1px);
}
.btn-back-icon {
    width: 16px;
    height: 16px;
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .product-view-card {
        grid-template-columns: 1fr;
    }
    .product-view-img-wrap {
        min-height: 280px;
    }
    .product-view-details {
        padding: 2rem 1.5rem;
    }
    .product-view-wrap {
        padding: 2rem 1.25rem 4rem;
    }
}
</style>

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

<div class="product-view-wrap">
    <div class="product-view-inner">
        <!-- Back button -->
        <div class="product-view-back">
            <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="btn-back">
                <svg class="btn-back-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/>
                    <path d="M12 19l-7-7 7-7"/>
                </svg>
                Back to Products
            </a>
        </div>

        <div class="product-view-card">

            <!-- Image -->
            <div class="product-view-img-wrap">
                <?php if (!empty($product->image_url)): ?>
                    <?= $this->Html->image('products/' . $product->image_url, [
                        'class' => 'product-view-img',
                        'alt'   => h($product->name),
                    ]) ?>
                <?php else: ?>
                    <img class="product-view-img" src="https://placehold.co/800x600/d9ede4/2e7d52?text=No+Image" alt="No image">
                <?php endif; ?>

                <!-- Save / star button -->
                <?php $identity = $this->request->getAttribute('identity'); ?>
                <?php if ($identity): ?>
                <button type="button"
                    class="product-view-save product-save-btn<?= !empty($isSaved) ? ' is-saved' : '' ?>"
                    aria-pressed="<?= !empty($isSaved) ? 'true' : 'false' ?>"
                    aria-label="Save <?= h($product->name) ?>"
                    data-product-id="<?= h($product->id) ?>"
                    data-save-url="<?= h($this->Url->build(['controller' => 'Products', 'action' => 'toggleSave', $product->id])) ?>"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </button>
                <?php endif; ?>
            </div>

            <!-- Details -->
            <div class="product-view-details">
                <div>
                    <span class="product-view-category"><?= h($product->category) ?></span>
                </div>

                <h1 class="product-view-name"><?= h($product->name) ?></h1>

                <?php if (!empty($product->user)): ?>
                <div class="product-view-seller">
                    by <?= h($product->user->first_name) ?> <?= h($product->user->last_name) ?>
                </div>
                <?php endif; ?>

                <div class="product-view-price">
                    $<?= $this->Number->format($product->price, ['places' => 2]) ?>
                </div>

                <div class="product-view-divider"></div>

                <p class="product-view-desc"><?= h($product->description) ?></p>

                <?php if (!empty($product->filtertags)): ?>
                <div class="product-view-tags">
                    <?php foreach ($product->filtertags as $filtertag): ?>
                    <span class="product-view-tag">
                        <span class="product-view-tag-dot"></span>
                        <?= h($filtertag->name) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="product-view-divider"></div>

                <div class="product-view-actions">
                    <button class="btn-cart" data-product-id="<?= h($product->id) ?>">
                        <svg class="btn-cart-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        Add to Cart
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Add to cart
    document.querySelector('.btn-cart')?.addEventListener('click', function () {
        const productId = this.dataset.productId;
        alert('Add to cart coming soon!');
    });
</script>

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