<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Controller\Front;

/**
* Просмотр одного товара
*/
class Product extends \RS\Controller\Front
{
    protected
        $id,
        $api,
        $dirapi,
        $lastpage;
    
    function init()
    {
        $this->id     = $this->url->get('id', TYPE_STRING);
        $this->api    = new \Catalog\Model\Api();
        $this->dirapi = new \Catalog\Model\Dirapi();
    }
    
    /**
    * Обычный просмотр товара
    */
    function actionIndex()
    {   
        /**
        * @var \Catalog\Model\Orm\Product
        */
        $item = $this->api->getById($this->id);
        if (!$item) $this->e404(t('Такого товара не существует'));
        $config = $this->getModuleConfig();
        if (!$item['public'] || ($config['hide_unobtainable_goods'] == 'Y' && $item['num']<=0)) $this->e404();
        
        
        $item->fillCategories();        
        $item->fillCost();
        $item->fillProperty();
        $item->fillOffers();
        $item->calculateUserCost();     
        
        $this->router->getCurrentRoute()->product = $item; //Прикрепляем к маршруту загруженый объект товара
        
        if ($debug_group = $this->getDebugGroup()) {
            $edit_href = $this->router->getAdminUrl('edit', array('id' => $item['id']), 'catalog-ctrl');
            $debug_group->addDebugAction(new \RS\Debug\Action\Edit($edit_href));
            $debug_group->addTool('create', new \RS\Debug\Tool\Edit($edit_href));
        } 
        
        //Находим путь к товару
        $dir_id = $this->dirapi->getBreadcrumbDir();
        if ($dir_id) {
            $dir_obj = $this->dirapi->getById($dir_id);
            if ($dir_obj) {
                $path = $item->getItemPathLine( $dir_obj['id'] );
            }        
        }else{
            $dir_obj = $this->dirapi->getById($item['maindir']);
        }
        
        if (!isset($path)) {
            $path = $this->dirapi->getPathToFirst( $item['maindir'] );
        }
        
        foreach($path as $dir) {
            $this->app->breadcrumbs->addBreadCrumb($dir['name'], $dir->getUrl());
        }
        
        $this->view->assign('path', $path);
        $this->view->assign('product', $item);
        $this->view->assign('back_url', $this->url->getSavedUrl('catalog.list.index'));
        
        //Заполняем meta теги
        $item_keywords = $item->getMetaKeywords();
        $item_description = $item->getMetaDescription();
        
        foreach($path as $one_dir) {
            $seoGen = new \Catalog\Model\SeoReplace\Dir(array(
                $one_dir,
            ));
            if ($config['concat_dir_meta']) {
                $this->app->title->addSection(!empty($one_dir['meta_title']) ? $seoGen->replace($one_dir['meta_title']) : $one_dir['name']);
            }
            if ($config['concat_dir_meta'] || !$item_keywords) {
                $this->app->meta->addKeywords( !empty($one_dir['meta_keywords']) ? $seoGen->replace($one_dir['meta_keywords']) : $one_dir['name'] );
            }
        }
        
        //Инициализируем SEO генератор
        $seoGen = new \Catalog\Model\SeoReplace\Product(array(
            $item,
            'cat_' => $dir_obj,
            'price' => $item->getCost(),
        ));
        
        if ($item['meta_title']) {
            $item['meta_title'] = $seoGen->replace($item['meta_title']);
            $this->app->title->clean();
        }
        
        if ($item['meta_keywords']) {
            $item_keywords = $seoGen->replace($item['meta_keywords']);
            $this->app->meta->cleanMeta('keywords');
        }
        
        if ($item['meta_description']) {
            $item_description   = $seoGen->replace($item['meta_description']);
            $this->app->meta->cleanMeta('description');
        }
        
        $this->app->title->addSection(!empty($item['meta_title']) ? $item['meta_title'] : $item['title']);
        $this->app->meta->addKeywords($item_keywords);
        $this->app->meta->addDescriptions($item_description);        
        
        //Пишем лог
        \Users\Model\LogApi::appendUserLog(new \Catalog\Model\Logtype\ShowProduct(), $item['id'], null, $item['id']);
        return $this->result->setTemplate( 'product.tpl' );
    }
    
}