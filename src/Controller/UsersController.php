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
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;


/**
 * @property UsersTable Users
 */
class UsersController extends AppController
{
    private $_withMedia = false;

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

        if(
            isset($this->request->getQueryParams()['src']) &&
            !is_null($this->request->getCookie('media')) &&
            $this->request->getQueryParams()['src'] == 'with-media') {
            $this->_withMedia = true;
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

                if($this->_withMedia == true) {
                    return $this->response
                        ->withCookie($cookie)
                        ->withLocation(Router::url(['controller' => 'Posts', 'action' => 'add']));
                }

                return $this->response
                    ->withCookie($cookie)
                    ->withLocation(Router::url(['controller' => 'Posts', 'action' => 'index']));
            }

            $this->Flash->error(__('We could not sign you in'));

        }
    }

    public function register()
    {

        if(!Configure::read('App.registration')) {
            throw new MethodNotAllowedException(__('Registration is disabled'));
        }

        if($this->_withMedia == true) {
            $this->viewBuilder()
                ->setTemplate('auth-with-media');
        }

        $user = $this->Users->newEntity(['associated' => ['Roles']]);

        if($this->request->is('post')) {

            $user->primary_role = Configure::read('App.user_role');
            # TODO: fix this depracted code.
            $this->request->data['roles']['_ids'] = [Configure::read('App.user_role'), Configure::read('App.ajax_role')];

            $user = $this->Users->patchEntity($user, $this->request->getData(), ['associated' => ['Roles']]);

            if($this->Users->save($user, ['associated' => ['Roles']])) {

                if($this->_withMedia == true) {

                    $this->request->data['username'] = $user->username;
                    $this->request->data['password'] = $this->request->getData()['password'];

                    $user = $this->Auth->identify();

                    if($user) {

                        $cookie = (new Cookie('user'))
                            ->withValue($user['session']->id)
                            ->withExpiry(new \DateTime('+120 days'))
                            ->withHttpOnly(true)
                            ->withPath('/');


                        return $this->response
                            ->withCookie($cookie)
                            ->withLocation(Router::url(['controller' => 'Posts', 'action' => 'add']));
                    }

                }

                $this->Flash->success(__('Your account has been created'));
                return $this->redirect(['action' => 'login']);

            }
            else {
                $this->Flash->error(__('One or more errors have occurred'));
            }

        }

        $this->set(compact(['user']));
    }

    public function logout() {
        if($this->request->is(['post', 'patch', 'put'])) {

            if($this->Auth->logout()) {
                $this->response = $this->response->withExpiredCookie('user');
                $this->Flash->success(__('You have been successfully logged out!'));
                $this->redirect(['action' => 'login']);
            }

        } else {
            throw new NotFoundException();
        }
    }
}