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

        /* Foundation: no horizontal scroll anywhere */
        html, body {
            max-width: 100vw;
            overflow-x: hidden;
        }
        *, *::before, *::after { box-sizing: border-box; }
        img, svg, video { max-width: 100%; height: auto; }

        /* Tablet and mobile < 1024px*/
        @media (max-width: 1023px) {

    .nav {
        display: grid !important;
        grid-template-columns: auto 1fr auto !important;
        grid-template-areas:
            "logo  spacer  search"
            "links links   user"
            !important;
        gap: 0.35rem 0.5rem !important;
        align-items: center;
        padding: 0.6rem 0.75rem !important;
        height: auto !important;
        min-height: 68px;
    }

    /* Let children of nav-left / nav-right participate directly
       in the nav grid (no layout boxes of their own) */
            .nav-left,
            .nav-right {
                display: contents !important;
            }

            .nav-logo {
                grid-area: logo;
                justify-self: start;
            }

            .nav-links {
                grid-area: links;
                justify-self: start;
                width: auto !important;
                flex-wrap: wrap;
                gap: 1rem !important;
                padding-top: 0.25rem;
                border-top: 1px solid rgba(255, 255, 255, 0.08);
            }
            .nav-links li a {
                padding: 0.25rem 0 !important;
                font-size: 0.85rem !important;
            }

            .nav-search {
                grid-area: search;
                justify-self: end;
                flex: 0 0 auto !important;
            }
            .nav-search-icon {
                padding: 4px !important;
            }

            /* When search is expanded, it grows to fill the top row to the right of the logo */
            .nav-search.open {
                grid-column: 2 / 4 !important;
                justify-self: stretch !important;
                align-items: center;
            }
            .nav-search.open .nav-search-form {
                max-width: 100% !important;
                flex: 1 1 auto !important;
                opacity: 1 !important;
            }
            .nav-search-input {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0;
            }

            .nav-user-wrap {
                grid-area: user;
                justify-self: end;
            }
            .nav-user-btn {
                padding: 0.3rem 0.6rem !important;
                font-size: 0.78rem !important;
                max-width: 140px;
            }
            .nav-user-btn > span:not(.user-dot):not(.chevron) {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            /* Auth (logged-out) buttons also land in the user slot */
            .nav-right > .btn {
                grid-area: user;
                justify-self: end;
            }

            /* Keep dropdown panel aligned to the user button on the right */
            .nav-dropdown {
                max-width: calc(100vw - 1rem) !important;
                right: 0 !important;
                left: auto !important;
            }

        /* ==== MOBILE ONLY  (< 640px) ==== */
        @media (max-width: 639px) {

            /* ---- Nav text smaller ---- */
            .nav-logo-name   { font-size: 0.9rem !important; }
            .nav-links li a  { font-size: 0.85rem !important; }

            /* ---- Hero banners ---- */
            .marketplace-header,
            .form-hero {
                padding: 2rem 1rem 1.5rem !important;
            }
            .marketplace-title,
            .marketplace-header .marketplace-title,
            .form-hero-title {
                font-size: clamp(1.5rem, 7vw, 2.2rem) !important;
                line-height: 1.15 !important;
            }
            .marketplace-subtitle { font-size: 0.85rem !important; }

            /* ---- Products stack in 1 column (with higher specificity
                to beat template-level !important rules) ---- */
            body .product-grid,
            body .products-grid,
            body .slider-track {
                display: grid !important;
                grid-template-columns: 1fr !important;
                grid-auto-columns: 100% !important;
                grid-auto-flow: row !important;
                gap: 1rem !important;
                overflow-x: visible !important;
            }

            /* Dashboard saved-listings wrapper — remove slider arrows + padding */
            .saved-listings-wrapper {
                padding: 0 !important;
            }
            .slider-arrow {
                display: none !important;
            }

            /* ---- Dashboard stats stack ---- */
            body .stats-row {
                display: grid !important;
                grid-template-columns: 1fr !important;
            }

            /* ---- Body padding tighter ---- */
            .dash-page,
            .products-col,
            .filter-sidebar,
            .form-main {
                padding: 1rem !important;
            }
            .form-card { padding: 1.25rem !important; }

            /* ---- Search + filter row stacks ---- */
            .search-filter-row {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            .search-filter-row input,
            .search-filter-row button,
            .search-filter-row a {
                width: 100% !important;
            }

            /* ---- Admin enquiries TABLE → CARDS ---- */
            .admin-table-wrap {
                overflow: visible !important;
                border: none !important;
                background: transparent !important;
                border-radius: 0 !important;
            }
            table.enquiries-table,
            table.enquiries-table tbody,
            table.enquiries-table tr,
            table.enquiries-table td {
                display: block !important;
                width: 100% !important;
            }
            table.enquiries-table thead { display: none !important; }
            table.enquiries-table tr {
                background: #fff !important;
                border: 1px solid #e0e0e0 !important;
                border-radius: 12px !important;
                padding: 1rem !important;
                margin-bottom: 0.75rem !important;
            }
            table.enquiries-table tr.is-unread { background: #fcfcf4 !important; }
            table.enquiries-table td {
                padding: 0.35rem 0 !important;
                border: none !important;
            }
            table.enquiries-table td[data-label]::before {
                content: attr(data-label);
                display: block;
                font-size: 0.7rem;
                font-weight: 700;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                color: #888;
                margin-bottom: 0.15rem;
            }
            /* Let long subjects wrap instead of overflowing */
            table.enquiries-table td.subject-cell {
                white-space: normal !important;
                overflow-wrap: anywhere !important;
                word-break: break-word !important;
            }
            table.enquiries-table .subject-link {
                overflow-wrap: anywhere !important;
                word-break: break-word !important;
                display: inline-block;
                max-width: 100%;
            }
            table.enquiries-table .row-actions {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 0.5rem !important;
                margin-top: 0.5rem;
            }

            /* ---- Admin view: action bar stacks ---- */
            .action-bar {
                flex-direction: column !important;
            }
            .action-bar .btn,
            .action-bar form button {
                width: 100% !important;
                justify-content: center;
            }
            .admin-back {
                width: 100%;
                justify-content: center;
            }

            /* ---- Prev / [current title] / Next nav on enquiry view ----
            If the container has a flex layout with two .admin-back buttons
            and a span in between, stack them. */
            :is(div, nav):has(> .admin-back + span + .admin-back),
            :is(div, nav):has(> .admin-back + span + span.admin-back) {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
            }
            :is(div, nav):has(> .admin-back + span + .admin-back) > span {
                text-align: center;
                font-size: 0.8rem;
                color: #666;
                padding: 0.25rem 0;
                order: -1;  /* show current-title label above the buttons */
            }

            /* ---- Enquiry list items ---- */
            .enquiry-header {
                flex-direction: column !important;
                gap: 0.25rem !important;
            }
            .enquiry-item { padding: 1rem !important; }

            /* ---- Auth cards full-width ---- */
            .auth-card,
            .form-card,
            .auth-page {
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: hidden;
            }

            /* ---- Decorative hero circles: keep from causing scroll ---- */
            .form-hero-circle {
                display: none !important;
            }

            /*Global text-overflow guard */
            .marketplace-title,
            .marketplace-header .marketplace-title,
            .form-hero-title,
            h1, h2, h3, h4 {
                overflow-wrap: anywhere;
                word-break: break-word;
            }
            p, span, a, td, th, li, label {
                overflow-wrap: anywhere;
            }

            /* Listing table*/
            .my-listings-page table,
            .my-listings-page table tbody,
            .my-listings-page table tr,
            .my-listings-page table td {
                display: block !important;
                width: 100% !important;
            }
            .my-listings-page table thead {
                display: none !important;
            }
            .my-listings-page table tr {
                background: #fff !important;
                border: 1px solid #e0e0e0 !important;
                border-radius: 12px !important;
                padding: 1rem !important;
                margin-bottom: 0.75rem !important;
                position: relative;
            }
            /* HIDE everything except Product (first) and Actions (last) */
            .my-listings-page table td {
                padding: 0 !important;
                border: none !important;
            }
            .my-listings-page table td:not(:first-child):not(:last-child) {
                display: none !important;
            }
            .my-listings-page table td:first-child {
                font-weight: 600;
                font-size: 1rem;
                padding-bottom: 0.75rem !important;
            }
            .my-listings-page table td:last-child {
                text-align: left;
            }
            .my-listings-page table td:last-child a,
            .my-listings-page table td:last-child button {
                display: inline-block;
                padding: 0.4rem 0.9rem;
                background: #2e7d52;
                color: #fff;
                border-radius: 6px;
                text-decoration: none;
                font-size: 0.85rem;
                font-weight: 600;
            }

            /* Dashboard saved listings*/
            body .saved-listings-wrapper {
                padding: 0 2.5rem !important;
                position: relative;
            }
            body .saved-listings-wrapper .slider-viewport {
                overflow: hidden !important;
            }
            body .slider-track,
            body .products-grid.slider-track {
                display: grid !important;
                grid-template-columns: 100% !important;
                grid-auto-columns: 100% !important;
                grid-auto-flow: column !important;
                gap: 0 !important;
                overflow-x: auto !important;
                scroll-snap-type: x mandatory !important;
                scrollbar-width: none !important;
            }
            body .slider-track::-webkit-scrollbar {
                display: none;
            }
            body .slider-track > * {
                scroll-snap-align: start;
                min-width: 0;
                width: 100%;
            }
            /* Force arrows VISIBLE on mobile (override the display:none from previous round) */
            body .slider-arrow {
                display: flex !important;
                position: absolute !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
                width: 36px !important;
                height: 36px !important;
                border-radius: 50% !important;
                background: #2e7d52 !important;
                color: #fff !important;
                border: none !important;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 5;
                font-size: 0.9rem;
                box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            }
            body .slider-arrow.prev { left: 0 !important; right: auto !important; }
            body .slider-arrow.next { right: 0 !important; left: auto !important; }
            body .slider-arrow:hover {
                background: #276a46 !important;
            }
        }

        /* ---- Tighter nav clamping on tablet/mobile ---- */
        .nav {
            padding: 0.6rem 0.75rem !important;
        }
        .nav-left {
            width: auto !important;
            flex: 1 1 auto;
            min-width: 0;
            gap: 0.75rem !important;
        }
        .nav-right {
            width: auto !important;
            flex: 0 1 auto;
            gap: 0.4rem !important;
        }
        .nav-links {
            flex: 1 1 100%;
            order: 99;
            width: 100%;
            justify-content: flex-start;
            padding-top: 0.35rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .nav-links li a {
            padding: 0.25rem 0 !important;
        }
        /* User dropdown button: smaller + truncated name */
        .nav-user-btn {
            padding: 0.3rem 0.6rem !important;
            font-size: 0.78rem !important;
            max-width: 140px;
        }
        .nav-user-btn > span:not(.user-dot):not(.chevron) {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        /* Search icon stays compact */
        .nav-search-icon {
            padding: 4px !important;
        }
    </style>
</head>

<body>
<nav class="nav">
    <div class="nav-left">
        <a href="<?= $this->Url->build(['prefix' => false, 'controller' => 'Pages', 'action' => 'landingPage']) ?>" class="nav-logo">
            <div class="nav-logo-icon">🌿</div>
            <span class="nav-logo-name">Sustain<span>Chain</span></span>
        </a>

        <ul class="nav-links">
            <li><?= $this->Html->link('Marketplace', ['prefix' => false, 'controller' => 'Products', 'action' => 'index']) ?></li>
            <?php $identity = $this->request->getAttribute('identity'); ?>
            <?php if ($identity): ?>
                <?php if ($identity->get('role') !== 'admin'): ?>
                    <li><?= $this->Html->link('Contact Us', ['prefix' => false, 'controller' => 'Enquiries', 'action' => 'add']) ?></li>
                <?php endif; ?>
            <?php else: ?>
                <li><?= $this->Html->link('Contact Us', ['prefix' => false, 'controller' => 'Enquiries', 'action' => 'add']) ?></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="nav-right">
        
        <div class="nav-search" id="navSearch">
        <button class="nav-search-icon" id="navSearchBtn" aria-label="Search">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </button>
        <form class="nav-search-form" action="<?= $this->Url->build(['prefix' => false,'controller' => 'Products', 'action' => 'index'], ['fullBase' => true]) ?>" method="get">
        <input type="text" name="keyword" class="nav-search-input" placeholder="Search products..." />
        </form>
    </div>



        <?php $identity = $this->request->getAttribute('identity'); ?>
        <?php if ($identity): ?>

            <!-- User dropdown -->
            <button
                class="nav-user-btn"
                id="user-menu-btn"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls="user-menu"
                onclick="toggleUserMenu()"
            >
                <div class="user-dot">
                    <?php 
                        // Fallback to 'U' if first_name is null to prevent substr error
                        $fName = $identity->first_name ?? 'User';
                        echo strtoupper(substr(h($fName), 0, 1)); 
                    ?>
                </div>
                <?= h($identity->first_name ?? 'Account') ?>
                <svg class="chevron" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 4l4 4 4-4"/>
                </svg>
            </button>

                <div class="nav-dropdown" id="user-menu" role="menu">
                    <div class="dropdown-header">
                        <div class="d-name"><?= h($identity->first_name) ?> <?= h($identity->last_name ?? '') ?></div>
                    </div>

                    <?php if ($identity->get('role') === 'admin'): ?>
                        <?= $this->Html->link(
                            '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1l6 3v5c0 3.5-2.5 6-6 7-3.5-1-6-3.5-6-7V4z"/></svg> Admin Dashboard',
                            ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
                            ['class' => 'dropdown-item', 'role' => 'menuitem', 'escape' => false]
                        ) ?>
                        <div class="dropdown-divider"></div>
                    <?php endif; ?>

                    <?= $this->Html->link(
                        '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="5" r="3" /><path d="M3 14c0-2.5 2-4.5 5-4.5s5 2 5 4.5" /></svg> My Account',
                        ['prefix' => false, 'controller' => 'Auth', 'action' => 'view', $identity->get('id')],
                        ['class' => 'dropdown-item', 'role' => 'menuitem', 'escape' => false]
                    ) ?>

                    <?= $this->Html->link(
                        '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="12" height="10" rx="1.5"/><path d="M5 3V2m6 1V2M2 7h12"/></svg> Dashboard',
                        ['prefix' => false, 'controller' => 'Dashboard', 'action' => 'index'],
                        ['class' => 'dropdown-item', 'role' => 'menuitem', 'escape' => false]
                    ) ?>

                    <?= $this->Html->link(
                        '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 2l1.8 3.6L14 6.3l-3 2.9.7 4.1L8 11.2l-3.7 2.1.7-4.1L2 6.3l4.2-.7z"/></svg> My Listings',
                        ['prefix' => false, 'controller' => 'Products', 'action' => 'myListings'],
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

<!-- footer -->
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="footer-logo-wrap">
                <span class="footer-logo-name">Sustain<span>Chain</span></span>
            </div>
            <p class="footer-tagline">A vibrant marketplace for responsible consumption and a greener future.</p>
        </div>

        <div class="footer-col">
            <h4>Platform</h4>
            <ul>
                <li><a href="#about">Features</a></li>
                <li><a href="#marketplace">Marketplace</a></li>
                <li><a href="#innovators">Discover Innovators</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Commerce</h4>
            <ul>
                <li><a href="/buyers">For Buyers</a></li>
                <li><a href="/sellers">For Sellers</a></li>
                <li><a href="/manufacturers">For Manufacturers</a></li>
                <li><a href="/farmers">For Farmers</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Company</h4>
            <ul>
                <li><a href="#mission">Our Mission</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/enquiries">Enquire</a></li>
                <li><a href="/users/login">Log in</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p class="footer-copy">© <?= date('Y') ?> SustainChain. All rights reserved.</p>
    </div>
</footer>

<script>
    const navSearch = document.getElementById('navSearch');
    const navSearchBtn = document.getElementById('navSearchBtn');
    const navSearchInput = navSearch.querySelector('.nav-search-input');

    navSearchBtn.addEventListener('click', () => {
        navSearch.classList.toggle('open');
        if (navSearch.classList.contains('open')) {
            navSearchInput.focus();
        }
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!navSearch.contains(e.target)) {
            navSearch.classList.remove('open');
        }
    });

    // Submit on Enter
    navSearchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            navSearchInput.closest('form').submit();
        }
    });
</script>

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