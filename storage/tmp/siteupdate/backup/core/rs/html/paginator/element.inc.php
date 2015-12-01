<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Html\Paginator;

class Element extends \RS\Html\AbstractHtml
{
    public
        $tpl = 'system/admin/html_elements/paginator/paginator.tpl',
        $page_key = 'p',
        $pagesize_key = 'perpage',
        $page_count,
        $total,
        $page_size = 20,
        $url_pattern,
        $page;        

    
    function __construct($total = null, $urlPattern = null, $options = array())
    {
        parent::__construct($options);
        $this->total = $total;
        $this->url_pattern = $urlPattern;
    }
    
    function setPageKey($page_key)
    {
        $this->page_key = $page_key;
    }
    
    function setPageSizeKey($pagesize_key)
    {
        $this->pagesize_key = $pagesize_key;
    }
    
    function setPageSize($pageSize)
    {
        $this->page_size = $pageSize;
    }
    
    function setTotal($total)
    {
        $this->total = $total;
    }

    
    /**
    * Устанавливает текущую страницу.
    * @param integer $page - номер страницы, минимально: "1"
    */
    function setPage($page)
    {
        if (isset($this->total) && !empty($this->page_size))
        {
            $maxpage = ceil($this->total/$this->page_size);
            $this->page = ($page > $maxpage) ? $maxpage : $page;
        } else {
            $this->page = $page;
        }
        if ($this->page<1) $this->page = 1;        
    }
       
    function getView($local_options = array())
    {        
        $this->page_count = ceil($this->total/$this->page_size);
        if ($this->page_count<1) $this->page_count = 1;
        
        $leftpage = ($this->page == 1) ? 1 : $this->page-1;
        $rightpage = ($this->page >= $this->page_count) ? $this->page_count : $this->page+1;
        
        if (isset($this->url_pattern)) {
            $this->left = str_replace('%PAGE%', $leftpage, $this->url_pattern);
            $this->right = str_replace('%PAGE%', $rightpage, $this->url_pattern);
            $this->perPageUrl = str_replace('%PERPAGE%', $rightpage, $this->url_pattern);
        } else {
            $this->left = $this->url->replaceKey(array('p' => $leftpage));
            $this->right = $this->url->replaceKey(array('p' => $rightpage));
            $this->perPageUrl = str_replace('%PERPAGE%', $rightpage, $this->url_pattern);
        }        
        
        $view = new \RS\View\Engine();
        $view->assign('paginator', $this);
        $view->assign('local_options', $local_options);
        
        return $view->fetch($this->tpl);
    }
    
}
