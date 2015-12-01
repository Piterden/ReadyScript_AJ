<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Menu\Config;

/**
* Патчи к модулю
*/
class Patches extends \RS\Module\AbstractPatches
{
    /**
    * Возвращает массив имен патчей.
    * 
    * @return array
    */
    function init()
    {
        return array(
            '20035',
            '20023'
        );
    }
    
    function beforeUpdate20035()
    {
        $menu = new \Menu\Model\Orm\Menu();
        $menu->getPropertyIterator()->append(array(
            'link_template' => new \RS\Orm\Type\Template(array(
                'description' => t('Шаблон'),
                'visible' => false
            )),
        ));
        $menu->dbUpdate();        
    }
    
    function beforeUpdate20023() 
    {
        $menu = new \Menu\Model\Orm\Menu();
        $menu->getPropertyIterator()->append(array(
            'target_blank' => new \RS\Orm\Type\Integer(array(
                'maxLength' => 1,
                'description' => t('Открывать ссылку в новом окне'),
                'checkboxView' => array(1,0),
                'default' => 0,
                'visible' => false
            ))
        ));
        $menu->dbUpdate();
    }
}
