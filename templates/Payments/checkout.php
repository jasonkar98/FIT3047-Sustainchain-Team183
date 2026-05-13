<?php
$this->assign('title', 'Checkout — SustainChain');
$this->Html->css('marketplace', ['block' => true]);
$csrfToken = $this->request->getAttribute('csrfToken');
?>

<style>
/* ── Header ── */
.checkout-header {
    background: var(--g0);
    color: var(--white);
    padding: 4rem 2.5rem 3rem;
    text-align: center;
}
.checkout-header-inner { max-width: 800px; margin: 0 auto; }
.checkout-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1.05;
    margin: 0.5rem 0 0;
}

/* ── Layout ── */
.checkout-wrap {
    padding: 3rem 2.5rem 6rem;
    max-width: 1000px;
    margin: 0 auto;
}
.checkout-body {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 2.5rem;
    align-items: start;
}

/* ── Payment form card ── */
.payment-card {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(13,31,20,.07);
}
.payment-card-title {
    font-family: 'Fraunces', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--g0);
    margin-bottom: 1.5rem;
}
#payment-element { margin-bottom: 1.5rem; }
#payment-message {
    color: #c62828;
    font-size: 0.88rem;
    font-family: 'Cabinet Grotesk', sans-serif;
    margin-bottom: 1rem;
    display: none;
}
#payment-message.visible { display: block; }

.btn-pay {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    background: var(--g0);
    color: var(--white);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: 1rem;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: var(--r16);
    cursor: pointer;
    box-sizing: border-box;
    letter-spacing: -0.01em;
    transition: background 0.2s, transform 0.18s;
}
.btn-pay:hover:not(:disabled) { background: var(--g2); transform: translateY(-2px); }
.btn-pay:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

.secure-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    color: var(--ink);
    opacity: 0.55;
    margin-top: 0.75rem;
    font-family: 'Cabinet Grotesk', sans-serif;
}

/* ── Order summary card ── */
.order-summary-card {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    padding: 1.75rem;
    box-shadow: 0 4px 20px rgba(13,31,20,.07);
    position: sticky;
    top: 90px;
}
.order-summary-title {
    font-family: 'Fraunces', serif;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--g0);
    margin-bottom: 1.25rem;
}
.order-item-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 0;
    border-bottom: 1px solid var(--s1);
}
.order-item-row:last-of-type { border-bottom: none; }
.order-item-thumb {
    width: 44px;
    height: 44px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
}
.order-item-name {
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--g0);
    flex: 1;
    line-height: 1.25;
}
.order-item-qty {
    font-size: 0.78rem;
    color: var(--ink);
    opacity: 0.6;
}
.order-item-price {
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--g0);
}
.summary-divider { height: 1px; background: var(--s2); margin: 1rem 0; }
.summary-total-row {
    display: flex;
    justify-content: space-between;
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--g0);
}

@media (max-width: 820px) {
    .checkout-body { grid-template-columns: 1fr; }
    .order-summary-card { position: static; }
}
@media (max-width: 540px) {
    .checkout-wrap { padding: 1.5rem 1rem 4rem; }
    .checkout-header { padding: 2.5rem 1rem 2rem; }
}
</style>

<!-- Header -->
<div class="checkout-header">
    <div class="checkout-header-inner">
        <span class="t-label section-tag">SustainChain</span>
        <h1 class="checkout-title">Secure Checkout</h1>
    </div>
</div>

<div class="checkout-wrap">
    <div class="checkout-body">

        <!-- Left: Stripe Payment Element -->
        <div class="payment-card">
            <div class="payment-card-title">Payment Details</div>
            <form id="payment-form">
                <div id="payment-element"></div>
                <div id="payment-message"></div>
                <button id="submit-btn" class="btn-pay" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    <span id="btn-label">Loading…</span>
                </button>
            </form>
            <p class="secure-note">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Payments secured by Stripe
            </p>
        </div>

        <!-- Right: order summary -->
        <div class="order-summary-card">
            <div class="order-summary-title">Order Summary</div>

            <?php foreach ($products as $product):
                $qty = $cartQtys[$product->id] ?? 1;
                $lineTotal = $product->price * $qty;
            ?>
            <div class="order-item-row">
                <?php if (!empty($product->image_url)): ?>
                    <?= $this->Html->image('products/' . $product->image_url, [
                        'class' => 'order-item-thumb',
                        'alt'   => h($product->name),
                    ]) ?>
                <?php else: ?>
                    <img class="order-item-thumb" src="https://placehold.co/44x44/d9ede4/2e7d52?text=?" alt="">
                <?php endif; ?>
                <span class="order-item-name"><?= h(mb_strimwidth($product->name, 0, 32, '…')) ?></span>
                <span class="order-item-qty">×<?= $qty ?></span>
                <span class="order-item-price">$<?= $this->Number->format($lineTotal, ['places' => 2]) ?></span>
            </div>
            <?php endforeach; ?>

            <div class="summary-divider"></div>
            <div class="summary-total-row">
                <span>Total (<?= $totalItems ?> item<?= $totalItems !== 1 ? 's' : '' ?>)</span>
                <span>$<?= $this->Number->format($grandTotal, ['places' => 2]) ?></span>
            </div>
        </div>

    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
(async () => {
    const stripe = Stripe(<?= json_encode($stripePublishableKey) ?>);
    const csrfToken = <?= json_encode($csrfToken) ?>;
    const returnUrl = <?= json_encode($this->Url->build(['controller' => 'Payments', 'action' => 'success'], ['fullBase' => true])) ?>;

    // Fetch clientSecret from server
    let clientSecret;
    try {
        const res = await fetch(<?= json_encode($this->Url->build(['controller' => 'Payments', 'action' => 'createPaymentIntent'])) ?>, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken,
            },
            body: JSON.stringify({}),
        });
        const data = await res.json();
        if (data.error) throw new Error(data.error);
        clientSecret = data.clientSecret;
    } catch (err) {
        showMessage(err.message || 'Could not initialise payment. Please try again.');
        return;
    }

    const elements = stripe.elements({ clientSecret });
    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    const submitBtn = document.getElementById('submit-btn');
    const btnLabel  = document.getElementById('btn-label');

    paymentElement.on('ready', () => {
        submitBtn.disabled = false;
        btnLabel.textContent = 'Pay $<?= number_format($grandTotal, 2) ?> AUD';
    });

    document.getElementById('payment-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        setLoading(true);

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: { return_url: returnUrl },
        });

        // Only runs if confirmPayment fails (redirect didn't happen)
        if (error) {
            showMessage(error.message);
        }
        setLoading(false);
    });

    function setLoading(loading) {
        submitBtn.disabled = loading;
        btnLabel.textContent = loading
            ? 'Processing…'
            : 'Pay $<?= number_format($grandTotal, 2) ?> AUD';
    }

    function showMessage(msg) {
        const el = document.getElementById('payment-message');
        el.textContent = msg;
        el.classList.add('visible');
        setTimeout(() => el.classList.remove('visible'), 6000);
    }
})();
</script>
