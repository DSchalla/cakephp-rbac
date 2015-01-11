<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Permission'), ['action' => 'edit', $controller->id]);?></li>
        <li><?= $this->Html->link(__('Generate Permission'), ['action' => 'add']) ?></li>
    </ul>
</div>
<div class="groups index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th style="width:50%"><?= $this->Paginator->sort('controller') ?></th>
            <th><?= $this->Paginator->sort('action') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($permissions as $permission): ?>
            <tr>
                <td><?= $this->Number->format($permission->id) ?></td>
                <td><?= h($permission->controller->controller) ?></td>
                <td><?= h($permission->action) ?></td>
                <td class="actions">
                    <?= $this->Form->postLink(
                        __('Toggle'),
                        ['action' => 'toggle', $permission->id],
                        ['confirm' => __('Are you sure you want to toggle # {0}? This will remove all restrictions.', $permission->id)]
                    ) ?>
                    <?= $this->Form->postLink(
                        __('Delete'),
                        ['action' => 'delete', $permission->id],
                        ['confirm' => __('Are you sure you want to delete # {0}?', $permission->id)]
                    ) ?>
                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')); ?>
            <?= $this->Paginator->numbers(); ?>
            <?=	$this->Paginator->next(__('next') . ' >'); ?>
        </ul>
        <p><?= $this->Paginator->counter(); ?></p>
    </div>
</div>
