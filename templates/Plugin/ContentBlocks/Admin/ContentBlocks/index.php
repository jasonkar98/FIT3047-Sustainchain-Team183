<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\ContentBlocks\Model\Entity\ContentBlock> $contentBlocksGrouped
 */

$this->assign('title', 'Content Blocks');

$this->Html->css('contentblocks', ['block' => true]);

$slugify = function($text) {
    return preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($text));
};

$sectionIcons = [
    'Homepage' => 'ti-home',
    'About'    => 'ti-info-circle',
    'Footer'   => 'ti-layout-bottombar',
    'Errors'   => 'ti-alert-circle',
];

?>
<div class="contentBlocks index content">

    <div class="cb-header">
        <h3><?= __('Content Blocks') ?></h3>
    </div>

    <div class="cb-nav">
        <?php foreach (array_keys($contentBlocksGrouped) as $parent): ?>
            <a class="cb-nav-pill" href="#<?= $slugify($parent) ?>">
                <?= h($parent) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php foreach ($contentBlocksGrouped as $parent => $contentBlocks): ?>

        <div class="cb-section" id="<?= $slugify($parent) ?>">

            <p class="cb-section-heading">
                <i class="ti <?= h($sectionIcons[$parent] ?? 'ti-folder') ?>" aria-hidden="true"></i>
                <?= h($parent) ?>
            </p>

            <div class="cb-list">
                <?php foreach ($contentBlocks as $contentBlock): ?>
                    <div class="cb-item">

                        <div class="cb-item-text">
                            <p class="cb-label"><?= h($contentBlock['label']) ?></p>
                            <p class="cb-desc"><?= h($contentBlock['description']) ?></p>
                            <p class="cb-slug"><?= h($contentBlock->parent) ?>/<?= h($contentBlock->slug) ?></p>
                        </div>

                        <div class="cb-actions">
                            <?php if (!empty($contentBlock->previous_value)): ?>
                                <?= $this->Form->postLink(
                                    '<i class="ti ti-history" aria-hidden="true"></i> ' . __('Restore'),
                                    ['action' => 'restore', $contentBlock->id],
                                    [
                                        'class'   => 'cb-btn cb-btn-restore',
                                        'escape'  => false,
                                        'confirm' => __("Restore previous version of \"{0}\"?\n{1}/{2}\n\nThis cannot be undone.", $contentBlock->label, $contentBlock->parent, $contentBlock->slug),
                                    ]
                                ) ?>
                            <?php endif; ?>

                            <?= $this->Html->link(
                                '<i class="ti ti-edit" aria-hidden="true"></i> ' . __('Edit'),
                                ['action' => 'edit', $contentBlock->id],
                                [
                                    'class'  => 'cb-btn',
                                    'escape' => false,
                                ]
                            ) ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

        </div>

    <?php endforeach; ?>

</div>
