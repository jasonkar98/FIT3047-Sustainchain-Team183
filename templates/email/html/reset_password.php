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
<p>Hi <?= h($first_name) ?> <?= h($last_name) ?>,</p>
<p>We received a request to reset the password for your account associated with <strong><?= h($email) ?></strong>.</p>
<p>Click the link below to reset your password. This link will expire in 7 days.</p>
<p><a href="<?= $resetUrl ?>">Reset My Password</a></p>
<p>If you did not request a password reset, you can safely ignore this email — your password will not be changed.</p>
<p>Thanks,<br>The Team183 Team</p>
