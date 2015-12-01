<?php
namespace Imldelivery\Controller\Front;

/**
* Фронт контроллер
*/
class AjaxCtrl extends \RS\Controller\Front
{
	public
        $iml;

    function init()
    {
        //$this->iml = new \Imldelivery\Model\DeliveryType\Iml();
        //$order = Shop\Model\Orm\Order::currentOrder();
        //$this->iml = $order->getDelivery();
    }   

    function actionIndex()
    {
        if ($this->url->isAjax()) {
	        $action = $this->url->request('action', TYPE_STRING, '');
	        $params = $this->url->request('params', TYPE_ARRAY, '');
	        $output = \Imldelivery\Model\DeliveryType\Iml::$action($params);
	        //$this->result->addSection('params', $params);
	        return $this->result->addSection($action, $output);
        }
    }
}
?>