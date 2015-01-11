<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('List Permissions'), ['action' => 'index']) ?></li>
	</ul>
</div>
<div class="groups form large-10 medium-9 columns">
	<?= $this->Form->create(); ?>
	<fieldset>
		<legend><?= __('Generate Permissions') ?></legend>

        <?php
            foreach($controllerActionList as $controllerAction) {
                $name=$controllerAction['namespace'].'\\'.$controllerAction['controller'];
                echo $this->Form->input($name, [
                    'label'=>$name,
                    'options'=>array_combine($controllerAction['actions'], $controllerAction['actions']),
                    'multiple'=>true,
                    'val'=>false
                ]);
            }
        ?>
	</fieldset>
	<?= $this->Form->button(__('Submit')) ?>
	<?= $this->Form->end() ?>
</div>
