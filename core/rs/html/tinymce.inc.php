<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace RS\Html;

/**
 * HTML элемент.
 * TinyMce
 */
class Tinymce implements ElementInterface
{
	protected $options, $data;
	protected static $ids = array();
	function __construct($options, $data)
	{
		$this->options = $options;
		$this->data = $data;
		self::$ids[] = $this->options['id'];
		
		$view = new \RS\View\Engine ();
		$view->assign ( 'param', $this->options );
		$view->assign ( 'textarea_ids', implode ( ",", self::$ids ) );
	}
	function getView()
	{
		$view = new \RS\View\Engine ();
		$view->assign ( 'param', $this->options );
		$view->assign ( 'data', $this->data );
		return $view->fetch ( 'system/admin/html_elements/tinymce/textarea.tpl' );
	}
}

