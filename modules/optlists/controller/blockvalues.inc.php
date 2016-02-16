<?php
namespace Optlists\Controller;

class BlockValues extends \RS\Controller\Block
{
    protected static  
        $list;
    
    function actionIndex()
    {
        if (!isset(self::$list)) {
            $optlists_api = new \Optlists\Model\Api();
            self::$list = $optlists_api->getAssocList('title');
        }
        
        $this->view->assign('optlists', self::$list);
        return $this->result;
    }
}

