<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Post $post
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
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <title>
        Droppert ~
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link rel="image_src" href="<?= $this->Url->assetUrl("media/thumbnails/thumb_{$post->media[0]->filename}.png"); ?>" />
    <meta property="og:image" content="<?= $this->Url->assetUrl("media/thumbnails/thumb_{$post->media[0]->filename}.png"); ?>" />
    <meta name="title" content="<?= $post->title; ?>" />
    <meta name="description" content="<?= $post->description; ?>" />
    <meta name="keywords" content="<?= $post->tags; ?>" />
    <meta name="mediatype" content="post" />
    <script>var zone="video";</script>
    <meta name="nopreroll" content="true" />
    <!-- |1| -->
    <meta property="og:title" content="<?= $post->title; ?>" />
    <meta property="og:description" content="<?= $post->description; ?>" />
    <meta property="og:url" content="<?= $this->Url->build(['controller' => 'Posts', 'action' => 'view', $post->id, $post->slug], true); ?>" />
    <meta property="og:type" content="video" />
    <link rel="canonical" href="<?= $this->Url->build(['controller' => 'Posts', 'action' => 'view', $post->id, $post->slug], true); ?>" />
    <?php if(!is_null($post->tags)): ?>
    <?php
    $tags = explode(',', $post->tags);
    foreach($tags as $tag): ?>
    <meta property="video:tag" content="<?= $tag; ?>" />
    <?php endforeach; ?>
    <?php endif; ?>
    <meta name="twitter:site" value="@siebertje98" />
    <meta name="twitter:app:country" value="nl" />
    <meta name="twitter:card" value="summary_large_image" />
    <meta name="twitter:url" value="<?= $this->Url->build(['controller' => 'Posts', 'action' => 'view', $post->id, $post->slug], true); ?>" />
    <meta name="twitter:title" value="<?= $post->title; ?>" />
    <meta name="twitter:description" value="<?= $post->description; ?>" />
    <meta name="twitter:image" value="<?= $this->Url->assetUrl("media/thumbnails/thumb_{$post->media[0]->filename}.png"); ?>" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="theme-color" content="#3498DB" />
    <meta name="category" content="<?= $post->tags; ?>" />

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


                            <!-- Right Nav Section -->
                            <div class="top-bar-right">
                                <ul class="vertical medium-horizontal menu" data-responsive-menu="drilldown medium-dropdown">
                                    <li class="nsfw-button">

                                        <?= $this->Form->create(null, ['url' => [
                                            'controller' => 'Posts',
                                            'action' => 'toggleNswf',
                                            'prefix' => false
                                        ],
                                            'id' => 'nsfwForm'
                                        ]);
                                        ?>
                                        <strong style="display: inline-block">NSFW</strong>
                                        <?= $this->Form->radio(
                                            'nsfw',
                                            [__('Off'), __('On')],
                                            [
                                                'value' => $nsfw,
                                                'onchange' => 'validateNswf()',
                                                'class' => 'nsfw-toggle',
                                                'label' => [
                                                    'class' => 'nsfw-label'
                                                ]
                                            ]
                                        ); ?>
                                        <?= $this->Form->end(); ?>
                                    </li>
                                    <?php if(isset($user->id)): ?>
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
                                    <?php else: ?>
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
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </nav>
                        <?= $this->fetch('content') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cell content">
        <footer class="text-center" style="color: #555;padding: 5px; font-size:11px;background: #ccc;">
            Copyright &copy; FHD 2018. Alle rechten voorbehouden ~ Sponsored by Stefan
        </footer>
    </div>
</div>


<footer>
</footer>

<script>
    function validateNswf() {
        document.getElementById('nsfwForm').submit();
    }
</script>

<?= $this->Html->script([
    'vendor/jquery.js',
    'vendor/what-input.js',
    'vendor/foundation.js',
    'app.js'
]); ?>
</body>
</html>
