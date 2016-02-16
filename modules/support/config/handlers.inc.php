<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Support\Config;
use \RS\Event\Manager as EventManager;
use \RS\Router;
use \RS\Orm\Type as OrmType;

class Handlers extends \RS\Event\HandlerAbstract
{
    function init()
    {
        $this
            ->bind('getmenus')
            ->bind('getroute');
    }
    
    /**
    * Возвращает маршруты данного модуля
    */
    public static function getRoute(array $routes) 
    {        
        $routes[] = new \RS\Router\Route('support-front-support', array('/my/support/{Act}/{id}/', '/my/support/'), null, t('Поддержка'));
        return $routes;
    }
    
    /**
    * Возвращает пункты меню этого модуля в виде массива
    * 
    */
    public static function getMenus($items)
    {
        $items[] = array(
                'title' => 'Поддержка',
                'alias' => 'support',
                'link' => '%ADMINPATH%/support-topicsctrl/',
                'typelink' => 'link',                      
                'parent' => 'modules',
                'sortn' => 0
            );
        return $items;
    }
    
}