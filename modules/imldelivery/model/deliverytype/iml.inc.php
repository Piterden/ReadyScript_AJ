<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Imldelivery\Model\DeliveryType;

class Iml extends \Shop\Model\DeliveryType\AbstractType
{
	const API_HOST_MAIN = 'http://api.iml.ru/json/', // Основное API
API_HOST_HELP = 'http://api.iml.ru/list/', // Справочные таблицы для API (?type=json для JSON ответа)
API_HOST_LIST = 'http://list.iml.ru/'; // Остальные справочники (?type=json для JSON ответа)
	                                        
	// Учетные данные
	                                        // API_LOGIN = '07308',
	                                        // API_PASS = 'TAaB4myF',
	                                        
	// URL_API_STATUS = 'http://api.iml.ru/json/GetStatuses',
	                                        // URL_API_ORDERSLIST = 'http://api.iml.ru/json/GetOrders',
	                                        // URL_API_CREATEORDER = 'http://api.iml.ru/json/CreateOrder',
	                                        // URL_API_GETPRICE = 'http://api.iml.ru/json/GetPrice',
	                                        
	// URL_HELP_DELIVERYSTATUS = 'http://api.iml.ru/list/deliverystatus?type=json',
	                                        // URL_HELP_ORDERSTATUS = 'http://api.iml.ru/list/orderstatus?type=json',
	                                        // URL_HELP_REGION = 'http://api.iml.ru/list/region?type=json',
	                                        // URL_HELP_SD = 'http://api.iml.ru/list/sd?type=json',
	                                        // URL_HELP_SERVICES = 'http://api.iml.ru/list/service?type=json',
	                                        
	// URL_LOCATION = 'http://list.iml.ru/Location?type=json',
	                                        // URL_ZONE = 'http://list.iml.ru/zone?type=json',
	                                        // URL_EXCEPTIONSERVICEREGION = 'http://list.iml.ru/ExceptionServiceRegion?type=json',
	                                        // URL_POSTDELIVERYLIMIT = 'http://list.iml.ru/PostDeliveryLimit?type=json',
	                                        // URL_REGION = 'http://list.iml.ru/region?type=json',
	                                        // URL_SELFDELIVERY = 'http://list.iml.ru/sd?type=json',
	                                        // URL_STATUS = 'http://list.iml.ru/status?type=json',
	                                        // URL_POSTRATEZONE = 'http://list.iml.ru/PostRateZone?type=json',
	                                        // URL_POSTCODE = 'http://list.iml.ru/PostCode?type=json',
	                                        // URL_CALENDAR = 'http://list.iml.ru/calendar?type=json',
	                                        // URL_SERVICE = 'http://list.iml.ru/service?type=json';
	private $cache_api_requests = array(); // Кэш запросов к серверу рассчета
	
	/**
	 * Возвращает название расчетного модуля (типа доставки)
	 * 
	 * @return string
	 */
	function getTitle()
	{
		return t ( 'Служба доставки IML' );
	}
	
