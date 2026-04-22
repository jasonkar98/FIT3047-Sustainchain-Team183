<?php
/** @var \App\View\AppView $this */
/** @var iterable<\App\Model\Entity\Enquiry> $enquiries */
/** @var string $status */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$this->assign('title', 'Admin — Enquiries');
?>

<style>
    /* Page-scoped admin styles */
    .admin-page {
        max-width: 1240px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        font-family: 'DM Sans', sans-serif;
        color: #1a1a1a;
    }

    .admin-toolbar {
        max-width: 1240px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
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
        transition: background .15s, border-color .15s;
    }
    .admin-back:hover {
        background: #f5f7f5;
        border-color: #2e7d52;
    }

    .admin-filters {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }
    .admin-filter {
        display: inline-block;
        padding: .5rem 1rem;
        border-radius: 999px;
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #cfd7cf;
        color: #1a1a1a;
        background: #fff;
        transition: background .15s, border-color .15s, color .15s;
    }
    .admin-filter:hover {
        border-color: #2e7d52;
    }
    .admin-filter.is-active {
        background: #2e7d52;
        border-color: #2e7d52;
        color: #fff;
    }

    .admin-table-wrap {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
    }

    table.enquiries-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .9rem;
        table-layout: fixed;
    }
    table.enquiries-table td,
    table.enquiries-table th {
        overflow: visible;
        text-overflow: clip;
    }
    table.enquiries-table td.subject-cell {
        white-space: nowrap;
    }

    table.enquiries-table thead th,
    table.enquiries-table tbody td {
        padding: .9rem 1rem;
        text-align: left;
        vertical-align: middle;
        box-sizing: border-box;
    }
    table.enquiries-table thead th {
        background: #f7f9f7;
        color: #555;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }
    table.enquiries-table tbody td {
        border-bottom: 1px solid #f0f0f0;
    }
    table.enquiries-table tbody tr:last-child td {
        border-bottom: none;
    }
    table.enquiries-table tr.is-unread {
        background: #fcfcf4;
        font-weight: 600;
    }
    table.enquiries-table a.subject-link {
        color: #1a1a1a;
        text-decoration: none;
    }
    table.enquiries-table a.subject-link:hover {
        color: #2e7d52;
        text-decoration: underline;
    }

    .status-pill {
        display: inline-block;
        padding: .2rem .6rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-pill.ok     { background: #d4edda; color: #155724; }
    .status-pill.warn   { background: #fff3cd; color: #856404; }
    .status-pill.closed { background: #d1ecf1; color: #0c5460; }

    .row-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: .5rem;
    }
    .row-actions button,
    .row-actions .action-btn,
    .row-actions form {
        margin: 0;
    }
    .row-actions button,
    .row-actions .action-btn {
        width: 100%;
        background: none;
        border: 1px solid #cfd7cf;
        color: #2e7d52;
        font-size: .78rem;
        font-weight: 600;
        padding: .45rem .5rem;
        border-radius: 6px;
        cursor: pointer;
        font-family: inherit;
        text-align: center;
        transition: background .15s, border-color .15s;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        color: #888;
    }

    .paginator-wrap {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }
    .paginator-wrap ul.pagination {
        display: flex;
        gap: .35rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .paginator-wrap ul.pagination li a,
    .paginator-wrap ul.pagination li.active a {
        padding: .4rem .75rem;
        border: 1px solid #cfd7cf;
        border-radius: 6px;
        color: #1a1a1a;
        text-decoration: none;
        font-size: .85rem;
    }
    .paginator-wrap ul.pagination li.active a {
        background: #2e7d52;
        border-color: #2e7d52;
        color: #fff;
    }
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Admin</span>
        <h1 class="marketplace-title t-display">All <em>Enquiries</em></h1>
    </div>
</div>

<div class="admin-page">

    <!-- Back + filters -->
    <div class="admin-toolbar">
        <?= $this->Html->link(
            '← Back to dashboard',
            ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
            ['class' => 'admin-back', 'escape' => false]
        ) ?>

        <div class="admin-filters">
            <?= $this->Html->link('All',        ['?' => ['status' => 'all']],        ['class' => 'admin-filter' . ($status === 'all'        ? ' is-active' : '')]) ?>
            <?= $this->Html->link('Unread',     ['?' => ['status' => 'unread']],     ['class' => 'admin-filter' . ($status === 'unread'     ? ' is-active' : '')]) ?>
            <?= $this->Html->link('Unresolved', ['?' => ['status' => 'unresolved']], ['class' => 'admin-filter' . ($status === 'unresolved' ? ' is-active' : '')]) ?>
        </div>
    </div>

    <?php $enquiriesList = $enquiries->toArray(); ?>

    <?php if (empty($enquiriesList)): ?>
        <div class="empty-state">
            <p>No enquiries match this filter.</p>
        </div>
    <?php else: ?>
        <div class="admin-table-wrap">
            <table class="enquiries-table">
                <thead>
                    <tr>
                        <th style="width:110px;">Date</th>
                        <th style="width:110px;">From</th>
                        <th style="width:190px;">Email</th>
                        <th>Subject</th>
                        <th style="width:80px;">Read</th>
                        <th style="width:95px;">Resolved</th>
                        <th style="width:140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($enquiriesList as $enquiry): ?>
                    <tr class="<?= $enquiry->is_read ? '' : 'is-unread' ?>">
                        <td><?= $enquiry->date->i18nFormat('dd MMM YYYY') ?></td>
                        <td><?= h($enquiry->full_name) ?></td>
                        <td><?= h($enquiry->email) ?></td>
                        <td class="subject-cell">
                            <?= $this->Html->link(
                                h(mb_strimwidth($enquiry->subject, 0, 60, '…')),
                                ['action' => 'view', $enquiry->id],
                                ['class' => 'subject-link']
                            ) ?>
                        </td>
                        <td>
                            <span class="status-pill <?= $enquiry->is_read ? 'closed' : 'warn' ?>">
                                <?= $enquiry->is_read ? 'Read' : 'Unread' ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-pill <?= $enquiry->is_resolved ? 'ok' : 'warn' ?>">
                                <?= $enquiry->is_resolved ? 'Resolved' : 'Open' ?>
                            </span>
                        </td>
                        
                        <td>
                            <div class="row-actions">
                                <?= $this->Form->postLink(
                                    $enquiry->is_read ? 'Mark unread' : 'Mark read',
                                    ['action' => 'toggleRead', $enquiry->id],
                                    ['class' => 'action-btn']
                                ) ?>
                                <?= $this->Form->postLink(
                                    $enquiry->is_resolved ? 'Reopen' : 'Resolve',
                                    ['action' => 'toggleResolved', $enquiry->id],
                                    ['class' => 'action-btn']
                                ) ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="paginator-wrap">
            <ul class="pagination">
                <?= $this->Paginator->prev('‹ ' . __('prev')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' ›') ?>
            </ul>
        </div>
    <?php endif; ?>
</div>