<?php
$this->assign('title', 'Your Cart — SustainChain');
$this->Html->css('marketplace', ['block' => true]);

// Compute grand total and item count from displayed products only
$grandTotal = 0;
$totalItems = 0;
foreach ($products as $p) {
    $qty = $cartQtys[$p->id] ?? 1;
    $grandTotal += $p->price * $qty;
    $totalItems += $qty;
}
?>

<style>
/* ── Header ── */
.cart-header {
    background: var(--g0);
    color: var(--white);
    padding: 4rem 2.5rem 3rem;
    text-align: center;
}
.cart-header-inner { max-width: 800px; margin: 0 auto; }
.cart-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1.05;
    margin: 0.5rem 0 0;
}

/* ── Page layout ── */
.cart-wrap {
    padding: 3rem 2.5rem 6rem;
    max-width: 1100px;
    margin: 0 auto;
}

/* ── Empty state ── */
.cart-empty {
    text-align: center;
    padding: 5rem 1rem;
}
.cart-empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.4; }
.cart-empty-heading {
    font-family: 'Fraunces', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--g0);
    margin-bottom: 0.5rem;
}
.cart-empty-sub { color: var(--ink); font-size: 1rem; margin-bottom: 2rem; }
.btn-go-market {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--g0);
    color: var(--white);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 0.9rem 2rem;
    border-radius: var(--r16);
    text-decoration: none;
    transition: background 0.2s, transform 0.18s;
    box-shadow: 0 4px 20px rgba(13,31,20,.2);
}
.btn-go-market:hover { background: var(--g2); transform: translateY(-2px); }

/* ── Two-column layout: items | summary ── */
.cart-body {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 2rem;
    align-items: start;
}

/* ── Item list ── */
.cart-items { display: flex; flex-direction: column; gap: 1rem; }

.cart-item {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    display: grid;
    grid-template-columns: 120px 1fr auto;
    gap: 0;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(13,31,20,.05);
    transition: box-shadow 0.2s;
}
.cart-item:hover { box-shadow: 0 6px 24px rgba(13,31,20,.1); }

/* Thumbnail */
.cart-item-thumb {
    display: block;
    width: 120px;
    height: 120px;
    object-fit: cover;
    flex-shrink: 0;
}

/* Middle: name + controls */
.cart-item-mid {
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 0.5rem;
}
.cart-item-category {
    display: inline-flex;
    align-items: center;
    background: rgba(200,232,64,.12);
    border: 1px solid rgba(200,232,64,.22);
    color: var(--g2);
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 0.2rem 0.7rem;
    border-radius: var(--r999);
    width: fit-content;
}
.cart-item-name {
    font-family: 'Fraunces', serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--g0);
    line-height: 1.2;
    letter-spacing: -0.02em;
    text-decoration: none;
}
.cart-item-name:hover { text-decoration: underline; }

.cart-item-controls {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 0.25rem;
}
.qty-box {
    display: inline-flex;
    align-items: center;
    border: 1.5px solid var(--s2);
    border-radius: var(--r16);
    overflow: hidden;
}
.qty-btn {
    background: var(--s1);
    border: none;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--g0);
    transition: background 0.15s;
}
.qty-btn:hover { background: var(--s2); }
.qty-display {
    width: 36px;
    text-align: center;
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--g0);
    pointer-events: none;
    border-left: 1.5px solid var(--s2);
    border-right: 1.5px solid var(--s2);
    padding: 0.1rem 0;
}
.btn-delete {
    background: none;
    border: none;
    color: var(--ink);
    font-size: 0.82rem;
    font-family: 'Cabinet Grotesk', sans-serif;
    cursor: pointer;
    text-decoration: underline;
    padding: 0;
    opacity: 0.6;
    transition: opacity 0.15s, color 0.15s;
}
.btn-delete:hover { opacity: 1; color: #c62828; }

/* Right: price column */
.cart-item-price-col {
    padding: 1rem 1.25rem 1rem 0.5rem;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: space-between;
    min-width: 100px;
}
.cart-item-unit-price {
    font-size: 0.75rem;
    color: var(--ink);
    opacity: 0.65;
}
.cart-item-line-total {
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--g0);
    letter-spacing: -0.02em;
}
.cart-item-view-link {
    font-size: 0.78rem;
    color: var(--g3);
    text-decoration: none;
    font-family: 'Cabinet Grotesk', sans-serif;
}
.cart-item-view-link:hover { text-decoration: underline; }

