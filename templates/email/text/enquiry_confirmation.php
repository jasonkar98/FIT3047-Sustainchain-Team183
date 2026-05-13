<?php
/**
 * @var \Cake\View\View $this
 * @var string $full_name
 * @var string $email
 * @var string $subject
 * @var string $body
 */
?>
Hi <?= h($full_name) ?>,

Thank you for reaching out! We have received your enquiry and will get back to you as soon as possible.

Here is a copy of what you sent us:

Subject: <?= h($subject) ?>

Message:
<?= h($body) ?>

We will reply to <?= h($email) ?>.

Thanks,
The SustainChain Team
