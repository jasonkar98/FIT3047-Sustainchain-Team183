<?php
/**
 * @var \App\View\AppView $this
 * @var string $message
 */
use Cake\Core\Configure;
use Cake\Error\Debugger;

if (Configure::read('debug')) :
    $this->setLayout('dev_error');
    $this->assign('title', $message);
    $this->assign('templateName', 'error500.php');
    $this->start('file');
?>
<?php if ($error instanceof Error) : ?>
    <?php $file = $error->getFile() ?>
    <?php $line = $error->getLine() ?>
    <strong>Error in: </strong>
    <?= $this->Html->link(sprintf('%s, line %s', Debugger::trimPath($file), $line), Debugger::editorUrl($file, $line)); ?>
<?php endif; ?>
<?php
    echo $this->element('auto_table_warning');
    $this->end();
    return;
endif;

$this->assign('title', '500 — Server Error');
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
    background: rgba(255, 200, 100, 0.12);
    border: 1px solid rgba(255, 200, 100, 0.3);
    color: #a05c00;
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
            <span style="width:6px;height:6px;border-radius:50%;background:#a05c00;display:inline-block;"></span>
            Server error
        </div>

        <div class="error-code">5<em>0</em>0</div>

        <h1 class="error-title">Oops! Something went wrong.</h1>

        <p class="error-message">
            Our server ran into an unexpected problem.<br>
            This has been logged and we'll look into it, please try again shortly.
        </p>

        <div class="error-actions">
            <?= $this->Html->link(
                'Return to Home',
                ['controller' => 'Pages', 'action' => 'landingPage', 'prefix' => false],
                ['class' => 'btn btn-lime']
            ) ?>
        </div>

        <div class="error-divider"></div>

        <div class="error-links">
            <?= $this->Html->link('Contact Us', ['controller' => 'Enquiries', 'action' => 'add', 'prefix' => false], ['class' => 'error-link']) ?>

    </div>
</div>