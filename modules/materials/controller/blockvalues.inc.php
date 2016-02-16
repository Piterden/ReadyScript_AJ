<?php
namespace Materials\Controller;

class BlockValues extends \RS\Controller\Block
{
    protected static  
        $list;
    
    function actionIndex()
    {
        if (!isset(self::$list)) {
            $materials_api = new \Materials\Model\Api();
            self::$list = $materials_api->getAssocList('title');
        }
        
        $this->view->assign('materials', self::$list);
        return $this->result;
    }
}

