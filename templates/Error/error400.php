<?php
/**
 * @var \App\View\AppView $this
 * @var string $message
 * @var string $url
 */
use Cake\Core\Configure;

if (Configure::read('debug')) :
    $this->setLayout('dev_error');
    $this->assign('title', $message);
    $this->assign('templateName', 'error400.php');
    $this->start('file');
    echo $this->element('auto_table_warning');
    $this->end();
    return;
endif;

$this->assign('title', '404 - Page Not Found');
?>

<style>
.error-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    background: var(--s0);
}

.error-card {
    background: var(--white);
    border: 1px solid var(--s2);
    border-radius: var(--r24);
    padding: 4rem 3.5rem;
    max-width: 560px;
    width: 100%;
    text-align: center;
    box-shadow: 0 8px 40px rgba(13,31,20,.07);
    animation: reveal .5s ease both;
}

@keyframes reveal {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

.error-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(200,232,64,.12);
    border: 1px solid rgba(200,232,64,.22);
    color: var(--g2);
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 0.3rem 1rem;
    border-radius: var(--r999);
    margin-bottom: 1.75rem;
}

.error-code {
    font-family: 'Fraunces', serif;
    font-size: clamp(5rem, 12vw, 9rem);
    font-weight: 700;
    line-height: 1;
    letter-spacing: -0.04em;
    color: var(--g0);
    margin-bottom: 0.5rem;
}

.error-code em {
    font-style: italic;
    font-weight: 300;
    color: var(--e1);
}

.error-title {
    font-family: 'Fraunces', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--g0);
    margin-bottom: 0.75rem;
    letter-spacing: -0.02em;
}

.error-message {
    font-size: 0.9rem;
    color: var(--muted);
    line-height: 1.7;
    margin-bottom: 2.5rem;
}

.error-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: wrap;
}

.error-divider {
    height: 1px;
    background: var(--s2);
    margin: 2rem 0;
}

.error-links {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

.error-link {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--g3);
    text-decoration: none;
    transition: color 0.15s;
}
.error-link:hover {
    color: var(--g1);
    text-decoration: underline;
}
</style>

<div class="error-page">
    <div class="error-card">

        <div class="error-eyebrow">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--g3);display:inline-block;"></span>
            Page not found
        </div>

        <div class="error-code">4<em>0</em>4</div>

        <h1 class="error-title">We lost that page</h1>

        <p class="error-message">
            The page you're looking for doesn't exist or may have been moved.<br>
            Let's get you back on track.
        </p>

        <div class="error-actions">
            <?= $this->Html->link(
                'Return to Shopping',
                ['controller' => 'Products', 'action' => 'index', 'prefix' => false],
                ['class' => 'btn btn-lime']
            ) ?>
        </div>

        <div class="error-divider"></div>

        <div class="error-links">
            <?= $this->Html->link('Contact Us', ['controller' => 'Enquiries', 'action' => 'add', 'prefix' => false], ['class' => 'error-link']) ?>
        </div>

    </div>
</div>