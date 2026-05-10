<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$this->layout = 'login';
$this->assign('title', 'Register new user');

$selectRolesData = [
    'farmer' => [
        'title' => 'Farmer',
        'image' => 'farmer.jpg',
        'desc' => 'People that grow their crops and raise their farmland life. They obtain raw products from their organic sources.'
    ],
    'manufacturer' => [
        'title' => 'Manufacturer',
        'image' => 'manufacturer.jpg',
        'desc' => 'Businesses that run factories to produce packaged products with raw materials provided by Farmers'
    ],
    'seller' => [
        'title' => 'Seller',
        'image' => 'seller.jpg',
        'desc' => 'A mix of Businesses that purchase products as stock from Manufacturers to sell to customers, and Sole Traders that sell their products to customers'
    ],
    'buyer' => [
        'title' => 'Buyer',
        'image' => 'buyer.jpg',
        'desc' => 'People that purchase products from Sellers.'
    ]
];

$selectRolesOptions = array_combine(
    array_keys($selectRolesData), 
    array_column($selectRolesData, 'title')
);

?>

<style>
    /* ── Image side ── */
.product-view-img-wrap {
    position: relative;
    background: var(--s1);
    min-height: 300px;
    min-width: 400px;
    max-height: 300px;
    max-width: 400px;
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

tr, td {
    text-align: center;
    vertical-align: middle;
}

</style>

<div class="auth-page register">
    <section class="auth-hero">
        <div class="hero-eyebrow">
            <span class="eyebrow-dot"></span>
            Join the supply chain
        </div>
        <h1>Sustain<em>Chain</em></h1>
        <p class="auth-subtitle">Create your account and join a more natural way to collaborate.</p>
        <div class="auth-steps">
            <div class="auth-step">
                <div class="step-num">1</div>
                <div><div class="step-title">Create your profile</div><div class="step-desc">Set up your name, email and credentials</div></div>
            </div>
            <div class="auth-step">
                <div class="step-num">2</div>
                <div><div class="step-title">Connect your supply chain</div><div class="step-desc">Buy, sell, and collaborate with eco-friendly suppliers</div></div>
            </div>
            <div class="auth-step">
                <div class="step-num">3</div>
                <div><div class="step-title">Grow responsibly</div><div class="step-desc">Make an impact with your sustainable choices</div></div>
            </div>
        </div>
    </section>

    <?php if ($step == 1): ?>

        <section class="auth-card">
            <p class="card-eyebrow">Get started</p>
            <h2>Create account</h2>
            <p class="auth-card-subtitle">Set up your profile to get started.</p>

            <?= $this->Flash->render() ?>
            <?= $this->Form->create($user, ['class' => 'auth-form']) ?>

            <div class="carousel-selector-wrapper text-center">

                <table>
                    <tr>

                        <td>
                            <button type="button" id="prev-btn" class="button auth-primary-btn">←</button>
                        </td>

                        <td>
                            <div id="carousel-container">
                                <?php $i = 0; foreach ($selectRolesData as $id => $info): ?>
                                    <div class="selection-card" 
                                        data-id="<?= $id ?>" 
                                        data-title="<?= h($info['title']) ?>"
                                        style="<?= $i === 0 ? '' : 'display: none;' ?>">
                                        <h2><?= h($info['title']) ?></h2>
                                        <br>
                                        <div class="card">
                                            <div class="product-view-img-wrap">
                                                <?= $this->Html->image('user_roles/' . $info['image'], [
                                                    'class' => 'product-view-img']) ?>
                                            </div>
                                            <br>
                                            <p><?= h($info['desc']) ?></p>
                                            <?= $this->Form->button('Select Role', ['class' => 'button auth-primary-btn']) ?>
                                        </div>
                                    </div>
                                <?php $i++; endforeach; ?>
                            </div>
                        </td>

                        <td>
                            <button type="button" id="next-btn" class="button auth-primary-btn">→</button>
                        </td>
                    </tr>

                </table>

                <?= $this->Form->control('role', ['type' => 'select',
                'options' => $selectRolesOptions,
                'id' => 'hidden-select',
                'style' => 'display:none',
                'label' => false,
                'empty' => false]) ?>

                <?= $this->Form->hidden('role_selected', ['value' => 1]); ?>

                <div class="auth-links">
                    <?= $this->Html->link('Login', ['controller' => 'Auth', 'action' => 'login']) ?>
                    <?= $this->Html->link('Back', ['controller' => 'Pages', 'action' => 'landingPage']) ?>
                </div>
            </div>
        </section>

    <?php else: ?>

        <section class="auth-card">
            <p class="card-eyebrow">Get started</p>
            <h2>Create account</h2>
            <p class="auth-card-subtitle">Set up your profile to get started.</p>

            <?= $this->Flash->render() ?>
            <?= $this->Form->create($user, ['class' => 'auth-form']) ?>

            <?= $this->Form->control('email', ['placeholder' => 'name@company.com']); ?>

            <div class="auth-grid">
                <?= $this->Form->control('first_name', ['label' => 'First name', 'placeholder' => 'Ava', 'pattern' => '[a-zA-Z ]+']); ?>
                <?= $this->Form->control('last_name', ['label' => 'Last name', 'placeholder' => 'Patel', 'pattern' => '[a-zA-Z ]+']); ?>
            </div>

            <?= $this->Form->control('role', ['type' => 'hidden', 'required' => true, 'default' => $role_selected]) ?>

            <div class="auth-grid">
                <?php
                echo $this->Form->control('password', [
                    'value' => '', // Ensure password is not sent back to the client
                    'placeholder' => 'Create password',
                ]);
                echo $this->Form->control('password_confirm', [
                    'type' => 'password',
                    'value' => '', // Ensure password is not sent back to the client
                    'label' => 'Retype Password',
                    'placeholder' => 'Repeat password',
                ]);
                ?>
            </div>
            <ul class="auth-card-subtitle">
                Your password must contain at least:
                <li>8 characters</li>
                <li>One uppercase letter</li>
                <li>One number</li>
                <li>One special character</li>
            </ul>

            <!-- <?= $this->Form->control('avatar', ['type' => 'file', 'label' => 'Profile photo (optional)']); ?> -->

            <?= $this->Form->hidden('submit_details', ['value' => 1]); ?>
            <?= $this->Form->button('Register', ['class' => 'button auth-primary-btn']) ?>
            <?= $this->Form->end() ?>

            <div class="auth-links">
                <?= $this->Html->link('Login', ['controller' => 'Auth', 'action' => 'login']) ?>
                <?= $this->Html->link('Back', ['controller' => 'Pages', 'action' => 'landingPage']) ?>
            </div>
        </section>

    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const cards = document.querySelectorAll('.selection-card');
        const selectBox = document.getElementById('hidden-select');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        
        let currentIndex = 0;
        const totalCards = 4;

        function updateDisplay(newIndex) {

            cards[currentIndex].style.display = 'none';

            currentIndex = newIndex;

            cards.forEach(c => c.style.display = 'none');
            const activeCard = cards[currentIndex];
            cards[currentIndex].style.display = 'block';

            const activeId = cards[currentIndex].getAttribute('data-id');
            selectBox.value = activeId;
            selectBox.dispatchEvent(new Event('change', { bubbles: true }));

        }

        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            let index = (currentIndex + 1) % totalCards;
            updateDisplay(index);
        });

        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            let index = (currentIndex - 1 + totalCards) % totalCards;
            updateDisplay(index);
        });

        if(totalCards > 0) {
            selectBox.value = cards[0].getAttribute('data-id');
        }
    });
</script>
