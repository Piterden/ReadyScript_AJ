<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Menu\Config;
use \RS\Router;

class Handlers extends \RS\Event\HandlerAbstract
{
    function init()
    {
        $this
            ->bind('getmenus')
            ->bind('getroute')
            ->bind('getpages');
    }
    
    public static function getRoute($routes) 
    {
        if (\RS\Site\Manager::getSite() !== false) {
            $api = new \Menu\Model\Api();
            $api->setFilter('menutype', 'user');
            $list = $api->getList();
            
            foreach($list as $item) {
                if ($item['typelink'] != \Menu\Model\Orm\Menu::TYPELINK_SEPARATOR 
                    && $item['typelink'] != \Menu\Model\Orm\Menu::TYPELINK_LINK) 
                {
                    $path_str = '';
                    $sections = '';
                    foreach($api->getPathToFirst($item['id']) as $one) {
                        $path_str .= ' > '.$one['title'];
                        if (!$one['hide_from_url']) {
                            $sections .= '/'.str_replace(' ','-', $one['alias']);
                        }
                    }
                    
                    if ($sections !== '') {
                        $routes[] = new Router\Route(
                            'menu.item_'.$item['id'],
                            $sections.'/',
                            array(
                                'controller' => 'menu-front-menupage',
                                'menu_item_id' => $item['id'],
                                'menu_object' => $item
                            ),
                            t('Меню').$path_str,
                            false,
                            '^{pattern}$'
                        );
                    }
                }
            }
            
            return $routes;
        }
    }
    
    public static function getPages($urls)
    {
        $api = new \Menu\Model\Api();
        $api->setFilter('public', 1);
        $api->setFilter('menutype', 'user');
        $list = $api->getList();
        $local_urls = array();
        foreach($list as $item) {
            $url = $item->getHref();
            $local_urls[$url] = array(
                'loc' => $url
            );
        }
        $urls = array_merge($urls, array_values($local_urls));
        return $urls;
    }
    
    /**
    * Возвращает пункты меню этого модуля в виде массива
    * 
    */
    public static function getMenus($items)
    {
        $items[] = array(
                'title' => 'Веб-сайт',
                'alias' => 'website',
                'link' => '%ADMINPATH%/menu-ctrl/',
                'sortn' => 30,
                'typelink' => 'link',
                'parent' => 0
            );
        $items[] = array(
                'title' => 'Управление',
                'alias' => 'control',
                'link' => '%ADMINPATH%/main-options/',
                'sortn' => 40,
                'typelink' => 'link',
                'parent' => 0
            );
        $items[] = array(
                'title' => 'Разное',
                'alias' => 'modules',
                'link' => 'JavaScript:;',
                'sortn' => 50,
                'typelink' => 'link',
                'parent' => 0
            );            
        $items[] = array(
                'title' => 'Меню',
                'alias' => 'menu',
                'link' => '%ADMINPATH%/menu-ctrl/',
                'parent' => 'website',
                'sortn' => 0,
                'typelink' => 'link',
                'parent' => 'website'
            );            
        $items[] = array(
                'title' => 'Пользователи',
                'alias' => 'userscontrol',
                'link' => '%ADMINPATH%/users-ctrl/',
                'sortn' => 6,
                'parent' => 'control',
                'typelink' => 'link',
                'parent' => 'control'
            );            
        
        return $items;
    }
}

