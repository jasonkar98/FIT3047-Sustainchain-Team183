<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Filtertag $filtertag
 * @var string[]|\Cake\Collection\CollectionInterface $products
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $filtertag->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $filtertag->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Filtertags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="filtertags form content">
            <?= $this->Form->create($filtertag) ?>
            <fieldset>
                <legend><?= __('Edit Filtertag') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('products._ids', ['options' => $products]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
