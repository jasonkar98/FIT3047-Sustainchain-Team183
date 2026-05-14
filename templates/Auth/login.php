<?php
/**
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;

$debug = Configure::read('debug');

$this->layout = 'login';
$this->assign('title', 'Login');
?>

<div class="auth-page">
    <section class="auth-hero">
        <div class="hero-eyebrow">
            <span class="eyebrow-dot"></span>
            Sustainable supply chain
        </div>
        <h1>Sustain<em>Chain</em></h1>
        <p class="auth-subtitle">Sustainable future, connected supply chains.</p>
        <div class="auth-stats">
            <div class="stat-pill">
                <div class="stat-num">4.2k</div>
                <div class="stat-desc">Active suppliers tracked</div>
            </div>
            <div class="stat-pill">
                <div class="stat-num">98%</div>
                <div class="stat-desc">Audit compliance rate</div>
            </div>
        </div>
    </section>

    <section class="auth-card">
        <p class="card-eyebrow">Welcome back</p>
        <h2>Sign in</h2>
        <?= $this->Flash->render() ?>
        <?= $this->Form->create(null, ['class' => 'auth-form']) ?>
        <?php
        /*
         * NOTE: regarding 'value' config in the login page form controls
         * In this demo the email and password fields will be filled by demo account
         * credentials when debug mode is on. You should NOT do that in your production
         * systems. Also it's a good practice to clear (set password value to empty)
         * in the view so when an error occurred with form validation, the password
         * values are always cleared.
         */
        echo $this->Form->control('email', [
            'type' => 'email',
            'required' => true,
            'autofocus' => true,
            'label' => 'Email',
            'placeholder' => 'name@company.com',
        ]);
        echo $this->Form->control('password', [
            'type' => 'password',
            'required' => true,
            'label' => 'Password',
            'placeholder' => 'Enter your password',
        ]);
        ?>
        <?= $this->Form->button('Login', ['class' => 'button auth-primary-btn']) ?>
        <?= $this->Form->end() ?>
        <div class="auth-links">
            <?= $this->Html->link('Forgot password?', ['controller' => 'Auth', 'action' => 'forgetPassword']) ?>
            <?= $this->Html->link('Sign up', ['controller' => 'Auth', 'action' => 'register']) ?>
            <?= $this->Html->link('Back', ['plugin' => false, 'controller' => 'Pages', 'action' => 'landingPage']) ?>
        </div>
    </section>
</div>
