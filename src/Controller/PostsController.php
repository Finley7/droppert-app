<?php
/**
 * Created by PhpStorm.
 * User: finleysiebert
 * Date: 12-02-18
 * Time: 17:44
 */

namespace App\Controller;
use App\Model\Table\PostsTable;


/**
 * @property PostsTable Posts
 */
class PostsController extends AppController
{
    public function index() {

        $posts = $this->Posts->find('all');

        $this->set(compact(['posts']));
    }
}