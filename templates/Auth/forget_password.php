<?php
/**
 * @var \App\View\AppView $this
 */

$this->layout = 'login';
$this->assign('title', 'Forget Password');
?>

<div class="auth-page">
    <section class="auth-hero">
        <p class="auth-tagline">Sustainable future, connected supply chains</p>
        <h1>Sustain Chain</h1>
        <p class="auth-subtitle">Secure account recovery with a clean, simple flow.</p>
    </section>

    <section class="auth-card">
        <h2>Forgot password</h2>
        <p class="auth-card-subtitle">Enter your registered email and we will send reset instructions.</p>

        <?= $this->Flash->render() ?>
        <?= $this->Form->create(null, ['class' => 'auth-form']) ?>
        <?php
        echo $this->Form->control('email', [
            'type' => 'email',
            'required' => true,
            'autofocus' => true,
            'label' => 'Email',
            'placeholder' => 'name@company.com',
        ]);
        ?>
        <?= $this->Form->button('Send verification email', ['class' => 'button auth-primary-btn']) ?>
        <?= $this->Form->end() ?>

        <div class="auth-links">
            <?= $this->Html->link('Back to login', ['controller' => 'Auth', 'action' => 'login']) ?>
            <?= $this->Html->link('Register new user', ['controller' => 'Auth', 'action' => 'register']) ?>
        </div>
    </section>
</div>
