<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Controller\Admin;

/**
* Содержит действия по обслуживанию
*/
class Tools extends \RS\Controller\Admin\Front
{
    function actionAjaxCleanProperty()
    {
        $api = new \Catalog\Model\PropertyApi();
        $count = $api->cleanUnusedProperty();
        
        return $this->result->setSuccess(true)->addMessage(t('Удалено %0 характеристик', array($count)));
    }
    
    function actionAjaxCheckAliases()
    {
        $api = new \Catalog\Model\Api();
        $product_count = $api->addTranslitAliases();

        $dir_api = new \Catalog\Model\Dirapi();
        $dir_count = $dir_api->addTranslitAliases();
        
        return $this->result->setSuccess(true)->addMessage(t('Обновлено %0 товаров, %1 категорий', array($product_count, $dir_count)));
    }
    
    function actionAjaxCleanOffers()
    {
        $api = new \Catalog\Model\OfferApi();
        $delete_count = $api->cleanUnusedOffers();
        
        return $this->result->setSuccess(true)->addMessage(t('Удалено %0 комплектаций', array($delete_count)));
    }
    
    function actionajaxReIndexProducts()
    {
        $config = $this->getModuleConfig();
        $property_index = in_array('properties', $config['search_fields']);
        
        $api = new \Catalog\Model\Api();
        $count = 0;
        $page = 1;
        while($list = $api->getList($page, 200)) {
            if ($property_index) {
                $list = $api->addProductsProperty($list);
            }
            
            foreach($list as $product) {
                $product->updateSearchIndex();
            }
            $count += count($list);
            $page++;
        }
        
        return $this->result->setSuccess(true)->addMessage(t('Обновлено %0 товаров', array($count)));
    }
}