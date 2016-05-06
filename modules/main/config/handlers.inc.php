<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Main\Config;
use \RS\Router;

class Handlers extends \RS\Event\HandlerAbstract
{
    function init()
    {
        $this
            ->bind('getmenus')
            ->bind('geoip.getservices')
            ->bind('getroute');
    }
    
    public static function getRoute(array $routes) 
    {        
        $routes[] = new \RS\Router\Route('main.image', '/storage/system/resized/{type}/{picid}\.{ext}$', array(
            'controller' => 'main-front-photohandler'
        ), t('Изображение для ORM-объектов'), true);
                
        $routes[] = new Router\Route('main.index', "/", array(
                'controller' => 'main-front-stub'
            ),
            t('Главная страница'),false, '^{pattern}$'
        );
        
        $routes[] = new Router\Route('main-front-cmssign', '/cms-sign/', null, t('Отпечаток CMS'), true);
        
        return $routes;
    }
    
    
    /**
    * Возвращает пункты меню этого модуля в виде массива
    * 
    */
    public static function getMenus($items)
    {
        $items[] = array(
                'title' => 'Настройка системы',
                'alias' => 'systemoptions',
                'link' => '%ADMINPATH%/main-options/',
                'parent' => 'control',
                'sortn' => 0,
                'typelink' => 'link',
            );            
        if (!defined('CANT_MANAGE_LICENSE')) {
            $items[] = array(
                'title' => 'Лицензии',
                'alias' => 'license',
                'link' => '%ADMINPATH%/main-license/',
                'parent' => 'control',
                'sortn' => 1,
                'typelink' => 'link',
            );
        }
        $items[] = array(
                'typelink' => 'separator',
                'alias' => 'afterupdate',
                'parent' => 'control',
                'sortn' => 3,
            );  
        return $items;
    }
    
    
    /**
    * Возвращает список сервисов для определения Геопозиции по IP
    * 
    * @param \Main\Model\GeoIp\IpGeoBase[] $list
    * @return \Main\Model\GeoIp\IpGeoBase[]
    */
    public static function GeoIpGetservices($list)
    {
        $list[] = new \Main\Model\GeoIp\IpGeoBase();
        $list[] = new \Main\Model\GeoIp\Dadata();
        return $list;
    }    

    
}
