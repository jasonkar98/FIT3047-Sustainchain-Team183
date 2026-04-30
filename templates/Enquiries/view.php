<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\Enquiry $enquiry */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$this->assign('title', 'Enquiry: ' . h($enquiry->subject));
?>

<style>
    .enquiry-view-page {
        max-width: 820px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        font-family: 'DM Sans', sans-serif;
        color: #1a1a1a;
    }

    .enquiry-view-back {
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
    .enquiry-view-back:hover {
        background: #f5f7f5;
        border-color: #2e7d52;
    }

    .enquiry-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 16px;
        padding: 2rem;
    }

    .enquiry-card h1 {
        margin: 0 0 1.5rem 0;
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .meta-row {
        display: flex;
        gap: 1rem;
        font-size: .88rem;
        color: #555;
        margin-bottom: .75rem;
        flex-wrap: wrap;
    }
    .meta-row strong {
        color: #1a1a1a;
        font-weight: 700;
    }

    .enquiry-status {
        display: flex;
        gap: 0.5rem;
        margin: 1rem 0 1.25rem;
        flex-wrap: wrap;
    }

    .status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status.sent {
        background: #d4edda;
        color: #155724;
    }

    .status.pending {
        background: #fff3cd;
        color: #856404;
    }

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
        word-wrap: break-word;
    }
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Enquiry</span>
        <h1 class="marketplace-title t-display"><?= h($enquiry->subject) ?></h1>
    </div>
</div>

<div class="enquiry-view-page">
    <?= $this->Html->link('← Back to Dashboard', ['controller' => 'Dashboard', 'action' => 'index'], ['class' => 'enquiry-view-back']) ?>
    
    <div class="enquiry-card">
        <h1><?= h($enquiry->subject) ?></h1>
        
        <div class="meta-row">
            <div><strong>Your Email:</strong> <?= h($enquiry->email) ?></div>
        </div>
        <div class="meta-row">
            <div><strong>Sent:</strong> <?= $enquiry->date->i18nFormat('dd MMM YYYY, HH:mm') ?></div>
        </div>

        <div class="enquiry-status">
            <?php if ($enquiry->email_sent): ?>
                <span class="status sent">Response Sent</span>
            <?php endif; ?>
            <?php if ($enquiry->is_resolved): ?>
                    <span class="status sent">Resolved</span>
            <?php else: ?>
                <span class="status pending">Pending Response</span>
            <?php endif; ?>
        </div>

        <hr class="divider">

        <div class="enquiry-body">
            <?= h($enquiry->body) ?>
        </div>
    </div>
</div>