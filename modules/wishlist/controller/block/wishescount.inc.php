<?php

namespace Wishlist\Controller\Block;
 // Задаем пространство имен, соответствующее пути к файлу, относительно папки /modules
use \RS\Orm\Type;

/**
 * Класс блочного контроллера "Список желаний".
 * Будем наследовать от абстрактного класса блочных контроллеров, в котором реализовано все необходимое.
 */
class WishesCount extends \RS\Controller\StandartBlock
{
	protected static $controller_title = 'Количество желаний пользователя', // Краткое название контроллера
$controller_description = 'Отображает количество желаний пользователя'; // Описание контроллера
	protected $default_params = array('indexTemplate' => 'blocks/wishescount/list.tpl','pageSize' => '5' 
	);
	public function getParamObject()
	{
		return parent::getParamObject ()->appendProperty ( array('indexTemplate' => new Type\String ( array('description' => t ( 'Tpl' ) 
		) ) 
		) );
	}
	
	/**
	 * Action контроллера
	 * 
	 * @return \RS\Controller\Result\Standart
	 */
	function actionIndex()
	{
		$this->api = new \Wishlist\Model\WishApi ();
		$this->api->setFilter ( 'user_id', $this->user['id'] );
		
		$this->view->assign ( array('wishCount' => $this->api->getListCount (),'wishList' => $this->api->getList () 
		) );
		
		return $this->result->setTemplate ( $this->getParam ( 'indexTemplate' ) );
	}
}