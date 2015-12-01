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
* Тип доставки - Фиксированная цена доставки
*/
class FixedPay extends AbstractType
{
    protected
        $form_tpl = 'type/fixed.tpl';
    
    function getTitle()
    {
        return t('Фиксированная цена');
    }
    
    function getDescription()
    {
        return t('Стоимость доставки не зависит ни от каких параметров');
    }
    
    function getShortName()
    {
        return 'fixedpay';
    }
    
    function getFormObject()
    {
        return new \RS\Orm\FormObject(new \RS\Orm\PropertyIterator(array(
            'cost' => new Type\Float(array(
                'description' => t('Стоимость')
            ))
        )));
    }
    
    function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
    {
        $cost = $this->opt['cost'];
        if ($use_currency) $cost = $order->applyMyCurrency($cost);
        return $cost;
    }
}