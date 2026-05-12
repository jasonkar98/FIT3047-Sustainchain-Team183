<?php
/** @var \App\View\AppView $this */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$identity = $this->request->getAttribute('identity');
$this->assign('title', 'Dashboard');

$stats = [
    ['label' => 'Total Enquiries', 'value' => $counts['total']],
    ['label' => 'Unread',          'value' => $counts['unread']],
    ['label' => 'Unresolved',      'value' => $counts['unresolved']],
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
        <span class="t-label section-tag">Admin Dashboard</span>
        <h1 class="marketplace-title t-display">
            Welcome back, <em><?= $first_name ?></em>
        </h1>
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
    <!-- Incoming Enquiries (admin only) -->
    <div>
        <div class="section-head">
            <h2>Incoming Enquiries</h2>
            <?= $this->Html->link('Content Blocks', ['prefix' => false, 'plugin' => 'ContentBlocks', 'controller' => 'ContentBlocks', 'action' => 'index'], ['class' => 'btn btn-lime']) ?>
            <?= $this->Html->link('View all →', ['prefix' => 'Admin', 'controller' => 'Enquiries', 'action' => 'index'], ['class' => 'btn btn-lime']) ?>
        </div>

        <?php if (empty($recentEnquiries)): ?>
            <div class="favourites-empty"><p>No enquiries yet.</p></div>
        <?php else: ?>
            <div class="enquiries-list">
                <?php foreach ($recentEnquiries as $enquiry): ?>
                    <?= $this->Html->link(
                        '<div class="enquiry-item' . ($enquiry->is_read ? '' : ' unread') . '">
                            <div class="enquiry-header">
                                <h3>' . h($enquiry->subject) . '</h3>
                                <span class="enquiry-date">' . $enquiry->date->i18nFormat('dd MMM YYYY') . '</span>
                            </div>
                            <p class="enquiry-body">' . h(substr($enquiry->body, 0, 150)) . '…</p>
                            <div class="enquiry-status">
                                <span class="status ' . ($enquiry->is_read ? 'sent' : 'pending') . '">' . ($enquiry->is_read ? 'Read' : 'Unread') . '</span>
                                <span class="status ' . ($enquiry->is_resolved ? 'sent' : 'pending') . '">' . ($enquiry->is_resolved ? 'Resolved' : 'Open') . '</span>
                            </div>
                        </div>',
                        ['prefix' => 'Admin', 'controller' => 'Enquiries', 'action' => 'view', $enquiry->id],
                        ['escape' => false]
                    ) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>