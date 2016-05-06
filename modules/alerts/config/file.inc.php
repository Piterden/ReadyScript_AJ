<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Alerts\Config;
use \RS\Orm\Type;

/**
* Конфигурационный файл модуля
*/
class File extends \RS\Orm\ConfigObject
{

    function _init()
    {
        parent::_init()->append(array(
            'sms_sender_class' => new Type\Varchar(array(
                'description' => t('SMS провайдер'),
                'List' => array(array(new \Alerts\Model\Api(), 'selectSendersList')),
            )),
            'sms_sender_login' => new Type\Varchar(array(
                'description' => t('Логин'),
            )),
            'sms_sender_pass' => new Type\Varchar(array(
                'description' => t('Пароль'),
            )),
        ));        

    }
       
    /**
    * Возвращает значения свойств по-умолчанию
    * 
    * @return array
    */
    public static function getDefaultValues()
    {
        return 
            parent::getDefaultValues() + array(
                'tools' => array(
                    array(
                        'url' => \RS\Router\Manager::obj()->getAdminUrl('ajaxTestSms', array(), 'alerts-ctrl'),
                        'title' => t('Отправить тестовое SMS-сообщение'),
                        'description' => t('Отправляет SMS-сообщение, на номер администратора, указанный в настройках сайта'),
                        'confirm' => t('Вы действительно хотите отправить тестовое SMS-сообщение')
                    ),
                )
            );
    }
}

