<?php
/** @var \App\View\AppView $this */
/** @var \App\Model\Entity\User $user */
/** @var array<int, string> $managedRoles */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$this->assign('title', 'Admin — Edit User');

$roleLabels = [
    'buyer'        => 'Buyer',
    'seller'       => 'Seller',
    'manufacturer' => 'Manufacturer',
    'farmer'       => 'Farmer',
];

$roleOptions = [];
foreach ($managedRoles as $r) {
    $roleOptions[$r] = $roleLabels[$r] ?? ucfirst($r);
}

$isActive = (int)($user->is_active ?? 1) === 1;
?>

<style>
    .admin-page {
        max-width: 720px;
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
        margin-bottom: 1.5rem;
    }
    .admin-back:hover {
        background: #f5f7f5;
        border-color: #2e7d52;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }
    .form-card h2 {
        font-family: "DM Serif Display", serif;
        font-size: 1.4rem;
        font-weight: 400;
        margin: 0 0 .25rem;
    }
    .form-card .subtle {
        color: #666;
        font-size: .85rem;
        margin: 0 0 1.25rem;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: .35rem;
        margin-bottom: 1rem;
    }
    .field label {
        font-size: .8rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: #555;
    }
    .field input[type="text"],
    .field select {
        padding: .6rem .9rem;
        border: 1px solid #cfd7cf;
        border-radius: 8px;
        font-size: .95rem;
        font-family: inherit;
        background: #fff;
        margin: 0;
    }
    .field input:focus,
    .field select:focus {
        outline: none;
        border-color: #2e7d52;
    }
    .field .error {
        color: #b00020;
        font-size: .8rem;
    }

    .static-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: .55rem 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: .9rem;
    }
    .static-row:last-child { border-bottom: none; }
    .static-row .label { color: #555; font-weight: 600; }
    .static-row .value { color: #1a1a1a; word-break: break-all; text-align: right; }

    .status-pill {
        display: inline-block;
        padding: .2rem .6rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
    }
    .status-pill.ok   { background: #d4edda; color: #155724; }
    .status-pill.warn { background: #f8d7da; color: #721c24; }

    .form-actions {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        margin-top: 1.25rem;
    }
    .form-actions .btn {
        padding: .6rem 1.2rem;
        border-radius: 999px;
        font-size: .9rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid transparent;
        text-decoration: none;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .form-actions .btn-primary {
        background: #2e7d52;
        color: #fff;
        border-color: #2e7d52;
    }
    .form-actions .btn-primary:hover { background: #25623f; }
    .form-actions .btn-secondary {
        background: #fff;
        color: #2e7d52;
        border-color: #cfd7cf;
    }
    .form-actions .btn-secondary:hover { border-color: #2e7d52; }

    /* Reset link button — kept visually distinct from save */
    .reset-link-form {
        display: inline-block;
        margin: 0;
    }
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Admin</span>
        <h1 class="marketplace-title t-display">Edit <em>User</em></h1>
    </div>
</div>

<div class="admin-page">
    <?= $this->Html->link(
        '← Back to users',
        ['action' => 'index'],
        ['class' => 'admin-back', 'escape' => false]
    ) ?>

    <!-- Editable fields -->
    <div class="form-card">
        <h2><?= h($user->full_name ?: 'User #' . $user->id) ?></h2>
        <p class="subtle"><?= h($user->email) ?></p>

        <?= $this->Form->create($user, [
            'url' => ['action' => 'edit', $user->id],
        ]) ?>

        <div class="field">
            <label for="first-name">First name</label>
            <?= $this->Form->control('first_name', [
                'label' => false,
                'id' => 'first-name',
                'type' => 'text',
                'pattern' => '[a-zA-Z ]+',
                'required' => true,
                'maxlength' => 50,
                'value' => $user->first_name,
            ]) ?>
        </div>

        <div class="field">
            <label for="last-name">Last name</label>
            <?= $this->Form->control('last_name', [
                'label' => false,
                'id' => 'last-name',
                'type' => 'text',
                'pattern' => '[a-zA-Z ]+',
                'required' => true,
                'maxlength' => 50,
                'value' => $user->last_name,
            ]) ?>
        </div>

        <div class="field">
            <label for="role">Role</label>
            <?= $this->Form->control('role', [
                'label' => false,
                'id' => 'role',
                'type' => 'select',
                'options' => $roleOptions,
                'default' => $user->role,
                'value' => $user->role,
                'empty' => false,
                'required' => true,
            ]) ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save changes</button>
            <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>

    <!-- Read-only account info -->
    <div class="form-card">
        <h2>Account info</h2>
        <p class="subtle">Read-only details about this account.</p>

        <div class="static-row">
            <span class="label">Status</span>
            <span class="value">
                <span class="status-pill <?= $isActive ? 'ok' : 'warn' ?>">
                    <?= $isActive ? 'Active' : 'Deactivated' ?>
                </span>
            </span>
        </div>
        <div class="static-row">
            <span class="label">Signup date</span>
            <span class="value">
                <?= $user->created ? $user->created->i18nFormat('dd MMM YYYY, HH:mm') : '—' ?>
            </span>
        </div>
        <div class="static-row">
            <span class="label">Last modified</span>
            <span class="value">
                <?= $user->modified ? $user->modified->i18nFormat('dd MMM YYYY, HH:mm') : '—' ?>
            </span>
        </div>
    </div>

    <!-- Password reset -->
    <div class="form-card">
        <h2>Password</h2>
        <p class="subtle">Sends a one-time reset link to the user's email. Their existing password keeps working until they complete the reset. Link expires in 7 days.</p>

        <?= $this->Form->postLink(
            'Send password reset link',
            ['action' => 'sendResetLink', $user->id],
            [
                'class' => 'btn btn-secondary',
                'confirm' => 'Send a password reset link to ' . $user->email . '?',
            ]
        ) ?>
    </div>
</div>
