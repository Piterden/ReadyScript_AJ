<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Controller\Front;

/**
* Контроллер отвечает за отображение всех брендов в виде алфавита
*/
class AllBrands extends \RS\Controller\Front
{            
    public
        /**
        * @var \Catalog\Model\BrandApi
        */
        $api;
        
    function init()
    {
        $this->api = new \Catalog\Model\BrandApi();
    }
    
    function actionIndex()
    {
        $api = new \Catalog\Model\BrandApi();
        $api->setFilter('public', 1);
        $api->setOrder("title ASC");
        $brands = $api->getList();
        
        //Хлебные крошки
        $this->app->breadcrumbs->addBreadCrumb(t("Производители"), $this->router->getUrl('catalog-front-allbrands'));
        $this->app->title->addSection('Все производители');
            
        $this->view->assign(array(
            'brands' => $brands,         //Товары бренда в спец. категориях
        ));
        
        return $this->result->setTemplate('allbrands.tpl');
    }
}