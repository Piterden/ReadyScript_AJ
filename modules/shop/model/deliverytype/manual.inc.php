<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\DeliveryType;
use \RS\Orm\Type;

/**
* Тип доставки - Устанавливаема вручную стоимость
*/
class Manual extends AbstractType
{
    protected
        $form_tpl = 'type/fixed.tpl';
    
    function getTitle()
    {
        return t('Изменяемая вручную цена');
    }
    
    function getDescription()
    {
        return t('Стоимость рассчитывается менеджером, после оформления заказа');
    }
    
    function getShortName()
    {
        return 'manual';
    }
    
    function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
    {
        return 0;
    }

    function getDeliveryCostText(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        $cost = $this->getDeliveryCost($order);
        return ($cost) ? CCustomView::cost($cost) : 'Будет рассчитана менеджером';
    }    
}