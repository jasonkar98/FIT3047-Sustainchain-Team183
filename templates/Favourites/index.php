<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Favourite> $favourites
 */
?>

<?php debug($favourites->toArray()); ?>
<div class="favourites index content">
    <?= $this->Html->link(__('New Favourite'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Favourites') ?></h3>
    <?php if ($favourites->isEmpty()): ?>
        <div class="favourites-empty">
            <h4><?= __('No favourites yet') ?></h4>
            <p><?= __('You have not saved any favourites. Start browsing products and add some favourites to see them here.') ?></p>
        </div>

    <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('user_id') ?></th>
                        <th><?= $this->Paginator->sort('product_id') ?></th>
                        <th><?= $this->Paginator->sort('created') ?></th>
                        <th><?= $this->Paginator->sort('modified') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($favourites as $favourite): ?>
                    <tr>
                        <td><?= $this->Number->format($favourite->id) ?></td>
                        <td><?= $favourite->hasValue('user') ? $this->Html->link($favourite->user->email, ['controller' => 'Users', 'action' => 'view', $favourite->user->id]) : '' ?></td>
                        <td><?= $favourite->hasValue('product') ? $this->Html->link($favourite->product->name, ['controller' => 'Products', 'action' => 'view', $favourite->product->id]) : '' ?></td>
                        <td><?= h($favourite->created) ?></td>
                        <td><?= h($favourite->modified) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $favourite->id]) ?>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $favourite->id]) ?>
                            <?= $this->Form->postLink(
                                __('Delete'),
                                ['action' => 'delete', $favourite->id],
                                [
                                    'method' => 'delete',
                                    'confirm' => __('Are you sure you want to delete # {0}?', $favourite->id),
                                ]
                            ) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
        </div>
        </div>
    <?php endif; ?>

