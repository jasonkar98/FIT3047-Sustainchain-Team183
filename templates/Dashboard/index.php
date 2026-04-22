<?php
/** @var \App\View\AppView $this */

$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('dash', ['block' => true]);

$identity = $this->request->getAttribute('identity');
$this->assign('title', 'Dashboard');

$first_name = $identity ? h($identity->first_name) : 'there';
$avatar_initial = $identity ? strtoupper(substr(h($identity->first_name), 0, 1)) : '?';
?>

<style>
    .dash-page {
        font-family: 'DM Sans', sans-serif;
        color: var(--color-text-primary, #1a1a1a);
        max-width: 960px;
        padding: 2rem 0 3rem;
    }

    /* Empty state */
    .favourites-empty {
        text-align: center;
        padding: 2.5rem;
        background: #ffffff;
        border-radius: 12px;
        color: #888;
        font-size: 14px;
        margin-bottom: 2.5rem;
    }
    .favourites-empty p {
        margin-bottom: 1.5rem;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    .section-head h2 {
        font-family: "DM Serif Display", serif;
        font-size: 1.5rem;
        font-weight: 400;
        margin: 0;
    }

    /* Enquiries */
    .enquiries-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .enquiry-item {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e0e0e0;
    }
    .enquiry-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    .enquiry-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: var(--color-text-primary, #1a1a1a);
    }
    .enquiry-date {
        font-size: 0.9rem;
        color: #666;
    }
    .enquiry-body {
        color: #555;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    .enquiry-status .status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .status.sent {
        background: #d4edda;
        color: #155724;
    }
    .status.pending {
        background: #fff3cd;
        color: #856404;
    }

    /* Orders */
    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .order-item {
        background: var(--white);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e0e0e0;
    }
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    .order-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: var(--color-text-primary, #1a1a1a);
    }
    .order-date {
        font-size: 0.9rem;
        color: #666;
    }
    .order-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .order-amount {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--g2);
    }
    .order-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: capitalize;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    .status-paid {
        background: #d1ecf1;
        color: #0c5460;
    }
    .status-shipped {
        background: #d4edda;
        color: #155724;
    }
    .status-delivered {
        background: #d4edda;
        color: #155724;
    }
    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    /* Slider */
    .saved-listings-wrapper {
        position: relative;
        /* leave room for the arrows that hang off the edges */
        padding: 0 24px;
    }

    .slider-viewport {
        overflow: hidden; /* clips cards beyond 3 */
        width: 100%;
    }

    .slider-track {
        display: grid !important;
        /* 3 equal columns — each card is exactly 1/3 of the viewport */
        grid-template-columns: repeat(auto-fill, calc(33.333% - 1rem)) !important;
        grid-auto-columns: calc(33.333% - 1rem) !important;
        grid-auto-flow: column !important;
        gap: 1.5rem !important;
        /* scroll happens on the track itself */
        overflow-x: auto !important;
        scroll-snap-type: x mandatory !important;
        scrollbar-width: none !important;
        padding-bottom: 0.5rem !important;
        /* make sure all cards stay in one row */
        grid-template-rows: 1fr !important;
    }

    .slider-track::-webkit-scrollbar {
        display: none;
    }

    /* Snap each card into place */
    .slider-track > * {
        scroll-snap-align: start;
        min-width: 0;
    }

    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: var(--g2);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: background 0.15s, transform 0.15s;
    }
    .slider-arrow:hover {
        background: var(--g1);
        transform: translateY(-50%) scale(1.05);
    }
    .slider-arrow.prev { left: -20px; }
    .slider-arrow.next { right: -20px; }
</style>

<div class="marketplace-header">
    <div class="marketplace-header-inner">
        <span class="t-label section-tag">Dashboard</span>
        <h1 class="marketplace-title t-display">
            Welcome back, <em><?= $first_name ?></em>
        </h1>
        <p class="marketplace-subtitle">
            Everything you need to manage your sustainable shopping in one place.
        </p>
    </div>
</div>

