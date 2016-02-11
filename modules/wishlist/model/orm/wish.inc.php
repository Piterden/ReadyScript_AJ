<?php

namespace Wishlist\Model\Orm;

use \RS\Orm\Type;

/**
 * ORM объект
 */
class Wish extends \RS\Orm\OrmObject
{
	protected static $table = 'wishlist';
	
	/**
	 * Инициализирует свойства ORM объекта
	 * 
	 * @return void
	 */
	function _init()
	{
		parent::_init ()->append ( array('site_id' => new Type\CurrentSite (), // Создаем поле, которое будет содержать id текущего сайта
'user_id' => new Type\Integer ( array('maxLength' => '12','description' => t ( 'Идентификатор пользователя' ) 
		) ),'user_name' => new Type\String ( array('maxLength' => '100','description' => t ( 'Имя пользователя' ) 
		) ),'product_id' => new Type\Integer ( array('maxLength' => '12','description' => t ( 'Идентификатор товара' ) 
		) ),'product_name' => new Type\String ( array('maxLength' => '100','description' => t ( 'Название товара' ) 
		) ) 
		) );
	}
}
