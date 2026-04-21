<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\Enquiry $enquiry */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$this->assign('title', 'Enquiry: ' . h($enquiry->subject));
?>

<style>
    .admin-page {
        max-width: 820px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        font-family: 'DM Sans', sans-serif;
        color: #1a1a1a;
    }

    .admin-back {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .9rem;
        font-weight: 600;
        color: #2e7d52;
        text-decoration: none;
        padding: .5rem 1rem;
        border-radius: 999px;
        border: 1px solid #cfd7cf;
        background: #fff;
        margin-bottom: 1.25rem;
        transition: background .15s, border-color .15s;
    }
    .admin-back:hover {
        background: #f5f7f5;
        border-color: #2e7d52;
    }

    .enquiry-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 16px;
        padding: 2rem;
    }

    .meta-row {
        display: flex;
        gap: 1rem;
        font-size: .88rem;
        color: #555;
        margin-bottom: .5rem;
        flex-wrap: wrap;
    }
    .meta-row strong {
        color: #1a1a1a;
        font-weight: 700;
    }

    .pill-row {
        display: flex;
        gap: .5rem;
        margin: 1rem 0 1.25rem;
        flex-wrap: wrap;
    }
    .status-pill {
        display: inline-block;
        padding: .25rem .75rem;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
    }
    .status-pill.ok     { background: #d4edda; color: #155724; }
    .status-pill.warn   { background: #fff3cd; color: #856404; }
    .status-pill.closed { background: #d1ecf1; color: #0c5460; }

    hr.divider {
        border: none;
        border-top: 1px solid #ececec;
        margin: 1.5rem 0;
    }

    .enquiry-body {
        white-space: pre-wrap;
        line-height: 1.6;
        color: #333;
        font-size: .98rem;
    }

    .action-bar {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        margin-top: 1.75rem;
        padding-top: 1.5rem;
        border-top: 1px solid #ececec;
    }

    /* All action buttons share the same base */
    .action-bar .btn,
    .action-bar form button,
    .action-bar form input[type="submit"] {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .6rem 1.2rem;
        border-radius: 8px;
        font-size: .88rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        font-family: inherit;
        border: 1px solid #cfd7cf;
        background: #fff;
        color: #1a1a1a;
        transition: background .15s, border-color .15s, color .15s, transform .12s;
    }
    .action-bar .btn:hover,
    .action-bar form button:hover {
        background: #f5f7f5;
        border-color: #2e7d52;
        color: #2e7d52;
    }
    .action-bar .btn-primary,
    .action-bar form.primary button {
        background: #c8e840;
        border-color: #c8e840;
        color: #0d1f14;
    }
    .action-bar .btn-primary:hover,
    .action-bar form.primary button:hover {
        background: #b5d435;
        border-color: #b5d435;
        color: #0d1f14;
    }
    .action-bar .btn-dark {
        background: #2e7d52;
        border-color: #2e7d52;
        color: #fff;
    }
    .action-bar .btn-dark:hover {
        background: #276a46;
        border-color: #276a46;
        color: #fff;
    }
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Admin · Enquiry</span>
        <h1 class="marketplace-title t-display"><?= h($enquiry->subject) ?></h1>
    </div>
</div>

<div class="admin-page">
    <?= $this->Html->link(
        '← Back to all enquiries',
        ['action' => 'index'],
        ['class' => 'admin-back', 'escape' => false]
    ) ?>

    <div class="enquiry-card">
        <div class="meta-row"><strong>From:</strong> <?= h($enquiry->full_name) ?> &lt;<?= h($enquiry->email) ?>&gt;</div>
        <?php if ($enquiry->user): ?>
            <div class="meta-row"><strong>Platform user:</strong> <?= h($enquiry->user->email) ?> (ID <?= h($enquiry->user->id) ?>)</div>
        <?php else: ?>
            <div class="meta-row"><strong>Platform user:</strong> <em>Guest (not logged in)</em></div>
        <?php endif; ?>
        <div class="meta-row"><strong>Received:</strong> <?= $enquiry->date->i18nFormat('dd MMM YYYY, HH:mm') ?></div>

        <div class="pill-row">
            <span class="status-pill <?= $enquiry->is_read ? 'closed' : 'warn' ?>">
                <?= $enquiry->is_read ? 'Read' : 'Unread' ?>
            </span>
            <span class="status-pill <?= $enquiry->is_resolved ? 'ok' : 'warn' ?>">
                <?= $enquiry->is_resolved ? 'Resolved' : 'Open' ?>
            </span>
        </div>

        <hr class="divider">

        <div class="enquiry-body"><?= h($enquiry->body) ?></div>

        <div class="action-bar">
            <?= $this->Form->postLink(
                $enquiry->is_read ? 'Mark as unread' : 'Mark as read',
                ['action' => 'toggleRead', $enquiry->id],
                ['class' => 'btn']
            ) ?>

            <?= $this->Form->postLink(
                $enquiry->is_resolved ? 'Reopen' : 'Mark as resolved',
                ['action' => 'toggleResolved', $enquiry->id],
                ['class' => 'btn btn-primary']
            ) ?>

            <?= $this->Html->link(
                'Reply via email',
                'mailto:' . h($enquiry->email) . '?subject=' . rawurlencode('Re: ' . $enquiry->subject),
                ['class' => 'btn btn-dark']
            ) ?>
        </div>
    </div>
</div>