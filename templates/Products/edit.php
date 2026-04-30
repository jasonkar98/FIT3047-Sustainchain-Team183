<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */

use Cake\Core\Configure;

// $this->Html->script('https://challenges.cloudflare.com/turnstile/v0/api.js', [
//     'block' => true,
//     'async' => true,
//     'defer' => true,
// ]);
// $this->Html->meta([
//     'block' => true,
//     'link' => 'https://challenges.cloudflare.com',
//     'rel' => 'preconnect',
// ]);

$this->assign('title', 'Add a Product — SustainChain');
$this->Html->css('marketplace', ['block' => true]);

?>

<style>
/* ── Hero banner ── */
.form-hero {
    background: var(--g0);
    position: relative;
    overflow: hidden;
    padding: 5rem 2.5rem 4rem;
}
.form-hero-bg {
    position: absolute; inset: 0; z-index: 0;
    background:
        radial-gradient(ellipse 60% 70% at 100% 0%,   rgba(46,125,82,.22) 0%, transparent 60%),
        radial-gradient(ellipse 50% 50% at 0%   100%,  rgba(200,232,64,.07) 0%, transparent 55%);
}
.form-hero-circle {
    position: absolute;
    width: 480px; height: 480px;
    border: 1px solid rgba(77,170,122,.1);
    border-radius: 50%;
    top: -120px; right: -80px;
    animation: spin-slow 60s linear infinite;
}
@keyframes spin-slow { to { transform: rotate(360deg); } }

.form-hero-inner {
    position: relative; z-index: 2;
    max-width: 780px; margin: 0 auto;
    text-align: center;
}
.form-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(200,232,64,.12);
    border: 1px solid rgba(200,232,64,.22);
    color: var(--e1);
    padding: .3rem 1rem; border-radius: var(--r999);
    margin-bottom: 1.25rem;
    animation: reveal .5s ease both;
}
.form-eyebrow-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--e1);
    animation: blink 2s ease infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

.form-hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 700; line-height: 1.05; letter-spacing: -0.03em;
    color: var(--white); margin-bottom: .75rem;
    animation: reveal .6s .1s ease both;
}
.form-hero-title em {
    font-style: italic; font-weight: 300; color: var(--e1);
}
.form-hero-sub {
    font-size: 1rem; line-height: 1.7;
    color: rgba(255,255,255,.45);
    animation: reveal .6s .2s ease both;
}

@keyframes reveal {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Main form area ── */
.form-main {
    background: var(--s0);
    padding: 4rem 2.5rem 6rem;
}
.form-main-inner {
    max-width: 680px; margin: 0 auto;
}

/* ── Form card ── */
.form-card {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    padding: 3rem;
    box-shadow: 0 8px 40px rgba(13,31,20,.07);
    animation: reveal .6s .3s ease both;
}

/* ── Form fields ── */
.form-card .input {
    margin-bottom: 1.5rem;
}

.form-card label {
    display: block;
    font-family: 'Cabinet Grotesk', sans-serif;
    font-size: .72rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: var(--muted);
    margin-bottom: .5rem;
}

.form-card input[type="text"],
.form-card input[type="email"],
.form-card input[type="number"],
.form-card select,
.form-card textarea {
    width: 100%;
    background: var(--s0);
    border: 1.5px solid var(--s2);
    border-radius: var(--r16);
    padding: .85rem 1.1rem;
    font-family: 'Satoshi', 'Cabinet Grotesk', sans-serif;
    font-size: .95rem; font-weight: 400;
    color: var(--ink);
    transition: border-color .2s, box-shadow .2s, background .2s;
    outline: none;
    resize: vertical;
}
.form-card input[type="text"]:focus,
.form-card input[type="email"]:focus,
.form-card input[type="number"]:focus,
.form-card select:focus,
.form-card textarea:focus {
    border-color: var(--g3);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(46,125,82,.1);
}
.form-card input[type="text"]:hover,
.form-card input[type="email"]:hover,
.form-card input[type="number"]:hover,
.form-card select:hover,
.form-card textarea:hover {
    border-color: var(--g5);
}

.form-card textarea {
    min-height: 140px;
}

/* Error messages */
.form-card .error-message {
    font-size: .78rem; color: #C0392B;
    margin-top: .35rem; display: block;
}
.form-card .input.error input,
.form-card .input.error textarea {
    border-color: #F0B8B2;
    background: #FEF8F7;
}

/* ── Divider ── */
.form-divider {
    height: 1px; background: var(--s2);
    margin: 2rem 0;
}

/* ── Turnstile wrapper ── */
.turnstile-wrap {
    margin-bottom: 1.5rem;
    border-radius: var(--r16);
    overflow: hidden;
    border: 1.5px solid var(--s2);
    transition: border-color .2s;
}
.turnstile-wrap.verified {
    border-color: var(--g4);
}

/* Turnstile status message */
#turnstile-message {
    display: none;
    background: #FEF3CD;
    border: 1px solid #F6D860;
    border-radius: var(--r8);
    padding: .75rem 1rem;
    font-size: .82rem;
    color: #7B4A00;
    margin-top: .75rem;
    line-height: 1.5;
}

/* ── File button ── */
.btn-file::file-selector-button {
    display: inline-flex; align-items: center; gap: .5rem;
    background: var(--g0);
    color: var(--white);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700; font-size: .80rem;
    padding: .5rem 1.25rem;
    border: none; border-radius: var(--r16);
    cursor: pointer;
    transition: background .2s, transform .18s var(--ease-spring), box-shadow .2s;
    box-shadow: 0 4px 20px rgba(13,31,20,.2);
    letter-spacing: -.01em;
}
.btn-file::file-selector-button:hover {
    background: var(--g2);
    box-shadow: 0 8px 28px rgba(13,31,20,.3);
}

