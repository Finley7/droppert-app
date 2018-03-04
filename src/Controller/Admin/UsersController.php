<?php
/**
 * Created by PhpStorm.
 * User: finleysiebert
 * Date: 12-02-18
 * Time: 18:37
 */

namespace App\Controller\Admin;


use App\Controller\AppController;
use App\Model\Table\BlockedIpsTable;
use App\Model\Table\UsersTable;
use Cake\Core\Configure;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * Class UsersController
 * @package App\Controller\Admin
 * @property UsersTable Users
 * @property BlockedIpsTable BlockedIps
 */
class UsersController extends AppController
{
    /**
     * @param $userId
     * @return \Cake\Http\Response|null
     */
    public function purge($userId) {
        $this->loadModel('BlockedIps');

        if($this->request->is(['post', 'patch', 'put'])) {

            $user = $this->Users->get($userId);
            $user->primary_role = Configure::read('App.ban_role');

            $session = $this->Users->Sessions->findByUserId($user->id)->orderDesc('created')->first();
            $blockedIp = $this->BlockedIps->newEntity();
            $blockedIp->ip_address = $session->ip_address;
            $blockedIp->reason = __('Your account has been purged');

            $posts = [];
            $_posts = $this->Users->Posts->find('all')
                ->where(['user_id' => $user->id]);

            foreach($_posts as $post) {
                $post->deleted = true;
                array_push($posts, $post);
            }

            if($this->Users->save($user) && $this->Users->Posts->saveMany($posts) && $this->BlockedIps->save($blockedIp)) {
                $this->Flash->success(__('The user has been purged'));
                return $this->redirect($this->referer());
            }
            else
            {
                $this->Flash->error(__('Something went wrong.'));
                return $this->redirect($this->referer());
            }

        }
        else {
            throw new MethodNotAllowedException();
        }
    }

    /**
     * @param $userId
     * @return \Cake\Http\Response|null
     */
    public function block($userId) {
        if($this->request->is(['post', 'patch', 'put'])) {

            $user = $this->Users->get($userId);
            $user->primary_role = Configure::read('App.ban_role');


            if($this->Users->save($user)) {
                $this->Flash->success(__('The user has been blocked'));
                return $this->redirect($this->referer());
            }
            else
            {
                $this->Flash->error(__('Something went wrong.'));
                return $this->redirect($this->referer());
            }

        }
        else {
            throw new MethodNotAllowedException();
        }
    }
}