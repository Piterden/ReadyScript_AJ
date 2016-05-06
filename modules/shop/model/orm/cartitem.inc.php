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
class CartItem extends \RS\Orm\AbstractObject
{
    protected static
        $table = 'cart';
    
    function _init()
    {
        $this->getPropertyIterator()->append(array(
            'site_id' => new Type\CurrentSite(),
            'session_id' => new Type\Varchar(array(
                'description' => 'ID сессии',
                'maxLength' => 32
            )),
            'uniq' => new Type\Varchar(array(
                'maxLength' => 10,
                'description' => t('ID в рамках одной корзины')
            )), 
            'dateof' => new Type\Datetime(array(
                'description' => t('Дата добавления')
            )),
            'user_id' => new Type\Integer(array(
                'description' => t('Пользователь')
            )),            
            'type' => new Type\Enum(array('product', 'service', 'coupon'), array(
                'description' => t('Тип записи товар, услуга, скидочный купон')
            )),
            'entity_id' => new Type\Varchar(array(
                'description' => t('ID объекта type'),
                'maxLength' => 50
            )),
            'offer' => new Type\Integer(array(
                'description' => t('Комплектация')
            )),
            'multioffers' => new Type\Text(array(
                'description' => t('Многомерные комплектации')
            )),
            'amount' => new Type\Integer(array(
                'description' => t('Количество'),
                'default' => 1
            )),
            'title' => new Type\Varchar(array(
                'description' => t('Название')
            )),
            'extra' => new Type\Text(array(
                'description' => t('Дополнительные сведения')
            ))
        ));
        
        $this->addIndex(array('site_id', 'session_id', 'uniq'), self::INDEX_PRIMARY);   
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

