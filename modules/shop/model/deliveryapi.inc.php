<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model;

/**
* API функции для работы со способами доставки для текущего сайта
*/
class DeliveryApi extends \RS\Module\AbstractModel\EntityList
{
    protected static
        $types;

    function __construct()
    {
        parent::__construct(new \Shop\Model\Orm\Delivery,
        array(
            'nameField' => 'title',
            'multisite' => true,
            'defaultOrder' => 'sortn',
            'sortField' => 'sortn'
        ));
    }
    
    /**
    * Возвращает Имеющиеся в системе обработчики типов доставок.
    * 
    * @return array
    */
    function getTypes()
    {
        if (self::$types === null) {
            $event_result = \RS\Event\Manager::fire('delivery.gettypes', array());
            $list = $event_result->getResult();
            self::$types = array();
            foreach($list as $delivery_type_object) {
                if (!($delivery_type_object instanceof DeliveryType\AbstractType)) {
                    throw new \rs\Exception(t('Тип доставки должен быть наследником \Shop\Model\DeliveryType\AbstractType'));
                }
                self::$types[$delivery_type_object->getShortName()] = $delivery_type_object;
            }
        }
        
        return self::$types;
    }
    
    /**
    * Возвращает массив ключ => название типа доставки
    * 
    * @return array
    */
    public static function getTypesAssoc()
    {
        $_this = new self();
        $result = array();
        foreach($_this->getTypes() as $key => $object) {
            $result[$key] = $object->getTitle();
        }
        return $result;
    }
    
    /**
    * Получение списка доставок для типа оплаты
    * 
    */
    public static function getListForPayment()
    {
        $list = parent::staticSelectList();
        $list = array('0'=>'-Все-') + $list;
        return $list;
    }
    
    /**
    * Возвращает объект типа доставки по идентификатору
    * 
    * @param string $name
    */
    public static function getTypeByShortName($name)
    {
        $_this = new self();
        $list = $_this->getTypes();
        return isset($list[$name]) ? $list[$name] : new DeliveryType\Stub($name);
    }
    
    public static function getZonesList()
    {
        return array(0 => t(' - все - ')) + \Shop\Model\ZoneApi::staticSelectList();
    }
    
    /**
    * Устанавливает фильтр по магистральным поясам
    * 
    * @param array $zone - массив с магистральными поясами
    */
    public function setZoneFilter($zone)
    {
        $zone = array_merge(array(0), (array)$zone);
        $q = $this->queryObj()
            ->join(new Orm\DeliveryXZone(), 'DZ.delivery_id = A.id AND DZ.zone_id IN ('.implode(',', $zone).')', 'DZ');
    }
        
}
