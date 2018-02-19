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
            'videojs/video-js.min.css',
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
    <div class="callouts">
    <?php if(isset($unprocessedMedia) && $unprocessedMedia): ?>

        <div class="callout warning">
            <?= __('You still have unprocessed media, If you upload a new set of files, the current unprocessed media files will be deleted'); ?>
        </div>

    <?php endif; ?>
    </div>
    <ul class="preview-medias">
    </ul>
    <ul class="denied-medias">

    </ul>
    <div id="upload-modal-body">
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Media', 'action' => 'add', 'prefix' => 'ajax'],
            'type' => 'file',
            'enctype' => 'multipart/form-data',
            'onsubmit' => 'uploadFiles(event)',
            'id' => 'upload-files-form'
    ]); ?>
        <div id="upload-button-group" class="input-group">
            <input type="file" name="media" id="upload-input" multiple="true" onchange="handleSelect(event)">
        </div>
    <?= $this->Form->button(__('Upload'), ['id' => 'upload-button', 'class' => 'success button expanded icon upload']); ?>
    <?= $this->Form->end(); ?>
    <button class="close-button" onclick="emptyList()" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="loader" style="text-align:center;">
        <div class="loading"></div>
        <br>
        <?= __('Uploading..'); ?>
    </div>

</div>
    <?= $this->Flash->render() ?>

    <div class="grid-container" style="margin-top: 20px;">
        <div class="grid-x">
            <div class="cell">
                <div class="main-container">
                    <div class="grid-x">
                        <div class="cell large-9 medium-9 small-12 header">
                            <?= $this->Html->image('logo.png', ['url' => ['controller' => 'Posts', 'action' => 'index', 'prefix' => false]]); ?>
                            <p class="slogan"><?= __('Media sharing like a boss.'); ?></p>
                        </div>
                        <div class="cell large-3 medium-3 small-12">
                            <div id="upload-files"><?= __('DROP MEDIA'); ?></div>
                        </div>
                    </div>
                    <div class="grid-x navigation">

                    </div>
                    <div class="grid-x">
                        <div class="cell large-3 medium-3 small-12 header">
                            <?= $this->element('sidebar'); ?>
                        </div>
                        <div class="cell large-9 medium-9 small-12 content">


                            <div class="title-bar" data-responsive-toggle="nav-menu" data-hide-for="medium">
                                <button class="menu-icon" type="button" data-toggle></button>
                                <div class="title-bar-title">Menu</div>
                            </div>

                            <!-- Medium-Up Navigation -->
                            <nav class="top-bar" id="nav-menu">

                                <!-- Left Nav Section -->
                                <div class="top-bar-left">
                                    <ul class="vertical medium-horizontal menu">
                                        <li><?= $this->Html->link(__('Today\'s best'), ['controller' => 'posts', 'action' => 'index', 'prefix' => false]); ?></li>
                                        <li><?= $this->Html->link(__('Alltime best'), ['controller' => 'posts', 'action' => 'index', 'prefix' => false]); ?></li>
                                    </ul>
                                </div>

                                <?php if(isset($user->id)): ?>
                                <!-- Right Nav Section -->
                                <div class="top-bar-right">
                                    <ul class="vertical medium-horizontal menu" data-responsive-menu="drilldown medium-dropdown">
                                        <li class="has-submenu">
                                            <a href="#"><?= $user->username; ?></a>
                                            <ul class="submenu menu vertical medium-horizontal" data-submenu>
                                                <li><?= $this->Html->link(__('Session management'), [
                                                        'controller' => 'Sessions',
                                                        'action' => 'index',
                                                        'prefix' => false
                                                    ]); ?></li>
                                                <li><?= $this->Form->postLink(__('Sign out'), [
                                                        'controller' => 'Users',
                                                        'action' => 'logout',
                                                        'prefix' => false
                                                    ]); ?></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <?php else: ?>
                                    <div class="top-bar-right">
                                        <ul class="vertical medium-horizontal menu" data-responsive-menu="drilldown medium-dropdown">
                                            <li class="has-submenu">
                                                <a href="#"><?= __('My account'); ?></a>
                                                <ul class="submenu menu vertical medium-horizontal" data-submenu>
                                                    <li><?= $this->Html->link(__('Sign in'), [
                                                            'controller' => 'Users',
                                                            'action' => 'login',
                                                            'prefix' => false
                                                        ]); ?></li>
                                                    <li><?= $this->Html->link(__('Register'), [
                                                            'controller' => 'Users',
                                                            'action' => 'register',
                                                            'prefix' => false
                                                        ]); ?></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </nav>
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
