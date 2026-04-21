<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Enquiry $enquiry
 */
?>

<style>
.enq-wrap { max-width: 760px; margin: 2rem auto; font-family: var(--font-sans, sans-serif); }
.enq-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }

/* Header */
.enq-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
.enq-header-left { display: flex; align-items: center; gap: 12px; }
.enq-icon-wrap { width: 36px; height: 36px; border-radius: 8px; background: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.enq-title { font-size: 17px; font-weight: 600; margin: 0; color: #0f172a; }
.enq-subtitle { font-size: 13px; color: #94a3b8; margin: 2px 0 0; }
.enq-badges { display: flex; gap: 6px; align-items: center; flex-shrink: 0; }
.enq-badge { font-size: 12px; font-weight: 500; padding: 3px 10px; border-radius: 20px; }
.enq-badge.new { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
.enq-badge.sent { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.enq-badge.unsent { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }

/* Fields grid */
.enq-fields { padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; border-bottom: 1px solid #e2e8f0; }
.enq-field-full { grid-column: 1 / -1; }
.enq-field-label { font-size: 13px; color: #64748b; margin: 0 0 6px; display: flex; align-items: center; gap: 6px; }
.enq-field-label svg { opacity: 0.5; }
.enq-field-value { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #0f172a; min-height: 40px; box-sizing: border-box; }
.enq-field-value.message { min-height: 100px; line-height: 1.6; }

/* Footer actions */
.enq-footer { padding: 1.25rem 1.5rem; display: flex; gap: 10px; }
.enq-btn { flex: 1; padding: 10px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; box-sizing: border-box; }
.enq-btn.reply { background: #2563eb; color: #fff; }
.enq-btn.reply:hover { background: #1d4ed8; }
.enq-btn.edit { background: #16a34a; color: #fff; }
.enq-btn.edit:hover { background: #15803d; }
.enq-btn.delete { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }
.enq-btn.delete:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

/* Nav */
.enq-nav { display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; }
.enq-nav-btn { display: flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; font-size: 14px; color: #334155; text-decoration: none; }
.enq-nav-btn:hover { background: #f8fafc; }
.enq-nav-center { font-size: 13px; color: #94a3b8; }
</style>

<div class="enq-wrap">
    <div class="enq-card">

        <div class="enq-header">
            <div class="enq-header-left">
                <div class="enq-icon-wrap">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                        <path d="M2 4h16v12a1 1 0 01-1 1H3a1 1 0 01-1-1V4z" stroke="#2563eb" stroke-width="1.5"/>
                        <path d="M2 4l8 7 8-7" stroke="#2563eb" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <p class="enq-title"><?= __('Enquiry #{0}', $this->Number->format($enquiry->id)) ?></p>
                    <p class="enq-subtitle"><?= h($enquiry->date) ?></p>
                </div>
            </div>
            <div class="enq-badges">
                <span class="enq-badge new"><?= __('New') ?></span>
                <span class="enq-badge <?= $enquiry->email_sent ? 'sent' : 'unsent' ?>">
                    <?= $enquiry->email_sent ? __('Email Sent') : __('Email Not Sent') ?>
                </span>
            </div>
        </div>

        <div class="enq-fields">
            <div>
                <p class="enq-field-label">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M2 14c0-3.314 2.686-5 6-5s6 1.686 6 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <?= __('Full Name') ?>
                </p>
                <div class="enq-field-value"><?= h($enquiry->full_name) ?></div>
            </div>
            <div>
                <p class="enq-field-label">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><rect x="1" y="3" width="14" height="10" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M1 5l7 5 7-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <?= __('Email Address') ?>
                </p>
                <div class="enq-field-value"><?= h($enquiry->email) ?></div>
            </div>
            <div class="enq-field-full">
                <p class="enq-field-label">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M2 3h12M2 6h8M2 9h10M2 12h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <?= __('Subject') ?>
                </p>
                <div class="enq-field-value"><?= h($enquiry->subject) ?></div>
            </div>
            <div class="enq-field-full">
                <p class="enq-field-label">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M2 2h12v9H9l-3 3v-3H2V2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                    <?= __('Message') ?>
                </p>
                <div class="enq-field-value message"><?= h($enquiry->body) ?></div>
            </div>
        </div>

        <div class="enq-footer">
            <?= $this->Html->link(__('Reply'), ['action' => 'edit', $enquiry->id], ['class' => 'enq-btn reply']) ?>
            <?= $this->Html->link(__('Mark as Resolved'), ['action' => 'index'], ['class' => 'enq-btn edit']) ?>
            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $enquiry->id], ['confirm' => __('Are you sure you want to delete enquiry #{0}?', $enquiry->id), 'class' => 'enq-btn delete']) ?>
        </div>

    </div>

    <div class="enq-nav">
    <?php if ($prev): ?>
        <?= $this->Html->link('← ' . __('Previous'), ['action' => 'view', $prev->id], ['class' => 'enq-nav-btn', 'escape' => false]) ?>
    <?php else: ?>
        <span class="enq-nav-btn" style="opacity: 0.4; cursor: default;">← <?= __('Previous') ?></span>
    <?php endif; ?>

    <span class="enq-nav-center"><?= __('Enquiry #{0}', $this->Number->format($enquiry->id)) ?></span>

    <?php if ($next): ?>
        <?= $this->Html->link(__('Next') . ' →', ['action' => 'view', $next->id], ['class' => 'enq-nav-btn', 'escape' => false]) ?>
    <?php else: ?>
        <span class="enq-nav-btn" style="opacity: 0.4; cursor: default;"><?= __('Next') ?> →</span>
    <?php endif; ?>
</div>
</div>