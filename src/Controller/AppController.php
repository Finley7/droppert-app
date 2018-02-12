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

use App\Model\Table\SessionsTable;
use App\Model\Table\UsersTable;
use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @property SessionsTable Sessions
 * @property UsersTable Users
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
                $this->Cookie->delete('user');
                $this->Auth->logout();
                $this->Flash->error(__('Your session deviated too much from your user agent and that is why you have been logged out'));
            }

            $user = $this->Users->get($userSession->user_id);

            $this->Auth->setUser($user->toArray());
            $this->set('user', $user);
        }
    }
}
