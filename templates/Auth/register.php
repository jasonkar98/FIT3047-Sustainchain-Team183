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
    vertical-align: top;
}


.role-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 0.75rem 1.5rem;
    background: #cdc699;
    color: var(--g0);
    border: none;
    border-radius: var(--r16);
    font-family: inherit;
    font-weight: 700;
    font-size: 0.9rem;
    letter-spacing: -0.01em;
    cursor: pointer;
    margin-top: 0.5rem;
    box-shadow: 0 4px 16px rgba(200, 232, 64, 0.25);
    transition:
        transform 0.18s var(--ease-spring),
        background 0.18s,
        box-shadow 0.18s;
}

.role-btn:hover {
    background: var(--e0);
    box-shadow: 0 8px 24px rgba(200, 232, 64, 0.35);
    transform: translateY(-2px);
}

.role-btn:focus {
    background: #72894a;
    box-shadow: 0 8px 24px rgba(200, 232, 64, 0.35);
    transform: translateY(-2px);
}

.tooltip-container {
    position: relative;
    display: inline-block;
}

.tooltip-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 3.5rem;
    width: 3.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--g5);
    color: var(--g0);
    border: none;
    border-radius: 50%;
    text-transform: none;
    font-family: 'Times New Roman', Times, serif;
    font-weight: 700;
    font-size: 0.9rem;
    letter-spacing: -0.01em;
    cursor: pointer;
    margin-top: 0.5rem;
    box-shadow: 0 4px 16px rgba(200, 232, 64, 0.25);
    transition:
        transform 0.18s var(--ease-spring),
        background 0.18s,
        box-shadow 0.18s;
}

.tooltip-btn:hover {
    background: var(--e0);
    box-shadow: 0 8px 24px rgba(200, 232, 64, 0.35);
    transform: translateY(-2px);
}

.overlay-box {
    visibility: hidden;
    position: absolute;
    z-index: 1;
    font-family: inherit;
    bottom: 105%;
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    background-color: var(--g0);
    color: var(--g6:);
    padding: 10px;
    border-radius: 5px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip-container:hover .overlay-box {
    visibility: visible;
    opacity: 1;
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

            <?= $this->Form->control('role', ['type' => 'select',
                'options' => $selectRolesOptions,
                'id' => 'hidden-select',
                'style' => 'display:none',
                'required' => true,
                'label' => false,
                'empty' => false]) ?>

            <table>
                <tr>
                    <td>
                        <div class="role-button-container">
                        <button type="button" id="role-btn" class="role-btn" value='farmer' title="<?= h($selectRolesData['farmer']['desc']) ?>"><?= h($selectRolesData['farmer']['title']) ?></button>
                        </div>
                    </td>
                    <td style="width:3.5rem;">
                        <div class="tooltip-container">
                            <button type="button" class="tooltip-btn">i</button>
                            <div class="overlay-box">
                                <?= h($selectRolesData['farmer']['desc']) ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="role-button-container">
                        <button type="button" id="role-btn" class="role-btn" value='manufacturer' title="<?= h($selectRolesData['manufacturer']['desc']) ?>"><?= h($selectRolesData['manufacturer']['title']) ?></button>
                        </div>
                    </td>
                    <td style="width:3.5rem;">
                        <div class="tooltip-container">
                            <button type="button" class="tooltip-btn">i</button>
                            <div class="overlay-box">
                                <?= h($selectRolesData['manufacturer']['desc']) ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="role-button-container">
                        <button type="button" id="role-btn" class="role-btn" value='seller' title="<?= h($selectRolesData['seller']['desc']) ?>"><?= h($selectRolesData['seller']['title']) ?></button>
                        </div>
                    </td>
                    <td style="width:3.5rem;">
                        <div class="tooltip-container">
                            <button type="button" class="tooltip-btn">i</button>
                            <div class="overlay-box">
                                <?= h($selectRolesData['seller']['desc']) ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="role-button-container">
                        <button type="button" id="role-btn" class="role-btn" value='buyer' title="<?= h($selectRolesData['buyer']['desc']) ?>"><?= h($selectRolesData['buyer']['title']) ?></button>
                        </div>
                    </td>
                    <td style="width:3.5rem;">
                        <div class="tooltip-container">
                            <button type="button" class="tooltip-btn">i</button>
                            <div class="overlay-box">
                                <?= h($selectRolesData['buyer']['desc']) ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <?= $this->Form->hidden('role_selected', ['value' => 1]); ?>
            <?= $this->Form->button('Select Role', ['id' => 'select-role-btn','class' => 'button auth-primary-btn', 'disabled' => 'true']) ?>
            
            <div class="auth-links">
                <?= $this->Html->link('Login', ['controller' => 'Auth', 'action' => 'login']) ?>
                <?= $this->Html->link('Back', ['controller' => 'Pages', 'action' => 'landingPage']) ?>
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

            <?php if (h($this->request->getData('role')) == 'manufacturer'): ?>
                <?= $this->Form->control('description', ['label' => 'Business Description', 'placeholder' => 'Describe your business...', 'type' => 'textarea']); ?>
            <?php endif; ?>

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
        const roleBtns = document.querySelectorAll('#role-btn');
        const selectRoleBtn = document.getElementById('select-role-btn');
        
        let currentIndex = 0;
        const totalCards = 4;

        roleBtns.forEach(roleBtn => {
            roleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                let index = roleBtn.value;
                selectBox.value = index
                selectBox.dispatchEvent(new Event('change', { bubbles: true }));
                selectRoleBtn.disabled = false;
            });
        });

    });
</script>
