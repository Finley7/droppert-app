<?php

/**
 * @var \Cake\View\View $this
 */
?>
<?= $this->Form->create(); ?>
    <?= $this->Form->text('username'); ?>
    <?= $this->Form->password('password'); ?>
    <?= $this->Form->submit(__('Sign in')); ?>
<?= $this->Form->end(); ?>
