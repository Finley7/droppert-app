<?php
/**
 * Created by PhpStorm.
 * User: finleysiebert
 * Date: 12-02-18
 * Time: 17:44
 */

namespace App\Controller;
use App\Model\Entity\User;
use App\Model\Table\MediaTable;
use App\Model\Table\PostsTable;
use Cake\Cache\Cache;
use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Http\Cookie\Cookie;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\Routing\Router;
use Cake\Utility\Text;


/**
 * @property PostsTable Posts
 */
class PostsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['index', 'view', 'media']);
        return parent::beforeFilter($event); // TODO: Change the autogenerated stub
    }

    public function index() {

        $posts = $this->Posts->find('all', [
          'contain' => ['Media']
        ])
            ->where(['deleted' => false])
            ->orderDesc('Posts.created');


        $this->set(compact(['posts']));
    }

    public function add() {
        $post = $this->Posts->newEntity();
        $savedMedia = $this->request->getCookie('media');
        $_passedMedias = [];

        if(is_null($savedMedia) || empty($savedMedia)) {
            throw new FatalErrorException('There is no media to post, please upload media first');
        }

        if($this->request->is('post')) {

            if(!empty($this->request->getData()['media'])) {

                $post = $this->Posts->patchEntity($post, $this->request->getData());
                $post->user_id = $this->Auth->user('id');

                if($this->request->getData()['nsfw'] && !$post->isNSFW()) {
                    $post->tags .= ',nsfw';
                }

                // Handle all selected medias and put them in a query.
                if ($this->Posts->save($post)) {

                    foreach ($this->request->getData()['media'] as $mediaId) {
                        $media = $this->Posts->Media->get($mediaId);
                        $media->user_id = $this->Auth->user('id');
                        $media->post_id = $post->id;

                        if ($this->Posts->Media->save($media)) {
                            array_push($_passedMedias, $media);
                        }
                    }

                    // Post has been saved. Now remove them from the temporary media cookie.
                    return $this->response
                        ->withExpiredCookie('media')
                        ->withLocation(Router::url(['action' => 'view', $post->id, $post->slug]));
                }
            }
            else
            {
                $this->Flash->error(__('You must at least select one media item'));
            }

        }

        $this->set(compact(['post', 'savedMedia']));
    }

    public function view($id = null, $slug = null) {

        $post = $this->Posts->get($id, [
            'contain' => [
                'Users',
                'Replies' => function(Query $q) {
                        return $q
                            ->contain(['Users'])
                            ->orderDesc('Replies.created');
                    }
                ,
                'Ratings', 'Media']
        ]);

        if($post->deleted && !(new User($this->Auth->user()))->hasRole('admin')) {
            throw new NotFoundException();
        }

        if($post->deleted && (new User($this->Auth->user()))->hasRole('admin')) {
            $this->Flash->error(__('You are watching a deleted post'));
        }

        $reply = $this->Posts->Replies->newEntity();

        if($this->request->is(['post', 'patch', 'put'])) {

            $reply = $this->Posts->Replies->patchEntity($reply, $this->request->getData());

            $reply->post_id = $post->id;
            $reply->user_id = $this->Auth->user('id');

            if($this->Posts->Replies->save($reply)) {
                $this->Flash->success(__('Yay, your reply has been posted'));
                return $this->redirect(['action' => 'view', $post->id, $post->slug]);
            }
            else
            {
                $this->Flash->error(__('Something went wrong while submitting your reply'));
            }

        }

        $tags = explode(',', $post->tags);

        $this
            ->viewBuilder()->setLayout('view-post');
        $this
            ->set(compact(['post', 'tags', 'reply']));

    }

//    public function alltime() {
//
//
//        $rating = $this->Posts->Ratings->find('all')
//            ->where(['type' => 'YAY']);
//
//
//        $this->viewBuilder()->setTemplate('index');
//        $this->set(compact(['rating']));
//
//    }

    public function toggleNswf() {

        if($this->request->is(['put', 'patch', 'post'])) {

            if(!is_null($this->request->getCookie('site'))) {

                $nswfSetting = json_decode(json_encode($this->request->getCookie('site')));
                $nswfSetting->NSFW = !$nswfSetting->NSFW;

                $cookie = (new Cookie('site'))
                    ->withExpiry(new \DateTime('+1 year'))
                    ->withHttpOnly(true)
                    ->withPath('/')
                    ->withValue(json_encode(['NSFW' => $nswfSetting->NSFW]));

                $this->Flash->success(
                    __('You are now seeing {0} NSFW content',
                        ($nswfSetting->NSFW) ? '' : 'no' )
                );

                return $this->redirect($this->referer())->withCookie($cookie);

            }

        }

        throw new MethodNotAllowedException();

    }

//    public function media($id){
//
//        // Receive media from table
//        $media = $this->Posts->Media->get($id);
//
//
//        // Check if body has been set in cache
//        if(!($body = Cache::read('media' . '-' . $media->extension . '-' . $media->id))) {
//
//            // Load file based on media.
//            switch ($media->extension) {
//
//                case (preg_match('/mp4/', $media->extension)) ? true : false:
//                    $file = new File(WWW_ROOT . 'media' . DS . 'videos' . DS . 'mp4' . DS . $media->filename . '.mp4', false);
//                break;
//
//                case (preg_match('/mp3/', $media->extension)) ? true : false:
//                    $file = new File(WWW_ROOT . 'media' . DS . 'audio' . DS . $media->filename . '.mp3', false);
//                break;
//
//                case (preg_match('/png|jpg|jpeg|gif/', $media->extension)) ? true : false:
//                    $file = new File(WWW_ROOT . 'media' . DS . 'images' . DS . $media->filename . '.png', false);
//                break;
//            }
//
//            // Check if file exists, if it does, put it in body variable
//            // and create the cache key.
//            // Else show 404 error image.
//            if($file->exists()) {
//                $body = $file->read();
//                Cache::write('media' . '-' . $media->extension . '-' . $media->id, $body);
//            }
//            else
//            {
//                $file = new File(WWW_ROOT . 'media' . DS . 'not-found.png', false);
//                return $this->response
//                ->withStringBody($file->read())
//                ->withType('image/jpeg');
//            }
//
//        }
//
//        // Return body with content
//        return $this->response
//            ->withStringBody($body)
//            ->withType($media->content_type);
//
//    }
}