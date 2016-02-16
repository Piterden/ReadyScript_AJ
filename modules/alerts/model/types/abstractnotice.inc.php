<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Alerts\Model\Types;

abstract class AbstractNotice{
    
    final function __construct()
    {
        // Перегрузка конструктора невозможна
    }
    
    /**
    * Возвращает краткое описание уведомления
    * @return string
    */
    abstract public function getDescription();

}