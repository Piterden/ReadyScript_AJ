<?php
namespace Imldelivery\Controller\Front;

/**
* AJAX Фронт контроллер
*/
class AjaxCtrl extends \RS\Controller\Front
{
	public
		$order,
        $iml;

    function init()
    {
        $this->iml = new \Imldelivery\Model\DeliveryType\Iml();
    }

    function actionIndex()
    {
        if ($this->url->isAjax()) {
	        $action = $this->url->request('action', TYPE_STRING, '');
	        $params = $this->url->request('params', TYPE_ARRAY, '');
	        $output = $this->iml->$action($params);
	        return $this->result->addSection($action, $output);
        }
    }
}
?>
