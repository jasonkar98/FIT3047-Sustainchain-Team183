<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $listings
 */

$this->Html->css('marketplace', ['block' => true]);
$this->assign('title', 'My Listings');
?>

<style>
    .my-listings-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2.5rem 2rem 4rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .listings-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .listings-count {
        font-size: 0.85rem;
        color: var(--muted);
    }

    .listings-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--white);
        border: 1px solid var(--s2);
        border-radius: var(--r16);
        overflow: hidden;
        font-size: 0.875rem;
    }

    .listings-table thead {
        background: var(--s1);
    }

    .listings-table th {
        text-align: left;
        padding: 0.85rem 1.25rem;
        font-size: 0.67rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--muted);
        border-bottom: 1px solid var(--s2);
        white-space: nowrap;
    }

    .listings-table th a {
        color: var(--muted);
        text-decoration: none;
    }
    .listings-table th a:hover {
        color: var(--g0);
    }

    .listings-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--s2);
        color: var(--ink);
        vertical-align: middle;
    }

    .listings-table tbody tr:last-child td {
        border-bottom: none;
    }

    .listings-table tbody tr:hover td {
        background: var(--s1);
    }

    .listing-name {
        font-weight: 600;
        color: var(--g0);
    }

    .listing-category {
        display: inline-block;
        background: var(--g6);
        color: var(--g2);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.2rem 0.6rem;
        border-radius: var(--r999);
    }

    .listing-price {
        font-weight: 700;
        color: var(--g2);
    }

    .listing-date {
        color: var(--muted);
        font-size: 0.8rem;
    }

    .row-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .action-link {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.75rem;
        border-radius: var(--r999);
        text-decoration: none;
        border: 1px solid var(--s2);
        color: var(--g0);
        background: var(--white);
        transition: border-color 0.15s, background 0.15s;
        cursor: pointer;
        font-family: inherit;
    }
    .action-link:hover {
        border-color: var(--g4);
        background: var(--s1);
    }
    .action-link.danger {
        color: #c0392b;
        border-color: #f5b8b8;
    }
    .action-link.danger:hover {
        background: #fde8e8;
        border-color: #e88080;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border: 1px solid var(--s2);
        border-radius: var(--r16);
        color: var(--muted);
    }
    .empty-state p {
        margin-bottom: 1.25rem;
        font-size: 0.95rem;
    }

    .paginator {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .pagination {
        display: flex;
        list-style: none;
        gap: 0.35rem;
        padding: 0;
        margin: 0;
    }
    .pagination li a,
    .pagination li span {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.7rem;
        border-radius: var(--r999);
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid var(--s2);
        color: var(--g0);
        text-decoration: none;
        background: var(--white);
        transition: border-color 0.15s, background 0.15s;
    }
    .pagination li a:hover {
        border-color: var(--g4);
        background: var(--s1);
    }
    .pagination li.active span {
        background: var(--g0);
        color: var(--e1);
        border-color: var(--g0);
    }
    .pagination li.disabled span {
        color: var(--muted);
        pointer-events: none;
    }
    .paginator-count {
        font-size: 0.8rem;
        color: var(--muted);
    }
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Dashboard</span>
        <h1 class="marketplace-title t-display"><em><?= $first_name ?></em>'s Listings</h1>
        <p class="marketplace-subtitle">Manage the products you've listed on the marketplace.</p>
    </div>
</div>

<div class="my-listings-page">

    <div class="listings-toolbar">
        <span class="listings-count">
            <?= $this->Paginator->counter(__('{{count}} listing(s)')) ?>
        </span>
        <?= $this->Html->link('+ New Listing', ['action' => 'add'], ['class' => 'btn btn-lime']) ?>
    </div>

    <?php if (empty($listings) || iterator_count($listings) === 0): ?>
        <div class="empty-state">
            <p><?= __("You haven't created any listings yet.") ?></p>
            <?= $this->Html->link('Create your first listing', ['action' => 'add'], ['class' => 'btn btn-lime']) ?>
        </div>
    <?php else: ?>
        <table class="listings-table">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('name', 'Product') ?></th>
                    <th><?= $this->Paginator->sort('category', 'Category') ?></th>
                    <th><?= $this->Paginator->sort('price', 'Price') ?></th>
                    <th><?= $this->Paginator->sort('created', 'Listed') ?></th>
                    <th><?= $this->Paginator->sort('modified', 'Updated') ?></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listings as $listing): ?>
                <tr>
                    <td>
                        <span class="listing-name"><?= h($listing->name) ?></span>
                    </td>
                    <td>
                        <span class="listing-category"><?= h($listing->category) ?></span>
                    </td>
                    <td>
                        <span class="listing-price">$<?= $this->Number->format($listing->price, ['places' => 2]) ?></span>
                    </td>
                    <td>
                        <span class="listing-date"><?= $listing->created->i18nFormat('dd MMM yyyy') ?></span>
                    </td>
                    <td>
                        <span class="listing-date"><?= $listing->modified->i18nFormat('dd MMM yyyy') ?></span>
                    </td>
                    <td>
                        <div class="row-actions">
                            <?= $this->Html->link('View', ['action' => 'view', $listing->id], ['class' => 'action-link']) ?>
                            <?= $this->Html->link('Edit', ['action' => 'edit', $listing->id], ['class' => 'action-link']) ?>
                            <?= $this->Form->postLink('Delete', ['action' => 'delete', $listing->id], [
                                'class' => 'action-link danger',
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete "{0}"?', $listing->name),
                            ]) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< First') ?>
                <?= $this->Paginator->prev('< Prev') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next('Next >') ?>
                <?= $this->Paginator->last('Last >>') ?>
            </ul>
            <p class="paginator-count">
                <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}')) ?>
            </p>
        </div>
    <?php endif; ?>

</div>