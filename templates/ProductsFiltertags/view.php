<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductsFiltertag $productsFiltertag
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Products Filtertag'), ['action' => 'edit', $productsFiltertag->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Products Filtertag'), ['action' => 'delete', $productsFiltertag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productsFiltertag->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Products Filtertags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Products Filtertag'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="productsFiltertags view content">
            <h3><?= h($productsFiltertag->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Product') ?></th>
                    <td><?= $productsFiltertag->hasValue('product') ? $this->Html->link($productsFiltertag->product->name, ['controller' => 'Products', 'action' => 'view', $productsFiltertag->product->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Filtertag') ?></th>
                    <td><?= $productsFiltertag->hasValue('filtertag') ? $this->Html->link($productsFiltertag->filtertag->name, ['controller' => 'Filtertags', 'action' => 'view', $productsFiltertag->filtertag->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($productsFiltertag->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>