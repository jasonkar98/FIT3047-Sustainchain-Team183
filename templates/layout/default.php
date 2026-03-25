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
    <?= $this->Html->css(['nav', 'app']) ?>
</head>


<body>
<!-- global navigation bar -->
<nav class="nav">
    <a href="/team183-onboarding-project/landingPage" class="nav-logo">
        <div class="nav-logo-icon">🌿</div>
        <span class="nav-logo-name">Sustain<span>Chain</span></span>
    </a>

    <ul class="nav-links">
        <li><a href="#about">About</a></li>
        <li><a href="#mission">Our Mission</a></li>
        <li><a href="#marketplace">Marketplace</a></li>
        <li><a href="#innovators">Discover Innovators</a></li>
        <li><?= $this->Html->link('Enquire', ['controller' => 'Enquiries', 'action' => 'add']) ?></li>
    </ul>

    <div class="nav-right">
        <?= $this->Html->link('Log in', ['controller' => 'Auth', 'action' => 'login'], ['class' => 'btn btn-outline']) ?>
        <?= $this->Html->link('Join SustainChain', ['controller' => 'Auth', 'action' => 'register'], ['class' => 'btn btn-lime']) ?>
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
</body>
</html>
