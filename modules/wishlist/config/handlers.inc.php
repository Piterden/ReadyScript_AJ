<?php

namespace Wishlist\Config;

/**
 * Класс содержит обработчики событий, на которые подписан модуль
 */
class Handlers extends \RS\Event\HandlerAbstract
{
	/**
	 * Добавляет подписку на события
	 * 
	 * @return void
	 */
	function init()
	{
		$this->bind ( 'getroute' )-> // событие сбора маршрутов модулей
bind ( 'getmenus' ); // событие сбора пунктов меню для административной панели
	}
	
	/**
	 * Возвращает маршруты данного модуля.
	 * Откликается на событие getRoute.
	 * 
	 * @param array $routes - массив с объектами маршрутов
	 * @return array of \RS\Router\Route
	 */
	public static function getRoute(array $routes)
	{
		$routes[] = new \RS\Router\Route ( 'wishlist-front-wishlist', array('/wishlist/' 
		), null, 'Список желаний пользователя' );
		
		return $routes;
	}
	
	/**
	 * Возвращает пункты меню этого модуля в виде массива
	 * 
	 * @param array $items - массив с пунктами меню
	 * @return array
	 */
	public static function getMenus($items)
	{
		$items[] = array('title' => 'Список желаний','alias' => 'wishlist-control','link' => '%ADMINPATH%/wishlist-control/','parent' => 'modules','sortn' => 40,'typelink' => 'link' 
		);
		return $items;
	}
}