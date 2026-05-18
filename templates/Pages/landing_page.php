<?php
/**
 * Landing Page
 */
use Cake\Core\Configure;

$this->assign('title', 'SustainChain');
?>

<!-- hero -->
<section class="hero">
    <!-- slideshow backgrounds -->
    <div class="hero-slides">
        <div class="hero-slide active" style="background-image:url('<?= $this->Url->image('HERO.png') ?>')"></div>
        <div class="hero-slide" style="background-image:url('<?= $this->Url->image('cowsHomepage.png') ?>')"></div>
        <div class="hero-slide" style="background-image:url('<?= $this->Url->image('cleanEnergy.png') ?>')"></div>
    </div>
    <div class="hero-bg"></div>
    <div class="hero-inner">
        <div class="hero-left">
            <div class="hero-eyebrow t-label">
                <span class="eyebrow-dot"></span>
                Sustainable Commerce Platform
            </div>

             <h1 class="hero-title t-display">
                Commerce that's<br>
                good for the <em>planet</em>
            </h1>

            <p class="hero-sub">
                <?= $this->ContentBlock->text('landing-page-description') ?>
            </p>

            <div class="hero-actions">
                <?= $this->Html->link('Shop now', ['controller' => 'Products', 'action' => 'index'], ['class' => 'btn btn-lime btn-lg']) ?>
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-stats">
                <div class="stat-pill">
                    <div class="stat-num">B2C</div>
                    <div class="stat-desc">Direct eco-product shopping for conscious consumers</div>
                </div>
                <div class="stat-pill">
                    <div class="stat-num">B2B</div>
                    <div class="stat-desc">Business partnerships built on sustainable values</div>
                </div>
                <div class="stat-pill">
                    <div class="stat-desc">Web & mobile: shop anywhere, anytime</div>
                </div>
            </div>
        </div>
    </div>

    <!-- slide dots -->
    <div class="hero-dots">
        <button class="hero-dot active" data-index="0" aria-label="Slide 1"></button>
        <button class="hero-dot" data-index="1" aria-label="Slide 2"></button>
        <button class="hero-dot" data-index="2" aria-label="Slide 3"></button>
    </div>
</section>


<!-- about -->
<section class="pillars" id="about">
    <div class="section-header">
        <p class="t-label section-tag">The Platform</p>
        <h2 class="section-title t-display">
            Everything you need to <em>trade sustainably</em>
        </h2>
        <p class="section-body">
            <?= $this->ContentBlock->text('landing-page-pillar-about') ?>
        </p>
    </div>

    <div class="pillars-grid">
        <div class="pillar pillar-dark">
            <p class="t-label pillar-tag">Eco Marketplace</p>
            <p class="pillar-title">Shop with purpose</p>
            <p class="pillar-desc">Browse thousands of verified eco-friendly products; from organic food to sustainable fashion, curated so every purchase makes an impact. Full product transparency, ethical sourcing labels, and carbon-footprint scores.</p>
        </div>

        <div class="pillar pillar-forest">
            <p class="t-label pillar-tag">B2B Relationships</p>
            <p class="pillar-title">Scale your sustainable business</p>
            <p class="pillar-desc">Connect with like-minded businesses, negotiate bulk deals, and build long-term supply chain relationships grounded in shared environmental values.</p>
        </div>

        <div class="pillar pillar-light">
            <p class="t-label pillar-tag">Farmers Direct</p>
            <p class="pillar-title">Farm to marketplace</p>
            <p class="pillar-desc">Farmers list produce directly. No middlemen, fair prices, full visibility of growing practices.</p>
        </div>

        <div class="pillar pillar-lime">
            <p class="t-label pillar-tag">Impact Tracking</p>
            <p class="pillar-title">Measure your footprint</p>
            <p class="pillar-desc">Real-time sustainability scores for every purchase, seller, and supply chain node.</p>
        </div>

        <div class="pillar pillar-light">
            <p class="t-label pillar-tag">Trust & Verification</p>
            <p class="pillar-title">Certified & verified</p>
            <p class="pillar-desc">Every seller goes through our rigorous eco-certification process before joining the platform.</p>
        </div>
    </div>
</section>


