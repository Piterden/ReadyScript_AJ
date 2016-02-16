<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Controller\Front;

/**
* Контроллер - список продукции в категории
* @ingroup Catalog
*/
class ListProducts extends \RS\Controller\Front
{
    const
        DEFAULT_PAGE_SIZE = 12;
        
    public
        $config,
        $query,
        $page,
        $pageSize,
        $search_type,
        $can_rank_sort,
        $items_on_page,
        $cur_sort,
        $cur_n_sort,
        $api,
        $dirapi;
    
    function init()
    {
        $this->api = new \Catalog\Model\Api();     
        $this->dirapi = new \Catalog\Model\Dirapi();
        $this->config = \RS\Config\Loader::byModule($this);
        
        $this->query = $this->url->get('query', TYPE_STRING);
        $this->search_type = \RS\Config\Loader::byModule('search')->searchtype;
        $this->can_rank_sort = ($this->search_type == 'fulltext' && !empty($this->query));

        $items_on_page_str = empty($this->config['items_on_page']) ? $this->config['list_page_size'] : $this->config['items_on_page'];
        $this->items_on_page = preg_split('/[ ]*,[ ]*/', trim($items_on_page_str));
                
        $this->pageSize = $this->url->request('pageSize', TYPE_INTEGER, $this->config['list_page_size']);
        $this->pageSize = $this->url->convert($this->pageSize, $this->items_on_page);
        
        $this->page = $this->url->get('p', TYPE_INTEGER, 1);
        if ($this->page<1) $this->page = 1;
        
        $this->view_as = $this->url->request('viewAs', TYPE_STRING, $this->config['list_default_view_as']);
        
        //Сортировка 
        $allow_sort = array('dateof', 'rating', 'cost', 'title', 'num', 'barcode');
        if ($this->can_rank_sort) {
            $allow_sort[] = 'rank';
        }
        
        $this->cur_sort = $this->url->convert($this->url->request('sort', TYPE_STRING, $this->config['list_default_order']), $allow_sort);
        $this->cur_n_sort = $this->url->convert($this->url->request('nsort', TYPE_STRING, $this->config['list_default_order_direction']), array('desc','asc'));
        
        $cookie_expire = time()+60*60*24*730;
        $cookie_path = $this->router->getUrl('catalog-front-listproducts');
        $this->app->headers
            ->addCookie('viewAs', $this->view_as, $cookie_expire, $cookie_path)
            ->addCookie('pageSize', $this->pageSize, $cookie_expire, $cookie_path)
            ->addCookie('sort', $this->cur_sort, $cookie_expire, $cookie_path)
            ->addCookie('nsort', $this->cur_n_sort, $cookie_expire, $cookie_path);
            
        //Если идет полнотекстовый поиск, то устанавливаем сортировку по-умолчанию - по релевантности
        if ($this->can_rank_sort && !$this->url->get('sort', TYPE_STRING, null) ) {
            $this->cur_sort = 'rank';
            $this->cur_n_sort = 'desc';
        }
        
        \RS\Event\Manager::fire('init.api.'.$this->getUrlName(), $this);
    } 
    
    
    /**
    * Открытие списка товаров
    */
    function actionIndex()
    {  
        $dir = urldecode($this->url->get('category', TYPE_STRING,0));      
        $this->url->saveUrl('catalog.list.index');                       
        $category = $dir ? $this->dirapi->getById($dir) : false;     
        if (!$category) {
            $category = new \Catalog\Model\Orm\Dir();
        }
        $dir_id = $category['id'] ? $category['id'] : false;   

        if (($dir_id !== false) || ($dir==="0" && $this->config['show_all_products']) || $this->url->isKey('query')) {
            if ($this->query != '' || $dir_id || ($this->config['show_all_products'] && !$this->url->isKey('query'))) { //Если есть категория или задан поисковый запрос или флаг отображать /catalog/
                if ($debug_group = $this->getDebugGroup()) {
                    $create_href = $this->router->getAdminUrl('add', array('dir' => $dir_id), 'catalog-ctrl');
                    $debug_group->addDebugAction(new \RS\Debug\Action\Create($create_href));
                    $debug_group->addTool('create', new \RS\Debug\Tool\Create($create_href));
                }              
                
                if ($dir_id) {
                    $this->dirapi->PutInBreadcrumb($dir_id);
                    if ($dir_ids = $this->dirapi->getChildsId($dir_id)) {
                        $this->api->setFilter('dir', $dir_ids, 'in');            
                    }
                }elseif ($dir === 0){
                    $dir_id = 0;
                }                             
                
                if ($this->query != '') {
                    //Если идет поиск по названию
                    $q = $this->api->queryObj();
                    $q->select = 'A.*';
                    $search = \Search\Model\SearchApi::currentEngine();
                    $search->setFilter('B.result_class', 'Catalog\Model\Orm\Product');
                    $search->setQuery($this->query);
                    $search->joinQuery($q);
                    
                    $this->app->breadcrumbs->addBreadCrumb(t('Результаты поиска'), $this->router->getUrl('catalog-front-listproducts', array('query' => $this->query)));
                }
                
                //Загружаем возможные значения характеристик товаров для текущей выборки товаров
                $cache_id = $dir_id.$this->query; //Ключ кэша
                $filter_allowed_values = $this->api->getAllowablePropertyValues($dir_id, $cache_id);
                $filter_brands_values  = $this->api->getAllowableBrandsValues($cache_id);
                
                $this->router->getCurrentRoute()->allowable_values       = $filter_allowed_values;
                $this->router->getCurrentRoute()->allowable_brand_values = $filter_brands_values;
                $this->result->addSection('filter_allowed_values', $filter_allowed_values);
                
                //Згружаем свойства для фильтров
                $filters = $this->url->request('f', TYPE_ARRAY);    
                
                $prop_api = new \Catalog\Model\Propertyapi();
                $prop_list = $prop_api->getGroupProperty($dir_id, true, true);
                
                $pids = $prop_api->getFilteredProductIds($filters);
                
                if ($pids !== false) $this->api->setFilter('id', $pids, 'in');
                
                //Сортировка    
                $sort = array( //Направление сортировки по умолчанию
                    'dateof' => 'desc',   
                    'title' => 'asc',
                    'cost' => 'asc',
                    'rating' => 'desc',
                    'rank' => 'desc',
                    'num' => 'desc',
                    'barcode' => 'asc'
                );
                if ($sort[$this->cur_sort] == $this->cur_n_sort) {
                    $sort[$this->cur_sort] = ($sort[$this->cur_sort] == 'asc') ? 'desc' : 'asc';
                }

                $this->api->setFilter('public', 1);
                
                $config = \RS\Config\Loader::byModule($this);
                if ($config['hide_unobtainable_goods'] == 'Y') {
                    $this->api->setFilter('num', '0', '>');
                }                        
                
                //Запросим данные по максимальной и минимальной цену в категории
                if ($config['price_like_slider']) {
                    /**
                    * @var \RS\Orm\Request
                    */
                    $minmax_query = clone $this->api->queryObj();                                          
                    
                    //Добавим цены к запросу
                    $cost_api = \Catalog\Model\CostApi::getInstance();
                    $money_array = $cost_api->getMinMaxCost($minmax_query);
                    $money_array += array(
                        'step'  => 1,
                        'round' => 1,
                        'unit'  => \Catalog\Model\CurrencyApi::getDefaultCurrency()->stitle,
                        'heterogeneity' => $this->api->getHeterogeneity($money_array['interval_from'], $money_array['interval_to'])
                    );
                
                    //Передадим сведения по фильтру
                    $this->router->getCurrentRoute()->money_array = $money_array;                                    
                }
                
                $basefilter = $this->api->applyBaseFilters();
                $total      = $this->api->getMultiDirCount();                 
                            
                $this->api->queryObj()->groupby($this->api->defAlias().'.id');
                //Устанавливаем сортировку
                $sort_field = $this->cur_sort == 'rank' ? $this->cur_sort : $this->api->defAlias().'.'.$this->cur_sort;
                $this->api->setSortOrder($sort_field, $this->cur_n_sort);
                
                if (!empty($this->query) && $dir == 0) { //Если это результат поиска, 
                    $sub_dirs = $this->api->getDirList(); //Загружаем список категорий, в которых найдены товары
                } else {
                    //Загружаем список подактегорий, у текущей категории
                    $this->dirapi->setFilter('parent', $dir_id);      
                    $this->dirapi->setFilter('public', 1);
                    $sub_dirs = $this->dirapi->getList();
                }                               

                
                $paginator = new \RS\Helper\Paginator($this->page, $total, $this->pageSize);                
                
                if ($this->page > $paginator->total_pages) {
                    $this->page = $paginator->total_pages;
                }
                
                $list = $this->api->getList($this->page, $this->pageSize);
                $list = $this->api->addProductsPhotos($list);
                $list = $this->api->addProductsCost($list);
                $list = $this->api->addProductsProperty($list);
                
                //Заполняем meta - теги
                $path = $this->dirapi->getPathToFirst($dir_id);
                
                foreach($path as $one_dir) {
                    $this->app->title->addSection(!empty($one_dir['meta_title']) ? $one_dir['meta_title'] : $one_dir['name']);
                    $this->app->meta->addKeywords( $one_dir['meta_keywords'] );
                    $this->app->meta->addDescriptions( $one_dir['meta_description'] );
                    $this->app->breadcrumbs->addBreadCrumb($one_dir['name'], $this->url->replaceKey(array('category' => $one_dir['_alias'], 'p' => null)));
                }
                
                //Если заданы значения у категории 
                if ($category['meta_title'] || $category['meta_keywords'] || $category['meta_description']) {   //Если задано мета описание
                    //Вызываем SEO генератор
                    $seoGen = new \Catalog\Model\SeoReplace\Dir(array(
                        $category,
                    ));
                    
                    //Делаем автозамену для SEO
                    if ($category['meta_title']){
                        $category['meta_title'] = $seoGen->replace($category['meta_title']);
                        $this->app->title->clean();
                        $this->app->title->addSection( $category['meta_title'] );
                    }       
                    if ($category['meta_keywords']){
                        $category['meta_keywords']    = $seoGen->replace($category['meta_keywords']);
                        $this->app->meta->cleanMeta('keywords');
                        $this->app->meta->addKeywords( $category['meta_keywords'] );
                    }    
                    if ($category['meta_description']){
                        $category['meta_description'] = $seoGen->replace($category['meta_description']);
                        $this->app->meta->cleanMeta('description');
                        $this->app->meta->addDescriptions( $category['meta_description'] );
                    } 
                }

                $this->view->assign(array(
                    'query' => $this->query,   
                    'can_rank_sort' => $this->can_rank_sort,
                    'dirapi' => $this->dirapi,
                    'path' => $path,
                    'dir' => $dir,
                    'category' => $category,
                    'sub_dirs' => $sub_dirs,
                    'dir_id' => $dir_id,
                    'cur_sort' => $this->cur_sort,
                    'cur_n' => $this->cur_n_sort,
                    'sort' => $sort,
                    'total' => $total,
                    'list' => $list,
                    'view_as' => $this->view_as,
                    'paginator' => $paginator,
                    'prop_list' => $prop_list,
                    'page_size' => $this->pageSize,
                    'items_on_page' => $this->items_on_page,
                    'filter' => $prop_api->cleanNoActiveFilters($filters),
                    'bfilter' => $this->api->getBaseFilters(),
                    'is_filter_active' => ($prop_api->isFilterActive() || $basefilter),
                    'clean_filter_url' => $prop_api->getCleanFilterUrl()
                ));
            } else {
                $this->view->assign(array(
                    'list' => array(),
                    'no_query_error' => true
                ));
            }
        } else {
            $this->e404(t('Такой категории не существует'));
        }
        
        return $this->result->setTemplate('list_products.tpl');
    }
}
