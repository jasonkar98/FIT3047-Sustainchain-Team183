<?php
/** @var \App\View\AppView $this */

$identity = $this->request->getAttribute('identity');
$this->assign('title', 'Dashboard');

$activity = [
    ['icon' => '🛒', 'theme' => 'green', 'text' => '<strong>Order placed</strong> — Weekly Veggie Box',     'time' => '2h ago'],
    ['icon' => '★',  'theme' => 'lime',  'text' => '<strong>Saved</strong> — Raw Wildflower Honey',         'time' => 'Yesterday'],
    ['icon' => '📦', 'theme' => 'warm',  'text' => '<strong>Delivered</strong> — Organic Matcha Blend',     'time' => 'Apr 6'],
    ['icon' => '🔄', 'theme' => 'green', 'text' => '<strong>Renewed</strong> Pro membership',               'time' => 'Apr 1'],
    ['icon' => '🛒', 'theme' => 'lime',  'text' => '<strong>Order placed</strong> — Ancient Grain Granola', 'time' => 'Mar 28'],
];

$stats = [
    ['label' => 'Saved Products', 'value' => count($favourites), 'sub' => 'Across 3 categories'],
    ['label' => 'Total Orders',   'value' => '12',                    'sub' => '2 pending delivery'],
    ['label' => 'Member Since',   'value' => 'Feb \'24',              'sub' => 'Pro plan · Renews Aug \'25'],
];

$first_name = $identity ? h($identity->first_name) : 'there';
?>

<div class="dash-page">
    <div class="welcome">
        <div class="welcome-text">
            <h1>Welcome back, <em><?= $first_name ?></em></h1>
        </div>
        <div class="welcome-actions">
            <?= $this->Html->link('View Orders', ['controller' => 'Orders', 'action' => 'index'], ['class' => 'btn btn-outline']) ?>
            <?= $this->Html->link('Browse Products →', ['controller' => 'Products', 'action' => 'marketplace'], ['class' => 'btn btn-lime']) ?>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <?php foreach ($stats as $s): ?>
        <div class="stat-card">
            <div class="stat-label"><?= h($s['label']) ?></div>
            <div class="stat-num"><?= h($s['value']) ?></div>
            <div class="stat-sub"><?= h($s['sub']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Saved Products -->

    <div>
        <div class="section-head">
            <div class="welcome-text">
                <h1>Favourites</h1>
            </div>
            <?= $this->Html->link('All Saved →', ['controller' => 'Favourites', 'action' => 'index'], ['class' => 'btn btn-lime']) ?>
        </div>
        <?php if (empty($favourites)): ?>
        <div class="favourites-empty">
            <p><?= __('You have not saved any favourites. Start browsing products and add some favourites to see them here.') ?></p>
        </div>
        <?php else: ?>
        <div class="products-grid">
            <?php foreach ($favourites as $p): ?>
            <div class="prod-card">
                <div class="prod-img <?= h($p['theme']) ?>"><?= $p['emoji'] ?></div>
                <div class="prod-body">
                    <div class="prod-name"><?= h($p['name']) ?></div>
                    <div class="prod-type"><?= h($p['category']) ?></div>
                    <div class="prod-footer">
                        <div class="prod-price"><?= h($p['price']) ?></div>
                        <div class="prod-unsave" title="Remove from saved">
                            <svg viewBox="0 0 12 12" width="12" height="12" fill="none" stroke="#cc4444" stroke-width="1.5">
                                <path d="M2 2 10 10M10 2 2 10"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>


    <!-- Account Details + Activity -->
    <div class="bottom-row">

        <!-- Account Details -->
        <div class="card">
            <div class="section-head">
                <h2>Account Details</h2>
                <?php if ($identity): ?>
                    <?= $this->Html->link('Edit profile', ['controller' => 'Users', 'action' => 'edit', $identity->id], ['class' => 'see-all']) ?>
                <?php endif; ?>
            </div>
            <?php if ($identity): ?>
            <div class="account-header">
                <div class="account-avatar">
                    <?= strtoupper(substr(h($identity->first_name), 0, 1)) ?>
                </div>
                <div>
                    <div class="account-name"><?= h($identity->first_name) ?> <?= h($identity->last_name ?? '') ?></div>
                    <div class="plan-badge">✦ Pro Member</div>
                </div>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-val"><?= h($identity->email ?? '—') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Username</span>
                <span class="detail-val"><?= h($identity->username ?? '—') ?></span>
            </div>
            <?php endif; ?>
            <button class="edit-btn">Edit Account →</button>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="section-head">
                <h2>Recent Activity</h2>
            </div>
            <?php foreach ($activity as $a): ?>
            <div class="act-row">
                <div class="act-dot <?= h($a['theme']) ?>"><?= $a['icon'] ?></div>
                <div class="act-text"><?= $a['text'] ?></div>
                <div class="act-time"><?= h($a['time']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>