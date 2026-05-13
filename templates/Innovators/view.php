<?php
/**
 * Public innovator detail page — read-only clone of the "My account"
 * template. Shown when a visitor clicks an innovator on the Discover
 * Innovators landing page.
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array<\App\Model\Entity\Product> $topProducts
 */

$this->assign('title', h($user->full_name) . ' — SustainChain');
$this->Html->css('marketplace', ['block' => true]);

$roleLabel = ucfirst((string)$user->role);
?>

<style>
.innovator-detail-wrap {
    background: var(--s0);
    padding: 4rem 2.5rem 6rem;
}
.innovator-detail-inner {
    max-width: 1100px;
    margin: 0 auto;
}

.innovator-detail-back {
    margin-bottom: 1.5rem;
}
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--white);
    border: 1px solid var(--s2);
    color: var(--g2);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.75rem 1.25rem;
    border-radius: var(--r16);
    transition: background 0.2s, color 0.2s, transform 0.18s var(--ease-spring);
    text-decoration: none;
}
.btn-back:hover {
    background: var(--s1);
    color: var(--g0);
    transform: translateY(-1px);
}
.btn-back-icon { width: 16px; height: 16px; }

.innovator-detail-card {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    box-shadow: 0 8px 40px rgba(13,31,20,.07);
    padding: 2.5rem;
    animation: reveal .6s .1s ease both;
}
@keyframes reveal {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

.innovator-role-pill {
    display: inline-flex;
    align-items: center;
    background: rgba(200,232,64,.12);
    border: 1px solid rgba(200,232,64,.22);
    color: var(--g2);
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 0.3rem 1rem;
    border-radius: var(--r999);
}

.innovator-detail-name {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 3vw, 2.6rem);
    font-weight: 700;
    line-height: 1.05;
    letter-spacing: -0.03em;
    color: var(--g0);
    margin: .65rem 0 .25rem;
}

.innovator-detail-section-label {
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: 0.7rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--g3);
    margin-top: 1.25rem;
    margin-bottom: .35rem;
}
.innovator-detail-body {
    font-size: 0.95rem;
    line-height: 1.7;
    color: var(--ink);
    white-space: pre-line;
}

.innovator-detail-divider {
    height: 1px;
    background: var(--s2);
    margin: 1.5rem 0 0;
}

@media (max-width: 768px) {
    .innovator-detail-wrap { padding: 2rem 1.25rem 4rem; }
    .innovator-detail-card { padding: 1.5rem; }
}
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Discover Innovators</span>
        <h1 class="marketplace-title t-display">
            <em><?= h($user->full_name) ?></em>
        </h1>
    </div>
</div>

<div class="innovator-detail-wrap">
    <div class="innovator-detail-inner">

        <div class="innovator-detail-back">
            <a href="<?= $this->Url->build(['controller' => 'Innovators', 'action' => 'index']) ?>" class="btn-back">
                <svg class="btn-back-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/>
                    <path d="M12 19l-7-7 7-7"/>
                </svg>
                Back to Discover Innovators
            </a>
        </div>

        <div class="innovator-detail-card">
            <span class="innovator-role-pill"><?= h($roleLabel) ?></span>
            <h2 class="innovator-detail-name"><?= h($user->full_name) ?></h2>

            <?php $hasGoals  = !empty($user->goals); ?>
            <?php $hasValues = !empty($user->business_values); ?>

            <?php if ($hasGoals): ?>
                <div class="innovator-detail-section-label">Business Goals</div>
                <p class="innovator-detail-body"><?= h($user->goals) ?></p>
            <?php endif; ?>

            <?php if ($hasValues): ?>
                <div class="innovator-detail-section-label">Business Values</div>
                <p class="innovator-detail-body"><?= h($user->business_values) ?></p>
            <?php endif; ?>

            <?php if (!$hasGoals && !$hasValues): ?>
                <p class="innovator-detail-body">This innovator hasn't shared their goals or values yet.</p>
            <?php endif; ?>

            <div class="innovator-detail-divider"></div>

            <?= $this->element('top_products', [
                'topProducts' => $topProducts ?? [],
                'heading'     => 'Most-sold products this month',
            ]) ?>
        </div>

    </div>
</div>
