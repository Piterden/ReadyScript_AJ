<?php

namespace Imldelivery\Model\Orm;

use \RS\Orm\Type;

/**
 * ORM объект
 */
class Model extends \RS\Orm\OrmObject
{
	protected static $table = 'imldelivery';
	function _init()
	{
		parent::_init ()->append ( array('title' => new Type\String ( array('description' => t ( 'Название' ) 
		) ) 
		) );
	}
}
