<?php

namespace Materials\Config;

class File extends \RS\Orm\ConfigObject
{
	/**
	 * Возвращает значения свойств по-умолчанию
	 * 
	 * @return array
	 */
	public static function getDefaultValues()
	{
		return array(
				// Default - значения
				'name' => t ( 'Справочник материалов' ),'description' => t ( 'Позволяет добавить к названию материала доп. текст' ),'version' => '1.0.0.1','author' => 'YellowmarkerWS' 
		);
	}
}