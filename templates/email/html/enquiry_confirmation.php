<?php
/**
 * @var \Cake\View\View $this
 * @var string $full_name
 * @var string $email
 * @var string $subject
 * @var string $body
 */
?>
<p>Hi <?= h($full_name) ?>,</p>
<p>Thank you for reaching out! We have received your enquiry and will get back to you as soon as possible.</p>
<p>Here is a copy of what you sent us:</p>
<table style="width:100%;border-collapse:collapse;margin:16px 0;">
    <tr>
        <td style="padding:8px 12px;background:#f4f4f4;font-weight:bold;width:100px;">Subject</td>
        <td style="padding:8px 12px;border-bottom:1px solid #eee;"><?= h($subject) ?></td>
    </tr>
    <tr>
        <td style="padding:8px 12px;background:#f4f4f4;font-weight:bold;vertical-align:top;">Message</td>
        <td style="padding:8px 12px;"><?= nl2br(h($body)) ?></td>
    </tr>
</table>
<p>We will reply to <strong><?= h($email) ?></strong>.</p>
<p>Thanks,<br>The SustainChain Team</p>
