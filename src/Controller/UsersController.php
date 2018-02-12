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
    }

    public function login() {

        if($this->request->is('post')) {

            $user = $this->Auth->identify();

            if($user) {

                $this->Cookie->configKey('uuid', ['expires' => '+120 days', 'httpOnly' => true]);
                $this->Cookie->write('uuid', ['id' => $user['session']->id]);

                $this->Flash->success(__('Login was successfull'));

            }

            debug($user);
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