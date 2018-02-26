<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BlockedIp Entity
 *
 * @property int $id
 * @property string $ip_address
 * @property string $reason
 * @property \Cake\I18n\FrozenTime $created
 */
class BlockedIp extends Entity
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
        'ip_address' => true,
        'reason' => true,
        'created' => true
    ];
}
