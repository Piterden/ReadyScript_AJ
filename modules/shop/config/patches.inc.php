<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Config;

/**
* Патчи к модулю
*/
class Patches extends \RS\Module\AbstractPatches
{
    /**
    * Возвращает массив имен патчей.
    */
    function init()
    {
        return array(
            '20073',
            '20063',
            '20087'
        );
    }
    
    
    /**
    * Патч для релиза 2.0.0.73 и ниже
    * Проставляет сортировки в доставках и оплатах
    */
    function afterUpdate20073()
    {
        //Обновление доставок
        $q = \RS\Orm\Request::make()
            ->update(new \Shop\Model\Orm\Delivery())
            ->set("`sortn` = `id`")
            ->where('sortn = 0')
            ->exec();
        
        //Обновление оплат    
        $q = \RS\Orm\Request::make()
            ->update(new \Shop\Model\Orm\Payment())
            ->set("`sortn` = `id`")
            ->where('sortn = 0')
            ->exec();
    } 
    
    /**
    * Плагие проставляет всем заказам уникальные номера заказа
    * 
    */
    function afterUpdate20063()
    {
        //Подгрузим все заказы
        \RS\Orm\Request::make()
            ->update(new \Shop\Model\Orm\Order())
            ->set('order_num = id')
            ->where('order_num IS NULL')
            ->exec();
    }    
    
    /**
    * Патч проставляет site_id идентификатор для адреса доставки
    */
    function afterUpdate20087()
    {
        \RS\Orm\Request::make()
            ->update(new \Shop\Model\Orm\Address())->asAlias('A')
            ->update(new \Shop\Model\Orm\Order())->asAlias('O')
            ->set('A.site_id = O.site_id')
            ->where('A.id = O.use_addr')
            ->exec();
        
        //Оставшиеся адреса привяжем к текущему сайту
        \RS\Orm\Request::make()
            ->update(new \Shop\Model\Orm\Address())
            ->set(array(
                'site_id' => \RS\Site\Manager::getSiteId()
            ))
            ->where('site_id IS NULL')
            ->exec();
    }
}
