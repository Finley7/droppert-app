<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Table\BlockedIpsTable;
use App\Model\Table\SessionsTable;
use App\Model\Table\UsersTable;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Cookie\Cookie;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Routing\Router;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @property SessionsTable Sessions
 * @property UsersTable Users
 * @property BlockedIpsTable BlockedIps
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        $this->loadComponent('Security');
        $this->loadComponent('Csrf');

        $this->loadComponent('Auth', [
            'authenticate' => 'Droppert',
            'authorize' => 'Droppert',
        ]);

        $this->_checkUser();
        $this->_checkUnprocessedMedia();
        $this->_checkIpBlocks();

        if(is_null($this->request->getCookie('site'))) {
            $siteSettingsCookie = (new Cookie('site'))
                ->withExpiry(new \DateTime('+1 year'))
                ->withHttpOnly(true)
                ->withPath('/')
                ->withValue(json_encode(['NSFW' => false]));

            $this->response = $this->response->withCookie($siteSettingsCookie);
            $this->set('nsfw', false);
        }
        else
        {
            $nswfOption = json_decode(json_encode($this->request->getCookie('site')));

            $this->set('nsfw', $nswfOption->NSFW);
        }
    }

    protected function _checkUser() {
        if (!is_null($this->request->getCookie('user'))) {
            $this->loadModel('Sessions');
            $this->loadModel('Users');

            $cookie = $this->request->getCookie('user');

            $userSession = $this->Sessions->find('all')
                ->where([
                    'Sessions.id' => $cookie,
                    'expires >= ' => new \DateTime('now')
                ])->first();

            similar_text($userSession->user_agent, $_SERVER['HTTP_USER_AGENT'], $percentage);

            if ($percentage <= 20) {
                $this->response = $this->response->withExpiredCookie('user');
                $this->Auth->logout();
                $this->Flash->error(__('Your session deviated too much from your user agent and that is why you have been logged out'));
            }

            $user = $this->Users->get($userSession->user_id);

            if($user->primary_role == Configure::read('App.ban_role'))
            {
                throw new MethodNotAllowedException(__('You have been blocked'));
            }

            $this->Auth->setUser($user->toArray());
            $this->set('user', $user);
        }
    }

    protected function _checkUnprocessedMedia() {
        if(!is_null($this->request->getCookie('media'))) {
            $cookie = $this->request->getCookie('media');

            if (
                $this->request->getAttributes()['params']['controller'] == 'Posts' &&
                $this->request->getAttributes()['params']['action'] != 'add'
            ) {
                $this->Flash->set(
                    __('We\'ve found some unprocessed media files. {0}. You have {1} left to process them.',
                        '
                        <a href="' .Router::url(['controller' => 'Posts', 'action' => 'add']) . '">
                        '. __('Process them') .'
                        </a>', 'some time'),
                    ['escape' => false]
                );
                $this->set('unprocessedMedia', true);
            };

        }
    }

    protected function _checkIpBlocks() {
        $this->loadModel('BlockedIps');

        $blockCheck = $this->BlockedIps->find('all')
            ->where(['ip_address' => $_SERVER['REMOTE_ADDR']]);

        if($blockCheck->count() > 0) {
            throw new MethodNotAllowedException(
                __('You have been banned for the following reason: {0}', $blockCheck->first()->reason)
            );
        }
    }
}
