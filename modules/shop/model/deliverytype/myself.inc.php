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

    /**
    * Возвращает название
    *
    * @return string
    */
    function getTitle()
    {
        return t('Самовывоз');
    }

    /**
    * Возвращает описание
    *
    * @return string
    */
    function getDescription()
    {
        return t('Не предполагает взимание оплаты');
    }

    /**
    * Возвращает короткое системное имя
    *
    * @return string
    */
    function getShortName()
    {
        return 'myself';
    }

    /**
    * Возвращает стоимость доставки для заданного заказа. Только число.
    *
    * @param \Shop\Model\Orm\Order $order - объект заказа
    * @param \Shop\Model\Orm\Address $address - адрес доставки
    * @param \Shop\Model\Orm\Delivery $delivery - объект доставки
    * @param boolean $use_currency - использовать валюту?
    * @return double
    */
    function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, \Shop\Model\Orm\Delivery $delivery, $use_currency = true)
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
