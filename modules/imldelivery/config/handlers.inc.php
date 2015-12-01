<?php
namespace Imldelivery\Config;

/**
* Класс содержит обработчики событий, на которые подписан модуль
*/
class Handlers extends \RS\Event\HandlerAbstract
{
    /**
    * Добавляет подписку на события
    * 
    * @return void
    */
    function init()
    {
        $this
            ->bind('delivery.gettypes')
            ->bind('getroute')  //событие сбора маршрутов модулей
            ->bind('getmenus'); //событие сбора пунктов меню для административной панели
    }
    
    /**
    * Возвращает маршруты данного модуля. Откликается на событие getRoute.
    * @param array $routes - массив с объектами маршрутов
    * @return array of \RS\Router\Route
    */
    public static function getRoute(array $routes) 
    {        
        $routes[] = new \RS\Router\Route('imldelivery-front-ajaxctrl',
        array(
            '/imldelivery/'
        ), null, 'Ajax шлюз модуля imldelivery');
        
        return $routes;
    }

    /**
    * Возвращает пункты меню этого модуля в виде массива
    * @param array $items - массив с пунктами меню
    * @return array
    */
    public static function getMenus($items)
    {
        $items[] = array(
            'title' => 'Пункт модуля imldelivery',
            'alias' => 'imldelivery-control',
            'link' => '%ADMINPATH%/imldelivery-control/',
            'parent' => 'modules',
            'sortn' => 40,
            'typelink' => 'link',
        );
        return $items;
    }

    /**
    * Возвращает процессоры(типы) доставки, присутствующие в текущем модуле
    * 
    * @param array $list - массив из передаваемых классов доставок
    */
    public static function deliveryGetTypes($list)
    {        
        $list[] = new \Imldelivery\Model\DeliveryType\Iml();
        return $list;
    }
}