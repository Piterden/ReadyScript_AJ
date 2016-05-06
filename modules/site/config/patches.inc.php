<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Site\Config;

/**
* Патчи к модулю
*/
class Patches extends \RS\Module\AbstractPatches
{
    /**
    * Возвращает список имен существующих патчей
    */
    function init()
    {
        return array(
            '20027',
            '20021'
        );
    }
    
    function beforeUpdate20027()
    {
        $site_config = new \Site\Model\Orm\Config();
        $site_config->getPropertyIterator()->append(array(
            t('Основные'),
            'favicon' => new \RS\Orm\Type\File(array(
                'description' => t('Иконка сайта 16x16 (PNG, ICO)'),
                'hint' => t('Отображается возле заголовка страницы в браузерах'),
                'max_file_size' => 100000, //100 Кб
                'allow_file_types' => array('image/png', 'image/x-icon'),
                'storage' => array(\Setup::$ROOT, \Setup::$FOLDER.'/storage/favicon/')
            ))
        ));
        $site_config->dbUpdate();
    }
    
    function beforeUpdate20021()
    {
        $site = new \Site\Model\Orm\Site();
        $site->getPropertyIterator()->append(array(
            'redirect_to_main_domain' => new \RS\Orm\Type\Integer(array(
                'description' => t('Перенаправлять на основной домен'),
                'allowEmpty' => false,
                'checkboxView' => array(1,0),
                'hint' => t('Если включено, то при обращении к НЕ основному домену будт происходить 301 редирект на основной домен')
            )),
        ));
        $site->dbUpdate();
    }
}
?>
