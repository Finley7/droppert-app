<?php
/**
 * Created by PhpStorm.
 * User: finleysiebert
 * Date: 12-02-18
 * Time: 01:49
 */

namespace App\Auth;


use Cake\Auth\BaseAuthenticate;
use Cake\Auth\BaseAuthorize;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;

/**
 * Class DroppertAuthorize
 * @package App\Auth
 */
class DroppertAuthorize extends BaseAuthorize
{

    /**
     * Checks user authorization.
     *
     * @param array|\ArrayAccess $user Active user data
     * @param \Cake\Http\ServerRequest $request Request instance.
     * @return bool
     */
    public function authorize($user, ServerRequest $request)
    {

        if(isset($request->getAttributes()['params']['prefix']))
        {
            if ($this->_hasRole($request, $this->_getRoles($user))) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * @param $user
     * @return array
     */
    protected function _getRoles($user) {

        $_roles = [];
        $userRolesRegistry = TableRegistry::get('UsersRoles');
        $userRoles = $userRolesRegistry->find('all', ['contain' => ['Roles']])
            ->where([
                'user_id' => $user['id']
            ])
            ->toList();

        foreach($userRoles as $role) {
            array_push($_roles, $role->role->name);
        }

        return $_roles;
    }

    /**
     * @param $request
     * @param $roleList
     * @return bool
     */
    protected function _hasRole($request, $roleList) {
        return in_array($request->getAttributes()['params']['prefix'], $roleList);
    }
}