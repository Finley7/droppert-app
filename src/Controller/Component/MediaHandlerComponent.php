<?php
/**
 * Created by PhpStorm.
 * User: finleysiebert
 * Date: 16-02-18
 * Time: 21:57
 */

namespace App\Controller\Component;


use App\Model\Entity\Media;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Log\Log;
use claviska\SimpleImage;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\X264;

/**
 * Class MediaHandlerComponent
 * @package App\Controller\Component
 * @property FFMpeg _ffmpeg
 */
class MediaHandlerComponent extends Component
{
    /**
     * @var array
     */
    private $_allowedMedias;

    /**
     * @var
     */
    private $_ffmpeg;
    private $_watermarkPath = WWW_ROOT . DS . 'img' . DS . 'watermerk.png';

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config); // TODO: Change the autogenerated stub
        $this->_ffmpeg = FFMPEG::create([
            'ffmpeg.binaries' => Configure::read('FFMpeg.ffmpeg'),
            'ffprobe.binaries' => Configure::read('FFMpeg.ffprobe')
        ]);
        $this->_allowedMedias = Configure::read('Files.allowed_content_types');

    }

    /**
     * @param Media $media
     * @param $tmpFile
     */
    public function processAudio(Media $media, $tmpFile) {

        move_uploaded_file($tmpFile, WWW_ROOT . DS . 'media' . DS . 'raw' . DS . $media->filename . '.' . $media->extension);

        if ($media->extension != 'mp3') {
            $audio = $this->_ffmpeg->open(WWW_ROOT . DS . 'media' . DS . 'raw' . DS . $media->filename . '.' . $media->extension);
            $audio
                ->save(new Mp3(), WWW_ROOT . DS . 'media' . DS . 'audio' . DS . $media->filename . '.mp3');

        }
        else
        {
            $file = new File(WWW_ROOT . DS . 'media' . DS . 'raw' . DS . $media->filename . '.mp3');
            $file->copy(WWW_ROOT . DS . 'media' . DS . 'audio' . DS . $media->filename . '.mp3');
        }

    }

    /**
     * @param Media $media
     * @param $tmpFile
     */
    public function processImage(Media $media, $tmpFile)
    {
        move_uploaded_file($tmpFile, WWW_ROOT . DS . 'media' . DS . 'raw' . DS . $media->filename . '.' . $media->extension);

        if($media->extension != 'gif') {
            try {
                $image = (new SimpleImage())

                    ->fromFile(WWW_ROOT . DS . 'media' . DS . 'raw' . DS . $media->filename . '.' . $media->extension)
                    ->autoOrient()
                    ->bestFit(600, 600)
//                    ->overlay($this->_watermarkPath, 'bottom right')
                    ->toFile(WWW_ROOT . DS . 'media' . DS . 'images' . DS . $media->filename . '.png', 'image/png', 70);


            }
            catch (\Exception $e) {
                die($e->getMessage());
            }
        }
        else
        {
            $video = $this->_ffmpeg->open(WWW_ROOT . DS . 'media' . DS . 'raw' . DS . $media->filename . '.' . $media->extension);

            $video->filters()
//                ->watermark($this->_watermarkPath, [
//                    'position' => 'relative',
//                    'bottom' => 1,
//                    'right' => 1
                ->synchronize();

            $video->save(new X264(), WWW_ROOT . DS . 'media' . DS . 'videos' . DS . 'mp4' . DS . $media->filename . '.mp4');
        }

        try {
            $image = (new SimpleImage())
                ->fromFile(WWW_ROOT . DS . 'media' . DS . 'raw' . DS . $media->filename . '.' . $media->extension)
                ->autoOrient()
                ->resize(300)
                ->toFile(WWW_ROOT . DS . 'media' . DS . 'thumbnails' . DS . 'thumb_' . $media->filename . '.png', 'image/png', 40);
        }
        catch(\Exception $e) {
            die($e->getMessage());
        }

    }

    public function processVideo(Media $media, $tmpFile)
    {
        move_uploaded_file($tmpFile, WWW_ROOT . DS . 'media' . DS . 'raw' . DS . $media->filename . '.' . $media->extension);
        $video = $this->_ffmpeg->open(WWW_ROOT . DS . 'media' . DS . 'raw' . DS . $media->filename . '.' . $media->extension);

        $video->filters()
//            ->watermark($this->_watermarkPath, [
//                'position' => 'relative',
//                'bottom' => 10,
//                'right' => 10
//            ])
                ->synchronize();

        $video
            ->frame(TimeCode::fromSeconds(1))
            ->save(WWW_ROOT . DS . 'media' . DS . 'thumbnails' . DS . 'thumb_' . $media->filename . '.png');

        //TODO: bug for webm files and other.
        switch ($media->extension) {

            case 'mp4':
                $video->save(new X264('libmp3lame', 'libx264'), WWW_ROOT . DS . 'media' . DS . 'videos' . DS . 'mp4' . DS . $media->filename . '.mp4');

                break;

            case 'webm':

                $format = new X264();

                $video
                    ->save($format, WWW_ROOT . DS . 'media' . DS . 'videos' . DS . 'mp4' . DS . $media->filename . '.mp4');

                $video->save(new X264(), WWW_ROOT . DS . 'media' . DS . 'videos' . DS . 'mp4' . DS . $media->filename . '.mp4');
                break;

            default:
                $format = new X264();

                $video
                    ->save($format, WWW_ROOT . DS . 'media' . DS . 'videos' . DS . 'mp4' . DS . $media->filename . '.mp4');
                break;
        }

    }

    /**
     * @param Media $media
     * @return bool
     */
    public function isMediaAllowed(Media $media)
    {
        return in_array($media->content_type, $this->_allowedMedias);
    }

    protected function _cleanRaw() {
        $folder = new Folder(WWW_ROOT . DS . 'media' . DS . 'raw', false);
        foreach($folder->find() as $file) {
            $f = new File(WWW_ROOT . DS . 'media' . DS . 'raw' . DS .  $file);
            $f->delete();
        }

        Log::info('Raw folder has been cleared');
    }

    public function isMediaTooBig(Media $media) {
        return ($media->size > Configure::read('Files.max_uploadsize'));
    }
}