<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Kaptcha\Controller\Front;

class Image extends \RS\Controller\Front
{
    function actionIndex()
    {
        $this->wrapOutput(false);
        $img = new \Kaptcha\Model\Img();
    }
}

