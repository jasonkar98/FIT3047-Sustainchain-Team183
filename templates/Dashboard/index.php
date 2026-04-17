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
</style>

<div class="dash-page">

    <!-- Welcome -->
    <div class="welcome">
        <div class="welcome-text">
            <h1>Welcome back, <em><?= $first_name ?></em></h1>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <?php foreach ($stats as $s): ?>
        <div class="stat-card">
            <div class="stat-label"><?= h($s['label']) ?></div>
            <div class="stat-num"><?= h($s['value']) ?></div>
        </div>
        <?php endforeach; ?>
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

    <!-- Account Details + Activity -->
    <div class="bottom-row">
        <!-- Account Details -->
        <div class="card">
            <div class="section-head">
                <h2>Account Details</h2>
                <?php if ($identity): ?>
                    <?= $this->Html->link('Edit Details', ['controller' => 'Users', 'action' => 'edit'], ['class' => 'btn btn-dark']) ?>
                <?php endif; ?>
            </div>
            <?php if ($identity): ?>
            <div class="account-header">
                <div class="account-avatar"><?= $avatar_initial ?></div>
                <div>
                    <div class="account-name"><?= h($identity->first_name) ?> <?= h($identity->last_name ?? '') ?></div>
                </div>
            </div>
            <div class="detail-row">
                <span class="detail-label">First Name</span>
                <span class="detail-val"><?= h($identity->first_name ?? '—') ?></span>
            </div>
                <div class="detail-row">
                <span class="detail-label">Last Name</span>
                <span class="detail-val"><?= h($identity->last_name ?? '—') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-val"><?= h($identity->email ?? '—') ?></span>
            </div>
            <?php endif; ?>
            
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="section-head">
                <h2>Recent Activity</h2>
            </div>
            <?php foreach ($activity as $a): ?>
            <div class="act-row">
                <div class="act-dot <?= h($a['theme']) ?>"><?= $a['icon'] ?></div>
                <div class="act-text"><?= h($a['text']) ?></div>
                <div class="act-time"><?= h($a['time']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>