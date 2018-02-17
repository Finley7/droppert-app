<?php

/**
 * @var \Cake\View\View $this
 */
?>
<div class="card">
    <div class="card-body">
        <h3><?= __('Sign in'); ?></h3>
        <p><?= __('Sign in with your Droppert account'); ?></p>
        <?= $this->Form->create(); ?>
        <?= $this->Form->control('username'); ?>
        <?= $this->Form->control('password'); ?>
        <?= $this->Form->submit(__('Sign in'), ['class' => 'button']); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>