.btn-checkbox {
    accent-color: rgba(200,232,64,.45);
    width: 15px;
    height: 15px;
    vertical-align: middle;
    margin-right: 10px;
}


/* ── Submit button ── */
.submit-row {
    display: flex; align-items: center;
    justify-content: center;
    gap: 1rem; flex-wrap: wrap;
    margin-top: 2rem;
}

.btn-submit {
    display: inline-flex; align-items: center; gap: .5rem;
    background: var(--g0);
    color: var(--white);
    font-family: 'Cabinet Grotesk', sans-serif;
    font-weight: 700; font-size: .95rem;
    padding: .9rem 2.25rem;
    border: none; border-radius: var(--r16);
    cursor: pointer;
    transition: background .2s, transform .18s var(--ease-spring), box-shadow .2s;
    box-shadow: 0 4px 20px rgba(13,31,20,.2);
    letter-spacing: -.01em;
}
.btn-submit:not(:disabled):hover {
    background: var(--g2);
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(13,31,20,.3);
}
.btn-submit:disabled {
    opacity: .45;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}
.btn-submit.ready {
    background: var(--e1);
    color: var(--g0);
    box-shadow: 0 4px 20px rgba(200,232,64,.35);
}
.btn-submit.ready:hover {
    background: var(--e0);
    box-shadow: 0 8px 28px rgba(200,232,64,.45);
}

.submit-note {
    font-size: .78rem; color: var(--muted);
    line-height: 1.5; max-width: 260px;
}

/* ── Trust badges below form ── */
.trust-strip {
    display: flex; align-items: center;
    justify-content: center;
    gap: 2rem; flex-wrap: wrap;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid var(--s2);
}
.trust-item {
    display: flex; align-items: center; gap: .45rem;
    font-size: .78rem; color: var(--muted); font-weight: 500;
}
.trust-item span { font-size: 1rem; }


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

/* ── Responsive ── */
@media (max-width: 768px) {
    .form-hero { padding: 4rem 1.5rem 3rem; }
    .form-main { padding: 3rem 1.5rem 4rem; }
    .form-card { padding: 2rem 1.5rem; }
    .submit-row { flex-direction: column; align-items: stretch; }
    .btn-submit { text-align: center; justify-content: center; }
    .submit-note { max-width: none; }
}
</style>


<!-- ════ HERO ════ -->
<div class="form-hero">
    <div class="form-hero-bg"></div>
    <div class="form-hero-circle"></div>

    <div class="form-hero-inner">
        <div class="form-eyebrow t-label">
            <span class="form-eyebrow-dot"></span>
            Modify A Product
        </div>
        <h1 class="form-hero-title">
            Modify Your Product, <em><?= h($product->name) ?></em>
        </h1>
        <p class="form-hero-sub">
            Want to make changes to how others see your product on our page? Submit this form and your product modifications will be seen by others.
        </p>
    </div>
</div>

<!-- ════ FORM ════ -->
<div class="form-main">
    <div class="form-main-inner">

        <!-- Back button -->
        <div class="product-view-back">
            <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'my-listings']) ?>" class="btn-back">
                <svg class="btn-back-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/>
                    <path d="M12 19l-7-7 7-7"/>
                </svg>
                Back to Your Listings
            </a>
        </div>

        <div class="form-card">

            <?= $this->Form->create($product, ['id' => 'product-form', 'type' => 'file']) ?>



                <?= $this->Form->control('name', [
                    'label' => 'Product Name *',
                    'placeholder' => 'e.g. Farmland - Dozen Free Range Eggs',
                ]) ?>

                <?= $this->Form->control('description', [
                    'label' => 'Description *',
                    'placeholder' => 'e.g. A dozen free range eggs, from Chickens that are raised on farmland where they can freely roam around the paddock.',
                    'type' => 'textarea',
                    'maxlength' =>500,
                ]) ?>

                <?= $this->Form->control('price', [
                    'label' => 'Price (AUD) *',
                    'placeholder' => 'e.g. 6.50',
                    'min' => '0.01',
                ]) ?>

                <?= $this->Form->control('category', [
                    'label' => 'Product Category *',
                    'type' => 'select',
                    'options' => ['Food' => 'Food', 'Beauty' => 'Beauty', 'Apparel' => 'Apparel', 'Kitchenware' => 'Kitchenware', 'Bathroom' => 'Bathroom', 'Outdoors' => 'Outdoors', 'Sporting' => 'Sporting', 'Supplements' => 'Supplements', 'Other' => 'Other'],
                    'empty' => 'Select a Category',
                ]) ?>

                <?= $this->Form->control('filtertags._ids', [
                    'label' => 'Product Tags',
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'class' => 'btn-checkbox',
                    'options' => $filtertags
                ]) ?>

                <?= $this->Form->control('image_url', [
                    'label' => 'Image',
                    'id' => 'image-selection',
                    'type' => 'file',
                    'accept' => 'image/jpeg, image/png',
                    'class' => 'btn-file',
                ]) ?>

                <?= $this->Html->image('products/' . $product->image_url, [
                    'id' => 'image-viewing',
                    'style' => '
                    width:750px;
                    height: auto;
                    margin-top:10px;',
                ]); ?>

                <div class="form-divider"></div>

                <div class="submit-row">
                    <?= $this->Form->button('List Product >', [
                        'class' => 'btn-submit',
                        'id'    => 'submit-btn',
                    ]) ?>
                </div>

            <?= $this->Form->end() ?>

        </div>

    </div>
</div>

<script>
    document.getElementById('image-selection').onchange = function (event) {
        const [file] = event.target.files;
        const preview = document.getElementById('image-viewing');

        if (file) {
            // Generate a temporary URL for the selected file
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    };
</script>