/* ── Order summary card ── */
.cart-summary {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    padding: 1.75rem;
    box-shadow: 0 4px 20px rgba(13,31,20,.07);
    position: sticky;
    top: 90px;
}
.cart-summary-title {
    font-family: 'Fraunces', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--g0);
    margin-bottom: 1.25rem;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    color: var(--ink);
    padding: 0.4rem 0;
}
.summary-divider {
    height: 1px;
    background: var(--s2);
    margin: 0.75rem 0;
}
.summary-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--g0);
    padding: 0.25rem 0;
}
.summary-total-label { letter-spacing: -0.01em; }
.summary-total-amount { letter-spacing: -0.03em; }
.summary-note {
    font-size: 0.75rem;
    color: var(--ink);
    opacity: 0.6;
    margin: 0.75rem 0 1.25rem;
}
.btn-checkout {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: var(--g0);
    color: var(--white);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: 1rem;
    padding: 0.95rem 1.5rem;
    border: none;
    border-radius: var(--r16);
    cursor: not-allowed;
    opacity: 0.55;
    box-sizing: border-box;
    letter-spacing: -0.01em;
}
.checkout-note {
    text-align: center;
    font-size: 0.72rem;
    color: var(--ink);
    opacity: 0.5;
    margin: 0.6rem 0 0;
}
.cart-continue {
    margin-top: 1.75rem;
}
.btn-continue {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--g2);
    text-decoration: none;
    border: 1px solid var(--s2);
    background: var(--white);
    padding: 0.65rem 1.25rem;
    border-radius: var(--r16);
    transition: background 0.15s, color 0.15s, transform 0.15s;
}
.btn-continue:hover {
    background: var(--s1);
    color: var(--g0);
    transform: translateY(-1px);
}

@media (max-width: 820px) {
    .cart-body { grid-template-columns: 1fr; }
    .cart-summary { position: static; }
}
@media (max-width: 540px) {
    .cart-item { grid-template-columns: 90px 1fr; }
    .cart-item-price-col { display: none; }
    .cart-item-thumb { width: 90px; height: 90px; }
    .cart-wrap { padding: 1.5rem 1rem 4rem; }
    .cart-header { padding: 2.5rem 1rem 2rem; }
}
</style>

<!-- Header -->
<div class="cart-header">
    <div class="cart-header-inner">
        <span class="t-label section-tag">SustainChain</span>
        <h1 class="cart-title">Your Cart</h1>
    </div>
</div>

<div class="cart-wrap">

<?php if (empty($products)): ?>

    <div class="cart-empty">
        <div class="cart-empty-icon">🛒</div>
        <h2 class="cart-empty-heading">Nothing in your cart yet</h2>
        <p class="cart-empty-sub">Browse the marketplace and add some eco-friendly products!</p>
        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="btn-go-market">
            Browse Marketplace
        </a>
    </div>

