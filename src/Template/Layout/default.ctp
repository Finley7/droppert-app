<?php
/**
* @var \Cake\View\View $this
 * @var \App\Model\Entity\User $user
 */
for($i = 0; $i <= 10; $i++) {
    $this->Form->unlockField('file_' . $i);
}
$this->Form->unlockField('media');
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Droppert ~
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css([
            'foundation.min.css',
            'fontawesome-all.min.css',
            'app.css'
    ]) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<div class="reveal" id="uploadModal" data-reveal data-close-on-click="false">
    <h3><?= __('Upload media'); ?></h3>
    <p><?= __(
            'Uploading some media are you? Be sure to check our {0}',
            $this->Html->link(__('guidelines'), ['controller' => 'page', 'action' => 'guidelines'])
        ); ?></p>
    <ul class="preview-medias">
    </ul>
    <ul class="denied-medias">

    </ul>
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Media', 'action' => 'add', 'prefix' => 'ajax'],
            'type' => 'file',
            'enctype' => 'multipart/form-data',
            'onsubmit' => 'uploadFiles(event)',
            'id' => 'upload-files-form'
    ]); ?>
        <div id="upload-button-group" class="input-group">
<!--            --><?//= $this->Form->control('files', [
//                    'type' => 'file',
//                    'multiple' => 'true',
//                    'class' => 'form-control',
//                    'onchange' => 'handleSelect(event)'
//            ]); ?>
            <input type="file" name="media" multiple="true" onchange="handleSelect(event)">
        </div>
    <?= $this->Form->button(__('Upload'), ['id' => 'upload-button', 'class' => 'success button icon upload']); ?>
    <?= $this->Form->end(); ?>
    <button class="close-button" onclick="emptyList()" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
    <?= $this->Flash->render() ?>

    <div class="grid-container" style="margin-top: 20px;">
        <div class="grid-x">
            <div class="cell">
                <div class="main-container">
                    <div class="grid-x">
                        <div class="cell large-9 medium-9 small-12 header">
                            <?= $this->Html->image('logo.png'); ?>
                            <p class="slogan"><?= __('Media sharing like a boss.'); ?></p>
                        </div>
                        <div class="cell large-3 medium-3 small-12">
                            <div id="upload-files"><?= __('DROP MEDIA'); ?></div>
                        </div>
                    </div>
                    <div class="grid-x navigation">

                    </div>
                    <div class="grid-x">
                        <div class="cell">
                            <?= $this->fetch('content') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer>
    </footer>

    <?= $this->Html->script([
            'vendor/jquery.js',
            'vendor/what-input.js',
            'vendor/foundation.js',
            'app.js'
    ]); ?>
</body>
</html>
