<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Menu\Controller\Block;
use \RS\Orm\Type;

/**
* Блок - горизонтальное меню
*/
class Menu extends \RS\Controller\StandartBlock
{
    protected static
        $controller_title = 'Меню',
        $controller_description = 'Отображает публичные пункты меню';

    protected
        $default_params = array(
            'indexTemplate' => 'blocks/menu/hor_menu.tpl',
            'root' => 0
        );            
        
    function getParamObject()
    {
        return parent::getParamObject()->appendProperty(array(
            'root' => new Type\String(array(
                'description' => 'Какой элемент принимать за корневой?',
                'list' => array(array('Menu\Model\Api', 'selectList'))
            ))
        ));
    }
    
    function actionIndex()
    {
        //Кэшируем меню для неавторизованных пользователей
        $cache_id = $this->getBlockId().$this->url->parameters('menu_item_id');
        if ($this->user['id']>0 || !$this->view->cacheOn($cache_id)->isCached($this->getParam('indexTemplate')) ) {
            if ($debug_group = $this->getDebugGroup()) {
                $create_href = $this->router->getAdminUrl('add', array(), 'menu-ctrl');
                $debug_group->addDebugAction(new \RS\Debug\Action\Create($create_href));
                $debug_group->addTool('create', new \RS\Debug\Tool\Create($create_href));
            }
            
            $root = $this->getParam('root');
            
            $api = new \Menu\Model\Api();
            $root_orm = $api->getById($root);
            
            $public_menu = new \Menu\Model\Api();
            $public_menu -> setFilter('public', 1);
            $public_menu -> setFilter('menutype', 'user');
            
            $items = $public_menu->getTreeList( (int)$root_orm['id'] );
            $this->view->assign(array(
                'root' => $root_orm,
                'items' => $items
            ));
        }
        
        return $this->result->setTemplate( $this->getParam('indexTemplate') );
    }
    
}