<?php else: ?>

    <div class="cart-body">

        <!-- Left: item list -->
        <div class="cart-items">
            <?php foreach ($products as $product):
                $qty = $cartQtys[$product->id] ?? 1;
                $lineTotal = $product->price * $qty;
            ?>
            <div class="cart-item">

                <!-- Thumbnail — links to product detail -->
                <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'view', $product->id]) ?>">
                    <?php if (!empty($product->image_url)): ?>
                        <?= $this->Html->image('products/' . $product->image_url, [
                            'class' => 'cart-item-thumb',
                            'alt'   => h($product->name),
                        ]) ?>
                    <?php else: ?>
                        <img class="cart-item-thumb" src="https://placehold.co/120x120/d9ede4/2e7d52?text=No+Image" alt="No image">
                    <?php endif; ?>
                </a>

                <!-- Middle: name + qty controls -->
                <div class="cart-item-mid">
                    <div>
                        <div class="cart-item-category"><?= h($product->category) ?></div>
                        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'view', $product->id]) ?>" class="cart-item-name">
                            <?= h($product->name) ?>
                        </a>
                    </div>

                    <div class="cart-item-controls">
                        <!-- Decrease qty -->
                        <?= $this->Form->create(null, ['url' => ['controller' => 'Cart', 'action' => 'updateQty', $product->id], 'style' => 'display:contents']) ?>
                            <?= $this->Form->hidden('delta', ['value' => -1]) ?>
                            <div class="qty-box">
                                <button type="submit" class="qty-btn" aria-label="Decrease quantity">−</button>
                                <span class="qty-display"><?= $qty ?></span>
                            </div>
                        <?= $this->Form->end() ?>

                        <!-- Increase qty -->
                        <?= $this->Form->create(null, ['url' => ['controller' => 'Cart', 'action' => 'updateQty', $product->id], 'style' => 'display:contents']) ?>
                            <?= $this->Form->hidden('delta', ['value' => 1]) ?>
                            <button type="submit" class="qty-btn" style="border:1.5px solid var(--s2);border-radius:var(--r16);" aria-label="Increase quantity">+</button>
                        <?= $this->Form->end() ?>

                        <!-- Delete entirely -->
                        <?= $this->Form->create(null, ['url' => ['controller' => 'Cart', 'action' => 'remove', $product->id], 'style' => 'display:contents']) ?>
                            <button type="submit" class="btn-delete">Delete</button>
                        <?= $this->Form->end() ?>
                    </div>
                </div>

                <!-- Right: price -->
                <div class="cart-item-price-col">
                    <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'view', $product->id]) ?>" class="cart-item-view-link">View →</a>
                    <div>
                        <div class="cart-item-unit-price">$<?= $this->Number->format($product->price, ['places' => 2]) ?> each</div>
                        <div class="cart-item-line-total">$<?= $this->Number->format($lineTotal, ['places' => 2]) ?></div>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

        <!-- Right: order summary -->
        <div class="cart-summary">
            <div class="cart-summary-title">Order Summary</div>

            <?php foreach ($products as $product):
                $qty = $cartQtys[$product->id] ?? 1;
                $lineTotal = $product->price * $qty;
            ?>
            <div class="summary-row">
                <span><?= h(mb_strimwidth($product->name, 0, 28, '…')) ?> ×<?= $qty ?></span>
                <span>$<?= $this->Number->format($lineTotal, ['places' => 2]) ?></span>
            </div>
            <?php endforeach; ?>

            <div class="summary-divider"></div>

            <div class="summary-total-row">
                <span class="summary-total-label">Total (<?= $totalItems ?> item<?= $totalItems !== 1 ? 's' : '' ?>)</span>
                <span class="summary-total-amount">$<?= $this->Number->format($grandTotal, ['places' => 2]) ?></span>
            </div>
            <p class="summary-note">Taxes and shipping calculated at checkout.</p>

            <a href="<?= $this->Url->build(['controller' => 'Payments', 'action' => 'checkout']) ?>" class="btn-checkout" style="text-decoration:none;cursor:pointer;opacity:1;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Checkout
            </a>
            <p class="checkout-note">Secured by Stripe</p>
        </div>

    </div>

    <!-- Continue shopping — sits below the items list -->
    <div class="cart-continue">
        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="btn-continue">
            ← Continue Shopping
        </a>
    </div>

<?php endif; ?>

</div>
