<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Alerts\Config;

class Handlers extends \RS\Event\HandlerAbstract
{
    function init()
    {
        $this->bind('getmenus');
        $this->bind('getroute');
        $this->bind('alerts.getsmssenders');
    }
    
    public static function alertsGetSmsSenders($list)
    {
        $list[] = new \Alerts\Model\SMS\SMSUslugi\Sender();
        return $list;
    }
    
    public static function getRoute($routes) 
    {
        return $routes;
    }    
    
    /**
    * Возвращает пункты меню этого модуля в виде массива
    * 
    */
    public static function getMenus($items)
    {
          
       $items[] = array(
                'typelink'  => 'separator',
                'alias'     => 'alerts_separator',
                'parent'    => 'website',
                'sortn'     => 80
            );
       $items[] = array(
                'title'     => t('Уведомления'),
                'alias'     => 'alerts',
                'link'      => '%ADMINPATH%/alerts-ctrl/',
                'typelink'  => 'link',
                'parent'    => 'website',
                'sortn'     => 90
            );
       return $items;
    }

}
