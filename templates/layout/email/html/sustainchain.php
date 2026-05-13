<?php
/**
 * @var \App\View\AppView $this
 * @var string $first_name
 * @var string $last_name
 * @var string $email
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:0; }
        .wrapper { max-width:600px; margin:32px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.07); }
        .header { background:#1a3c2e; padding:28px 32px; }
        .header h1 { color:#ffffff; margin:0; font-size:22px; letter-spacing:1px; }
        .header p { color:#a8c5b5; margin:4px 0 0; font-size:13px; }
        .body { padding:32px; color:#333333; font-size:15px; line-height:1.6; }
        .footer { background:#f0f0f0; padding:16px 32px; font-size:12px; color:#888888; text-align:center; border-top:1px solid #e0e0e0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>SustainChain</h1>
            <p>Sustainable future, connected supply chains.</p>
        </div>
        <div class="body">
            <?= $this->fetch('content') ?>
        </div>
        <div class="footer">
            This email was sent to <?= h($email) ?><br>
            &copy; <?= date('Y') ?> SustainChain &mdash; Monash FIT Industry Experience
        </div>
    </div>
</body>
</html>
