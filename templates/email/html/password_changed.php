<?php
/**
 * @var \Cake\View\View $this
 * @var string $first_name
 * @var string $last_name
 * @var string $email
 */
?>
<p>Hi <?= h($first_name) ?> <?= h($last_name) ?>,</p>
<p>This is a confirmation that the password for your SustainChain account associated with <strong><?= h($email) ?></strong> has been successfully changed.</p>
<p>If you made this change, no further action is needed.</p>
<p>If you did not change your password, please contact us immediately as your account may have been compromised.</p>
<p>Thanks,<br>The SustainChain Team</p>
