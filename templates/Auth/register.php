<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$this->layout = 'login';
$this->assign('title', 'Register new user');
?>
<div class="auth-page">
    <section class="auth-hero">
        <p class="auth-tagline">Sustainable future, connected supply chains</p>
        <h1>Sustain Chain</h1>
        <p class="auth-subtitle">Create your account and join a more natural way to collaborate.</p>
    </section>

    <section class="auth-card">
        <h2>Create account</h2>
        <p class="auth-card-subtitle">Set up your profile to get started.</p>

        <?= $this->Flash->render() ?>
        <?= $this->Form->create($user, ['class' => 'auth-form']) ?>

        <?= $this->Form->control('email', ['placeholder' => 'name@company.com']); ?>

        <div class="auth-grid">
            <?= $this->Form->control('first_name', ['label' => 'First name', 'placeholder' => 'Ava']); ?>
            <?= $this->Form->control('last_name', ['label' => 'Last name', 'placeholder' => 'Patel']); ?>
        </div>

        <div class="auth-grid">
            <?php
            echo $this->Form->control('password', [
                'value' => '', // Ensure password is not sent back to the client
                'placeholder' => 'Create password',
            ]);
            echo $this->Form->control('password_confirm', [
                'type' => 'password',
                'value' => '', // Ensure password is not sent back to the client
                'label' => 'Retype Password',
                'placeholder' => 'Repeat password',
            ]);
            ?>
        </div>

        <?= $this->Form->control('avatar', ['type' => 'file', 'label' => 'Profile photo (optional)']); ?>

        <?= $this->Form->button('Register', ['class' => 'button auth-primary-btn']) ?>
        <?= $this->Form->end() ?>

        <div class="auth-links">
            <?= $this->Html->link('Back to login', ['controller' => 'Auth', 'action' => 'login']) ?>
            <?= $this->Html->link('Go to Homepage', '/') ?>
        </div>
    </section>
</div>
