<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<style>
.flash-message{position:fixed;bottom:2rem;right:2rem;z-index:9999;display:flex;align-items:center;gap:10px;padding:.85rem 1rem;border-radius:999px;border:1px solid;font-size:.875rem;font-weight:600;max-width:360px;cursor:pointer;box-shadow:0 4px 24px rgba(13,31,20,.1);animation:flash-in .3s ease forwards,flash-out .25s ease 4s forwards}
.flash-success{background:#e8f5e2;border-color:rgba(46,125,82,.25);color:#1a5c3a}
.flash-error{background:#fdf0f0;border-color:rgba(200,50,50,.2);color:#7a1f1f}
.flash-icon{display:flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;flex-shrink:0}
.flash-success .flash-icon{background:#2e7d52;color:#fff}
.flash-error .flash-icon{background:#c73030;color:#fff}
.flash-text{flex:1}
.flash-close{display:flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;border:none;background:transparent;cursor:pointer;opacity:.5;transition:opacity .15s;color:inherit;padding:0}
.flash-close:hover{opacity:1}
.flash-hidden{animation:flash-out .25s ease forwards!important;pointer-events:none}
@keyframes flash-in{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
@keyframes flash-out{from{opacity:1;transform:translateY(0)}to{opacity:0;transform:translateY(8px)}}
</style>
<div class="flash-message flash-success">
    <span class="flash-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </span>
    <span class="flash-text"><?= $message ?></span>
    <button class="flash-close" aria-label="Dismiss" onclick="this.closest('.flash-message').classList.add('flash-hidden')">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
</div>