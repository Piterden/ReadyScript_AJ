<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Controller\Admin;

class Tools extends \RS\Controller\Admin\Front
{
    function actionAjaxCalcProfit()
    {
        $position = $this->url->request('pos', TYPE_INTEGER);
        
        $order_api = new \Shop\Model\OrderApi();
        $result = $order_api->calculateOrdersProfit($position);
        
        if ($result === true) {
            return $this->result->addMessage(t('Пересчет успешно завершен'));
        } 
        elseif ($result === false) {
            return $this->result->addEMessage($order_api->getErrorsStr());
        }
        else {
            return $this->result
                            ->addSection('repeat', true)
                            ->addSection('queryParams', array(
                                'data' => array(
                                    'pos' => $result
                                ))
                            );
        }
    }
}