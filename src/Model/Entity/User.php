<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $primary_role
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Session[] $sessions
 * @property \App\Model\Entity\Role[] $roles
 */
class User extends Entity
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
        'username' => true,
        'email' => true,
        'password' => true,
        'primary_role' => true,
        'created' => true,
        'sessions' => true,
        'roles' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    protected function _setPassword($password) {
        return (new DefaultPasswordHasher)->hash($password);
    }

    public function hasRole($roleName) {

        $rolesRegistry = TableRegistry::get('UsersRoles');
        $_userRoles = $rolesRegistry->find('all', ['contain' => 'Roles'])->where(['user_id' => $this->id])->all();

        foreach($_userRoles as $role) {
            return ($role->role->name == $roleName) ? true : false;
        }

    }
}
