<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Controller\Front;

/**
* Контроллер отвечает за просмотр сведений о складе
*/
class Warehouse extends \RS\Controller\Front
{            
    public
        $api;
        
    function init()
    {
        $this->api = new \Catalog\Model\WareHouseApi(); 
    }
    
    function actionIndex()
    {
        $id = $this->url->get('id', TYPE_STRING,false);
        $warehouse = $this->api->getById($id, "alias");
        
        if (!$warehouse) $this->e404(t('Склада с таким именем не существует'));
              
        //Хлебные крошки
        $this->app->breadcrumbs
            ->addBreadCrumb($warehouse['title']);
        
        $this->app->title->addSection($warehouse['title']);
        
        $this->view->assign(array(
            'warehouse' => $warehouse    //Склад
        ));
        
        return $this->result->setTemplate('warehouse.tpl');
    }
    
    
}