<!-- marketplace -->
<section class="modes" id="marketplace" style="background-image:url('<?= $this->Url->image('marketplaceHomepage.png') ?>')">
    <div class="modes-overlay"></div>
    <div class="modes-text">
        <h2 class="modes-heading">Shop Sustainable.<br>Live Better.</h2>
        <p class="modes-sub">Discover eco-friendly products from brands that care for the planet.</p>
        <div class="modes-actions">
            <?= $this->Html->link('Shop Now', ['controller' => 'Products', 'action' => 'index'], ['class' => 'btn btn-lime btn-lg']) ?>
            <a href="#about" class="btn btn-modes-outline btn-lg">Learn More</a>
        </div>
        <div class="modes-features">
            <div class="modes-feature">
                <span class="modes-feature-icon">🌿</span>
                <span>Eco-Friendly<br>Products</span>
            </div>
            <div class="modes-feature">
                <span class="modes-feature-icon">📦</span>
                <span>Ethical<br>Sourcing</span>
            </div>
            <div class="modes-feature">
                <span class="modes-feature-icon">🌍</span>
                <span>Better for<br>Our Planet</span>
            </div>
        </div>
    </div>
</section>


<!-- discover innovators -->
<section class="innovators" id="innovators">
    <div class="innovators-bg"></div>
    <div class="innovators-inner">
        <div class="innovators-header">
            <div>
                <p class="t-label section-tag">Discover Innovators</p>
                <h2 class="section-title t-display">
                    Meet our top manufacturers<br>leading the <em>sustainability revolution</em>
                </h2>
            </div>
            <a href="<?= $this->Url->build(['controller' => 'Innovators', 'action' => 'index']) ?>" class="btn btn-lime">Browse all innovators →</a>
        </div>

        <div class="inno-grid">
            <div class="inno-card">
                <?= $this->Html->image('manufacturerLanding.png', ['alt' => 'Circular packaging', 'class' => 'inno-img']) ?>
                <span class="inno-badge">Reusables</span>
                <p class="inno-title">Circular packaging solutions</p>
                <p class="inno-desc">Manufacturers pioneering 100% compostable and reusable packaging for the food and logistics industry.</p>
            </div>

            <div class="inno-card">
                <?= $this->Html->image('farmTech.png', ['alt' => 'Regenerative farming', 'class' => 'inno-img']) ?>
                <span class="inno-badge">AgriTech</span>
                <p class="inno-title">Regenerative farming tech</p>
                <p class="inno-desc">Manufacturers bringing soil restoration, water-saving irrigation, and precision farming to smallholder farmers worldwide.</p>
            </div>

            <div class="inno-card">
                <?= $this->Html->image('cleanEnergy.png', ['alt' => 'Clean energy production', 'class' => 'inno-img']) ?>
                <span class="inno-badge">Organic</span>
                <p class="inno-title">Organically grown produce</p>
                <p class="inno-desc">Manufacturers who have committed to 100% organic goods across their production lines - verified on-chain.</p>
            </div>
        </div>
    </div>
</section>


<!-- misson/features -->
<section class="mission" id="mission">
    <div class="mission-text">
        <p class="t-label section-tag">Our Mission</p>

        <?= $this->ContentBlock->html('landing-page-mission') ?>

        <ul class="values">
            <?= $this->ContentBlock->html('landing-page-values') ?>
        </ul>
    </div>
</section>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    if (!slides.length) return;
    let current = 0;
    let timer;

    function goTo(n) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    function startTimer() { timer = setInterval(function () { goTo(current + 1); }, 5000); }
    function resetTimer()  { clearInterval(timer); startTimer(); }

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            goTo(parseInt(dot.dataset.index));
            resetTimer();
        });
    });

    startTimer();
});
</script>

<!-- login/register -->
<section class="final-cta">
    <div class="cta-blob">
        <?= $this->ContentBlock->html('landing-page-register') ?>
        <div class="cta-actions">
            <?= $this->Html->link('Sign in', ['controller' => 'Auth', 'action' => 'login'], ['class' => 'btn btn-outline btn-lg btn-signin-cta']) ?>
            <?= $this->Html->link('Create an account', ['controller' => 'Auth', 'action' => 'register'], ['class' => 'btn btn-lime btn-lg btn-create']) ?>
        </div>
    </div>
</section>


