<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Group'), ['action' => 'edit', $group->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Group'), ['action' => 'delete', $group->id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Group'), ['action' => 'add']) ?> </li>
	</ul>
</div>
<div class="groups view large-10 medium-9 columns">
	<h2><?= h($group->title) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Title') ?></h6>
			<p><?= h($group->title) ?></p>
		</div>
		<div class="large-2 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= $this->Number->format($group->id) ?></p>
		</div>
		<div class="large-2 columns dates end">
			<h6 class="subheader"><?= __('Created') ?></h6>
			<p><?= h($group->created) ?></p>
			<h6 class="subheader"><?= __('Modified') ?></h6>
			<p><?= h($group->modified) ?></p>
		</div>
	</div>
</div>
