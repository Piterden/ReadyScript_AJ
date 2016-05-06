<?php
namespace Optlists\Config;

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
    public static function getMenus($items){
        $items[] = array(
                'title' => 'Опции',
                'alias' => 'optlists',
                'link' => '%ADMINPATH%/optlists-ctrl/',
                'typelink' => 'link',                
                'parent' => 'products',
                'sortn' => 5
            );
        return $items;
    } 
}
