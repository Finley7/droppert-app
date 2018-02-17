<?php
/**
 * Created by PhpStorm.
 * User: finleysiebert
 * Date: 14-02-18
 * Time: 23:24
 */

namespace App\Controller\Ajax;


use App\Controller\AppController;
use App\Controller\Component\MediaHandlerComponent;
use App\Model\Table\MediaTable;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Http\Cookie\Cookie;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Routing\Router;
use Cake\Utility\Security;
use claviska\SimpleImage;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;

/**
 * @property MediaTable Media
 * @property MediaHandlerComponent MediaHandler
 */
class MediaController extends AppController
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['add']);
        return parent::beforeFilter($event); // TODO: Change the autogenerated stub
    }

    public function add()
    {

        $this->loadComponent('MediaHandler');

        $tempMediaCookie = null;

        if($this->request->is('ajax')) {
            $files = $this->request->getData();

            $_deniedMedias = [];
            $_acceptedMedias = [];

            foreach($files as $key => $file) {
                if(preg_match('/file_[a-zA-Z0-9]/xs', $key)) {

                    $media = $this->Media->newEntity();
                    $media->name = pathinfo($files[$key]['name'], PATHINFO_FILENAME);
                    $media->filename = bin2hex(Security::randomBytes(8));
                    $media->extension = pathinfo($files[$key]['name'], PATHINFO_EXTENSION);
                    $media->size = $files[$key]['size'];
                    $media->post_id = null;
                    $media->user_id = (!is_null($this->Auth->user('id'))) ? $this->Auth->user('id') : null;
                    $media->content_type = trim(shell_exec('file --brief --mime-type ' . escapeshellarg($files[$key]['tmp_name'])));

                    if($this->MediaHandler->isMediaAllowed($media) && !$this->MediaHandler->isMediaTooBig($media)) {

                        $tmpFile = $files[$key]['tmp_name'];

                        if($this->Media->save($media)) {
                            switch ($media->extension) {

                                case preg_match("/mp3|webm|wav/", $media->extension) ? true : false:
                                    $this->MediaHandler->processAudio($media, $tmpFile);
                                    break;
                                case preg_match("/png|jpeg|jpg|gif/", $media->extension) ? true : false:
                                    $this->MediaHandler->processImage($media, $tmpFile);
                                    break;
                                case preg_match("/mp4|mpeg|webm/", $media->extension) ? true : false:
                                    $this->MediaHandler->processVideo($media, $tmpFile);
                                    break;
                            }
                        }

                        array_push($_acceptedMedias, $media);
                    }
                    else
                    {
                        $media->reason = __('Invalid type or file is too big');
                        array_push($_deniedMedias, $media);
                    }


                }
            }

            $tempMediaCookie = (new Cookie('media'))
                ->withPath('/')
                ->withExpiry(new \DateTime('+7 days'))
                ->withValue(json_encode($_acceptedMedias))
                ->withHttpOnly(true);

            $response = [
                'uploaded' => $_acceptedMedias,
                'denied' => $_deniedMedias,
                'action' => is_null($this->Auth->user('id')) ? Router::url([
                    'controller' => 'Users',
                    'action' => 'register',
                    'src' => 'with-media',
                    'prefix' => false,
                ]) : Router::url(['controller' => 'Posts', 'action' => 'add', 'prefix' => false]),
            ];
        }
        else
        {
            throw new MethodNotAllowedException(__('AJAX Requests only!'));
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);

        return $this->response
            ->withCookie($tempMediaCookie)
            ->withStringBody(json_encode($response));
    }
}