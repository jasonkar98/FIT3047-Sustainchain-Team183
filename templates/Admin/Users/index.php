<?php
/** @var \App\View\AppView $this */
/** @var iterable<\App\Model\Entity\User> $users */
/** @var string $role */
/** @var string $keyword */
/** @var string $sort */
/** @var string $direction */
/** @var array<string, int> $roleCounts */
/** @var array<int, string> $managedRoles */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$this->assign('title', 'Admin — Users');

$roleLabels = [
    'buyer'        => 'Buyers',
    'seller'       => 'Sellers',
    'manufacturer' => 'Manufacturers',
    'farmer'       => 'Farmers',
];

// Helper for building sort links — preserves the active role + keyword.
$sortLink = function (string $key, string $label) use ($role, $keyword, $sort, $direction) {
    // Toggle direction if clicking the same key; otherwise default to a sensible direction per key.
    $defaultDir = $key === 'name' ? 'asc' : 'desc';
    $newDir = ($sort === $key)
        ? ($direction === 'asc' ? 'desc' : 'asc')
        : $defaultDir;

    $arrow = '';
    if ($sort === $key) {
        $arrow = $direction === 'asc' ? ' ↑' : ' ↓';
    }

    return $this->Html->link(
        h($label) . $arrow,
        ['?' => [
            'role'      => $role,
            'keyword'   => $keyword,
            'sort'      => $key,
            'direction' => $newDir,
        ]],
        ['class' => 'sort-link' . ($sort === $key ? ' is-active' : ''), 'escape' => false]
    );
};
?>

