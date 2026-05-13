<?php
/**
 * Discover Innovators landing page.
 *
 * @var \App\View\AppView $this
 * @var array<\App\Model\Entity\User> $manufacturers
 */

$this->assign('title', 'Discover Innovators — SustainChain');
$this->Html->css('marketplace', ['block' => true]);
?>

<style>
/* ── Page shell ── */
.innovators-page {
    /* Fill the viewport — no scroll on desktop. Header + grid stack vertically. */
    min-height: calc(100vh - var(--nav-h, 80px));
    background: var(--s0);
    padding: 1.5rem 1.5rem 1.5rem;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* ── Header block ── */
.innovators-head {
    max-width: 1240px;
    margin: 0 auto;
    width: 100%;
    text-align: center;
}
.innovators-head .section-tag {
    color: var(--g3);
}
.innovators-head h1 {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.6rem, 3vw, 2.4rem);
    font-weight: 700;
    letter-spacing: -0.02em;
    margin: .35rem 0 .5rem;
    line-height: 1.05;
}
.innovators-head p {
    font-size: .95rem;
    color: var(--ink);
    max-width: 720px;
    margin: 0 auto;
    line-height: 1.55;
}

/* ── Grid: 5 × 2 vertical rectangles, fills remaining viewport ── */
.innovators-grid {
    flex: 1 1 auto;
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    grid-template-rows: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    min-height: 0; /* let the grid shrink to fit available height */
}

/* ── Card: vertical rectangle, name strip at the bottom ── */
.innovator-card {
    position: relative;
    border-radius: var(--r16, 16px);
    overflow: hidden;
    background: var(--s1, #d9ede4);
    border: 1px solid var(--s2, #cfd7cf);
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    transition: transform .15s var(--ease-spring), box-shadow .15s, border-color .15s;
    min-height: 0;
}
.innovator-card:hover {
    transform: translateY(-3px);
    border-color: var(--g3, #2e7d52);
    box-shadow: 0 14px 32px rgba(13,31,20,.18);
}

.innovator-card-img {
    flex: 1 1 auto;
    background: linear-gradient(160deg, #e8f0ec 0%, #b9d4c4 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
    min-height: 0;
}
.innovator-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.innovator-card-img .initial {
    font-family: 'Fraunces', serif;
    font-weight: 700;
    font-size: clamp(2rem, 4vw, 4rem);
    color: rgba(13,31,20,.4);
    letter-spacing: -.05em;
}
.innovator-card-rank {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(13,31,20,.78);
    color: #fff;
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: .72rem;
    letter-spacing: .08em;
    padding: .2rem .55rem;
    border-radius: 999px;
}

.innovator-card-name-strip {
    background: var(--white, #fff);
    border-top: 1px solid var(--s2, #cfd7cf);
    padding: .65rem .8rem;
    display: flex;
    flex-direction: column;
    gap: .1rem;
}
.innovator-card-name {
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: .95rem;
    color: var(--g0, #0d1f14);
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.innovator-card-meta {
    font-size: .68rem;
    color: var(--g3, #2e7d52);
    letter-spacing: .04em;
    text-transform: uppercase;
    font-weight: 700;
}

/* ── Empty state ── */
.innovators-empty {
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--white, #fff);
    border: 1px dashed var(--s2, #cfd7cf);
    border-radius: var(--r16, 16px);
    color: var(--g3, #2e7d52);
    font-family: 'Cabinet Grotesk', sans-serif;
    text-align: center;
    padding: 2rem;
}

/* ── Responsive ── */
@media (max-width: 1100px) {
    .innovators-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        grid-template-rows: repeat(3, minmax(0, 1fr));
    }
}
@media (max-width: 800px) {
    .innovators-page {
        min-height: 0;
        height: auto;
    }
    .innovators-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        grid-template-rows: auto;
    }
    .innovator-card {
        min-height: 240px;
    }
}
@media (max-width: 480px) {
    .innovators-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="innovators-page">

    <header class="innovators-head">
        <span class="t-label section-tag">Discover Innovators</span>
        <h1>The makers <em>shaping</em> sustainable commerce</h1>
        <p>
            This page showcases the most innovative businesses over this past month.
            They have produced products that have been loved by customers and show
            what it truly means to produce sustainably.
        </p>
    </header>

    <?php if (empty($manufacturers)): ?>
        <div class="innovators-empty">
            <div>
                <p style="font-weight:700; margin: 0 0 .35rem;">No featured innovators just yet.</p>
                <p style="margin: 0; color: var(--ink);">This page is currently empty. Check back soon as more innovators make their mark.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="innovators-grid">
            <?php foreach ($manufacturers as $i => $m): ?>
                <?php $rank = $i + 1; ?>
                <a class="innovator-card" href="<?= $this->Url->build(['controller' => 'Innovators', 'action' => 'view', $m->id]) ?>">
                    <div class="innovator-card-img">
                        <span class="innovator-card-rank">#<?= $rank ?></span>
                        <?php if (!empty($m->profile)): ?>
                            <?= $this->Html->image('profiles/' . $m->profile, ['alt' => h($m->full_name)]) ?>
                        <?php else: ?>
                            <span class="initial"><?= h(strtoupper(substr($m->first_name ?? '?', 0, 1))) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="innovator-card-name-strip">
                        <span class="innovator-card-name"><?= h($m->full_name) ?></span>
                        <span class="innovator-card-meta">Manufacturer</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
