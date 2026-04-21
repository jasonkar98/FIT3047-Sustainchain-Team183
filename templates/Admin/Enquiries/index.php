<?php
/** @var \App\View\AppView $this */
/** @var iterable<\App\Model\Entity\Enquiry> $enquiries */
/** @var string $status */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$this->assign('title', 'Admin — Enquiries');
?>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Admin</span>
        <h1 class="marketplace-title t-display">All <em>Enquiries</em></h1>
    </div>
</div>

<div class="dash-page">
    <!-- Filter tabs -->
    <div class="filter-tabs" style="margin-bottom:1.5rem;">
        <?= $this->Html->link('All', ['?' => ['status' => 'all']], ['class' => 'btn ' . ($status === 'all' ? 'btn-lime' : 'btn-outline')]) ?>
        <?= $this->Html->link('Unread', ['?' => ['status' => 'unread']], ['class' => 'btn ' . ($status === 'unread' ? 'btn-lime' : 'btn-outline')]) ?>
        <?= $this->Html->link('Unresolved', ['?' => ['status' => 'unresolved']], ['class' => 'btn ' . ($status === 'unresolved' ? 'btn-lime' : 'btn-outline')]) ?>
    </div>

    <?php $enquiriesList = $enquiries->toArray(); ?>

    <?php if (empty($enquiriesList)): ?>
        <div class="favourites-empty"><p>No enquiries match this filter.</p></div>
    <?php else: ?>
        <table class="enquiries-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Date</th><th>From</th><th>Email</th><th>Subject</th><th>Read</th><th>Resolved</th><th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($enquiriesList as $enquiry): ?>
                <tr style="<?= $enquiry->is_read ? '' : 'font-weight:600;background:#fafafa;' ?>">
                    <td><?= $enquiry->date->i18nFormat('dd MMM YYYY, HH:mm') ?></td>
                    <td><?= h($enquiry->full_name) ?></td>
                    <td><?= h($enquiry->email) ?></td>
                    <td><?= $this->Html->link(
                        h(substr($enquiry->subject, 0, 60)),
                        ['action' => 'view', $enquiry->id]
                    ) ?></td>
                    <td><span class="status <?= $enquiry->is_read ? 'sent' : 'pending' ?>"><?= $enquiry->is_read ? 'Read' : 'Unread' ?></span></td>
                    <td><span class="status <?= $enquiry->is_resolved ? 'sent' : 'pending' ?>"><?= $enquiry->is_resolved ? 'Resolved' : 'Open' ?></span></td>
                    <td>
                        <?= $this->Form->postLink(
                            $enquiry->is_read ? 'Mark unread' : 'Mark read',
                            ['action' => 'toggleRead', $enquiry->id],
                            ['class' => 'btn-link']
                        ) ?>
                        <?= $this->Form->postLink(
                            $enquiry->is_resolved ? 'Reopen' : 'Resolve',
                            ['action' => 'toggleResolved', $enquiry->id],
                            ['class' => 'btn-link']
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="paginator" style="margin-top:1.5rem;">
            <ul class="pagination">
                <?= $this->Paginator->prev('‹ ' . __('prev')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' ›') ?>
            </ul>
        </div>
    <?php endif; ?>
</div>