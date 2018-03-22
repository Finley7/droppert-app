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
use App\Model\Table\RepliesTable;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * @property RepliesTable Replies
 */
class RepliesController extends AppController
{

    /**
     * @param $replyId
     * @return \Cake\Http\Response|null
     */
    public function delete($replyId) {

        if($this->request->is(['post', 'patch', 'put'])) {

            $post = $this->Replies->get($replyId);
            $post->deleted = true;

            if($this->Replies->save($post)) {
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
     * @param $replyId
     * @return \Cake\Http\Response|null
     */
    public function recover($replyId) {
        if($this->request->is(['post', 'patch', 'put'])) {

            $post = $this->Replies->get($replyId);
            $post->deleted = false;

            if($this->Replies->save($post)) {
                $this->Flash->success(__('The post has been recovered'));
                return $this->redirect($this->referer());
            }
            else
            {
                $this->Flash->error(__('Something went wrong while recovering this post'));
                return $this->redirect($this->referer());
            }

        }
        else {
            throw new MethodNotAllowedException();
        }
    }

}