<?php
$this->assign('title', 'Order Confirmed — SustainChain');
$this->Html->css('marketplace', ['block' => true]);
?>

<style>
.success-header {
    background: var(--g0);
    color: var(--white);
    padding: 5rem 2.5rem 4rem;
    text-align: center;
}
.success-header-inner { max-width: 640px; margin: 0 auto; }
.success-icon {
    width: 72px;
    height: 72px;
    background: rgba(200,232,64,.18);
    border: 2px solid rgba(200,232,64,.4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
}
.success-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    letter-spacing: -0.03em;
    margin: 0 0 0.5rem;
}
.success-subtitle {
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 1.05rem;
    opacity: 0.8;
}

.success-wrap {
    padding: 3rem 2.5rem 6rem;
    max-width: 640px;
    margin: 0 auto;
}

.order-card {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(13,31,20,.07);
    margin-bottom: 2rem;
}
.order-card-title {
    font-family: 'Fraunces', serif;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--g0);
    margin-bottom: 1.25rem;
}
.order-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 0.9rem;
    color: var(--ink);
    padding: 0.4rem 0;
    border-bottom: 1px solid var(--s1);
}
.order-meta-row:last-child { border-bottom: none; }
.order-meta-label { opacity: 0.65; }
.order-meta-value { font-weight: 700; color: var(--g0); }
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.25);
    color: #15803d;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 0.25rem 0.75rem;
    border-radius: var(--r999);
}

.success-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.btn-primary-action {
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
    border-radius: var(--r16);
    text-decoration: none;
    transition: background 0.2s, transform 0.18s;
    box-shadow: 0 4px 20px rgba(13,31,20,.2);
}
.btn-primary-action:hover { background: var(--g2); transform: translateY(-2px); }
.btn-secondary-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    background: var(--white);
    color: var(--g0);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 600;
    font-size: 0.95rem;
    padding: 0.85rem 1.5rem;
    border: 1px solid var(--s2);
    border-radius: var(--r16);
    text-decoration: none;
    transition: background 0.15s, transform 0.15s;
}
.btn-secondary-action:hover { background: var(--s1); transform: translateY(-1px); }

@media (max-width: 540px) {
    .success-wrap { padding: 1.5rem 1rem 4rem; }
    .success-header { padding: 3rem 1rem 2.5rem; }
}
</style>

<!-- Header -->
<div class="success-header">
    <div class="success-header-inner">
        <div class="success-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
        <h1 class="success-title">Order Confirmed!</h1>
        <p class="success-subtitle">Thank you for your purchase. Your order is being processed.</p>
    </div>
</div>

<div class="success-wrap">

    <div class="order-card">
        <div class="order-card-title">Order Details</div>

        <div class="order-meta-row">
            <span class="order-meta-label">Order Number</span>
            <span class="order-meta-value"><?= h($order->order_number) ?></span>
        </div>
        <div class="order-meta-row">
            <span class="order-meta-label">Date</span>
            <span class="order-meta-value"><?= $order->created->format('d M Y, g:i A') ?></span>
        </div>
        <div class="order-meta-row">
            <span class="order-meta-label">Total Paid</span>
            <span class="order-meta-value">$<?= $this->Number->format($order->total_amount, ['places' => 2]) ?> AUD</span>
        </div>
        <div class="order-meta-row">
            <span class="order-meta-label">Status</span>
            <span>
                <span class="status-badge">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    Paid
                </span>
            </span>
        </div>
    </div>

    <div class="success-actions">
        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="btn-primary-action">
            Continue Shopping
        </a>
        <a href="<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'index']) ?>" class="btn-secondary-action">
            Go to Dashboard
        </a>
    </div>

</div>
