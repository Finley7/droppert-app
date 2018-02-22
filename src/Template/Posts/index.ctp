<?php
/**
* @var \Cake\View\View $this
 * @var \App\Model\Entity\Post[] $posts
 * @var \App\Model\Entity\User $user;
 */
?>
<div class="grid-x">
    <?php foreach($posts as $post): ?>
    <?php if(!$post->deleted) : ?>
        <?php if(
                ($post->isNSFW() && $nsfw == true) ||
                (!$post->isNSFW() && $nsfw == true) ||
                (!$post->isNSFW() && $nsfw == false)
        ): ?>
        <div class="cell medium-4 large-4 small-12">
            <div class="post-preview">
                <div class="media-object">
                    <div class="media-object-section">
                        <div class="thumbnail">
                            <a href="<?= $this->Url->build(['controller' => 'Posts', 'action' => 'view', $post->id, $post->slug]); ?>">
                                <img style="width: 100px; height:100px;" src= "<?= $this->Url->assetUrl("media/thumbnails/thumb_{$post->media[0]->filename}.png"); ?>">
                            </a>
                        </div>
                    </div>
                    <div class="media-object-section main-section">
                        <h6 class="title"><?= $this->Html->link($post->title, ['controller' => 'Posts', 'action' => 'view', $post->id, $post->slug]); ?></h6>
                        <div class="description"><?= $this->Text->truncate($post->description, 50); ?></div>
                        <small style="color:grey;"><?= $post->created->nice(); ?></small>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; ?>
</div>