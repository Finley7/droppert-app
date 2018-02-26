<?php
namespace App\Shell;

use App\Model\Table\MediaTable;
use Cake\Console\Shell;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

/**
 * FaultyMedia shell command.
 * @property MediaTable Media
 */
class FaultyMediaShell extends Shell
{

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->loadModel('Media');
    }

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {

    }

    public function cleanOldMedia() {

        $_media = $this->Media->find('all');

        foreach($_media as $media) {
            if(is_null($media->post_id) && $media->created < new \DateTime('-24 hours')) {
                if($this->Media->delete($media)) {

                    switch ($media->extension) {

                        case preg_match("/mp3|wav/", $media->extension) ? true : false:
                            $file = new File(WWW_ROOT . DS . 'media' . DS . 'audio' . DS . $media->filename . '.mp3');
                            break;
                        case preg_match("/png|jpeg|jpg|gif/", $media->extension) ? true : false:
                            $file = new File(WWW_ROOT . DS . 'media' . DS . 'images' . DS . $media->filename . '.png');
                            break;
                        case preg_match("/mp4|mpeg|webm/", $media->extension) ? true : false:
                            $file = new File(WWW_ROOT . DS . 'media' . DS . 'videos' . DS . 'mp4' . DS . $media->filename . '.mp4');
                            break;
                    }

                    debug($file);

                    $this->success(__('Deleted entity {0}', $media->id));

                    if($file->exists()) {
                        if ($file->delete()) {
                            $this->success(__('Deleted file {0}', $media->filename . '.' . $media->extension));
                            $this->out('-------');
                        }
                    }
                    else {
                        $this->err(__('Media file {0} does not exists', $media->filename . '.' . $media->extension));
                        $this->out('--------');
                    }
                }
            }
        }
    }

    public function cleanRawMedia() {

        $folder = new Folder(WWW_ROOT . DS . 'media' . DS . 'raw');
        $count = 0;

        foreach($folder->find() as $fileName) {
            $file = new File(WWW_ROOT . 'media' . DS . 'raw' . DS . $fileName);

            if($file->exists()) {
                $file->delete();
                $count++;
            }
        }

        $this->success(__('Deleted {0} raw media', $count));

    }
}
