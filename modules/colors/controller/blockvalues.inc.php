<?php
namespace Colors\Controller;

class BlockValues extends \RS\Controller\Block
{
    protected static  
        $list;
    
    function actionIndex()
    {
        if (!isset(self::$list)) {
            $colors_api = new \Colors\Model\Api();
            self::$list = $colors_api->getAssocList('title');
        }
        
        $this->view->assign('colors', self::$list);
        return $this->result;
    }
}

