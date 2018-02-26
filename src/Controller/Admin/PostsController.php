<?php
/**
 * Created by PhpStorm.
 * User: finleysiebert
 * Date: 25-02-18
 * Time: 23:28
 */

namespace App\Controller\Admin;


use App\Controller\AppController;
use App\Model\Table\PostsTable;
use Cake\Core\Configure;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * @property PostsTable Posts
 */
class PostsController extends AppController
{

    /**
     * @param $postId
     * @return \Cake\Http\Response|null
     */
    public function delete($postId) {

        if($this->request->is(['post', 'patch', 'put'])) {

            $post = $this->Posts->get($postId);
            $post->deleted = true;

            if($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been marked as deleted'));
                return $this->redirect($this->referer());
            }
            else
            {
                $this->Flash->error(__('Something went wrong while deleting this post'));
                return $this->redirect($this->referer());
            }

        }
        else {
            throw new MethodNotAllowedException();
        }


    }

    /**
     * @param $postId
     * @return \Cake\Http\Response|null
     */
    public function recover($postId)
    {
        if ($this->request->is(['post', 'patch', 'put'])) {

            $post = $this->Posts->get($postId);
            $post->deleted = false;

            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been recovered'));
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error(__('Something went wrong while recovering this post'));
                return $this->redirect($this->referer());
            }

        } else {
            throw new MethodNotAllowedException();
        }
    }

}