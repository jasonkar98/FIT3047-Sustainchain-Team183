<?php
$this->assign('title', 'About SustainChain');
?>

<style>
/* ── About page ── */
.about-hero {
    background-color: var(--g0);
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    color: var(--white);
    padding: 5rem 1.5rem 4rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.about-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(5, 20, 6, 0.08);
    pointer-events: none;
}
.about-hero-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
}
.about-hero-logo img {
    width: 72px;
    height: 72px;
    object-fit: contain;
}
.about-hero-logo-name {
    font-family: "Fraunces", serif;
    font-size: 2.4rem;
    font-weight: 700;
    letter-spacing: -0.03em;
    color: var(--e0);
}
.about-hero-logo-name span {
    color: var(--e1);
}
.about-hero-tag {
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--g0);
    margin-bottom: 1.25rem;
    text-shadow: 0 1px 4px rgba(255,255,255,0.6);
}
.about-hero-title {
    font-size: clamp(2rem, 5vw, 3.4rem);
    font-weight: 700;
    font-family: "Fraunces", serif;
    line-height: 1.1;
    letter-spacing: -0.02em;
    color: #ffffff;
    margin-bottom: 1.25rem;
    text-shadow: 0 2px 6px rgba(0,0,0,0.95), 0 4px 20px rgba(0,0,0,0.85);
}
.about-hero-title em {
    font-style: italic;
    font-weight: 300;
    color: var(--e1);
    text-shadow: 0 2px 6px rgba(0,0,0,0.95);
}
.about-hero-sub {
    max-width: 640px;
    margin: 0 auto;
    font-size: 1.05rem;
    line-height: 1.7;
    color: #ffffff;
    text-shadow: 0 1px 4px rgba(0,0,0,0.95), 0 2px 14px rgba(0,0,0,0.85);
}

/* ── Mission strip ── */
.about-mission {
    background: var(--e0);
    padding: 4rem 1.5rem;
    text-align: center;
}
.about-mission-inner {
    max-width: 760px;
    margin: 0 auto;
}
.about-mission-tag {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--g3);
    margin-bottom: 1rem;
}
.about-mission h2 {
    font-family: "Fraunces", serif;
    font-size: clamp(1.7rem, 4vw, 2.6rem);
    font-weight: 700;
    line-height: 1.1;
    letter-spacing: -0.02em;
    color: var(--g0);
    margin-bottom: 1.25rem;
}
.about-mission h2 em {
    font-style: italic;
    font-weight: 300;
    color: var(--g4);
}
.about-mission p {
    font-size: 1.05rem;
    line-height: 1.75;
    color: var(--muted);
}

/* ── Endpoints / features grid ── */
.about-features {
    padding: 4.5rem 1.5rem;
    background: var(--s1);
}
.about-features-inner {
    max-width: 1100px;
    margin: 0 auto;
}
.about-section-tag {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--g3);
    margin-bottom: 0.75rem;
}
.about-features-title {
    font-family: "Fraunces", serif;
    font-size: clamp(1.7rem, 4vw, 2.4rem);
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1.1;
    color: var(--g0);
    margin-bottom: 0.75rem;
}
.about-features-title em {
    font-style: italic;
    font-weight: 300;
    color: var(--g4);
}
.about-features-sub {
    font-size: 1rem;
    color: var(--muted);
    line-height: 1.6;
    max-width: 580px;
    margin-bottom: 3rem;
}
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}
.feature-card {
    background: var(--white);
    border: 1px solid var(--g6);
    border-radius: var(--r16);
    padding: 2rem 1.75rem;
    transition: box-shadow 0.18s, transform 0.18s;
}
.feature-card:hover {
    box-shadow: 0 8px 32px rgba(10,64,12,.1);
    transform: translateY(-2px);
}
.feature-card.dark {
    background: var(--g0);
    border-color: transparent;
}
.feature-icon {
    font-size: 1.8rem;
    margin-bottom: 1rem;
}
.feature-tag {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--g3);
    margin-bottom: 0.5rem;
}
.feature-card.dark .feature-tag {
    color: var(--e1);
}
.feature-title {
    font-family: "Fraunces", serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--g0);
    margin-bottom: 0.6rem;
    line-height: 1.2;
}
.feature-card.dark .feature-title {
    color: var(--e0);
}
.feature-desc {
    font-size: 0.9rem;
    line-height: 1.65;
    color: var(--muted);
}
.feature-card.dark .feature-desc {
    color: rgba(254,250,224,.7);
}
.feature-link {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    margin-top: 1.25rem;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--g0);
    text-decoration: none;
    transition: gap 0.15s;
}
.feature-card.dark .feature-link {
    color: var(--e1);
}
.feature-link:hover {
    gap: 0.55rem;
}

/* ── Values strip ── */
.about-values {
    background: var(--g6);
    padding: 4rem 1.5rem;
}
.about-values-inner {
    max-width: 1000px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 2rem;
}
@media (max-width: 700px) {
    .about-values-inner { grid-template-columns: 1fr; }
}
.value-item {
    padding: 1.5rem 0;
}
.value-number {
    font-family: "Fraunces", serif;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--g0);
    opacity: 0.5;
    line-height: 1;
    margin-bottom: 0.5rem;
}
.value-title {
    font-weight: 700;
    font-size: 1.05rem;
    color: var(--g0);
    margin-bottom: 0.4rem;
}
.value-desc {
    font-size: 0.9rem;
    line-height: 1.6;
    color: var(--muted);
}

