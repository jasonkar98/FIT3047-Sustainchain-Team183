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

/** @var iterable<\App\Model\Entity\User> $newestUsers */
$newestUsers = $newestUsers ?? [];
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
        gap: 1rem;
        margin-bottom: 0.5rem;
    }
    .enquiry-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: var(--color-text-primary, #1a1a1a);
        /* allow long unbroken strings to wrap so they don't blow out the column */
        min-width: 0;
        flex: 1 1 auto;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    .enquiry-date {
        font-size: 0.9rem;
        color: #666;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .enquiry-body {
        color: #555;
        margin-bottom: 1rem;
        line-height: 1.5;
        overflow-wrap: anywhere;
        word-break: break-word;
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

    /* Two-column dashboard layout: main + side */
    .dash-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 2rem;
        align-items: start;
    }
    @media (max-width: 900px) {
        .dash-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Sidebar — Newest Users widget */
    .side-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.25rem;
    }
    .side-card .section-head { margin-bottom: 1rem; }
    .side-card .section-head h2 { font-size: 1.15rem; }
    .users-mini-list {
        display: flex;
        flex-direction: column;
        gap: .65rem;
        margin-bottom: 1rem;
    }
    .user-mini {
        display: flex;
        flex-direction: column;
        gap: .15rem;
        padding: .55rem .75rem;
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        background: #fcfcfa;
    }
    .user-mini .name {
        font-weight: 600;
        font-size: .92rem;
        color: #1a1a1a;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    .user-mini .meta {
        font-size: .75rem;
        color: #666;
        display: flex;
        gap: .5rem;
        align-items: center;
        flex-wrap: wrap;
    }
    .user-mini .role-pill {
        background: #e8f0ec;
        color: #2e7d52;
        padding: .1rem .5rem;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 600;
    }
    .users-empty {
        font-size: .85rem;
        color: #888;
        padding: .5rem 0 1rem;
    }
    .view-all-btn {
        display: block;
        text-align: center;
        width: 100%;
        padding: .55rem;
        border-radius: 999px;
        background: #2e7d52;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        font-size: .85rem;
        transition: background .15s;
    }
    .view-all-btn:hover { background: #25623f; }

    /* ===== Make entire card clickable ===== */
    /* Wrapping anchors around enquiry items and user-mini blocks */
    a.card-link,
    a.card-link:visited {
        display: block;
        color: inherit;
        text-decoration: none;
        cursor: pointer;
        transition: transform .12s ease, box-shadow .12s ease;
    }
    a.card-link:hover .enquiry-item,
    a.card-link:hover .user-mini {
        border-color: #2e7d52;
        box-shadow: 0 4px 14px rgba(46, 125, 82, .12);
        transform: translateY(-1px);
    }
    a.card-link .enquiry-item,
    a.card-link .user-mini {
        transition: border-color .15s, box-shadow .15s, transform .15s;
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

    <div class="dash-grid">
        <!-- Main column: Incoming Enquiries -->
        <div>
            <div class="section-head">
                <h2>Incoming Enquiries</h2>
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
                            ['escape' => false, 'class' => 'card-link']
                        ) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar: Newest Users -->
        <aside class="side-card">
            <div class="section-head">
                <h2>Newest Users</h2>
            </div>

            <?php if (empty($newestUsers)): ?>
                <p class="users-empty">No users have signed up yet.</p>
            <?php else: ?>
                <div class="users-mini-list">
                    <?php foreach ($newestUsers as $u): ?>
                        <?= $this->Html->link(
                            '<div class="user-mini">'
                                . '<span class="name">' . h($u->full_name) . '</span>'
                                . '<span class="meta">'
                                    . '<span class="role-pill">' . h(ucfirst((string)$u->role)) . '</span>'
                                    . '<span>' . ($u->created ? $u->created->i18nFormat('dd MMM YYYY') : '') . '</span>'
                                . '</span>'
                            . '</div>',
                            ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'edit', $u->id],
                            ['escape' => false, 'class' => 'card-link']
                        ) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?= $this->Html->link(
                'View all users',
                ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'index'],
                ['class' => 'view-all-btn']
            ) ?>
        </aside>
    </div>
</div>