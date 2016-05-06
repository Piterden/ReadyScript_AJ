<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Alerts\Model\Types;

interface InterfaceEmail
{
    /**
    * Возвращает путь к шаблону письма
    * @return string
    */
    public function getTemplateEmail();
    
    /**
    * Возвращает объект NoticeData
    * @return \Alerts\Model\Types\NoticeDataEmail
    */
    public function getNoticeDataEmail();
}