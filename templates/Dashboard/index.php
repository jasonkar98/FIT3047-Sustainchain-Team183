<?php
/** @var \App\View\AppView $this */

$this->Html->css('marketplace', ['block' => true]);

$identity = $this->request->getAttribute('identity');
$this->assign('title', 'Dashboard');

$stats = [
    ['label' => 'Saved Products', 'value' => count($favourites)],
    ['label' => 'Total Orders',   'value' => '12'],
    ['label' => 'Member Since', 'value' => $identity ? $identity->created->i18nFormat('dd MMM YYYY') : '—'],
];

$first_name = $identity ? h($identity->first_name) : 'there';
$avatar_initial = $identity ? strtoupper(substr(h($identity->first_name), 0, 1)) : '?';
?>

<style>
    .dash-page {
        font-family: 'DM Sans', sans-serif;
        color: var(--color-text-primary, #1a1a1a);
        max-width: 960px;
        padding: 2rem 0 3rem;
    }

    /* Welcome */
    .welcome {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2rem;
    }
    .welcome h1 {
        font-family: 'DM Serif Display', serif;
        font-size: 2.4rem;
        font-weight: 400;
        line-height: 1.1;
        color: inherit;
    }
    .welcome h1 em {
        font-style: italic;
        color: var(--g3);
    }

    /* Empty state */
    .favourites-empty {
        text-align: center;
        padding: 2.5rem;
        background: #ffffff;
        border-radius: 12px;
        color: #888;
        font-size: 14px;
        margin-bottom: 2.5rem;
    }
    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    .section-head h1 {
        font-family: "DM Serif Display", serif;
        font-size: 2rem;
        font-weight: 400;
    }
    .section-head h2 {
        font-family: "DM Serif Display", serif;
        font-size: 1.5rem;
        font-weight: 400;
        margin: 0;
    }

    /* Enquiries */
    .enquiries-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .enquiry-item {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e0e0e0;
    }
    .enquiry-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    .enquiry-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: var(--color-text-primary, #1a1a1a);
    }
    .enquiry-date {
        font-size: 0.9rem;
        color: #666;
    }
    .enquiry-body {
        color: #555;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    .enquiry-status .status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .status.sent {
        background: #d4edda;
        color: #155724;
    }
    .status.pending {
        background: #fff3cd;
        color: #856404;
    }
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Dashboard</span>
        <h1 class="marketplace-title t-display">
            Welcome back, <em><?= $first_name ?></em>
        </h1>
        <p class="marketplace-subtitle">
            Everything you need to manage your sustainable shopping in one place.
        </p>
    </div>
</div>


<div class="dash-page">

    <!-- Stats -->
    <div class="stats-row">
        <?php foreach ($stats as $s): ?>
        <div class="stat-card">
            <div class="stat-label"><?= h($s['label']) ?></div>
            <div class="stat-num"><?= h($s['value']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div>
        <div class="section-head">
            <h2>My Inquiries</h2>
        </div>

        <?php if (empty($enquiries)): ?>
        <div class="favourites-empty">
            <p><?= __('You have not submitted any inquiries.') ?></p>
        </div>
        <?php else: ?>
        <div class="enquiries-list">
            <?php foreach ($enquiries as $enquiry): ?>
            <div class="enquiry-item">
                <div class="enquiry-header">
                    <h3><?= h($enquiry->subject) ?></h3>
                    <span class="enquiry-date"><?= $enquiry->date->i18nFormat('dd MMM YYYY') ?></span>
                </div>
                <p class="enquiry-body"><?= h(substr($enquiry->body, 0, 200)) ?><?php if (strlen($enquiry->body) > 200): ?>...<?php endif; ?></p>
                <div class="enquiry-status">
                    <?php if ($enquiry->email_sent): ?>
                        <span class="status sent">Response Sent</span>
                    <?php else: ?>
                        <span class="status pending">Pending Response</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Ordered Products -->
    <div>
        <div class="section-head">
            <h2>My Orders</h2>
        </div>

        <?php if (empty($orders)): ?>
        <div class="favourites-empty">
            <p><?= __('You have not placed any orders.') ?></p>
        </div>
        <?php else: ?>
        <div class="product-grid">
            <?php foreach ($orders as $order): ?>
                <?php if (!empty($order->product)): ?>
                    <?= $this->element('product_card', [
                        'product' => $order->product,
                        'showSaveButton' => true,
                        'isSaved' => true,
                    ]) ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Saved Products -->
    <div>
        <div class="section-head">
            <h2>Saved Listings</h2>
            <?= $this->Html->link('All Saved →', ['controller' => 'Favourites', 'action' => 'index'], ['class' => 'btn btn-lime']) ?>
        </div>

        <?php if (empty($favourites)): ?>
        <div class="favourites-empty">
            <p><?= __('You have not saved any products. Start browsing products and add some favourites to see them here.') ?></p>
        </div>
        <?php else: ?>
        <div class="product-grid">
            <?php foreach ($favourites as $favourite): ?>
                <?php if (!empty($favourite->product)): ?>
                    <?= $this->element('product_card', [
                        'product' => $favourite->product,
                        'showSaveButton' => true,
                        'isSaved' => true,
                    ]) ?>
                <?php endif; ?>
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

    <div>
        <div class="section-head">
            <h2>My Listings</h2>
            <?= $this->Html->link('All Listings →', ['controller' => 'Listings', 'action' => 'index'], ['class' => 'btn btn-lime']) ?>
        </div>

        <?php if (empty($favourites)): ?>
        <div class="favourites-empty">
            <p><?= __('You have not created any listings.') ?></p>
        </div>
        <?php else: ?>
        <div class="product-grid">
            <?php foreach ($favourites as $favourite): ?>
                <?php if (!empty($favourite->product)): ?>
                    <?= $this->element('product_card', [
                        'product' => $favourite->product,
                        'showSaveButton' => true,
                        'isSaved' => true,
                    ]) ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>