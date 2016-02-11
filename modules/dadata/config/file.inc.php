<?php

namespace DaData\Config;

use \RS\Orm\Type;

/**
 * Класс конфигурации модуля
 */
class File extends \RS\Orm\ConfigObject
{
	function _init()
	{
		parent::_init ()->append ( array('api_key' => new Type\String ( array('description' => t ( 'API-ключ' ),'hint' => t ( 'Выдаётся в личном кабинете DaData' ) 
		) ),'email_show_hint' => new Type\Integer ( array('description' => t ( 'Показывать подсказки в E-mail?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'email_show_all' => new Type\Integer ( array('description' => t ( 'Показывать в E-mail адресе только последнюю часть в подсказках?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'address_show_hint' => new Type\Integer ( array('description' => t ( 'Показывать подсказки в адресе?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'city_show_hint' => new Type\Integer ( array('description' => t ( 'Показывать подсказки в городе?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'company_show_hint' => new Type\Integer ( array('description' => t ( 'Показывать подсказки в наименовании компании?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'company_inn_input' => new Type\Integer ( array('description' => t ( 'Вставлять ИНН компании при выборе компании?' ),'default' => 1,'hint' => t ( 'Действует только если включена предыдущая опция' ),'CheckBoxView' => array(1,0 
		) 
		) ),'fio_show_hint' => new Type\Integer ( array('description' => t ( 'Вставлять подсказки в поле Ф.И.О.?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'surname_show_hint' => new Type\Integer ( array('description' => t ( 'Вставлять подсказки в поле Фамилия?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'name_show_hint' => new Type\Integer ( array('description' => t ( 'Вставлять подсказки в поле Имя?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'midname_show_hint' => new Type\Integer ( array('description' => t ( 'Вставлять подсказки в поле Отчество?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'ip_check_city' => new Type\Integer ( array('description' => t ( 'Проверять по IP город для подстановки?' ),'default' => 1,'CheckBoxView' => array(1,0 
		) 
		) ),'count' => new Type\Integer ( array('description' => t ( 'Количество выпадающих подсказок' ),'default' => 5 
		) ),'dadata_api_js' => new Type\String ( array('description' => t ( 'Путь к JS файлу jQuery API' ),'hint' => t ( 'Если поле пустое, то будет использоваться путь по умолчанию.<br/> https://dadata.ru/static/js/lib/jquery.suggestions-15.10.min.js' ) 
		) ) 
		) );
	}
}
