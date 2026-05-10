<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$this->layout = 'login';
$this->assign('title', 'Register new user');
?>

<div class="auth-page register">
    <section class="auth-hero">
        <div class="hero-eyebrow">
            <span class="eyebrow-dot"></span>
            Join the supply chain
        </div>
        <h1>Sustain<em>Chain</em></h1>
        <p class="auth-subtitle">Create your account and join a more natural way to collaborate.</p>
        <div class="auth-steps">
            <div class="auth-step">
                <div class="step-num">1</div>
                <div><div class="step-title">Create your profile</div><div class="step-desc">Set up your name, email and credentials</div></div>
            </div>
            <div class="auth-step">
                <div class="step-num">2</div>
                <div><div class="step-title">Connect your supply chain</div><div class="step-desc">Buy, sell, and collaborate with eco-friendly suppliers</div></div>
            </div>
            <div class="auth-step">
                <div class="step-num">3</div>
                <div><div class="step-title">Grow responsibly</div><div class="step-desc">Make an impact with your sustainable choices</div></div>
            </div>
        </div>
    </section>

    <?php if ($step == 1): ?>

        <section class="auth-card">
            <p class="card-eyebrow">Get started</p>
            <h2>Create account</h2>
            <p class="auth-card-subtitle">Set up your profile to get started.</p>

            <?= $this->Flash->render() ?>
            <?= $this->Form->create($user, ['class' => 'auth-form']) ?>

            <div class="auth-grid">
                <?= $this->Form->control('role', ['options' => $roles, 'empty' => 'Select a Role', 'required' => true, 'default' => '']) ?>
            </div>

            <?= $this->Form->hidden('role_selected', ['value' => 1]); ?>
            <?= $this->Form->button('Select Role', ['class' => 'button auth-primary-btn']) ?>

            <div class="auth-links">
                <?= $this->Html->link('Login', ['controller' => 'Auth', 'action' => 'login']) ?>
                <?= $this->Html->link('Back', ['controller' => 'Pages', 'action' => 'landingPage']) ?>
            </div>
        </section>

    <?php else: ?>

        <section class="auth-card">
            <p class="card-eyebrow">Get started</p>
            <h2>Create account</h2>
            <p class="auth-card-subtitle">Set up your profile to get started.</p>

            <?= $this->Flash->render() ?>
            <?= $this->Form->create($user, ['class' => 'auth-form']) ?>

            <?= $this->Form->control('email', ['placeholder' => 'name@company.com']); ?>

            <div class="auth-grid">
                <?= $this->Form->control('first_name', ['label' => 'First name', 'placeholder' => 'Ava', 'pattern' => '[a-zA-Z ]+']); ?>
                <?= $this->Form->control('last_name', ['label' => 'Last name', 'placeholder' => 'Patel', 'pattern' => '[a-zA-Z ]+']); ?>
            </div>

            <?= $this->Form->control('role', ['type' => 'hidden', 'required' => true, 'default' => $role_selected]) ?>

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
            <ul class="auth-card-subtitle">
                Your password must contain at least:
                <li>8 characters</li>
                <li>One uppercase letter</li>
                <li>One number</li>
                <li>One special character</li>
            </ul>

            <!-- <?= $this->Form->control('avatar', ['type' => 'file', 'label' => 'Profile photo (optional)']); ?> -->

            <?= $this->Form->hidden('submit_details', ['value' => 1]); ?>
            <?= $this->Form->button('Register', ['class' => 'button auth-primary-btn']) ?>
            <?= $this->Form->end() ?>

            <div class="auth-links">
                <?= $this->Html->link('Login', ['controller' => 'Auth', 'action' => 'login']) ?>
                <?= $this->Html->link('Back', ['controller' => 'Pages', 'action' => 'landingPage']) ?>
            </div>
        </section>

    <?php endif; ?>
</div>
