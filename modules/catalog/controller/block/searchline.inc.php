<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Controller\Block;
use \RS\Orm\Type;

/**
* Блок-контроллер Поиск по товарам
*/
class SearchLine extends \RS\Controller\StandartBlock
{
    protected static
        $controller_title = 'Поиск товаров по названию',
        $controller_description = 'Отображает форму для поиска товаров по ключевым словам';

    protected
        $action_var = 'sldo',
        $default_params = array(
            'searchLimit' => 5,
            'hideAutoComplete' => 0,
            'indexTemplate' => 'blocks/searchline/searchform.tpl',
        );      
        
    function getParamObject()
    {
        return parent::getParamObject()->appendProperty(array(
            'hideAutoComplete' => new Type\Integer(array(
                'description' => t('Отключить подсказку результатов поиска в выпадающем списке'),
                'checkboxView' => array(1,0)
            )),
            'searchLimit' => new Type\Integer(array(
                'description' => t('Количество результатов в выпадающем списке')
            ))
        ));
    }   

    function actionIndex()
    {             
        $query = trim($this->url->get('query', TYPE_STRING));      
        if ($this->router->getCurrentRoute() && $this->router->getCurrentRoute()->getId() == 'catalog-front-listproducts' && !empty($query)) {
            $this->view->assign('query', $query);
        }
        return $this->result->setTemplate( $this->getParam('indexTemplate') );
    }
    
    function actionAjaxSearchItems()
    {
        $query = trim($this->url->request('term', TYPE_STRING));
        $result_json = array();        

        if (!empty($query)){
            $api = new \Catalog\Model\Api();
            
            //Если идет поиск по названию
            $q = $api->queryObj();
            $q->select = 'A.*';
            
            $search = \Search\Model\SearchApi::currentEngine();
            $search->setQuery($query);
            $search->joinQuery($q); 
            
            $api->setFilter('public', 1);
                
            if ($this->getModuleConfig()->hide_unobtainable_goods == 'Y') {
                $api->setFilter('num', '0', '>');
            } 
            
            $list = $api->getList(1, $this->getParam('searchLimit'));
            $list = $api->addProductsPhotos($list);
            $list = $api->addProductsCost($list);
            
            $shop_config = \RS\Config\Loader::byModule('shop');
            
            foreach($list as $product){
                $price = ($shop_config->check_quantity && $product->num<1) ? t('Нет в наличии') : $product->getCost().' '.$product->getCurrency();
                
                $result_json[] = array(
                    'value' => $product->title,
                    'label' => preg_replace("%($query)%iu", '<b>$1</b>', $product->title),
                    'barcode' => preg_replace("%($query)%iu", '<b>$1</b>', $product->barcode),
                    'image' => $product->getMainImage()->getUrl(62, 62, "xy"),
                    'price' => $price,
                    'url' => $product->getUrl()
                );
            }
        }
        
        $this->app->headers->addHeader('content-type', 'application/json');
        return json_encode($result_json);
    }
}