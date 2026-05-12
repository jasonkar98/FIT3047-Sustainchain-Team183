<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$this->layout = 'login';
$this->assign('title', 'Reset Password');
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
        <p class="card-eyebrow">Account security</p>
        <h2>Reset password</h2>
        <p class="auth-card-subtitle">Enter and confirm your new password below.</p>

        <?= $this->Flash->render() ?>
        <?= $this->Form->create($user, ['class' => 'auth-form']) ?>
        <?php
        echo $this->Form->control('password', [
            'type' => 'password',
            'label' => 'New Password',
            'required' => true,
            'autofocus' => true,
            'placeholder' => 'Enter new password',
            'value' => '',
        ]);
        echo $this->Form->control('password_confirm', [
            'type' => 'password',
            'label' => 'Confirm New Password',
            'required' => true,
            'placeholder' => 'Repeat new password',
            'value' => '',
        ]);
        ?>
        <?= $this->Form->button('Reset Password', ['class' => 'button auth-primary-btn']) ?>
        <?= $this->Form->end() ?>

        <div class="auth-links">
            <?= $this->Html->link('Back to login', ['controller' => 'Auth', 'action' => 'login']) ?>
        </div>
    </section>
</div>
