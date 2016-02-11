<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace RS\Orm;

use RS\Orm\Type;

/**
 * Базовый core объект для конфигурационных файлов модулей
 */
abstract class ConfigObject extends AbstractObject implements \RS\Module\ConfigInterface
{
	const BIT_COUNT = 8; // Количество битов для прав модулей
	public static $table = 'module_config';
	protected static $init_default_method = '_configInitDefaults';
	public 
	// Описывает за что отвечает каждый из 8-ми бит числа, обозначающего уровень доступа
	// Каждый модуль может определить свою таблицу значимости битов
	$access_bit = array('Чтение', // 1-й бит - будет означать Разрешение на Чтение
'Запись' 
	) // 2-й бит будет означать разрешение на Запись
;
	
	/**
	 * Объявляет стандартные поля у объектов
	 * 
	 * @return \RS\Orm\PropertyIterator
	 */
	function _init()
	{
		$properties = $this->getPropertyIterator ()->append ( array(t ( 'Основные' ),'name' => new Type\String ( array('useToSave' => false,'description' => t ( 'Название модуля' ),'readOnly' => true 
		) ),'description' => new Type\String ( array('useToSave' => false,'maxLength' => 500,'description' => t ( 'Описание' ),'readOnly' => true 
		) ),'is_system' => new Type\Integer ( array('useToSave' => false,'maxLength' => 1,'description' => t ( 'Это системный модуль?' ),'visible' => false 
		) ),'dependfrom' => new Type\String ( array('useToSave' => false,'description' => t ( 'Зависит от модулей(через запятую)' ),'visible' => false,'readOnly' => true 
		) ),
				// Пример версии: 0.1.0.0 -
				// первая цифра - изменения, влияющие на совместимость,
				// вторая - изменение в стуктуре БД,
				// третья - мелкие правки,
				// четвертая - ревизия из системы контроля версий
				'version' => new Type\String ( array('useToSave' => false,'maxLength' => 10,'description' => t ( 'Версия модуля' ),'readOnly' => true 
				) ),'version_date' => new Type\String ( array('useToSave' => false,'maxLength' => '20','visible' => false 
				) ),/**
				 * core_version
				 * Например: '0.1.0.0' (одна версия)
				 * или '0.1.0.0 - 0.2.0.0' (Диапазон версий)
				 * или '>=0.1.0.156' или '<=0.1.0.200' (для всех версий младше или старше требуемой)
				 * Можно указать смешанно, через запятую так: '<=0.1.0.200, 0.2.0.0 - 0.3.0.0, 1.0.0.0, 1.1.0.0'
				 */
				'core_version' => new Type\String ( array('useToSave' => false,'description' => t ( 'Необходимая версия системы' ),'readOnly' => true 
				) ),'author' => new Type\String ( array('useToSave' => false,'description' => t ( 'Автор модуля' ),'readOnly' => true 
				) ),'installed' => new Type\Integer ( array('maxLength' => 1,'visible' => false 
				) ),
				// Timestamp обновления модуля
				'lastupdate' => new Type\Integer ( array('visible' => false 
				) ),'enabled' => new Type\Integer ( array('maxLength' => 1,'description' => t ( 'Включен' ),'checkboxview' => array(1,0 
				) 
				) ),
				// Утилиты по обслуживанию модуля
				'tools' => new Type\ArrayList ( array('useToSave' => false,'visible' => false 
				) ) 
		)
		 );
		return $properties;
	}
	function getPrimaryKeyProperty()
	{
		return array('site_id','module' 
		);
	}
	function getStorageInstance()
	{
		return new \RS\Orm\Storage\Serialized ( $this, array('primary' => array('module' => \RS\Module\Item::nameByObject ( $this, false ) 
		) 
		) );
	}
	function _configInitDefaults()
	{
		$this['enabled'] = true; // Включаем по-умолчанию модуль
		$this['installed'] = false;
		$this['site_id'] = \RS\Site\Manager::getSiteId ();
		$this->getFromArray ( $this->getDefaultValues () );
		$this->_initDefaults (); // Вызов стандартного метода установки параметров
	}
	function load($site_id = null)
	{
		if ($site_id !== null) {
			$this['site_id'] = $site_id;
		}
		
		if ($this['site_id']) {
			$result = parent::load ();
			if ($result === false) {
				// Проверяем установлен ли модуль.
				// Модуль считается установленным, если сохранена конфигурация хотя бы для одного сайта
				$this_module = \RS\Module\Item::nameByObject ( $this, false );
				$this['installed'] = \RS\Orm\Request::make ()->from ( $this )->where ( array('module' => $this_module 
				) )->count () > 0;
			}
		}
		return true;
	}
	
	/**
	 * Возвращает папку для хранения файлов данного модуля
	 * 
	 * @return string
	 */
	function getModuleStorageDir()
	{
		$this_module = \RS\Module\Item::nameByObject ( $this, false );
		$module_storage_dir = \Setup::$PATH . \Setup::$STORAGE_DIR . DS . $this_module;
		if (! is_dir ( $module_storage_dir )) {
			\RS\File\Tools::makePath ( $module_storage_dir );
		}
		return $module_storage_dir;
	}
	
	/**
	 * Загружает значения свойств по-умолчанию из файла module.xml
	 * При перегрузке данного метода, обязательно вызывайте родительский метод
	 * 
	 * @return array
	 */
	public static function getDefaultValues()
	{
		$this_module = \RS\Module\Item::nameByObject ( get_called_class () );
		$filename = \Setup::$PATH . \Setup::$MODULE_FOLDER . '/' . $this_module . \Setup::$CONFIG_FOLDER . '/' . \Setup::$CONFIG_XML;
		if (! $result = \RS\Module\Item::parseModuleXml ( $filename )) {
			throw new \RS\Module\Exception ( t ( 'Не удается найти или распарсить XML файл конфигурации модуля %module - %file', array('module' => $this_module,'file' => $filename 
			) ) );
		}
		
		return $result;
	}
}
