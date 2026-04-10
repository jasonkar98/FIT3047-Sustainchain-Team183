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
</head>


<body>
<!-- global navigation bar -->
<nav class="nav">
    <a href="/" class="nav-logo">
        <div class="nav-logo-icon">🌿</div>
        <span class="nav-logo-name">Sustain<span>Chain</span></span>
    </a>

    <ul class="nav-links">
        <li><a href="#about">About</a></li>
        <li><a href="#mission">Our Mission</a></li>
        <li><?= $this->Html->link('Marketplace', ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><a href="#innovators">Discover Innovators</a></li>
        <li><?= $this->Html->link('Enquire', ['controller' => 'Enquiries', 'action' => 'add']) ?></li>
    </ul>

    <div class="nav-right">
        
         <div class="nav-search" id="navSearch">
        <button class="nav-search-icon" id="navSearchBtn" aria-label="Search">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </button>
        <form class="nav-search-form" action="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index'], ['fullBase' => true]) ?>" method="get">
        <input type="text" name="keyword" class="nav-search-input" placeholder="Search products..." />
        </form>
    </div>

    
        <?php $identity = $this->request->getAttribute('identity'); ?>
        <?php if ($identity): ?>
            <span class="nav-user">
                <?= h($identity->email) ?>
            </span>
            <?= $this->Html->link('Log out', ['controller' => 'Auth', 'action' => 'logout'], ['class' => 'btn btn-outline']) ?>
        <?php else: ?>
            <?= $this->Html->link('Log in', ['controller' => 'Auth', 'action' => 'login'], ['class' => 'btn btn-outline']) ?>
            <?= $this->Html->link('Join SustainChain', ['controller' => 'Auth', 'action' => 'register'], ['class' => 'btn btn-lime']) ?>
        <?php endif; ?>
    </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
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

</body>
</html>


