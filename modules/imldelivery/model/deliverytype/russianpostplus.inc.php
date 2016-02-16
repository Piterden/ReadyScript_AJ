<?php
namespace Imldelivery\Model\DeliveryType;

class RussianPostPlus extends \Shop\Model\DeliveryType\RussianPost
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Возвращает текст, в случае если доставка невозможна.
     * false - в случае если доставка возможна
     *
     * @param \Shop\Model\Orm\Order $order
     * @param \Shop\Model\Orm\Address $address - Адрес доставки
     * @return mixed
     */
    function somethingWrong(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null) {
        if ($order->use_addr > 0) { // выбран адрес
    		return false;
        }
    	return "Недоступно";
    }

    /**
    * Возвращает true, если тип доставки предполагает самовывоз
    *
    * @return bool
    */
    function isMyselfDelivery()
    {
        return false;
    }

    /**
    * Возвращает название расчетного модуля (типа доставки)
    *
    * @return string
    */
    function getTitle()
    {
        return t('Почта России +');
    }

    /**
    * Возвращает описание типа доставки
    *
    * @return string
    */
    function getDescription()
    {
        return t("Рассчет доставки Почтой России с проверкой активности");
    }

    /**
    * Возвращает идентификатор данного типа доставки. (только англ. буквы)
    *
    * @return string
    */
    function getShortName()
    {
        return 'russianpostplus';
    }

}
