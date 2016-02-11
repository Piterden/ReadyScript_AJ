<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Search\Config;

/**
 * Патчи к модулю
 */
class Patches extends \RS\Module\AbstractPatches
{
	/**
	 * Возвращает массив имен патчей.
	 */
	function init()
	{
		return array('20072' 
		);
	}
	
	/**
	 * Патч для релиза 2.0.0.72 и ниже
	 * Удаляет дубликаты из таблицы с поисковыми индексами, чтобы установить уникальный индекс
	 */
	function beforeUpdate20072()
	{
		$search_index_orm = new \Search\Model\Orm\Index ();
		$total = \RS\Orm\Request::make ()->from ( $search_index_orm )->count ();
		
		$distinct = \RS\Orm\Request::make ()->select ( 'COUNT(DISTINCT result_class,entity_id) as cnt' )->from ( $search_index_orm )->exec ()->getOneField ( 'cnt', 0 );
		
		if ($total != $distinct) {
			// Удаляем дубликаты
			$sqls = array("CREATE TEMPORARY TABLE search_index_tmp AS SELECT * FROM (SELECT * FROM " . $search_index_orm->_getTable () . " ORDER BY dateof DESC) as sorted_table GROUP BY result_class, entity_id","DELETE FROM " . $search_index_orm->_getTable (),"INSERT INTO " . $search_index_orm->_getTable () . " SELECT * FROM search_index_tmp","DROP TABLE search_index_tmp" 
			);
			foreach($sqls as $sql) {
				\RS\Db\Adapter::sqlExec ( $sql );
			}
		}
	}
}
