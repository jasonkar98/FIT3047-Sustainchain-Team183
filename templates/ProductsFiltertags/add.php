<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductsFiltertag $productsFiltertag
 * @var \Cake\Collection\CollectionInterface|string[] $products
 * @var \Cake\Collection\CollectionInterface|string[] $filtertags
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Products Filtertags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="productsFiltertags form content">
            <?= $this->Form->create($productsFiltertag) ?>
            <fieldset>
                <legend><?= __('Add Products Filtertag') ?></legend>
                <?php
                    echo $this->Form->control('product_id', ['options' => $products]);
                    echo $this->Form->control('filtertag_id', ['options' => $filtertags]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
