<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Main\Controller\Admin;

/**
* Контроллер виджетов на главной странице административной панели
* @ingroup Main
*/
class Widgets extends \RS\Controller\Admin\Front
{
    protected
        $api;
    
    function init()
    {
        $this->api = new \Main\Model\Widgets();
        $this->api->setUserId($this->user['id']);
    }
    
    function actionIndex()
    {
        $total = 0;
        $this->view->assign(array(
            'widgetsByCol' => $this->api->getMainList($total),
            'total' => $total
        ));
        return $this->result->setTemplate('widgets.tpl');
    }
    
    /**
    * AJAX
    */
    function actionGetWidgetList()
    {
        $this->view->assign('list', $this->api->getFullList(true));
        return $this->result->setTemplate('widget_list.tpl');
    }
    
    /**
    * AJAX
    */
    function actionAjaxAddWidget()
    {
        $wclass = $this->url->request('wclass', TYPE_STRING);
        $newid = $this->api->insertWidget($wclass); //Записываем в базу информацию о добавлении виджета
        return $this->api->getWidgetOut($wclass, array('id' => $newid, 'force_wrap' => true));
    }
    
    /**
    * AJAX
    */
    function actionAjaxRemoveWidget()
    {
        $wclass = $this->url->request('wclass', TYPE_STRING);
        $this->api->removeWidget($wclass); //Записываем в базу информацию о добавлении виджета
        return $this->result->setSuccess(true);
    }
    
    /**
    * AJAX
    */
    function actionAjaxMoveWidget()
    {
        $wid = $this->url->request('wid', TYPE_INTEGER);
        $col = $this->url->request('col', TYPE_STRING);
        $pos = $this->url->request('pos', TYPE_INTEGER);
        $this->api->moveWidget($wid, $col, $pos);
        return $this->result->setSuccess(true);
    }
}

