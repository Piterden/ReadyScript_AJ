<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Search\Model;

/**
 * Класс организации поиска
 * @ingroup Search
 */
class SearchApi
{
	protected static $default_engine = '\Search\Model\Engine\Mysql';
	
	/**
	 * Возвращает объект текущего поискового движка
	 * 
	 * @return \Search\Model\Engine\Mysql
	 */
	public static function currentEngine()
	{
		$class = self::$default_engine;
		return new $class ();
	}
}