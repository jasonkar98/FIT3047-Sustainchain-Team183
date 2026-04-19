<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Favourite $favourite
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Favourite'), ['action' => 'edit', $favourite->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Favourite'), ['action' => 'delete', $favourite->id], ['confirm' => __('Are you sure you want to delete # {0}?', $favourite->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Favourites'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Favourite'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="favourites view content">
            <h3><?= h($favourite->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $favourite->hasValue('user') ? $this->Html->link($favourite->user->email, ['controller' => 'Users', 'action' => 'view', $favourite->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Product') ?></th>
                    <td><?= $favourite->hasValue('product') ? $this->Html->link($favourite->product->name, ['controller' => 'Products', 'action' => 'view', $favourite->product->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($favourite->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($favourite->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($favourite->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>