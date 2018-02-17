<?php

/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="grid-container">
    <div class="grid-x">
        <div class="cell medium-6 large-6 small-12">
            <div class="card">
                <div class="card-body">
                    <?= $this->Flash->render() ?>
                    <h4><?= __('Already on Droppert? Sign in to continue!'); ?></h4>
                    <?= $this->Form->create(null, ['url' => ['action' => 'login', 'src' => 'with-media']]); ?>
                    <div class="form-group">
                        <?= $this->Form->control('username', ['class' => 'form-control']); ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('password', ['class' => 'form-control']); ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->submit(__('Sign in'), ['class' => 'btn btn-success']); ?>
                    </div>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
        <div class="cell medium-6 large-6 small-12">
            <div class="card">
                <div class="card-body">
                    <?= $this->Flash->render() ?>
                    <h4><?= __('New to droppert? Register to continue!'); ?></h4>
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
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>