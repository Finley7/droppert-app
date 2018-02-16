<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Media Entity
 *
 * @property string $id
 * @property string $name
 * @property string $content_type
 * @property int $size
 * @property string $extension
 * @property string $post_id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Post $post
 * @property \App\Model\Entity\User $user
 */
class Media extends Entity
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
        'id' => true,
        'name' => true,
        'content_type' => true,
        'size' => true,
        'extension' => true,
        'post_id' => true,
        'user_id' => true,
        'created' => true,
        'post' => true,
        'user' => true
    ];
}
