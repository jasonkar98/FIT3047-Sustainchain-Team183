<?php
/**
 * @var \App\View\AppView $this
 * @var \ContentBlocks\Model\Entity\ContentBlock $contentBlock
 */

$this->assign('title', 'Edit Content Block - Content Blocks');

$this->Html->script('ContentBlocks.ckeditor/ckeditor', ['block' => true]);
$this->Html->css('marketplace', ['block' => true]);
$this->Html->css('contentblocks', ['block' => true]);
?>

<style>
    .ck-editor__editable_inline {
        min-height: 25rem;
    }
</style>

<div class="contentBlocks edit content">

    <div class="marketplace-header">
        <div class="marketplace-header-inner">
            <span class="t-label section-tag">
                <?= h($contentBlock->parent) ?> / <?= h($contentBlock->slug) ?>
            </span>
            <h1 class="marketplace-title"><?= h($contentBlock->label) ?></h1>
            <?php if ($contentBlock->description): ?>
                <p class="marketplace-subtitle"><?= h($contentBlock->description) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="cb-edit-body">

        <div class="cb-edit-card">

            <?php if (!empty($contentBlock->previous_value)): ?>
                <div class="cb-prev-notice">
                    <i class="ti ti-history" aria-hidden="true"></i>
                    A previous version is available —
                    <?= $this->Form->postLink(
                        __('restore it'),
                        ['action' => 'restore', $contentBlock->id],
                        [
                            'class'   => 'cb-prev-link',
                            'escape'  => false,
                            'confirm' => __("Restore previous version of \"{0}\"?\n{1}/{2}\n\nThis cannot be undone.", $contentBlock->label, $contentBlock->parent, $contentBlock->slug),
                        ]
                    ) ?>
                    instead.
                </div>
            <?php endif; ?>

            <?= $this->Form->create($contentBlock, ['type' => 'file']) ?>

            <div class="cb-field">
                <?php if ($contentBlock->type === 'text'): ?>

                    <label class="cb-field-label">Value</label>
                    <?= $this->Form->control('value', [
                        'type'  => 'text',
                        'value' => html_entity_decode($contentBlock->value),
                        'label' => false,
                        'class' => 'cb-input',
                    ]) ?>

                <?php elseif ($contentBlock->type === 'html'): ?>

                    <label class="cb-field-label">Content</label>
                    <?= $this->Form->control('value', [
                        'type'  => 'textarea',
                        'label' => false,
                        'id'    => 'content-value-input',
                        'class' => 'cb-textarea',
                    ]) ?>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            CKSource.Editor.create(
                                document.getElementById('content-value-input'),
                                {
                                    toolbar: [
                                        "heading", "|",
                                        "bold", "italic", "underline", "|",
                                        "bulletedList", "numberedList", "|",
                                        "alignment", "blockQuote", "|",
                                        "indent", "outdent", "|",
                                        "link", "|",
                                        "insertTable", "imageInsert", "mediaEmbed", "horizontalLine", "|",
                                        "removeFormat", "|",
                                        "sourceEditing", "|",
                                        "undo", "redo",
                                    ],
                                    simpleUpload: {
                                        uploadUrl: <?= json_encode($this->Url->build(['action' => 'upload'])) ?>,
                                        headers: {
                                            'X-CSRF-TOKEN': <?= json_encode($this->request->getAttribute('csrfToken')) ?>,
                                        }
                                    }
                                }
                            ).then(editor => {
                                console.log(Array.from(editor.ui.componentFactory.names()));
                            });
                        });
                    </script>

                <?php elseif ($contentBlock->type === 'image'): ?>

                    <?php if ($contentBlock->value): ?>
                        <div class="cb-img-preview-wrap">
                            <?= $this->Html->image($contentBlock->value, ['class' => 'cb-img-preview']) ?>
                        </div>
                    <?php endif; ?>

                    <label class="cb-field-label">Replace image</label>
                    <label class="cb-file-drop">
                        <i class="ti ti-upload" aria-hidden="true"></i>
                        <span>Choose an image or drag it here</span>
                        <?= $this->Form->control('value', [
                            'type'   => 'file',
                            'accept' => 'image/*',
                            'label'  => false,
                            'class'  => 'cb-file-input',
                        ]) ?>
                    </label>

                <?php endif; ?>
            </div>

            <div class="cb-form-actions">
                <?= $this->Form->button(__('Save changes'), ['class' => 'btn-product']) ?>
                <?= $this->Html->link(
                    '<i class="ti ti-arrow-left" aria-hidden="true"></i> ' . __('Back to all blocks'),
                    ['action' => 'index'],
                    ['class' => 'cb-back-link', 'escape' => false]
                ) ?>
            </div>

            <?= $this->Form->end() ?>

        </div>

        <div class="cb-edit-meta">
            <p class="cb-meta-heading">Block details</p>
            <div class="cb-meta-row">
                <span class="cb-meta-label">Type</span>
                <span class="cb-meta-val cb-type-badge cb-type-<?= h($contentBlock->type) ?>"><?= h($contentBlock->type) ?></span>
            </div>
            <div class="cb-meta-row">
                <span class="cb-meta-label">Parent</span>
                <span class="cb-meta-val"><?= h($contentBlock->parent) ?></span>
            </div>
            <div class="cb-meta-row">
                <span class="cb-meta-label">Slug</span>
                <span class="cb-meta-val cb-meta-mono"><?= h($contentBlock->slug) ?></span>
            </div>
            <?php if ($contentBlock->modified): ?>
            <div class="cb-meta-row">
                <span class="cb-meta-label">Last saved</span>
                <span class="cb-meta-val"><?= $contentBlock->modified->toFormattedDateString() ?></span>
            </div>
            <?php endif; ?>
        </div>

    </div>

</div>