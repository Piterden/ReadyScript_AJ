<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
/**
* Класс, отвечающий за отдачу фотографий, по адресу /storage/system/resized/%ФОРМАТ%/ИмяКартинки.jpg
*/
namespace Banners\Controller\Front;

class PhotoHandler extends \RS\Img\Handler\AbstractHandler
{
    protected 
        $srcFolder = '/storage/banners/original',
        $dstFolder = '/storage/banners/resized';
}