	/**
	 * Возвращает описание типа доставки
	 * 
	 * @return string
	 */
	function getDescription()
	{
		return t ( 'Служба доставки IML <br/><br/>
        <div class="notice-box no-padd">
            <div class="notice-bg">
                Для работы доставки необходимо указывать Вес у товара в граммах.<br/>
                Укажите Вес по умолчанию в <b>"Веб-сайт" &rarr; "Настройка модулей" &rarr; "Каталог товаров" &rarr; "Вес одного товара по-умолчанию"</b><br/>
                <b>Минимальный вес для расчётов - 100 грамм.</b><br/>
                У товаров должны быть обязательно указаны -  длинна, ширина и высота в характеристиках.</b>
            </div>
        </div>' );
	}
	
	/**
	 * Возвращает идентификатор данного типа доставки.
	 * (только англ. буквы)
	 * 
	 * @return string
	 */
	function getShortName()
	{
		return t ( 'iml' );
	}
	
	/**
	 * Возвращает ORM объект для генерации формы или null
	 * 
	 * @return \RS\Orm\FormObject | null
	 */
	function getFormObject()
	{
		$properties = new \RS\Orm\PropertyIterator ( array('region_id_from' => new Type\String ( array('description' => t ( 'Регион, откуда осуществляется отправка' ),'hint' => t ( 'Список регионов, обслуживаемых компанией IML.' ),'list' => array(array('\Imldelivery\Model\DeliveryType\Iml','staticGetSdRegions' 
		),$this 
		) 
		) ),'service_id_all' => new Type\String ( array('description' => t ( 'Услуги, предоставляемые компанией IML' ),'hint' => t ( 'Полный список услуг.' ),'maxLength' => 11,'visible' => false,'List' => array(array('\Imldelivery\Model\DeliveryType\Iml','staticGetServices' 
		),$this 
		),'ChangeSizeForList' => false,'attr' => array(array('size' => 16,'multiple' => true 
		) 
		) 
		) ),'service_id' => new Type\ArrayList ( array('description' => t ( 'Услуги этого способа доставки по приоритету' ),'hint' => t ( 'При расчёте стоимости доставки на карте, если указанная услуга не будет предоставляться отправления, то расчёт будет вестись по нижеследующему тарифу указанному в списке.' ),'maxLength' => 1000,'runtime' => false,'attr' => array(array('multiple' => true 
		) 
		),'template' => '%imldelivery%/form/delivery/iml/services_list.tpl','listFromArray' => array(array() 
		) 
		) ),'show_map' => new Type\Integer ( array('description' => t ( 'Показывать карту' ),'hint' => t ( 'Показывать карту с выбором пункта доставки рядом с этим способом доставки на странице "Оформление заказа -> Способ доставки"' ),'maxLength' => 1,'default' => 0,'CheckboxView' => array(1,0 
		) 
		) ),'secret_login' => new Type\String ( array('description' => t ( 'Логин API' ),'hint' => t ( 'Логин для доступа к IML API. Выдается компанией IML.' ),'maxLength' => 150 
		) ),'secret_pass' => new Type\String ( array('description' => t ( 'Пароль API' ),'hint' => t ( 'Пароль для доступа к IML API. Выдается компанией IML.' ),'maxLength' => 150 
		) ) 
		)
		// 'timeout' => new Type\Integer(array(
		// 'description' => t('Время ожидания ответа IML, сек'),
		// 'hint' => t('Иногда запросы к IML идут очень долго,<br/> чтобы не дожидатся ответа используется это значение.<br/>Рекоммендуемое значение 2 сек.'),
		// 'default' => 2,
		// )),
		 );
		return new \RS\Orm\FormObject ( $properties );
	}
	
	/**
	 * Основной запрос к API.
	 * Ответ сервера кешируется в php массив.
	 * 
	 * @param string $host
	 * @param string $action
	 * @param array $params
	 * @param string $method
	 * @return array response
	 */
	private function apiRequest($host, $action, $method = "POST", array $params = array())
	{
		ksort ( $params );
		$cache_key = md5 ( serialize ( $params ) . $action . $method );
		$request_url = constant ( 'self::' . $host ) . $action;
		
		if (! isset ( $this->cache_api_requests[$cache_key] )) {
			
			$curl = curl_init ( $url );
			curl_setopt ( $curl, CURLOPT_HEADER, false );
			curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
			if (stripos ( $method, 'post' ) !== false) {
				curl_setopt ( $curl, CURLOPT_POST, true );
				curl_setopt ( $curl, CURLOPT_POSTFIELDS, http_build_query ( $params ) );
			}
			curl_setopt ( $curl, CURLOPT_USERPWD, $this->getOption ( 'secret_login' ) . ":" . $this->getOption ( 'secret_pass' ) );
			curl_setopt ( $curl, CURLOPT_SSLVERSION, 3 );
			curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false );
			
			$response = curl_exec ( $curl );
			if (curl_errno ( $ch )) {
				throw new Exception ( curl_error ( $ch ) );
			}
			$this->cache_api_requests[$cache_key] = json_decode ( $response, true );
		}
		return $this->cache_api_requests[$cache_key];
	}
	
	/**
	 * Получает общий набор справочников
	 * 
	 * @return array of listings
	 *         array "StatusItems"
	 *         array "RegionItems"
	 *         array "SelfDeliveryItems"
	 *         array "ServiceItems"
	 *         array "ZoneItems"
	 *         array "IMLCalendarItems"
	 *         array "LocationItems"
	 */
	private function requestHelpersAll()
	{
		try {
			return $this->apiRequest ( 'API_HOST_LIST', 'All', 'GET' );
		} catch ( Exception $ex ) {
			throw new \RS\Exception ( '[Ошибка] Не удалось получить справочники IML' );
		}
	}
	
