<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <?= $this->fetch('script') ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ?></title>
    <?= $this->fetch('meta') ?>
    <?= $this->Html->css(['app', 'nav']) ?>
    <?= $this->fetch('css') ?>
    <style>
        .nav-user-wrap {
            position: relative;
        }

        .nav-user-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: rgba(200, 232, 64, 0.1);
            border: 1px solid rgba(200, 232, 64, 0.22);
            color: var(--e1);
            font-size: 0.82rem;
            font-weight: 700;
            padding: 0.4rem 0.9rem;
            border-radius: var(--r999);
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
            font-family: inherit;
            letter-spacing: -0.01em;
        }

        .nav-user-btn:hover {
            background: rgba(200, 232, 64, 0.18);
            border-color: rgba(200, 232, 64, 0.4);
        }

        .nav-user-btn .chevron {
            width: 12px;
            height: 12px;
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .nav-user-btn .user-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--g3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.62rem;
            font-weight: 700;
            color: var(--white);
            flex-shrink: 0;
        }

        /* dropdown panel */
        .nav-dropdown {
            position: absolute;
            top: calc(100% + 0.6rem);
            right: 0;
            min-width: 200px;
            background: var(--g0);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--r16);
            padding: 0.5rem;
            z-index: 200;
            opacity: 0;
            pointer-events: none;
            transform: translateY(-6px);
            transition: opacity 0.18s ease, transform 0.18s ease;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        }

        .nav-dropdown.open {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .nav-user-btn[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }

        .dropdown-header {
            padding: 0.6rem 0.75rem 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            margin-bottom: 0.35rem;
        }

        .dropdown-header .d-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--white);
        }

        .dropdown-header .d-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--e1);
            margin-top: 0.15rem;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 0.75rem;
            border-radius: var(--r8);
            font-size: 0.82rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.55);
            text-decoration: none;
            transition: background 0.12s, color 0.12s;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.07);
            color: var(--white);
        }

        .dropdown-item svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .dropdown-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.07);
            margin: 0.35rem 0;
        }

        .dropdown-item.danger {
            color: rgba(255, 120, 100, 0.7);
        }

        .dropdown-item.danger:hover {
            background: rgba(255, 80, 60, 0.1);
            color: #ff7060;
        }
    </style>
</head>

<body>
<nav class="nav">
    <a href="<?= $this->Url->build(['prefix' => false, 'controller' => 'Pages', 'action' => 'landingPage']) ?>" class="nav-logo">
        <div class="nav-logo-icon">🌿</div>
        <span class="nav-logo-name">Sustain<span>Chain</span></span>
    </a>

    <ul class="nav-links">
        <li><?= $this->Html->link('Marketplace', ['prefix' => false, 'controller' => 'Products', 'action' => 'index']) ?></li>
        <li><a href="#innovators">Discover Innovators</a></li>
        <li><?= $this->Html->link('Contact Us', ['prefix' => false, 'controller' => 'Enquiries', 'action' => 'add']) ?></li>
    </ul>

    <div class="nav-right">
        <?php $identity = $this->request->getAttribute('identity'); ?>
        <?php if ($identity): ?>

            <!-- User dropdown -->
            <div class="nav-user-wrap">
                <button
                    class="nav-user-btn"
                    id="user-menu-btn"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="user-menu"
                    onclick="toggleUserMenu()"
                >
                    <div class="user-dot">
                        <?= strtoupper(substr(h($identity->first_name), 0, 1)) ?>
                    </div>
                    <?= h($identity->first_name) ?>
                    <svg class="chevron" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 4l4 4 4-4"/>
                    </svg>
                </button>

                <div class="nav-dropdown" id="user-menu" role="menu">
                    <div class="dropdown-header">
                        <div class="d-name"><?= h($identity->first_name) ?> <?= h($identity->last_name ?? '') ?></div>
                        <?php if ($identity->get('role') === 'admin'): ?>
                            <?= $this->Html->link(
                                '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1l6 3v5c0 3.5-2.5 6-6 7-3.5-1-6-3.5-6-7V4z"/></svg> Admin Area',
                                ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
                                ['class' => 'dropdown-item', 'role' => 'menuitem', 'escape' => false]
                            ) ?>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>
                    </div>

                    <?= $this->Html->link(
                        '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="12" height="10" rx="1.5"/><path d="M5 3V2m6 1V2M2 7h12"/></svg> Dashboard',
                        ['prefix' => false, 'controller' => 'Dashboard', 'action' => 'index'],
                        ['class' => 'dropdown-item', 'role' => 'menuitem', 'escape' => false]
                    ) ?>

                    <?= $this->Html->link(
                        '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 2l1.8 3.6L14 6.3l-3 2.9.7 4.1L8 11.2l-3.7 2.1.7-4.1L2 6.3l4.2-.7z"/></svg> Saved Products',
                        ['prefix' => false, 'controller' => 'Favourites', 'action' => 'index'],
                        ['class' => 'dropdown-item', 'role' => 'menuitem', 'escape' => false]
                    ) ?>

                    <?= $this->Html->link(
                        '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 2l1.8 3.6L14 6.3l-3 2.9.7 4.1L8 11.2l-3.7 2.1.7-4.1L2 6.3l4.2-.7z"/></svg> My Listings',
                        ['prefix' => false, 'controller' => 'Listings', 'action' => 'index'],
                        ['class' => 'dropdown-item', 'role' => 'menuitem', 'escape' => false]
                    ) ?>

                    <?= $this->Html->link(
                        '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="3"/><path d="M8 1v2m0 10v2M1 8h2m10 0h2"/></svg> Settings',
                        ['prefix' => false, 'controller' => 'Users', 'action' => 'edit', $identity->id],
                        ['class' => 'dropdown-item', 'role' => 'menuitem', 'escape' => false]
                    ) ?>

                    <div class="dropdown-divider"></div>

                    <?= $this->Html->link(
                        '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 3H3v10h3m3-7 3 3-3 3m3-3H6"/></svg> Log out',
                        ['prefix' => false, 'controller' => 'Auth', 'action' => 'logout'],
                        ['class' => 'dropdown-item danger', 'role' => 'menuitem', 'escape' => false]
                    ) ?>
                </div>
            </div>

        <?php else: ?>
            <?= $this->Html->link('Log in', ['controller' => 'Auth', 'action' => 'login'], ['class' => 'btn btn-outline']) ?>
            <?= $this->Html->link('Join SustainChain', ['controller' => 'Auth', 'action' => 'register'], ['class' => 'btn btn-lime']) ?>
        <?php endif; ?>
    </div>
</nav>

<main class="main">
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</main>

<footer>
</footer>

<script>
    function toggleUserMenu() {
        const btn = document.getElementById('user-menu-btn');
        const menu = document.getElementById('user-menu');
        const isOpen = menu.classList.contains('open');

        if (isOpen) {
            menu.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        } else {
            menu.classList.add('open');
            btn.setAttribute('aria-expanded', 'true');
        }
    }

    document.addEventListener('click', function (e) {
        const wrap = document.querySelector('.nav-user-wrap');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('user-menu')?.classList.remove('open');
            document.getElementById('user-menu-btn')?.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('user-menu')?.classList.remove('open');
            document.getElementById('user-menu-btn')?.setAttribute('aria-expanded', 'false');
        }
    });
</script>

</body>
</html>