/* ── CTA ── */
.about-cta {
    background: rgba(99, 112, 75, 0.7);
    padding: 5rem 1.5rem;
    text-align: center;
}
.about-cta-inner {
    max-width: 640px;
    margin: 0 auto;
}
.about-cta-tag {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--e1);
    margin-bottom: 1rem;
}
.about-cta h2 {
    font-family: "Fraunces", serif;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 700;
    line-height: 1.1;
    letter-spacing: -0.02em;
    color: var(--e0);
    margin-bottom: 1rem;
}
.about-cta h2 em {
    font-style: italic;
    font-weight: 300;
    color: var(--e1);
}
.about-cta p {
    font-size: 1rem;
    line-height: 1.7;
    color: rgba(254,250,224,.7);
    margin-bottom: 2rem;
}
.about-cta-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}
</style>

<!-- Hero -->
<section class="about-hero" style="background-image: url('<?= $this->Url->image('AboutUs.png') ?>')">
    <p class="about-hero-tag">Who we are</p>
    <h1 class="about-hero-title">Commerce that's good<br>for the <em>planet</em></h1>
    <p class="about-hero-sub">
        SustainChain is a sustainable marketplace that connects buyers, sellers,
        manufacturers, and farmers in a single transparent ecosystem. Every transaction
        on SustainChain is designed to make a measurable difference.
    </p>
</section>

<!-- Mission -->
<section class="about-mission">
    <div class="about-mission-inner">
        <p class="about-mission-tag">Our Mission</p>
        <h2>Making <em>sustainability</em><br>the default, not the exception</h2>
        <p>
            SustainChain was founded on the belief that commerce can be a force for good.
            By connecting every participant in the supply chain — from the farmer growing the crop
            to the consumer at the door — we remove friction, eliminate middlemen, and make
            ethical trade accessible to everyone. We verify eco-credentials, celebrate transparency,
            and reward responsible choices.
        </p>
    </div>
</section>

<!-- Platform endpoints -->
<section class="about-features">
    <div class="about-features-inner">
        <p class="about-section-tag">What we offer</p>
        <h2 class="about-features-title">Everything you need to <em>trade sustainably</em></h2>
        <p class="about-features-sub">
            SustainChain is more than a marketplace. It's a complete ecosystem built around
            transparency, trust, and positive impact.
        </p>

        <div class="features-grid">

            <div class="feature-card dark">
                <div class="feature-icon">🛍️</div>
                <p class="feature-tag">Shop Marketplace</p>
                <p class="feature-title">Eco-friendly products, curated for impact</p>
                <p class="feature-desc">
                    Browse thousands of verified sustainable products — from organic food to
                    ethical fashion. Every listing is vetted for genuine environmental credentials.
                    No greenwashing, ever.
                </p>
                <?= $this->Html->link('Browse the marketplace →', ['prefix' => false, 'controller' => 'Products', 'action' => 'index'], ['class' => 'feature-link']) ?>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🤝</div>
                <p class="feature-tag">B2B Relationships</p>
                <p class="feature-title">Scale your sustainable business</p>
                <p class="feature-desc">
                    Connect with like-minded businesses, negotiate bulk deals, and build
                    long-term supply chain relationships grounded in shared environmental values.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🌾</div>
                <p class="feature-tag">Farmers Direct</p>
                <p class="feature-title">Farm to marketplace</p>
                <p class="feature-desc">
                    Farmers list produce directly — no middlemen, fair prices, and full visibility
                    of growing practices. Know exactly where your food comes from.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🏭</div>
                <p class="feature-tag">Manufacturers</p>
                <p class="feature-title">Circular & renewable production</p>
                <p class="feature-desc">
                    Discover innovators pioneering compostable packaging, renewable-powered
                    production, and regenerative farming technology.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <p class="feature-tag">Impact Tracking</p>
                <p class="feature-title">Measure your footprint</p>
                <p class="feature-desc">
                    Real-time sustainability scores for every purchase and seller. Track the
                    impact of your choices and share your progress with the community.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">✅</div>
                <p class="feature-tag">Trust & Verification</p>
                <p class="feature-title">Certified &amp; verified sellers</p>
                <p class="feature-desc">
                    Every seller on SustainChain goes through a rigorous eco-certification
                    process. We verify, so you don't have to second-guess.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- Core values -->
<section class="about-values">
    <div class="about-values-inner">
        <div class="value-item">
            <div class="value-number">01</div>
            <p class="value-title">Responsible consumption</p>
            <p class="value-desc">Every product on SustainChain carries genuine environmental credentials. We hold sellers to strict standards and publish all verification data openly.</p>
        </div>
        <div class="value-item">
            <div class="value-number">02</div>
            <p class="value-title">Ethical commerce</p>
            <p class="value-desc">Fair pricing for farmers, transparent margins for sellers, and confidence for buyers. We believe a fair deal for everyone is the only sustainable deal.</p>
        </div>
        <div class="value-item">
            <div class="value-number">03</div>
            <p class="value-title">Community-driven</p>
            <p class="value-desc">A living, growing community of people who believe trade and ecology can co-exist — and that every purchase is a small vote for the world we want.</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="about-cta">
    <div class="about-cta-inner">
        <p class="about-cta-tag">Get in touch</p>
        <h2>Have a question or want to <em>partner with us?</em></h2>
        <p>
            Whether you're a buyer, seller, manufacturer, or farmer, we'd love to hear from you.
            Reach out and our team will get back to you shortly.
        </p>
        <div class="about-cta-actions">
            <?= $this->Html->link('Contact Us', ['prefix' => false, 'controller' => 'Enquiries', 'action' => 'add'], ['class' => 'btn btn-lime btn-lg']) ?>
            <?= $this->Html->link('Shop the Marketplace', ['prefix' => false, 'controller' => 'Products', 'action' => 'index'], ['class' => 'btn btn-outline btn-lg']) ?>
        </div>
    </div>
</section>