	/**
	 * Дополнительный справочник непредоставляемых услуг
	 * 
	 * @return array $response
	 */
	public function requestExceptionServiceRegion()
	{
		try {
			return $this->apiRequest ( 'API_HOST_LIST', 'ExceptionServiceRegion', 'GET' );
		} catch ( Exception $ex ) {
			throw new \RS\Exception ( '[Ошибка] Не удалось получить список непредоставляемых услуг IML' );
		}
	}
	
	/**
	 * Запрос актуального списка услуг IML
	 * 
	 * @return array [id]|string => [name]|string
	 */
	static function staticGetServices(Iml $iml)
	{
		$list = $iml->apiRequest ( 'API_HOST_LIST', 'Service', 'GET' );
		return $iml->getHelpIdList ( $list );
	}
	
	/**
	 * Запрос актуального списка регионов IML
	 * 
	 * @param
	 *
	 * @return array [id]|string => [name]|string
	 */
	static function staticGetSdRegions(Iml $iml)
	{
		$list = $iml->apiRequest ( 'API_HOST_LIST', 'SelfDeliveryRegions', 'GET' );
		return $iml->getHelpIdList ( $list );
	}
	
	/**
	 * Получает выбранные для способа доставки услуги
	 * 
	 * @return array
	 */
	private function getSelectedServices()
	{
		return $this->getOption ( 'service_id' );
	}
	
	/**
	 * Запрос стоимости доставки у IML на одну услугу
	 * 
	 * @param array|null $filters
	 * @return array
	 */
	private function getImlCostByServiceId(\Shop\Model\Orm\Order $order, $service_id)
	{
		if (! $service_id) {
			$this->addError ( 'Не указана услуга' );
		} else {
			$order_extra = $order['order_extra'];
			$imlData = isset ( $order_extra['delivery'] ) ? $order_extra['delivery'] : $order_extra['address'];
		}
		$parameters = array('Job' => $service_id, // услуга
'RegionFrom' => $imlData['region_id_from'], // регион отправки
'RegionTo' => $imlData['region_id_to'], // регион получения
'Volume' => '1', // кол-во мест
'Weigth' => $order->getWeight () ? $order->getWeight () : '1', // вес(кг)
'SpecialCode' => $imlData['request_code'] 
		) // код пункта самовывоза
;
		$costes[$code] = self::postApiRequest ( self::URL_API_GETPRICE, self::API_LOGIN, self::API_PASS, $parameters );
		
		return $costes;
	}
	
	/**
	 * Возвращает стоимость доставки для заданного заказа.
	 * Только число.
	 * 
	 * @param \Shop\Model\Orm\Order $order
	 * @param \Shop\Model\Orm\Address $address - Адрес доставки
	 * @param bool $use_currency - Использовать ли валюту
	 * @return double
	 */
	function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
	{
		$delivery = $order->getDelivery ();
		$cache_key = md5 ( $order['order_num'] . $delivery['id'] );
		if (! isset ( $this->cache_api_requests[$cache_key] )) {
			$response = $this->getImlCost ( $order, $address );
		} else {
			$response = $this->cache_api_requests[$cache_key];
		}
		
		if (! is_array ( $cost )) return - 1;
		$cost_arr = array();
		foreach($cost as $code => $obj) {
			$cost_arr[] = isset ( $obj['Price'] ) ? $obj['Price'] : true;
		}
		return min ( $cost_arr );
	}
	
