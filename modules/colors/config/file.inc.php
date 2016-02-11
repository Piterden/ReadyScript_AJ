<?php

namespace Colors\Config;

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
				'name' => t ( 'Справочник цветов' ),'description' => t ( 'Позволяет сопоставить название цвета и его код' ),'version' => '2.0.0.1','author' => 'ReadyScript lab.' 
		);
	}
}