<style>
    /* ===== Page shell — mirrors enquiries admin styling ===== */
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

    /* ===== Role tabs ===== */
    .role-tabs {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }
    .role-tab {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
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
    .role-tab:hover {
        border-color: #2e7d52;
    }
    .role-tab.is-active {
        background: #2e7d52;
        border-color: #2e7d52;
        color: #fff;
    }
    .role-tab .count {
        font-size: .72rem;
        opacity: .85;
        background: rgba(0,0,0,.06);
        padding: .05rem .45rem;
        border-radius: 999px;
    }
    .role-tab.is-active .count {
        background: rgba(255,255,255,.2);
    }

    /* ===== Search + sort row ===== */
    .controls-row {
        display: flex;
        gap: .75rem;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .controls-row form.search-form {
        display: flex;
        gap: .5rem;
        flex: 1 1 280px;
        margin: 0;
    }
    .controls-row input[type="text"] {
        flex: 1;
        padding: .55rem .9rem;
        border: 1px solid #cfd7cf;
        border-radius: 999px;
        font-size: .9rem;
        font-family: inherit;
        background: #fff;
        margin: 0;
    }
    .controls-row input[type="text"]:focus {
        outline: none;
        border-color: #2e7d52;
    }
    .controls-row button[type="submit"] {
        padding: .55rem 1.1rem;
        border-radius: 999px;
        border: 1px solid #2e7d52;
        background: #2e7d52;
        color: #fff;
        font-weight: 600;
        font-size: .85rem;
        cursor: pointer;
    }
    .controls-row .clear-link {
        font-size: .85rem;
        color: #666;
        text-decoration: none;
        align-self: center;
    }
    .controls-row .clear-link:hover { color: #2e7d52; }

    /* ===== Table ===== */
    .admin-table-wrap {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
    }
    table.users-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .9rem;
    }
    table.users-table thead th,
    table.users-table tbody td {
        padding: .9rem 1rem;
        text-align: left;
        vertical-align: middle;
    }
    table.users-table thead th {
        background: #f7f9f7;
        color: #555;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }
    table.users-table tbody td {
        border-bottom: 1px solid #f0f0f0;
    }
    table.users-table tbody tr:last-child td {
        border-bottom: none;
    }
    table.users-table tr.is-inactive {
        background: #fbf6f4;
        color: #888;
    }
    table.users-table a.name-link {
        color: #1a1a1a;
        text-decoration: none;
        font-weight: 600;
    }
    table.users-table a.name-link:hover {
        color: #2e7d52;
        text-decoration: underline;
    }

    /* Column header sort link */
    .sort-link {
        color: #555;
        text-decoration: none;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .sort-link:hover { color: #2e7d52; }
    .sort-link.is-active { color: #2e7d52; }

    /* Pills */
    .role-pill, .status-pill {
        display: inline-block;
        padding: .2rem .6rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .role-pill        { background: #e8f0ec; color: #2e7d52; }
    .status-pill.ok   { background: #d4edda; color: #155724; }
    .status-pill.warn { background: #f8d7da; color: #721c24; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        color: #888;
    }

    /* Pagination */
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

    /* ===== Mobile: collapse table to stacked cards ===== */
    @media (max-width: 720px) {
        table.users-table thead { display: none; }
        table.users-table,
        table.users-table tbody,
        table.users-table tr,
        table.users-table td { display: block; width: 100%; }
        table.users-table tr {
            border-bottom: 1px solid #e0e0e0;
            padding: .75rem 1rem;
        }
        table.users-table tbody tr:last-child { border-bottom: none; }
        table.users-table tbody td {
            border: none;
            padding: .25rem 0;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }
        table.users-table tbody td::before {
            content: attr(data-label);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #555;
            flex-shrink: 0;
        }
    }
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Admin</span>
        <h1 class="marketplace-title t-display">All <em>Users</em></h1>
    </div>
</div>

<div class="admin-page">

    <!-- Back + role tabs -->
    <div class="admin-toolbar">
        <?= $this->Html->link(
            '← Back to dashboard',
            ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
            ['class' => 'admin-back', 'escape' => false]
        ) ?>

        <div class="role-tabs">
            <?php foreach ($managedRoles as $r): ?>
                <?= $this->Html->link(
                    h($roleLabels[$r]) . ' <span class="count">' . (int)$roleCounts[$r] . '</span>',
                    ['?' => ['role' => $r]],
                    [
                        'class' => 'role-tab' . ($role === $r ? ' is-active' : ''),
                        'escape' => false,
                    ]
                ) ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Search -->
    <div class="controls-row">
        <?= $this->Form->create(null, [
            'type' => 'get',
            'class' => 'search-form',
            'novalidate' => true,
        ]) ?>
            <?= $this->Form->hidden('role', ['value' => $role]) ?>
            <?= $this->Form->hidden('sort', ['value' => $sort]) ?>
            <?= $this->Form->hidden('direction', ['value' => $direction]) ?>
            <input type="text"
                   name="keyword"
                   placeholder="Search by name or email…"
                   value="<?= h($keyword) ?>"
                   aria-label="Search users">
            <button type="submit">Search</button>
        <?= $this->Form->end() ?>

        <?php if ($keyword !== ''): ?>
            <?= $this->Html->link(
                'Clear',
                ['?' => ['role' => $role]],
                ['class' => 'clear-link']
            ) ?>
        <?php endif; ?>
    </div>

    <?php $userList = is_array($users) ? $users : iterator_to_array($users); ?>

    <?php if (empty($userList)): ?>
        <div class="empty-state">
            <p>No <?= h(strtolower($roleLabels[$role])) ?> match this search.</p>
        </div>
    <?php else: ?>
        <div class="admin-table-wrap">
            <table class="users-table">
                <thead>
                    <tr>
                        <th><?= $sortLink('name', 'Name') ?></th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th><?= $sortLink('listings', 'No. of listings') ?></th>
                        <th><?= $sortLink('signup_date', 'Signup date') ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($userList as $user): ?>
                    <?php $isActive = (int)($user->is_active ?? 1) === 1; ?>
                    <tr class="<?= $isActive ? '' : 'is-inactive' ?>">
                        <td data-label="Name">
                            <?= $this->Html->link(
                                h($user->full_name),
                                ['action' => 'edit', $user->id],
                                ['class' => 'name-link']
                            ) ?>
                        </td>
                        <td data-label="Email"><?= h($user->email) ?></td>
                        <td data-label="Role">
                            <span class="role-pill"><?= h(ucfirst((string)$user->role)) ?></span>
                        </td>
                        <td data-label="Status">
                            <span class="status-pill <?= $isActive ? 'ok' : 'warn' ?>">
                                <?= $isActive ? 'Active' : 'Deactivated' ?>
                            </span>
                        </td>
                        <td data-label="No. of listings">
                            <?= (int)($user->product_count ?? 0) ?>
                        </td>
                        <td data-label="Signup date">
                            <?= $user->created ? $user->created->i18nFormat('dd MMM YYYY') : '—' ?>
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