	/**
	 * Возвращает дополнительную информацию о доставке (например сроки достави)
	 * 
	 * @param \Shop\Model\Orm\Order $order
	 * @param \Shop\Model\Orm\Address $address - Адрес доставки
	 * @return string
	 */
	function getDeliveryExtraText(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
	{}
	
	/**
	 * Возвращает текст, в случае если доставка невозможна.
	 * false - в случае если доставка возможна
	 * 
	 * @param \Shop\Model\Orm\Order $order
	 * @param \Shop\Model\Orm\Address $address - Адрес доставки
	 * @return mixed
	 */
	function somethingWrong(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
	{
		return false;
	}
	
	/**
	 * Функция срабатывает после создания заказа
	 * 
	 * @param \Shop\Model\Orm\Order $order - объект заказа
	 * @param \Shop\Model\Orm\Address $address - Объект адреса
	 * @return mixed
	 */
	function onOrderCreate(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
	{}
	
	/**
	 * Возвращает HTML форму данного типа доставки
	 * 
	 * @return string
	 */
	function getFormHtml()
	{
		if ($params = $this->getFormObject ()) {
			$params->getPropertyIterator ()->arrayWrap ( 'data' );
			$params->getFromArray ( ( array ) $this->opt );
			$params->setFormTemplate ( strtolower ( str_replace ( '\\', '_', get_class ( $this ) ) ) );
			$module = \RS\Module\Item::nameByObject ( $this );
			$tpl_folder = \Setup::$PATH . \Setup::$MODULE_FOLDER . '/' . $module . \Setup::$MODULE_TPL_FOLDER;
			
			return $params->getForm ( null, null, false, null, '%system%/coreobject/tr_form.tpl', $tpl_folder );
		}
	}
	
	/**
	 * Возвращает дополнительный HTML для публичной части без плагина карты
	 * 
	 * @return string
	 */
	public function getAddittionalHtml(\Shop\Model\Orm\Delivery $delivery, \Shop\Model\Orm\Order $order = null)
	{
		if (! $order) {
			$order = \Shop\Model\Orm\Order::currentOrder ();
		}
		$currencyApi = new \Catalog\Model\CurrencyApi ();
		$currency = ($order->getMyCurrency () != '') ? $order->getMyCurrency () : $currencyApi->getDefaultCurrency ();
		$extra = $order['order_extra'];
		$region_id_to = $extra['delivery']['region_id_to'] ? $extra['delivery']['region_id_to'] : $extra['address']['region_id_to'];
		
		$view = new \RS\View\Engine ();
		$view->assign ( array('region_id_to' => $region_id_to, // Регион куда
'region_id_from' => $this->getOption ( 'region_id_from' ), // Регион откуда
'service_ids' => json_encode ( $this->getActiveServices () ), // Услуги этогй доставки
'delivery_cost_json' => json_encode ( $this->getImlCost ( $order ) ), // Текущий объект цены доставки
'delivery_cost' => $this->getImlCost ( $order ), // Текущий объект цены доставки
'order' => $order, // Текущий недоофрмленный заказ
'currency' => json_encode ( $currency->getValues () ), // Текущий объект валюты
'delivery' => $delivery, // Текущий объект доставки
'user' => \RS\Application\Auth::getCurrentUser () 
		) // Текущий объект пользователя
 + \RS\Module\Item::getResourceFolders ( $this ) );
		
		if ($this->getOption ( 'show_map' ) == 1) {
			return $view->fetch ( '%imldelivery%/delivery/iml/widget.tpl' );
		}
	}
	
	/**
	 * Возвращает шаблон Яндекс карты
	 * адреса пунктов самовывоза для карты
	 */
	function loadMap(\Shop\Model\Orm\Order $order, $delivery_id, $params)
	{
		// return print_r($order->getDelivery()->getTypeObject());
		$order_extra = $order['order_extra'];
		$order['delivery'] = $delivery_id;
		$delivery = $order->getDelivery ();
		$delivery_type = new Iml ();
		
		$view = new \RS\View\Engine ();
		$view->assign ( array('region_id_to' => $order_extra['address']['region_id_to'], // Регион куда
'service_ids' => json_encode ( $delivery_type->getActiveServices () ), // Услуги этой доставки
'delivery_cost_json' => json_encode ( $delivery_type->getImlCost ( $order ) ), // Текущий объект цены доставки
'delivery_cost' => $delivery_type->getImlCost ( $order ), // Текущий объект цены доставки
'order' => $order, // Текущий недоофрмленный заказ
'delivery' => $delivery, // Текущий объект доставки
'delivery_type' => $delivery_type, // Текущий объект типа доставки
'user' => \RS\Application\Auth::getCurrentUser () 
		) // Текущий объект пользователя
 + \RS\Module\Item::getResourceFolders ( $delivery_type ) );
		
		return $view->fetch ( '%imldelivery%/delivery/iml/map.tpl' );
	}
	
	/**
	 * Возвращает цену в текстовом формате, т.е.
	 * здесь может быть и цена и надпись, например "Бесплатно"
	 * 
	 * @param \Shop\Model\Orm\Order $order
	 * @param \Shop\Model\Orm\Address $address
	 * @return string
	 */
	function getDeliveryCostText(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
	{
		$cost = $this->getDeliveryCost ( $order, $address );
		return ($cost) ? \RS\Helper\CustomView::cost ( $cost ) . ' ' . $order->getMyCurrency ()->stitle : 'бесплатно';
	}
	
	/**
	 * Возвращает дополнительный HTML для админ части в заказе
	 * 
	 * @param \Shop\Model\Orm\Order $order - заказ доставки
	 * @return string
	 */
	function getAdminHTML(\Shop\Model\Orm\Order $order)
	{
		$view = new \RS\View\Engine ();
		
		$view->assign ( array('order' => $order 
		) );
		
		return $view->fetch ( "%imldelivery%/form/delivery/iml/iml_additional_html.tpl" );
	}
	
	/**
	 * Действие с запросами к заказу для получения дополнительной информации от доставки
	 */
	function actionOrderQuery(\Shop\Model\Orm\Order $order)
	{
		$url = new \RS\Http\Request ();
		$method = $url->request ( 'method', TYPE_STRING, false );
		switch ($method) {
			case "getInfo" : // Получение статуса заказа
				return $this->getHtmlInfo ( $order );
				break;
			case "getShortInfo" : // Получение статуса заказа
				return $this->getHtmlShortInfo ( $order );
				break;
			case "getHistory" : // Получение статуса заказа
				return $this->getHtmlHistory ( $order );
				break;
			case "getStatus" : // Получение статуса заказа
			default :
				return $this->getHtmlStatus ( $order );
				break;
		}
	}
	
	/**
	 * Возвращает HTML виджет с краткой информацией заказа для админки
	 * 
	 * @param \Shop\Model\Orm\Order $order - объект заказа
	 */
	private function getHtmlShortInfo(\Shop\Model\Orm\Order $order)
	{
		$view = new \RS\View\Engine ();
		$view->assign ( array('type' => 'short','title' => 'Краткие сведения заказа','order' => $order,'delivery_type' => $this 
		) );
		return $view->fetch ( '%imldelivery%/form/delivery/iml/iml_get_status.tpl' );
	}
	
	/**
	 * Возвращает HTML виджет с информацией заказа для админки
	 * 
	 * @param \Shop\Model\Orm\Order $order - объект заказа
	 */
	private function getHtmlInfo(\Shop\Model\Orm\Order $order)
	{
		$view = new \RS\View\Engine ();
		$view->assign ( array('public_api_js_url' => self::PUBLIC_API_URL_JS,'public_api_css_url' => self::PUBLIC_API_URL_CSS,'api_key' => $this->getOption ( 'admin_api', 0 ),'cultureId' => $this->getOption ( 'language', self::DEFAULT_LANGUAGE_ID ),'type' => 'full','title' => 'Сведения заказа','order' => $order,'delivery_type' => $this 
		) );
		return $view->fetch ( '%imldelivery%/form/delivery/iml/iml_get_status.tpl' );
	}
	
	/**
	 * Возвращает HTML виджет с историей заказа для админки
	 * 
	 * @param \Shop\Model\Orm\Order $order - объект заказа
	 */
	private function getHtmlHistory(\Shop\Model\Orm\Order $order)
	{
		$view = new \RS\View\Engine ();
		$view->assign ( array('public_api_js_url' => self::PUBLIC_API_URL_JS,'public_api_css_url' => self::PUBLIC_API_URL_CSS,'api_key' => $this->getOption ( 'admin_api', 0 ),'cultureId' => $this->getOption ( 'language', self::DEFAULT_LANGUAGE_ID ),'type' => 'history','title' => 'История заказа','order' => $order,'delivery_type' => $this 
		) );
		
		return $view->fetch ( '%imldelivery%/form/delivery/iml/iml_get_status.tpl' );
	}
	
	/**
	 * Возвращает HTML виджет со статусом заказа для админки
	 * 
	 * @param \Shop\Model\Orm\Order $order - объект заказа
	 */
	private function getHtmlStatus(\Shop\Model\Orm\Order $order)
	{
		$view = new \RS\View\Engine ();
		$view->assign ( array('public_api_js_url' => self::PUBLIC_API_URL_JS,'public_api_css_url' => self::PUBLIC_API_URL_CSS,'api_key' => $this->getOption ( 'admin_api', 0 ),'cultureId' => $this->getOption ( 'language', self::DEFAULT_LANGUAGE_ID ),'type' => 'standard','title' => 'Статус заказа','order' => $order,'delivery_type' => $this 
		) );
		return $view->fetch ( '%imldelivery%/form/delivery/iml/iml_get_status.tpl' );
	}
	
	/**
	 * Возвращает дополнительный HTML для административной части с выбором опций доставки в заказе
	 * 
	 * @param \Shop\Model\Orm\Order $order - заказ доставки
	 * @return string
	 */
	function getAdminAddittionalHtml(\Shop\Model\Orm\Order $order = null)
	{
		// Получим данные по товарам
		$products = $order->getCart ()->getProductItems ();
		
		if (empty ( $products )) {
			$this->addError ( t ( 'В заказ не добавлено ни одного товара' ) );
		}
		
		// Получим цену с параметрами по умолчанию
		$cost = $this->getDeliveryCostText ( $order );
		$delivery = $order->getDelivery ();
		$service_id = $this->getOption ( 'service_id' );
		
		$view = new \RS\View\Engine ();
		$view->assign ( array('errors' => $this->getErrors (),'order' => $order,'cost' => $cost,'extra_info' => $order->getExtraKeyPair (),'delivery' => $delivery,'service_id' => $service_id 
		) + \RS\Module\Item::getResourceFolders ( $this ) );
		
		return $view->fetch ( "%imldelivery%/form/delivery/iml/iml_admin.tpl" );
	}
	
	/**
	 * Производит валидацию текущих данных в свойствах
	 */
	function validate(\Shop\Model\Orm\Delivery $delivery)
	{}
	
	// /**
	// * Запрос получает статус и состояние заказа(ов)
	// *
	// * @param array|null $filters
	// * @return array
	// */
	// function getImlStatuses(array $filters = null)
	// {
	// $parameters = array(
	// 'Test' => 'True', // для тестового режима, иначе не указывайте
	// 'CustomerOrder' => '', // номер заказа
	// 'BarCode' => '', // штрих код
	// 'DeliveryDateStart' => '', // фильтр по дате доставки, с указанной даты и позднее
	// 'DeliveryDateEnd' => '', // фильтр по дате доставки, до указанной даты
	// 'State' => 999, // из справочника
	// 'OrderStatus' => 0, // из справочника
	// 'Job' => '', // из справочника услуг
	// 'RegionFrom' => '', // фильтр по региону отправки
	// 'RegionTo' => '', // фильтр по региону получения
	// 'CreationDateStart' => '', // фильтр по дате доставки, с указанной даты и позднее
	// 'CreationDateEnd' => '' // фильтр по дате доставки, до указанной даты
	// );
	
	// return $this->postApiRequest(self::URL_API_STATUS, self::API_LOGIN, self::API_PASS, array_merge($parameters, $filters));
	// }
	
	// /**
	// * Запрос позволяет получить список заказов по параметрам
	// *
	// * @param array|null $filters
	// * @return array
	// */
	// function getImlOrders(array $filters = null)
	// {
	// $parameters =array(
	// 'Test' => 'True', // для тестового режима, иначе не указывайте
	// //'CustomerOrder' => '', // номер заказа
	// //'BarCode' => '2624028597816', // штрих код
	// //'DeliveryDateStart' => '2014-01-15', // с указанной даты и позднее
	// //'DeliveryDateEnd' => '2014-07-15', // до указанной даты
	// //'State' => 3 // из справочника
	// //'OrderStatus' => 0, // из справочника
	// 'Job' => 'С24КО', // из справочника услуг
	// //'RegionFrom' => 'МОСКВА', // из справочника регионов
	// //'RegionTo' => 'МОСКВА', // из справочника регионов
	// 'CreationDateStart' => '2014-01-15' // с указанной даты и позднее
	// //'CreationDateEnd' => '2014-07-15' // до указанной даты
	// );
	
	// return $this->postApiRequest(self::URL_API_ORDERSLIST, self::API_LOGIN, self::API_PASS, array_merge($parameters, ilters));
	// }
	
	// /**
	// * Запрос позволяет создать заказ в системе IML
	// *
	// * @param array|null $filters
	// * @return array
	// */
	// function createImlOrder(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
	// {
	// if(!$address) $address = $order->getAddress();
	// $catalog_config = \RS\Config\Loader::byModule('catalog');
	
	// $currency = \Catalog\Model\CurrencyApi::getBaseCurrency(); //Базовая валюта
	// $date = date('c',strtotime($order['dateof'])); //Дата заказа
	// $delivery = $order->getDelivery(); //объект доставки
	// $delivery_cost = $delivery->getDeliveryCost($order);
	// $payment = $order->getPayment();
	// $user = $order->getUser();
	// $extra = $order->getExtraInfo();
	// $cartItemsArray = array();
	
	// $cart = $order->getCart();
	// $products = $cart->getProductItems();
	// $cartdata = $cart->getPriceItemsData();
	
	// $services = self::staticGetServices();
	
	// // Заполняем продукты
	// foreach ($products as $n => $item) {
	// $product = $item['product'];
	// $barcode = $product->getBarCode($item['cartitem']['offer']);
	// $offer_title = $product->getOfferTitle($item['cartitem']['offer']);
	
	// $cartItemsArray[] = array(
	// 'productNo'=> $barcode,
	// 'productName' => $product->title,
	// 'productVariant' => $offer_title,
	// 'productBarCode' => '',
	// 'couponCode' => '',
	// 'discount' => $item->discount,
	// 'weightLine' => $item->single_weight,
	// 'amountLine' => $item->single_cost,
	// 'statisticalValueLine' => '',
	// 'itemQuantity' => $item['cartitem']['amount'],
	// 'deliveryService' => FALSE
	// );
	// }
	
	// // Заполняем доставку
	// if ($delivery_cost > 0) {
	// $cartItemsArray[] = array(
	// 'productNo'=> $extra['iml_service_id']['value'],
	// 'productName' => $services[$extra['iml_service_id']['value']],
	// 'productVariant' => '',
	// 'productBarCode' => '',
	// 'couponCode' => '',
	// 'discount' => '',
	// 'weightLine' => '',
	// 'amountLine' => '',
	// 'statisticalValueLine' => '',
	// 'itemQuantity' => '',
	// 'deliveryService' => TRUE
	// );
	// }
	
	// // Заполняем инфо о заказе
	// $parameters = array(
	// 'Test' => 'True', // для тестового режима, иначе не указывайте
	// 'CustomerOrder' => $user['id'] ? $user['id'] : $order['id'],
	// 'Job' => $extra['iml_service_id']['value'], // из справочника услуг
	// 'Contact' => $user['name'],
	// 'RegionCodeFrom' => $this->getOption('region_id_from',0), // из справочника регионов
	// 'RegionCodeTo' => $extra['iml_region_to']['value'], // из справочника регионов
	// 'Address' => $address['address'],
	// 'DeliveryPoint' => $extra['iml_request_code']['value'], // из справочника пунктов самовывоза
	// 'Phone' => $user['phone'],
	// 'Amount' => $order['totalcost'], // разделитель '.' (точка)
	// 'ValuatedAmount' => '14.2', // разделитель '.' (точка)
	// 'Weight' => $order->getWeight(), // разделитель '.' (точка)
	// 'Comment' => $order['comments'],
	// 'GoodItems' => $cartItemsArray //не обязательно, если есть позиции заказа
	// );
	
	// return $this->postApiRequest(self::URL_API_CREATEORDER, self::API_LOGIN, self::API_PASS, $parameters);
	
	// }
	// // Test – тестовый режим, 'True' для тестового режима, иначе не указывайте
	// // Job – услуга доставки, Code из справочника услуг
	// // CustomerOrder – номер заказа
	// // DeliveryDate – дата доставки в строковом представлении, формат dd.MM.yyyy
	// // Volume – количество мест
	// // Weight – вес
	// // BarCode – штрих код заказа в формате EAN-13
	// // DeliveryPoint – пункта самовывоза, RequestCode из таблицы пунктов самовывоза
	// // Phone – телефон
	// // Contact – контактное лицо
	// // RegionCodeFrom – региона отправления, Code из таблицы регионов
	// // RegionCodeTo – код региона получения, Code из таблицы регионов
	// // Address – адрес доставки
	// // TimeFrom – начало временного периода доставки
	// // TimeTo – конец временного периода доставки
	// // Amount – сумма заказа
	// // ValuatedAmount – оценочная стоимость заказа
	// // Comment – комментарий
	// // City – город доставки, для отправки почтой России
	// // PostCode – индекс, для отправки почтой России
	// // PostRegion – регион, для отправки почтой России
	// // PostArea – район, для отправки почтой России
	// // PostContentType – тип вложения (0 - Печатная, 1 - Разное, 2 - 1 Класс), для отправки почтой России
}
