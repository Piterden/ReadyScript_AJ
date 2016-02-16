<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Site\Config;
use \RS\Router;
use \RS\Orm\Type as OrmType;

class Handlers extends \RS\Event\HandlerAbstract
{
    function init()
    {
        $this
            ->bind('start')
            ->bind('getmenus');
    }
    
    /**
    * Обрабатываем событие запуска скрипта.
    */
    public static function start()
    {
        $site = \RS\Site\Manager::getSite();
        if ($site!== false && $site['redirect_to_main_domain']) {
            //Реализация опции "Перенаправлять на основной домен сайта"
            $domains = $site->getDomainsList();
            if ($domains) {
                $request = \RS\Http\Request::commonInstance();
                $current_domain = $request->server('HTTP_HOST');            
                if ($current_domain != $domains[0]) {
                    //Отдаем 301 редирект с теми же параметрами, но на основной домен
                    $new_url = $site->getAbsoluteUrl( $request->server('REQUEST_URI') );
                    \RS\Application\Application::getInstance()->headers
                        ->addHeader('location', $new_url)
                        ->sendHeaders();
                        
                    exit;
                }
            }
        }
    }
    
    /**
    * Возвращает пункты меню этого модуля в виде массива
    * 
    */
    public static function getMenus($items)
    {
        $items[] = array(
                'title' => 'Настройка сайта',
                'alias' => 'siteoptions',
                'link' => '%ADMINPATH%/site-options/',
                'parent' => 'website',
                'sortn' => 60,
                'typelink' => 'link',
            );
        $items[] = array(
                'title' => 'Сайты',
                'alias' => 'sites',
                'link' => '%ADMINPATH%/site-control/',
                'parent' => 'control',
                'sortn' => 4,
                'typelink' => 'link',
            );            
        
        return $items;
    }
}