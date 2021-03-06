<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Article\Controller\Front;

/**
* Контроллер отвечает за список статей/новостей
*/
class PreviewList extends \RS\Controller\Front
{
    const
        SESSION_PAGE_KEY = 'article_previewlist_page';
        
    public
        $api,
        $cat_api;
        
    function init()
    {
        $this->api = new \Article\Model\Api();
        $this->cat_api = new \Article\Model\Catapi();
    }
    
    function actionIndex()
    {
        $category = $this->url->get('category', TYPE_STRING);
        $dir = $this->cat_api->getById($category);
        
        if (!$dir){ //Если категория не найдена
           $this->e404(t('Такой страницы не существует')); 
        }
        
        $page     = $_SESSION[self::SESSION_PAGE_KEY] = $this->url->get('p', TYPE_INTEGER, 1);
        $pageSize = \RS\Config\Loader::byModule('article')->preview_list_pagesize;
        
        //Заполняем хлебные крошки
        $path     = $this->cat_api->getPathToFirst($dir['id']);
        $last_dir = array_pop($path);    
        if (!empty($path)){
           foreach($path as $one_dir) {  
                if ($one_dir['public']) {
                   $this->app->breadcrumbs->addBreadCrumb($one_dir['title'], $one_dir->getUrl()); 
                }   
           } 
        }
        $this->app->breadcrumbs->addBreadCrumb($last_dir['title']); 

        if ($debug_group = $this->getDebugGroup()) {
            $create_href = $this->router->getAdminUrl('add', array('dir' => $dir['id']), 'article-ctrl');
            $debug_group->addDebugAction(new \RS\Debug\Action\Create($create_href));
            $debug_group->addTool('create', new \RS\Debug\Tool\Create($create_href));
        }          
        
        $this->api->setFilter('parent', $this->cat_api->getChildsId($dir['id']), 'in');
        $total = $this->api->getListCount();        
        $list  = $this->api->getList($page, $pageSize);
        
        //Подгрузим подкатегории, если есть такие 
        $this->cat_api->setFilter("public", 1);
        $this->cat_api->setOrder('title ASC');
        $sub_dirs = $this->cat_api->getTreeList($dir['id']);
        
        if (empty($list) && $page>1){
           $this->e404(t('Такой страницы не существует'));
        }
        
        $this->app->title
            ->addSection($dir['meta_title'] ?: $dir['title']);
                
        $this->app->meta
            ->addKeywords($dir['meta_keywords'])
            ->addDescriptions($dir['meta_description']);

        $paginator = new \RS\Helper\Paginator($page, $total, $pageSize);
        $this->view->assign(array(
            'dir' => $dir,             //Категория
            'dirlist' => $sub_dirs,   //Подкатегрии если есть
            'paginator' => $paginator, //html пагинации       
            'list' => $list            //Список статей
        ));
        
        return $this->result->setTemplate('preview_list.tpl');
    }
}
