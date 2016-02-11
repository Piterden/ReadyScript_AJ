<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Shop\Model\DeliveryType;

use \RS\Orm\Type;

class Ems extends AbstractType
{
	const API_URL = "http://emspost.ru/api/rest/", REQUEST_TIMEOUT = 10; // 10 сек. таймаут запроса к api
	private $cache_api_requests = array(); // Кэш запросов к серверу рассчета
	
	/**
	 * Возвращает название расчетного модуля (типа доставки)
	 * 
	 * @return string
	 */
	function getTitle()
	{
		return t ( 'EMS Почта России' );
	}
	
	/**
	 * Возвращает описание типа доставки
	 * 
	 * @return string
	 */
	function getDescription()
	{
		return t ( 'Международный сервис экспресс-доставки почтовой корреспонденции EMS<p><b>Для этого типа важно задать вес у товара в граммах, либо указать параметр в "Веб-сайт" &rarr; "Настройка модулей" &rarr; "Каталог товаров" &rarr; "Вес одного товара по-умолчанию"</b></p>' );
	}
	
	/**
	 * Возвращает идентификатор данного типа доставки.
	 * (только англ. буквы)
	 * 
	 * @return string
	 */
	function getShortName()
	{
		return 'ems';
	}
	
	/**
	 * Возвращает ORM объект для генерации формы или null
	 * 
	 * @return \RS\Orm\FormObject | null
	 */
	function getFormObject()
	{
		$properties = new \RS\Orm\PropertyIterator ( array('city_from' => new Type\String ( array('description' => t ( 'Город отправления' ),'ListFromArray' => array($this->getCities () 
		) 
		) ) 
		) );
		
		return new \RS\Orm\FormObject ( $properties );
	}
	
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
		if (! $address) $address = $order->getAddress ();
		if (! $this->getCityId ( $address->city )) {
			return t ( 'Доставка невозможна в город %0', array($address->city 
			) );
		}
		
		$data = $this->apiRequest ( array('method' => 'ems.test.echo' 
		) );
		
		if (! isset ( $data->rsp->stat ) || $data->rsp->stat != 'ok') {
			return t ( 'Не удалось соединиться с сервером EMS' );
		}
		
		return false;
	}
	
	/**
	 * Запрос к серверу рассчета стоимости доставки.
	 * Ответ сервера кешируется
	 * 
	 * @param array $params
	 */
	private function apiRequest($params)
	{
		ksort ( $params );
		$cache_key = md5 ( serialize ( $params ) );
		if (! isset ( $this->cache_api_requests[$cache_key] )) {
			$ctx = stream_context_create ( array('http' => array('timeout' => self::REQUEST_TIMEOUT 
			) 
			) );
			
			$url = self::API_URL . '?' . http_build_query ( $params );
			$json = file_get_contents ( $url, null, $ctx );
			$this->cache_api_requests[$cache_key] = json_decode ( $json );
		}
		return $this->cache_api_requests[$cache_key];
	}
	
	/**
	 * Возвращает стоимость доставки для заданного заказа.
	 * Только число.
	 * 
	 * @param \Shop\Model\Orm\Order $order
	 * @param \Shop\Model\Orm\Address $address - Адрес доставки
	 * @return double
	 */
	function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
	{
		if (! $address) $address = $order->getAddress ();
		$data = $this->apiRequest ( array('method' => 'ems.calculate','from' => $this->getOption ( 'city_from' ),'to' => $this->getCityId ( $address->city ),'weight' => $order->getWeight () / 1000 
		) // Вес заказа в килограммах
 );
		
		if (! isset ( $data->rsp->price )) {
			return false;
		}
		$cost = $data->rsp->price;
		if ($use_currency) {
			$cost = $order->applyMyCurrency ( $cost );
		}
		return $cost;
	}
	
	/**
	 * Возвращает дополнительную информацию о доставке (например сроки достави)
	 * 
	 * @param \Shop\Model\Orm\Order $order
	 * @param \Shop\Model\Orm\Address $address - Адрес доставки
	 * @return string
	 */
	function getDeliveryExtraText(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
	{
		if (! $address) $address = $order->getAddress ();
		$data = $this->apiRequest ( array('method' => 'ems.calculate','from' => $this->getOption ( 'city_from' ),'to' => $this->getCityId ( $address->city ),'weight' => $order->getWeight () / 1000 
		) // Вес заказа в килограммах
 );
		list ( $min, $max ) = array($data->rsp->term->min,$data->rsp->term->max 
		);
		if ($min == $max) {
			return t ( 'Срок доставки %0 [plural:%0:день|дня|дней]', array($max 
			) );
		}
		return t ( 'Срок доставки от %0 до %1 [plural:%1:дня|дней|дней]', array($min,$max 
		) );
	}
	
	/**
	 * Возвращает идентификатор города в данной системе доставки
	 * 
	 * @param string $city_title
	 * @return string
	 */
	private function getCityId($city_title)
	{
		$cities = $this->getCities ();
		$key = array_search ( mb_strtoupper ( $city_title ), $cities );
		return $key;
	}
	
	/**
	 * Возвращает список городов для данного типа доставки
	 * 
	 * @return array
	 */
	private function getCities()
	{
		return EmsLocations::$cities;
	}
}
?>
