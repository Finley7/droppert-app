<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity
 *
 * @property string $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property int $yays
 * @property int $nays
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 * @property bool $deleted
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Media[] $media
 * @property \App\Model\Entity\Reply[] $replies
 */
class Post extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'slug' => true,
        'title' => true,
        'description' => true,
        'yays' => true,
        'nays' => true,
        'user_id' => true,
        'created' => true,
        'updated' => true,
        'deleted' => true,
        'user' => true,
        'media' => true,
        'replies' => true
    ];
}
