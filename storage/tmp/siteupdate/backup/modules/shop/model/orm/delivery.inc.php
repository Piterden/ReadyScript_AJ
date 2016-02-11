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
 * Способ доставки текущего сайта, присутствующий в списке выбора при оформлении заказа.
 * Содержит связь с модулем расчета.
 */
class Delivery extends \RS\Orm\OrmObject
{
	protected static $table = 'order_delivery';
	function _init()
	{
		parent::_init ()->append ( array(t ( 'Основные' ),'site_id' => new Type\CurrentSite (),'title' => new Type\String ( array('maxLength' => '255','description' => t ( 'Название' ) 
		) ),'description' => new Type\Text ( array('description' => t ( 'Описание' ) 
		) ),'picture' => new Type\Image ( array('max_file_size' => 10000000,'allow_file_types' => array('image/pjpeg','image/jpeg','image/png','image/gif' 
		),'description' => t ( 'Логотип' ) 
		) ),'xzone' => new Type\ArrayList ( array('description' => t ( 'Зоны' ),'list' => array(array('\Shop\Model\DeliveryApi','getZonesList' 
		) 
		),'attr' => array(array('size' => 10,'multiple' => true 
		) 
		) 
		) ),'min_price' => new Type\Integer ( array('description' => t ( 'Минимальная сумма заказа' ),'default' => 0,'allowEmpty' => false,'hint' => t ( 'Условие при котором, будет показываться доставка.<br/>0 - условие не действует.' ) 
		) ),'max_price' => new Type\Integer ( array('description' => t ( 'Максимальная сумма заказа' ),'default' => 0,'allowEmpty' => false,'hint' => t ( 'Условие при котором, будет показываться доставка.<br/>0 - условие не действует.' ) 
		) ),'min_cnt' => new Type\Integer ( array('description' => t ( 'Минимальное количество товаров в заказе' ),'default' => 0,'allowEmpty' => false,'hint' => t ( 'Условие при котором, будет показываться доставка.<br/>0 - условие не действует.' ) 
		) ),'first_status' => new Type\Integer ( array('description' => t ( 'Стартовый статус заказа' ),'list' => array(array(__CLASS__,'getFirstStatusList' 
		) 
		) 
		) ),'user_type' => new Type\Enum ( array('all','user','company' 
		), array('allowEmpty' => false,'description' => t ( 'Категория пользователей для данного способа доставки' ),'listFromArray' => array(array('all' => 'Все','user' => 'Физические лица','company' => 'Юридические лица' 
		) 
		) 
		) ),'public' => new Type\Integer ( array('description' => t ( 'Публичный' ),'maxLength' => 1,'default' => 1,'checkboxView' => array(1,0 
		) 
		) ),'class' => new Type\String ( array('maxLength' => '255','description' => t ( 'Расчетный класс (тип доставки)' ),'template' => '%shop%/form/delivery/other.tpl','list' => array(array('\Shop\Model\DeliveryApi','getTypesAssoc' 
		) 
		) 
		) ),

		'_serialized' => new Type\Text ( array('description' => t ( 'Параметры расчетного класса' ),'visible' => false 
		) ),'data' => new Type\ArrayList ( array('visible' => false 
		) ),'sortn' => new Type\Integer ( array('maxLength' => '11','default' => 0,'description' => t ( 'Сорт. индекс' ),'visible' => false 
		) ) 
		) );
	}
	
	/**
	 * Действия перед записью объекта
	 * 
	 * @param string $flag - insert или update
	 */
	function beforeWrite($flag)
	{
		if ($flag == self::INSERT_FLAG) {
			$this['sortn'] = \RS\Orm\Request::make ()->select ( 'MAX(sortn) as max' )->from ( $this )->exec ()->getOneField ( 'max', 0 ) + 1;
		}
		if (empty ( $this['xzone'] )) {
			$this['xzone'] = array(0 
			);
		}
		$this['_serialized'] = serialize ( $this['data'] );
	}
	
	/**
	 * Действия после записи объекта
	 * 
	 * @param string $flag - insert или update
	 */
	function afterWrite($flag)
	{
		// Удаляем старые связи с зонами
		$this->deleteZones ();
		
		// Записываем новые зоны
		if (is_array ( $this->xzone )) {
			if (array_search ( 0, $this->xzone ) !== false) {
				$this->xzone = array(0 
				);
			}
			foreach($this->xzone as $zone_id) {
				$link = new DeliveryXZone ();
				$link->delivery_id = $this->id;
				$link->zone_id = $zone_id;
				$link->insert ();
			}
		}
	}
	
