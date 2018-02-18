<?php
/**
 * @var \Cake\View\View $this 5ce695f8186deb42
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Post $post
 */

?>

    <div class="card">
        <div class="card-body" style="background: #ddd;">
            <div class="media-section">
                <?php foreach ($post->media as $media): ?>
                    <?php if (preg_match('/png|jpeg|jpg|gif/', $media->extension)): ?>
                        <img src="<?= $this->Url->assetUrl("media/images/{$media->filename}.png"); ?>"
                             title="<?= $media->name; ?>">
                    <?php elseif (preg_match('/mp4|webm/', $media->extension)): ?>
                        <div class="video-wrapper">
                            <div class="video-content">
                                <video
                                        data-setup="{fluid: true}"
                                    <?= ($media == $post->media[0]) ? 'autoplay' : ''; ?>
                                        controls
                                        class="video-js droppert-video vjs-big-play-centered vjs-16-9 "
                                        id="<?= $media->filename; ?>"
                                        preload="auto"
                                        poster="<?= $this->Url->assetUrl("media/thumbnails/thumb_{$media->filename}.png"); ?>"
                                >
                                    <source src="<?= $this->Url->assetUrl("media/videos/mp4/{$media->filename}.mp4"); ?>"
                                            type="video/mp4">
                                    <p class="vjs-no-js">
                                        <?= __('To view this video please enable JavaScript, and consider upgrading to a web browser that'); ?>
                                        <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5
                                            video</a>
                                    </p>
                                </video>
                            </div>
                        </div>
                        <br>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <hr>
            <div class="grid-x">
                <div class="cell medium-7 large-7 small-12">
                    <h6 class="post-title"><?= $post->title; ?></h6>
                    <?= $this->Text->autoParagraph($post->description); ?>
                    <br>
                    <?php foreach ($tags as $tag): ?>
                        <?= $this->Html->link($tag, ['action' => 'tag', \Cake\Utility\Text::slug($tag)]); ?>
                    <?php endforeach; ?>
                </div>
                <div class="cell medium-5 large-5 small-12">
                    <div class="yaynay-box">
                        <div class="button-group" style="text-align:center;">
                            <?= $this->Form->postButton('YAY!', [
                                'controller' => 'Ratings',
                                'action' => 'rate',
                                'yay'
                            ], ['class' => 'success button icon yay']); ?>
                            <button>
                                <?= $this->Number->format(count($post->ratings) + 3889); ?>
                            </button>
                            <?= $this->Form->postButton('NAY!', [
                                'controller' => 'Ratings',
                                'action' => 'rate',
                                'nay'
                            ], ['class' => 'alert button icon nay']); ?>
                            <button>
                                <?= $this->Number->format(count($post->ratings) + 250); ?>
                            </button>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

<?= $this->Html->script('videojs/video.min.js'); ?>