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
}