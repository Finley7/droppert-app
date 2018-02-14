<?php

/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="card">
    <div class="card-body">
        <?= $this->Flash->render() ?>
        <legend><?= __('Create a new account'); ?></legend>
        <?= $this->Form->create($user); ?>
        <div class="form-group">
            <?= $this->Form->control('username', ['class' => 'form-control']); ?>
        </div>

        <div class="form-group">
            <?= $this->Form->control('email', ['class' => 'form-control']); ?>
        </div>

        <div class="form-group">
            <?= $this->Form->control('password', ['class' => 'form-control']); ?>
        </div>

        <div class="form-group">
            <?= $this->Form->control('password_verify', ['type' => 'password', 'class' => 'form-control']); ?>
        </div>

        <div class="form-group">
            <?= $this->Form->submit(__('Create account'), ['class' => 'btn btn-success']); ?>
        </div>
    </div>
</div>

<?= $this->Form->end(); ?>
