<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace RS\Html\Table\Type\Action;

/**
 * Тип инструмента - выпадающий список со значениями
 */
class DropDown extends AbstractAction
{
	protected $items = array(), $body_template = 'system/admin/html_elements/table/coltype/action/dropdown.tpl';
	function __construct(array $items)
	{
		$this->setItems ( $items );
	}
	
	/**
	 * Возвращает все пункты выпадающего меню
	 * 
	 * @return array
	 */
	function getItems()
	{
		return $this->items;
	}
	
	/**
	 * Добавляет пункты выпадающего меню
	 * 
	 * @param array $items - пункты меню
	 * @return DropDown
	 */
	function setItems(array $items)
	{
		foreach($items as $item) {
			$this->addItem ( $item );
		}
		return $this;
	}
	
	/**
	 * Добавляет один пункт к выпадающему списку
	 * 
	 * @param array $item - пункт списка
	 * @return DropDown
	 */
	function addItem($item)
	{
		$this->items[] = $item;
		return $this;
	}
}

