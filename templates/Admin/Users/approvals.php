<?php
/** @var \App\View\AppView $this */
/** @var array<\App\Model\Entity\User> $pending */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$this->assign('title', 'Admin — Pending Approvals');

$roleLabels = [
    'farmer'       => 'Farmer',
    'manufacturer' => 'Manufacturer',
];
?>

<style>
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
    .summary-pill {
        display: inline-flex;
        gap: .35rem;
        align-items: center;
        padding: .5rem 1rem;
        border-radius: 999px;
        background: #fff;
        border: 1px solid #cfd7cf;
        font-size: .85rem;
        font-weight: 600;
    }
    .summary-pill .count {
        background: #2e7d52;
        color: #fff;
        font-size: .72rem;
        padding: .1rem .55rem;
        border-radius: 999px;
    }

    .admin-table-wrap {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
    }
    table.approvals-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .9rem;
    }
    table.approvals-table thead th,
    table.approvals-table tbody td {
        padding: .9rem 1rem;
        text-align: left;
        vertical-align: middle;
    }
    table.approvals-table thead th {
        background: #f7f9f7;
        color: #555;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }
    table.approvals-table tbody td {
        border-bottom: 1px solid #f0f0f0;
    }
    table.approvals-table tbody tr:last-child td {
        border-bottom: none;
    }
    table.approvals-table .name {
        font-weight: 700;
        color: #1a1a1a;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    table.approvals-table .email {
        color: #555;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .role-pill {
        display: inline-block;
        padding: .2rem .6rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        background: #e8f0ec;
        color: #2e7d52;
    }

    /* Approve / Reject buttons */
    .row-actions {
        display: flex;
        gap: .5rem;
        justify-content: flex-end;
        flex-wrap: wrap;
    }
    .row-actions form { margin: 0; }
    .btn-approve,
    .btn-reject {
        padding: .5rem 1rem;
        border-radius: 999px;
        font-size: .8rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        border: 1px solid;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background .15s, color .15s;
    }
    .btn-approve {
        background: #2e7d52;
        border-color: #2e7d52;
        color: #fff;
    }
    .btn-approve:hover {
        background: #25623f;
        border-color: #25623f;
    }
    .btn-reject {
        background: #fff;
        border-color: #b00020;
        color: #b00020;
    }
    .btn-reject:hover {
        background: #b00020;
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        color: #888;
    }
    .empty-state p { margin: 0; }
    .empty-state .empty-icon {
        font-size: 2rem;
        margin-bottom: .5rem;
    }

    /* Mobile: collapse each row to a stacked card */
    @media (max-width: 720px) {
        .admin-table-wrap {
            background: transparent;
            border: none;
            border-radius: 0;
            overflow: visible;
        }
        table.approvals-table thead { display: none; }
        table.approvals-table,
        table.approvals-table tbody { display: block; width: 100%; }
        table.approvals-table tr {
            display: block;
            width: 100%;
            margin-bottom: .85rem;
            padding: 1rem 1.1rem;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .03);
        }
        table.approvals-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: .35rem 0;
            border: none;
            width: 100%;
        }
        table.approvals-table td::before {
            content: attr(data-label);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #555;
            flex-shrink: 0;
        }
        table.approvals-table td:first-child {
            display: block;
            padding: 0 0 .65rem;
            margin-bottom: .65rem;
            border-bottom: 1px solid #f0f0f0;
        }
        table.approvals-table td:first-child::before { content: none; }
        table.approvals-table .row-actions {
            justify-content: stretch;
        }
        table.approvals-table .row-actions .btn-approve,
        table.approvals-table .row-actions .btn-reject {
            flex: 1;
        }
    }
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Admin</span>
        <h1 class="marketplace-title t-display">Pending <em>Approvals</em></h1>
    </div>
</div>

<div class="admin-page">

    <div class="admin-toolbar">
        <?= $this->Html->link(
            '← Back to dashboard',
            ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
            ['class' => 'admin-back', 'escape' => false]
        ) ?>

        <span class="summary-pill">
            Pending
            <span class="count"><?= (int)count($pending) ?></span>
        </span>
    </div>

    <?php if (empty($pending)): ?>
        <div class="empty-state">
            <div class="empty-icon">✓</div>
            <p><strong>No accounts awaiting approval.</strong></p>
            <p>New farmer and manufacturer signups will appear here.</p>
        </div>
    <?php else: ?>
        <div class="admin-table-wrap">
            <table class="approvals-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width: 130px;">Role</th>
                        <th style="width: 130px;">Signed up</th>
                        <th style="width: 220px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending as $u): ?>
                        <tr>
                            <td data-label="Name" class="name"><?= h($u->full_name) ?></td>
                            <td data-label="Email" class="email"><?= h($u->email) ?></td>
                            <td data-label="Role">
                                <span class="role-pill"><?= h($roleLabels[$u->role] ?? ucfirst((string)$u->role)) ?></span>
                            </td>
                            <td data-label="Signed up">
                                <?= $u->created ? $u->created->i18nFormat('dd MMM YYYY') : '—' ?>
                            </td>
                            <td data-label="Action">
                                <div class="row-actions">
                                    <?= $this->Form->postLink(
                                        'Approve',
                                        ['action' => 'approve', $u->id],
                                        [
                                            'class' => 'btn-approve',
                                            'confirm' => 'Approve ' . $u->full_name . '? They will be notified by email once approved.',
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        'Reject',
                                        ['action' => 'reject', $u->id],
                                        [
                                            'class' => 'btn-reject',
                                            'method' => 'delete',
                                            'confirm' => 'Reject ' . $u->full_name . '? Their signup will be permanently removed.',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
