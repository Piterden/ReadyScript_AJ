<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\Orm;
use \RS\Orm\Type;

/**
* Позиция в корзине
*/
class OrderItem extends \RS\Orm\AbstractObject
{
    protected static
        $table = 'order_items';
    
    function _init()
    {
        $this->getPropertyIterator()->append(array(
            'order_id' => new Type\Integer(array(
                'description' => 'ID заказа',
            )),
            'uniq' => new Type\String(array(
                'maxLength' => 10,
                'description' => t('ID в рамках одной корзины')
            )), 
            'type' => new Type\Enum(array('product', 'coupon', 'tax', 'delivery', 'order_discount', 'subtotal'), array(
                'description' => t('Тип записи товар, услуга, скидочный купон')
            )),
            'entity_id' => new Type\String(array(
                'description' => t('ID объекта type'),
                'maxLength' => 50
            )),
            'multioffers' => new Type\Text(array(
                'description' => t('Многомерные комплектации')
            )),
            'offer' => new Type\Integer(array(
                'description' => t('Комплектация')
            )),
            'amount' => new Type\Integer(array(
                'description' => t('Количество'),
                'default' => 1
            )),
            'barcode' => new Type\String(array(
                'description' => t('Артикул'),
                'maxLength' => 100,
            )),
            'title' => new Type\String(array(
                'description' => t('Название')
            )),
            'model' => new Type\String(array(
                'description' => t('Модель')
            )),
            'single_weight' => new Type\Double(array(
                'description' => t('Вес')
            )),
            'single_cost' => new Type\Decimal(array(
                'description' => t('Цена за единицу продукции'),
                'maxlength' => 20,
                'decimal' => 2
            )),
            'price' => new Type\Decimal(array(
                'description' => t('Стоимость'),
                'maxlength' => 20,
                'decimal' => 2,
                'default' => 0
            )),
            'discount' => new Type\Decimal(array(
                'description' => t('Скидка'),
                'maxlength' => 20,
                'decimal' => 2,
                'default' => 0
            )),
            'sortn' => new Type\Integer(array(
                'description' => t('Порядок')
            )),
            'extra' => new Type\Text(array(
                'description' => t('Дополнительные сведения')
            )),
            'data' => new Type\ArrayList()
        ));
        
        $this->addIndex(array('order_id', 'uniq'), self::INDEX_PRIMARY);   
    }
    
    function afterObjectLoad()
    {
        @$this['data'] = unserialize($this['extra']);
    }
    
    function getExtraParam($key, $default = null)
    {
        @$data = unserialize($this['extra']);
        return isset($data[$key]) ? $data[$key] : $default;
    }
    
    /**
    * Возвращает массив с названиями и выбранными значениями многомерных комплектаций
    * 
    * @return array
    */
    function getMultiOfferTitles()
    {
        $multioffers = @unserialize($this['multioffers']);
        return $multioffers ?: array(); 
    }
    
}

