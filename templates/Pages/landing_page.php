<?php
/**
 * Landing Page
 */
use Cake\Core\Configure;

$this->assign('title', 'SustainChain');
?>

<!-- hero -->
<section class="hero">
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
                SustainChain connects buyers, sellers, manufacturers, and farmers
                in a vibrant marketplace built on transparency, sustainability, and
                ethical trade - from farm to door.
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
</section>


<!-- about -->
<section class="pillars" id="about">
    <div class="section-header">
        <p class="t-label section-tag">The Platform</p>
        <h2 class="section-title t-display">
            Everything you need to <em>trade sustainably</em>
        </h2>
        <p class="section-body">
            SustainChain is more than a marketplace. It's a complete ecosystem for responsible commerce,
            giving every participant the tools to make a measurable difference.
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


<!-- discover innovators -->
<section class="innovators" id="innovators">
    <div class="innovators-bg"></div>
    <div class="innovators-inner">
        <div class="innovators-header">
            <div>
                <p class="t-label section-tag">Discover Innovators</p>
                <h2 class="section-title t-display">
                    Meet the makers<br>changing the world
                </h2>
            </div>
            <a href="/manufacturers" class="btn btn-lime">Browse all innovators →</a>
        </div>

        <div class="inno-grid">
            <div class="inno-card">
                <?= $this->Html->image('manufacturerLanding.png', ['alt' => 'Circular packaging', 'class' => 'inno-img']) ?>
                <span class="inno-badge">Manufacturer</span>
                <p class="inno-title">Circular packaging solutions</p>
                <p class="inno-desc">Manufacturers pioneering 100% compostable and reusable packaging for the food and logistics industry.</p>
                <a href="/manufacturers" class="inno-cta">Explore category →</a>
            </div>

            <div class="inno-card">
                <?= $this->Html->image('farmTech.png', ['alt' => 'Regenerative farming', 'class' => 'inno-img']) ?>
                <span class="inno-badge">AgriTech</span>
                <p class="inno-title">Regenerative farming tech</p>
                <p class="inno-desc">Innovators bringing soil restoration, water-saving irrigation, and precision farming to smallholder farmers worldwide.</p>
                <a href="/manufacturers" class="inno-cta">Explore category →</a>
            </div>

            <div class="inno-card">
                <?= $this->Html->image('cleanEnergy.png', ['alt' => 'Clean energy production', 'class' => 'inno-img']) ?>
                <span class="inno-badge">Clean Energy</span>
                <p class="inno-title">Renewable-powered production</p>
                <p class="inno-desc">Manufacturers who have committed to 100% renewable energy across their production lines - verified on-chain.</p>
                <a href="/manufacturers" class="inno-cta">Explore category →</a>
            </div>
        </div>
    </div>
</section>


<!-- marketplace -->
<section class="modes" id="marketplace">
    <div class="modes-inner">
        <p class="t-label section-tag">Marketplace</p>
        <h2 class="section-title t-display">
            Built for every kind of<br><em>sustainable commerce</em>
        </h2>

        <div class="modes-grid">
            <div class="mode-card b2c">
                <span class="mode-chip">B2C · Consumer</span>
                <h3 class="mode-title">Shop consciously</h3>
                <p class="mode-desc">Discover and purchase eco-friendly products directly from verified sellers, farmers, and makers. Every product comes with full sustainability transparency.</p>
                <ul class="mode-list">
                    <li>Verified eco-labels on every product</li>
                    <li>Carbon footprint scores at checkout</li>
                    <li>Direct from farmers; no middlemen</li>
                    <li>Community reviews and impact stories</li>
                </ul>
            </div>

            <div class="mode-card b2b">
                <span class="mode-chip">B2B · Business</span>
                <h3 class="mode-title">Scale sustainably</h3>
                <p class="mode-desc">Build lasting business-to-business relationships with partners who share your commitment to responsible commerce and environmental stewardship.</p>
                <ul class="mode-list">
                    <li>Bulk purchasing with verified suppliers</li>
                    <li>Supply chain transparency dashboard</li>
                    <li>Connect with certified manufacturers</li>
                    <li>ESG reporting & compliance tools</li>
                </ul>
            </div>
        </div>
    </div>
</section>


<!-- misson/features -->
<section class="mission" id="mission">
    <div class="mission-text">
        <p class="t-label section-tag">Our Mission</p>
        <h2 class="section-title t-display">
            A greener future<br>starts with <em>better trade</em>
        </h2>
        <p class="section-body">
            SustainChain was founded on the belief that commerce can be a force
            for good. By connecting every participant in the supply chain - from the farmer growing the crop
            to the consumer at the door, we make sustainability the default, not the exception.
        </p>

        <ul class="values">
            <li>
                <div class="values-body">
                    <p class="values-title">Responsible consumption</p>
                    <p class="values-desc">Every product on SustainChain is vetted for genuine environmental credentials - no greenwashing.</p>
                </div>
            </li>
            <li>
                <div class="values-body">
                    <p class="values-title">Ethical commerce</p>
                    <p class="values-desc">Fair pricing for farmers, transparent margins for sellers, and trust for buyers.</p>
                </div>
            </li>
            <li>
                <div class="values-body">
                    <p class="values-title">Community-driven</p>
                    <p class="values-desc">A living, growing community of people who believe trade and ecology can co-exist.</p>
                </div>
            </li>
        </ul>
    </div>
</section>


<!-- login/register -->
<section class="final-cta">
    <div class="cta-blob">
        <p class="t-label section-tag" style="margin-bottom:.75rem">Get started today</p>
        <h2 class="section-title t-display">
            Ready to join the<br><em>sustainable commerce</em> revolution?
        </h2>
        <p class="section-body">
            Whether you're a buyer, seller, manufacturer, or farmer,
            SustainChain has a place for you. Join a growing community
            committed to a greener future.
        </p>
        <div class="cta-actions">
            <?= $this->Html->link('Sign in', ['controller' => 'Auth', 'action' => 'login'], ['class' => 'btn btn-outline btn-lg']) ?>
            <?= $this->Html->link('Create an account', ['controller' => 'Auth', 'action' => 'register'], ['class' => 'btn btn-lime btn-lg']) ?>
        </div>
    </div>
</section>


