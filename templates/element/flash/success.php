<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="toast toast-success" role="alert">
    <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><path d="M8 12l3 3 5-5"/>
    </svg>
    <span class="toast-msg"><?= $message ?></span>
    <button class="toast-close" onclick="this.closest('.toast').remove()" aria-label="Dismiss">&times;</button>
</div>
