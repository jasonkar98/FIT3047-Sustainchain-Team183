<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Enquiry $enquiry
 */

use Cake\Core\Configure;

// Load CF Turnstile captcha JS library

$this->Html->script('https://challenges.cloudflare.com/turnstile/v0/api.js', [
    'block' => true,
    'async' => true,
    'defer' => true,
]);
$this->Html->meta([
    'block' => true,
    'link' => 'https://challenges.cloudflare.com',
    'rel' => 'preconnect',
]);

?>
<div class="row">
    <div class="column column-80">
        <div class="enquiries form content">
            <?= $this->Form->create($enquiry) ?>
            <fieldset>
                <legend><?= __('Add Enquiry') ?></legend>
                <?php
                    echo $this->Form->control('full_name', ['label' => 'Your Full Name']);
                    echo $this->Form->control('email', ['label' => 'Enquiry Recipient Email Address']);
//                    echo $this->Form->control('date');
                    echo $this->Form->control('subject', ['label' => 'Enquiry Title']);
                    echo $this->Form->control('body', ['label' => 'What Is Your Enquiry?']);
//                    echo $this->Form->control('email_sent');
                ?>

                <div class="cf-turnstile"
                     data-size="flexible"
                     data-theme="light"
                     data-callback="turnstileOnSuccess"
                     data-error-callback="turnstileOnError"
                     data-expired-callback="turnstileOnExpired"
                     data-timeout-callback="turnstileOnTimeout"
                     data-sitekey="<?= Configure::read('Captcha.turnstile.siteKey') ?>"
                ></div>
                <blockquote id="turnstile-message" style="display:none"></blockquote>


            </fieldset>
<!--            --><?php //= $this->Form->button(__('Send Enquiry')) ?>
            <?= $this->Form->button('Send Enquiry', ['class' => 'action-button', 'disabled' => true]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>


<script>
    // Callbacks for Turnstile. Login button is disabled until Turnstile passes.
    var turnstileMessageBlock = document.querySelector('#turnstile-message');
    var actionButton = document.querySelector('button.action-button');

    function turnstileOnSuccess(token) {
        turnstileMessageBlock.style.display = 'none';
        actionButton.removeAttribute('disabled');
    }

    function turnstileOnError(errorCode) {
        turnstileMessageBlock.style.display = 'block';
        turnstileMessageBlock.innerText = "Challenge error. Please refresh the webpage and try again.";
        actionButton.setAttribute('disabled');
    }

    function turnstileOnExpired() {
        turnstileMessageBlock.style.display = 'block';
        turnstileMessageBlock.innerText = "Challenge token expired. Please validate again.";
        actionButton.setAttribute('disabled');
    }

    function turnstileOnTimeout() {
        turnstileMessageBlock.style.display = 'block';
        turnstileMessageBlock.innerText = "Challenge timed out. Please validate again.";
        actionButton.setAttribute('disabled');
    }
</script>
