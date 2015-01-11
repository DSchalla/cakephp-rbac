<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Controllers'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Controller Detail'), ['action' => 'view', $controller->id]); ?></li>
        <li><?= $this->Html->link(__('Generate Permission'), ['action' => 'genearte']) ?></li>
    </ul>
</div>
<div class="groups index large-10 medium-9 columns">
    <?= $this->Form->create(false); ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th>Action</th>
            <?php foreach ($groups as $group): ?>
                <th><?= h($group->title) ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($permissions as $permission): ?>

            <tr>
                <td><?= h($permission->action) ?></td>
                <?php foreach ($permission->groups as $group): ?>
                    <td>
                        <?=
                        $this->Form->input(
                            $permission->action . '[' . $group->title . ']',
                            [
                                'options' => [1 => 'Yes', 0 => 'No'],
                                'label' => false,
                                'value' => isset($group->_joinData->value) ? $group->_joinData->value : 0
                            ]
                        )
                        ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
