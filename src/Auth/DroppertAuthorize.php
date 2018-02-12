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
        // TODO: Implement authorize() method.
    }
}