<div class="dash-page">

    <!-- Saved Listings -->
    <div class="saved-listings-wrapper">
        <button class="slider-arrow prev" aria-label="Previous">❮</button>
        <button class="slider-arrow next" aria-label="Next">❯</button>

        <div class="section-head">
            <h2>Saved Listings</h2>
        </div>

        <?php if (empty($favourites)): ?>
            <div class="favourites-empty">
                <p><?= __('No saved products yet.') ?></p>
            </div>
        <?php else: ?>
            <div class="slider-viewport">
                <div class="products-grid slider-track">
                    <?php foreach ($favourites as $favourite): ?>
                        <?php if (!empty($favourite->product)): ?>
                            <?= $this->element('product_card', [
                                'product' => $favourite->product,
                                'showSaveButton' => true,
                                'isSaved' => true,
                            ]) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- My Orders -->
    <div>
        <div class="section-head">
            <h2>My Orders</h2>
        </div>

        <?php if (empty($orders)): ?>
        <div class="favourites-empty">
            <p><?= __('You have not placed any orders.') ?></p>
            <?= $this->Html->link('Browse Products', ['controller' => 'Products', 'action' => 'index'], ['class' => 'btn btn-lime']) ?>
        </div>
        <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
            <div class="order-item">
                <div class="order-header">
                    <h3>Order #<?= h($order->order_number) ?></h3>
                    <span class="order-date"><?= $order->created->i18nFormat('dd MMM YYYY') ?></span>
                </div>
                <div class="order-details">
                    <span class="order-amount">$<?= h($order->total_amount) ?></span>
                    <span class="order-status status-<?= h($order->status) ?>"><?= h(ucfirst($order->status)) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- My Listings -->
    <div>
        <div class="section-head">
            <h2>My Listings</h2>
            <?= $this->Html->link('All Listings →', ['controller' => 'Products', 'action' => 'my_listings'], ['class' => 'btn btn-lime']) ?>
        </div>

        <?php if (empty($listings)): ?>
        <div class="favourites-empty">
            <p><?= __('You have not created any listings.') ?></p>
        </div>
        <?php else: ?>
            <div class="slider-viewport">
                <div class="products-grid slider-track">
                    <?php foreach ($listings as $listing): ?>
                        <?php if (!empty($listing)): ?>
                            <?= $this->element('product_card', [
                                'product' => $listing,
                                'showSaveButton' => false,
                                'isSaved' => false,
                            ]) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

        <!-- My Inquiries -->
    <div>
        <div class="section-head">
            <h2>My Inquiries</h2>
            <?= $this->Html->link('+ New Inquiry', ['controller' => 'Enquiries', 'action' => 'add'], ['class' => 'btn btn-lime']) ?>
        </div>

        <?php if (empty($enquiries)): ?>
        <div class="favourites-empty">
            <p><?= __('You have not submitted any inquiries.') ?></p>
        </div>
        <?php else: ?>
        <div class="enquiries-list">
            <?php foreach ($enquiries as $enquiry): ?>
            <div class="enquiry-item">
                <div class="enquiry-header">
                    <h3><?= $this->Html->link(h($enquiry->subject), ['controller' => 'Enquiries', 'action' => 'view', $enquiry->id]) ?></h3>
                    <span class="enquiry-date"><?= $enquiry->date->i18nFormat('dd MMM YYYY') ?></span>
                </div>
                <p class="enquiry-body"><?= h(substr($enquiry->body, 0, 200)) ?><?php if (strlen($enquiry->body) > 200): ?>...<?php endif; ?></p>
                <div class="enquiry-status">
                    <?php if ($enquiry->email_sent): ?>
                        <span class="status sent">Response Sent</span>
                    <?php endif; ?>
                    <?php if ($enquiry->is_resolved): ?>
                            <span class="status sent">Resolved</span>
                    <?php else: ?>
                        <span class="status pending">Pending Response</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php $this->Html->scriptStart(['block' => true]); ?>
document.addEventListener('DOMContentLoaded', () => {

    // Save button toggle
    document.querySelectorAll('.product-save-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.productId;
            let url = btn.dataset.saveUrl || '';

            if (url.includes(':id')) {
                url = url.replace(':id', productId);
            }
            if (!url) {
                const appBase = window.location.pathname.split('/').filter(Boolean).slice(0, 1).join('/');
                url = `${window.location.origin}${appBase ? '/' + appBase : ''}/products/toggle-save/${productId}`;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': <?= json_encode($this->request->getAttribute('csrfToken')) ?>,
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    btn.classList.toggle('is-saved');
                    btn.setAttribute('aria-pressed', btn.classList.contains('is-saved'));

                    // If unsaving a product in Saved Listings, remove it from the section
                    const productCard = btn.closest('.product-card');
                    const savedListingsWrapper = document.querySelector('.saved-listings-wrapper');
                    
                    if (productCard && savedListingsWrapper && savedListingsWrapper.contains(productCard)) {
                        // Product is in Saved Listings and was just unsaved
                        if (!btn.classList.contains('is-saved')) {
                            // Remove the product card with animation
                            productCard.style.opacity = '0';
                            productCard.style.transition = 'opacity 0.3s ease';
                            setTimeout(() => {
                                productCard.remove();
                                
                                // Update stats count
                                const statNum = document.querySelector('[data-stat="saved-products"]');
                                if (statNum) {
                                    const currentCount = parseInt(statNum.textContent) || 0;
                                    statNum.textContent = Math.max(0, currentCount - 1);
                                }

                                // Check if no products remain
                                const sliderTrack = document.querySelector('.slider-track');
                                if (sliderTrack && sliderTrack.children.length === 0) {
                                    // Show empty state
                                    const emptyState = document.createElement('div');
                                    emptyState.className = 'favourites-empty';
                                    emptyState.innerHTML = '<p><?= __("No saved products yet.") ?></p>';
                                    
                                    const sliderViewport = document.querySelector('.slider-viewport');
                                    if (sliderViewport) {
                                        sliderViewport.replaceWith(emptyState);
                                    }
                                }
                            }, 300);
                        }
                    }
                }
            });
        });
    });

    // Slider — scroll by the width of one card + gap
    const track = document.querySelector('.slider-track');
    const nextBtn = document.querySelector('.slider-arrow.next');
    const prevBtn = document.querySelector('.slider-arrow.prev');

    if (track && nextBtn && prevBtn) {
        const getScrollAmount = () => {
            const card = track.firstElementChild;
            if (!card) return 320;
            return card.offsetWidth + 24; // card width + gap
        };

        nextBtn.addEventListener('click', () => track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' }));
        prevBtn.addEventListener('click', () => track.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' }));
    }

});
<?php $this->Html->scriptEnd(); ?>