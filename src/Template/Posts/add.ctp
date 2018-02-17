<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\Media[] $savedMedia
 * @var \App\Model\Entity\User $user
 */

echo $this->Form->unlockField('media');
?>
<div class="card">
    <div class="card-body">
        <h4><?= __('Create a new post'); ?></h4>
        <?= $this->Form->create($post); ?>
        <div class="form-group">
            <?= $this->Form->control('title'); ?>
        </div>
        <div class="form-group">
            <?= $this->Form->control('description'); ?>
        </div>
        <div class="form-group">
            <?= $this->Form->control('tags'); ?>
            <small class="help-text"><?= __('Use a comma to seperate the tags'); ?></small>
        </div>
        <hr>
        <div class="form-group">
            <label><?= __('Media that belongs to the post'); ?></label>
           <?php foreach($savedMedia as $media): ?>
               <label for="<?= $media['id']; ?>">
                   <input name="media[]" value="<?= $media['id']; ?>" id="<?= $media['id']; ?>" type="checkbox" checked>
                   <span>
                       <?= $media['name']; ?>.<?= $media['extension']; ?>
                   </span>
               </label>
           <?php endforeach; ?>
        </div>
        <hr>
        <div class="form-group">
            <?= $this->Form->button(__('Create post'), ['class' => 'expanded success button icon save', 'confirm' => __('Media that is not selected will be deleted and CANNOT be recoverd! Are you sure?')]); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>