	/**
	 * Удалить все связи этой доставки с зонами
	 */
	function deleteZones()
	{
		\RS\Orm\Request::make ()->delete ()->from ( new DeliveryXZone () )->where ( array('delivery_id' => $this->id 
		) )->exec ();
	}
	
	/**
	 * Заполнить поле xzone массивом идентификаторов зон
	 */
	function fillZones()
	{
		$zones = \RS\Orm\Request::make ()->select ( 'zone_id' )->from ( new DeliveryXZone () )->where ( array('delivery_id' => $this->id 
		) )->exec ()->fetchSelected ( null, 'zone_id' );
		$this->xzone = $zones;
		if (empty ( $zones )) {
			$this->xzone = array(0 
			);
		}
	}
	
	/**
	 * Возвращает клонированный объект доставки
	 * 
	 * @return Delivery
	 */
	function cloneSelf()
	{
		/**
		 *
		 * @var \Shop\Model\Orm\Delivery
		 */
		$clone = parent::cloneSelf ();
		
		// Клонируем фото, если нужно
		if ($clone['picture']) {
			/**
			 *
			 * @var \RS\Orm\Type\Image
			 */
			$clone['picture'] = $clone->__picture->addFromUrl ( $clone->__picture->getFullPath () );
		}
		return $clone;
	}
	function afterObjectLoad()
	{
		$this['data'] = @unserialize ( $this['_serialized'] );
	}
	
	/**
	 * Производит валидацию текущих данных в свойствах
	 * 
	 * @return bool Возвращает true, если нет ошибок, иначе - false
	 */
	function validate()
	{
		$this->getTypeObject ()->validate ( $this );
		return parent::validate ();
	}
	
	/**
	 * Возвращает объект расчетного класса (типа доставки)
	 * 
	 * @return \Shop\Model\DeliveryType\AbstractType | false
	 */
	function getTypeObject()
	{
		if ($type = \Shop\Model\DeliveryApi::getTypeByShortName ( $this['class'] )) {
			$type->loadOptions ( ( array ) $this['data'] );
		}
		return $type;
	}
	
	/**
	 * Возвращает дополнительный HTML для публичной части,
	 * если например нужен виджет с выбором для забора посылки
	 * 
	 * @return string
	 */
	function getAddittionalHtml(\Shop\Model\Orm\Order $order = null)
	{
		/**
		 *
		 * @var \Shop\Model\DeliveryType\AbstractType
		 */
		$delivery_type = $this->getTypeObject ();
		return $delivery_type->getAddittionalHtml ( $this, $order );
	}
	
	/**
	 * Возвращает список статусов заказа, для способа доставки
	 * 
	 * @return array
	 */
	public static function getFirstStatusList()
	{
		$userstatus_api = new \Shop\Model\UserStatusApi ();
		$list = $userstatus_api->getSelectList ();
		return array(0 => t ( 'По умолчанию (как у способа оплаты)' ) 
		) + $list;
	}
	
	/**
	 * Возвращает стоимость доставки
	 * 
	 * @param Order $order текущий заказ пользователя
	 * @param Address $address объект адреса доставки
	 * @param bool $use_currency применять валюту заказа
	 * @return \Shop\Model\DeliveryType\AbstractType
	 */
	function getDeliveryCost(Order $order, Address $address = null, $use_currency = true)
	{
		$type_obj = $this->getTypeObject ();
		$cost = $type_obj->getDeliveryCost ( $order, $address, $use_currency );
		
		return $cost;
	}
	
	/**
	 * Возвращает стоимость доставки в текстовом виде всегда в валюте заказа
	 * 
	 * @param Order $order текущий заказ пользователя
	 * @param Address $address объект адреса доставки
	 * @return \Shop\Model\DeliveryType\AbstractType
	 */
	function getDeliveryCostText(Order $order, Address $address = null)
	{
		$type_obj = $this->getTypeObject ();
		$cost = $type_obj->getDeliveryCostText ( $order, $address );
		return $cost;
	}
	function getDeliveryExtraText(Order $order, Address $address = null)
	{
		$type_obj = $this->getTypeObject ();
		$text = $type_obj->getDeliveryExtraText ( $order, $address );
		return $text;
	}
}
