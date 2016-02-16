<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace SiteUpdate\Config;

/**
* Класс предназначен для объявления событий, которые будет прослушивать данный модуль и обработчиков этих событий.
*/
class Handlers extends \RS\Event\HandlerAbstract
{
    function init()
    {
        $this->bind('getmenus');
    }
    
    /**
    * Возвращает пункты меню этого модуля в виде массива
    * 
    */
    public static function getMenus($items)
    {
       $items[] = array(
                'title' => 'Центр обновления',
                'alias' => 'siteupdate',
                'link' => '%ADMINPATH%/siteupdate-wizard/',
                'parent' => 'control',
                'sortn' => 2,
                'typelink' => 'link',
            );
        return $items;
    }
    
}