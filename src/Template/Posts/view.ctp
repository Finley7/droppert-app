<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\Reply $reply
 */
?>

<div class="card">
    <div class="card-body" style="background: #ddd;">
        <div class="media-section">
            <?php foreach ($post->media as $media): ?>
                <?php if (preg_match('/png|jpeg|jpg/', $media->extension)): ?>
                    <img src="<?= $this->Url->assetUrl("media/images/{$media->filename}.png"); ?>"
                         title="<?= $media->name; ?>">
                <?php elseif (preg_match('/mp4|webm|gif/', $media->extension)): ?>
                    <div class="video-wrapper">
                        <div class="video-content">
                            <video
                                    data-setup="{fluid: true}"
                                <?= ($media == $post->media[0]) ? 'autoplay' : ''; ?>
                                    <?= ($media->extension != 'gif') ? 'controls' : ''; ?>
                                    <?= ($media->extension == 'gif') ? 'loop' : ''; ?>
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
                        <?php if($user->hasRole('admin') && !$post->deleted) : ?>
                        <?= $this->Form->postButton('<i class="fa fa-trash"></i>', ['controller' => 'Posts', 'action' => 'delete', 'prefix' => 'admin', $post->id], ['class' => 'alert button']); ?>
                        <?php else: ?>
                            <?= $this->Form->postButton('<i class="fa fa-redo"></i>', ['controller' => 'Posts', 'action' => 'recover', 'prefix' => 'admin', $post->id], ['class' => 'warning button']); ?>
                        <?php endif; ?>
                        <?= $this->Form->create(null, [
                            'id' => 'yayForm',
                            'onsubmit' => 'ratePost(event, \'yay\')',
                            'url' => [
                                'controller' => 'Ratings',
                                'action' => 'rate',
                                'prefix' => 'ajax',
                            ]
                        ]); ?>
                        <?= $this->Form->hidden('post-id', ['value' => $post->id]); ?>
                        <?= $this->Form->hidden('type', ['value' => 'yay']); ?>
                        <?= $this->Form->button('YAY!', ['class' => 'success button icon yay']); ?>
                        <?= $this->Form->end(); ?>
                        <button>
                                <span id="post-yays" class="yay">
                                    <span style="width:15px;height:15px;" class="loading"></span>
                                </span>
                        </button>
                        <?= $this->Form->create(null, [
                            'id' => 'nayForm',
                            'onsubmit' => 'ratePost(event, \'nay\')',
                            'url' => [
                                'controller' => 'Ratings',
                                'action' => 'rate',
                                'prefix' => 'ajax',
                            ]]); ?>
                        <?= $this->Form->hidden('post-id', ['value' => $post->id]); ?>
                        <?= $this->Form->hidden('type', ['value' => 'nay']); ?>
                        <?= $this->Form->button('NAY!', ['class' => 'alert button icon nay']); ?>
                        <?= $this->Form->end(); ?>


                        <button>
                                <span id="post-nays" class="nays">
                                    <span style="width:15px;height:15px;" class="loading"></span>
                                </span>
                        </button>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class="grid-x">
        <div class="cell">
            <section class="replies">
                <h6 class="title"><?= __('Reply to this post'); ?></h6>
                <?php if(isset($user->id)): ?>
                    <?= $this->Form->create($reply); ?>
                        <div class="form-group">
                            <?= $this->Form->control('body', ['rows' => 2]); ?>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->button(__('Post it'), ['class' => 'default button']); ?>
                        </div>
                    <?= $this->Form->end(); ?>
                <?php endif; ?>
                <hr>
                <?php foreach($post->replies as $reply): ?>
                    <?php if(!$reply->deleted): ?>
                        <div class="reply">
                            <div class="reply-info">
                                <?= __('{0} replied on {1}', $reply->user->username, $reply->created->nice());?>
                                <?php if($user->hasRole('admin')) : ?>
                                    <?= $this->Form->postLink('(delete)', ['controller' => 'Replies', 'action' => 'delete', 'prefix' => 'admin', $reply->id]); ?>
                                <?php endif; ?>
                            </div>
                            <p><?= $reply->body; ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </section>

        </div>
    </div>
</div>

<?= $this->Html->script('videojs/video.min.js'); ?>
<script>
    _ = (element) => {
        return document.getElementById(element);
    };

    getRatings = () => {

        let xhr = new XMLHttpRequest();


        xhr.open('GET', '<?= $this->Url->build([
            'controller' => 'Ratings',
            'action' => 'ratings',
            'prefix' => 'ajax', $post->id]); ?>');

        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 400) {

                let yays = _('post-yays');
                let nays = _('post-nays');
                let response = JSON.parse(xhr.responseText).response;

                yays.innerHTML = response.yays;
                nays.innerHTML = response.nays;

            }
        };

        xhr.onerror = () => {
            console.log(xhr);
        };

        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.send();
    };

    ratePost = (event, type) => {
        event.preventDefault();

        <?php if(!isset($user->id)): ?>
        alert('<?= __('You need to be logged in to rate this post'); ?>');
        <?php endif; ?>

        let form = document.querySelectorAll('#' + type + 'Form input');
        let formData = new FormData();

        Array.prototype.forEach.call(form, (input, key) => {
            formData.append(input.getAttribute('name'), input.getAttribute('value'))
        });

        console.log(formData);

        let xhr = new XMLHttpRequest();

        xhr.open('POST', _(type + 'Form').getAttribute('action'));

        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 400) {

                let yays = _('post-yays');
                let nays = _('post-nays');
                let response = JSON.parse(xhr.responseText).response;

                yays.innerHTML = response.yays;
                nays.innerHTML = response.nays;

            }
            else {
                let response = JSON.parse(xhr.responseText);
                alert(response.message);
            }
        };

        xhr.onerror = () => {
            console.log(xhr);
        };

        xhr.send(formData);
    };

    (() => {
        getRatings();
    })();


</script>
