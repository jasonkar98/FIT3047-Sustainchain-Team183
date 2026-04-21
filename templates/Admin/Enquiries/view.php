<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\Enquiry $enquiry */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$this->assign('title', 'Enquiry: ' . h($enquiry->subject));
?>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Admin</span>
        <h1 class="marketplace-title t-display"><?= h($enquiry->subject) ?></h1>
    </div>
</div>

<div class="dash-page">
    <div class="enquiry-item">
        <p><strong>From:</strong> <?= h($enquiry->full_name) ?> &lt;<?= h($enquiry->email) ?>&gt;</p>
        <?php if ($enquiry->user): ?>
            <p><strong>Platform user:</strong> <?= h($enquiry->user->email) ?> (ID <?= h($enquiry->user->id) ?>)</p>
        <?php else: ?>
            <p><strong>Platform user:</strong> <em>Guest (not logged in)</em></p>
        <?php endif; ?>
        <p><strong>Received:</strong> <?= $enquiry->date->i18nFormat('dd MMM YYYY, HH:mm') ?></p>

        <hr>

        <div class="enquiry-body" style="white-space:pre-wrap;"><?= h($enquiry->body) ?></div>

        <hr>

        <div class="enquiry-status">
            <span class="status <?= $enquiry->is_read ? 'sent' : 'pending' ?>"><?= $enquiry->is_read ? 'Read' : 'Unread' ?></span>
            <span class="status <?= $enquiry->is_resolved ? 'sent' : 'pending' ?>"><?= $enquiry->is_resolved ? 'Resolved' : 'Open' ?></span>
        </div>

        <div style="margin-top:1.5rem;display:flex;gap:.75rem;">
            <?= $this->Form->postLink(
                $enquiry->is_read ? 'Mark as unread' : 'Mark as read',
                ['action' => 'toggleRead', $enquiry->id],
                ['class' => 'btn btn-outline']
            ) ?>
            <?= $this->Form->postLink(
                $enquiry->is_resolved ? 'Reopen' : 'Mark as resolved',
                ['action' => 'toggleResolved', $enquiry->id],
                ['class' => 'btn btn-lime']
            ) ?>
            <?= $this->Html->link('← Back to all enquiries', ['action' => 'index'], ['class' => 'btn btn-outline']) ?>
            <?= $this->Html->link(
                'Reply via email',
                'mailto:' . h($enquiry->email) . '?subject=' . rawurlencode('Re: ' . $enquiry->subject),
                ['class' => 'btn btn-lime']
            ) ?>
        </div>
    </div>
</div>