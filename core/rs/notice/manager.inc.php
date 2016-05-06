<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace RS\Notice;

/**
* Менеджер уведомлений
*/
class Manager
{
    /**
    * Отправляет уведомление по классу событий
    * 
    * @param $notice object событие
    */
    public static function send($notice)
    {
        if ($notice instanceof Types\EmailInterface){ 
            $notice->sendEmail();
        }

        if ($notice instanceof Types\SMSNotice){ 
            // Отправка SMS
        }
    }

}

