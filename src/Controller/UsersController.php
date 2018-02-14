<?php
/**
 * Created by PhpStorm.
 * User: finleysiebert
 * Date: 12-02-18
 * Time: 00:04
 */

namespace App\Controller;
use App\Model\Table\UsersTable;
use Cake\Core\Configure;
use Cake\Http\Cookie\Cookie;
use Cake\Network\Exception\MethodNotAllowedException;


/**
 * @property UsersTable Users
 */
class UsersController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Cookie');
        $this->Auth->allow(['register']);

        if($this->Auth->user('id') > 0) {
            $_guestViews = ['login', 'register'];

            if (in_array($this->request->getAttributes()['params']['action'], $_guestViews)) {
                $this->Flash->error(__('You are already logged in, {0}', $this->Auth->user('username')));
                return $this->redirect($this->Auth->redirectUrl());
            }
        }
    }

    public function login() {

        if($this->request->is('post')) {

            $user = $this->Auth->identify();

            if($user) {

                $cookie = (new Cookie('user'))
                    ->withValue($user['session']->id)
                    ->withExpiry(new \DateTime('+120 days'))
                    ->withHttpOnly(true)
                    ->withPath('/');

                $this->Flash->success(__('Login was successfull'));

                return $this->response
                    ->withCookie($cookie)
                    ->withLocation($this->Auth->redirectUrl());
            }

            $this->Flash->error(__('We could not sign you in'));

        }
    }

    public function register()
    {
        if(!Configure::read('App.registration')) {
            throw new MethodNotAllowedException(__('Registration is disabled'));
        }


        $user = $this->Users->newEntity(['associated' => ['Roles']]);

        if($this->request->is('post')) {

            $user->primary_role = Configure::read('App.user_role');
            # TODO: fix this depracted code.
            $this->request->data['roles']['_ids'] = [Configure::read('App.user_role')];

            $user = $this->Users->patchEntity($user, $this->request->getData(), ['associated' => ['Roles']]);

            if($this->Users->save($user, ['associated' => ['Roles']])) {

                $this->Flash->success(__('Your account has been created'));
                return $this->redirect(['action' => 'login']);

            }
            else {
                $this->Flash->error(__('One or more errors have occurred'));
            }

        }

        $this->set(compact(['user']));
    }
}