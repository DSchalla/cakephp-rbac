<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="groups form large-10 medium-9 columns">
    <h2><?=__('Member')?> - <?= h($group->title) ?></h2>
    <?= $this->Form->create($group); ?>
    <fieldset>
        <legend><?= __('Add Member') ?></legend>
        <?php
        echo $this->Form->input('users', ['multiple' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
