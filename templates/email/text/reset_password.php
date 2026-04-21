<?php
/**
 * @var \Cake\View\View $this
 * @var string $first_name
 * @var string $last_name
 * @var string $nonce
 * @var string $email
 */

$resetUrl = $this->Url->build(['controller' => 'Auth', 'action' => 'resetPassword', $nonce], ['fullBase' => true]);
?>
Hi <?= h($first_name) ?> <?= h($last_name) ?>,

We received a request to reset the password for your account associated with <?= h($email) ?>.

To reset your password, visit the following link (expires in 7 days):
<?= $resetUrl ?>

If you did not request a password reset, you can safely ignore this email.

Thanks,
The Team183 Team
