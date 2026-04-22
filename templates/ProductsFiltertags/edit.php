<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductsFiltertag $productsFiltertag
 * @var string[]|\Cake\Collection\CollectionInterface $products
 * @var string[]|\Cake\Collection\CollectionInterface $filtertags
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $productsFiltertag->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $productsFiltertag->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Products Filtertags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="productsFiltertags form content">
            <?= $this->Form->create($productsFiltertag) ?>
            <fieldset>
                <legend><?= __('Edit Products Filtertag') ?></legend>
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
