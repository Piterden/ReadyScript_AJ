<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Photo\Model;

/**
* Заглушка для фотографий.
* как и объект image имеет возможность возвращать заглушку в нужном размере
* @ingroup Photo
*/
class Stub extends \Photo\Model\Orm\Image
{
    protected
        $render_path = '/storage/photo/stub/',
        $image_path = NOPHOTO_IMAGE,
        $theme_image_path = NOPHOTO_THEME_PATH,
        $theme_image_file = NOPHOTO_THEME_FILE; 
    
    function setDefaultImage($pathToImage)
    {
        $this->image_path = $pathToImage;
    }
    
    function setImageFilename($filename)
    {
        $this->theme_image_file = $filename;
    }
    
    /**
    * Возвращает путь к заглушке, создает ее, если не существует.
    * 
    * @param integer $width
    * @param integer $height
    * @param mixed $type - не используется.
    * @param bool $absolute
    */
    function getUrl($width, $height, $type = null, $absolute = false)
    {
        $theme = \RS\Theme\Manager::getCurrentTheme('theme');
        $cimg = new \RS\Img\Core(\Setup::$ROOT, '', \Setup::$FOLDER.'/storage/photo/stub/resized');
        $url = $cimg->getImageUrl($this->theme_image_file, $width, $height, $theme, $absolute);
        
        return $url;
    }
    
        
    /**
    * Возвращает объект хранилища
    * 
    * @return \RS\Orm\Storage\AbstractStorage
    */
    protected function getStorageInstance()
    {
        return new \RS\Orm\Storage\Stub($this);
    }
}
