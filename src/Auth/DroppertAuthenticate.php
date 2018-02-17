<?php
/**
 * Created by PhpStorm.
 * User: finleysiebert
 * Date: 11-02-18
 * Time: 23:17
 */

namespace App\Auth;


use App\Model\Entity\Session;
use Cake\Auth\BaseAuthenticate;
use Cake\Controller\Component\CookieComponent;
use Cake\Core\Configure;
use Cake\Error\FatalErrorException;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;

/**
 * Class DroppertAuthenticate
 * @package App\Auth
 */
class DroppertAuthenticate extends BaseAuthenticate
{

    /**
     * Authenticate a user based on the request information.
     *
     * @param \Cake\Http\ServerRequest $request Request to get authentication information from.
     * @param \Cake\Http\Response $response A response object that can have headers added.
     * @return mixed Either false on failure, or an array of user data on success.
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        $user = $this->_findUser(
            h($request->getData()['username']),
            h($request->getData()['password'])
        );

        if($user) {

            $user['session'] = $this->_createSession($user);
            return $user;
        }

        return false;
    }

    /**
     * @param $user
     * @return Session|bool
     */
    protected function _createSession($user) {

        $sessionsRegistry = TableRegistry::get('Sessions');

        $session = $sessionsRegistry->newEntity([
            'user_id' => $user['id'],
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'expires' => new \DateTime('+120 days')
        ]);

        if($sessionsRegistry->save($session)) {
            return $session;
        }

        throw new FatalErrorException('Session was not saved in database');
    }
}