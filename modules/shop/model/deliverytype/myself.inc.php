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
* Тип доставки - Самовывоз. стоимость - 0
*/
class Myself extends AbstractType
{
    
    function getTitle()
    {
        return t('Самовывоз');
    }
    
    function getDescription()
    {
        return t('Не предполагает взимание оплаты');
    }
    
    function getShortName()
    {
        return 'myself';
    }
    
    function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
    {
        return 0;
    }
    
    /**
    * Возвращает true, если тип доставки предполагает самовывоз
    * 
    * @return bool
    */
    function isMyselfDelivery()
    {
        return true;
    }    
}