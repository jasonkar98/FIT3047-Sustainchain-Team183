<?php
/**
 * SustainChain — View Product
 * templates/Products/view.php
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

use Cake\Core\Configure;

$this->assign('title', h($user->first_name) . ' — SustainChain');
$this->Html->css('marketplace', ['block' => true]);
?>

<style>
/* ── Product view layout ── */
.product-view-wrap {
    background: var(--s0);
    padding: 4rem 2.5rem 6rem;
}
.product-view-inner {
    max-width: 1100px;
    margin: 0 auto;
}

.product-view-card {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    box-shadow: 0 8px 40px rgba(13,31,20,.07);
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr 1fr;
    animation: reveal .6s .1s ease both;
}

@keyframes reveal {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Image side ── */
.product-view-img-wrap {
    position: relative;
    background: var(--s1);
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.product-view-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    inset: 0;
}

/* ── Save button on image ── */
.product-view-save {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    border: 1px solid var(--s2);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s, transform 0.18s var(--ease-spring);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 2;
}
.product-view-save:hover {
    background: var(--white);
    transform: scale(1.1);
}
.product-view-save svg {
    width: 20px;
    height: 20px;
    stroke: var(--g3);
    fill: none;
    transition: fill 0.15s, stroke 0.15s;
}
.product-view-save.is-saved svg {
    fill: var(--white);
    stroke: var(--white);
}

/* Override marketplace.css .product-save-btn.is-saved to make background green */
.product-view-save.is-saved {
    background: var(--g3);
    border-color: var(--g3);
}

/* ── Details side ── */
.product-view-details {
    padding: 3rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.product-view-category {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
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

.product-view-name {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 3vw, 2.8rem);
    font-weight: 700;
    line-height: 1.05;
    letter-spacing: -0.03em;
    color: var(--g0);
}

.product-view-seller {
    font-family: 'Fraunces', serif;
    font-size: 1rem;
    font-style: italic;
    color: var(--g3);
}

.product-view-price {
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--g0);
    letter-spacing: -0.03em;
}

.product-view-divider {
    height: 1px;
    background: var(--s2);
}

.product-view-desc {
    font-size: 0.95rem;
    line-height: 1.8;
    color: var(--ink);
}

/* ── Filter tags ── */
.product-view-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.product-view-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: var(--s1);
    border: 1px solid var(--s2);
    color: var(--g2);
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.3rem 0.85rem;
    border-radius: var(--r999);
}
.product-view-tag-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--g3);
}

/* ── Actions ── */
.product-view-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: auto;
    padding-top: 0.5rem;
}

.btn-cart {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: var(--g0);
    color: var(--white);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 0.9rem 2rem;
    border: none;
    border-radius: var(--r16);
    cursor: pointer;
    transition: background 0.2s, transform 0.18s var(--ease-spring), box-shadow 0.2s;
    box-shadow: 0 4px 20px rgba(13,31,20,.2);
    letter-spacing: -0.01em;
}
.btn-cart:hover {
    background: var(--g2);
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(13,31,20,.3);
}

.btn-cart-icon {
    width: 18px;
    height: 18px;
}

/* ── Back button ── */
.product-view-back {
    margin-bottom: 2rem;
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
    cursor: pointer;
    transition: background 0.2s, color 0.2s, transform 0.18s var(--ease-spring);
    text-decoration: none;
}
.btn-back:hover {
    background: var(--s1);
    color: var(--g0);
    transform: translateY(-1px);
}
.btn-back-icon {
    width: 16px;
    height: 16px;
}

/* ── Edit Delete button ── */
.product-view-modify {
    margin-bottom: 2rem;
    display: flex;
    justify-content: center;
}

tr, td {
    text-align: center;
    vertical-align: middle;
}

.btn-modify {
    gap: 0.5rem;
    background: var(--white);
    border: 1px solid var(--s2);
    color: var(--g2);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.75rem 1.25rem;
    border-radius: var(--r16);
    cursor: pointer;
    transition: background 0.2s, color 0.2s, transform 0.18s var(--ease-spring);
    text-decoration: none;
}
.btn-modify:hover {
    background: var(--s1);
    color: var(--g0);
    transform: translateY(-1px);
}
.btn-modify-icon {
    width: 16px;
    height: 16px;
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .product-view-card {
        grid-template-columns: 1fr;
    }
    .product-view-img-wrap {
        min-height: 280px;
    }
    .product-view-details {
        padding: 2rem 1.5rem;
    }
    .product-view-wrap {
        padding: 2rem 1.25rem 4rem;
    }
}
</style>

<!-- Page header — dark green banner, same style as the landing page hero -->
<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">SustainChain</span>
        <h1 class="marketplace-title t-display">
            Your <em>User</em> Account
        </h1>
        <p class="marketplace-subtitle">
            View your user details, edit your user details, or request to terminate your account.
        </p>
    </div>
</div>

<div class="product-view-wrap">
    <div class="product-view-inner">
        <!-- Back button -->
        <div class="product-view-back">
            <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'landingPage']) ?>" class="btn-back">
                <svg class="btn-back-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/>
                    <path d="M12 19l-7-7 7-7"/>
                </svg>
                Back
            </a>
        </div>

        <div class="product-view-details">
            <div>
                <span class="product-view-category"><?= h($user->role) ?></span>
            </div>

            <h1 class="product-view-name"><?= h($user->first_name) ?> <?= h($user->last_name) ?></h1>

            <div class="product-view-seller">
                <?= h($user->email) ?>
            </div>

            <?php if (h($user->role) == 'manufacturer'): ?>
                <p class="product-view-desc"><?= h($user->description) ?></p>
            <?php endif; ?>

        </div>

        <div class="product-view-divider"></div>
        <br>

        <div class="product-view-modify">
            <table>
                <tr>
                    <td>
                    <a href="<?= $this->Url->build(['controller' => 'Auth', 'action' => 'edit', $user->id]) ?>" class="btn-modify">
                        Edit
                    </a>
                    </td>
                    <td>
                    <?= $this->Form->postLink(
                        __('Delete This Account'),
                        ['controller' => 'Auth', 'action' => 'delete', $user->id],
                        [
                            'method' => 'delete',
                            'confirm' => __('Are you sure you want to delete your account, {0} {1}?', $user->first_name, $user->last_name),
                            'class' => 'btn-modify'
                        ]
                    ) ?>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>
