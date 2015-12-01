<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\DeliveryType;
use \RS\Orm\Type;

class RussianPost extends Universal
{
    
    function __construct()
    {
        $this->loadDefalutOptions();
    } 
    
    function loadDefalutOptions()
    {
        $zoneApi = new \Shop\Model\ZoneApi();
        $this->setOption(array('max_weight' => '20000'));
        $this->setOption(array(
            'rules' => array (
              1 => array (
                'zone' => $zoneApi->getZoneByTitle('Магистральный пояс 1')->id,
                'ruletype' => 'weight',
                'from' => '0',
                'to' => '20000',
                'actiontype' => 'fixed',
                'value' => '138.80 + floor(($W-1)/500)*12',
              ),
              2 => array (
                'zone' => $zoneApi->getZoneByTitle('Магистральный пояс 2')->id,
                'ruletype' => 'weight',
                'from' => '0',
                'to' => '20000',
                'actiontype' => 'fixed',
                'value' => '140.70 + floor(($W-1)/500)*13.90',
              ),
              3 => array (
                'zone' => $zoneApi->getZoneByTitle('Магистральный пояс 3')->id,
                'ruletype' => 'weight',
                'from' => '0',
                'to' => '20000',
                'actiontype' => 'fixed',
                'value' => '146.40 + floor(($W-1)/500)*20.30',
              ),
              4 => array (
                'zone' => $zoneApi->getZoneByTitle('Магистральный пояс 4')->id,
                'ruletype' => 'weight',
                'from' => '0',
                'to' => '20000',
                'actiontype' => 'fixed',
                'value' => '178.30 + floor(($W-1)/500)*29.20',
              ),
              5 => array (
                'zone' => $zoneApi->getZoneByTitle('Магистральный пояс 5')->id,
                'ruletype' => 'weight',
                'from' => '0',
                'to' => '20000',
                'actiontype' => 'fixed',
                'value' => '199 + floor(($W-1)/500)*33.70',
              ),
              6 => array (
                'zone' => 'all',
                'ruletype' => 'weight',
                'from' => '10001',
                'to' => '20000',
                'actiontype' => 'delivery_percent',
                'value' => '30',
              ),
            ),
        ));
    } 
    

    /**
    * Возвращает название расчетного модуля (типа доставки)
    * 
    * @return string
    */
    function getTitle()
    {
        return t('Почта России');
    }
    
    /**
    * Возвращает описание типа доставки
    * 
    * @return string
    */
    function getDescription()
    {
        return t("Рассчет доставки Почтой России");
    }
    
    /**
    * Возвращает идентификатор данного типа доставки. (только англ. буквы)
    * 
    * @return string
    */
    function getShortName()
    {
        return 'russianpost';
    }
    
}
?>