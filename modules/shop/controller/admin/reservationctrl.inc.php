<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Shop\Controller\Admin;

use \RS\Html\Table\Type as TableType, \RS\Html\Toolbar\Button as ToolbarButton, \RS\Html\Filter, \RS\Html\Table;

/**
 * Контроллер Управление налогами
 */
class ReservationCtrl extends \RS\Controller\Admin\Crud
{
	function __construct()
	{
		parent::__construct ( new \Shop\Model\ReservationApi () );
	}
	function helperIndex()
	{
		$helper = parent::helperIndex ();
		$helper->setTopToolbar ( null );
		$helper->setTopTitle ( t ( 'Предварительные заказы' ) );
		$helper->setTable ( new Table\Element ( array('Columns' => array(new TableType\Checkbox ( 'id' ),new TableType\String ( 'product_title', t ( 'Товар' ), array('href' => $this->router->getAdminPattern ( 'edit', array(':id' => '@id' 
		) ),'LinkAttr' => array('class' => 'crud-edit' 
		) 
		) ),new TableType\String ( 'amount', t ( 'Количество' ) ),new TableType\String ( 'phone', t ( 'Телефон' ) ),new TableType\String ( 'email', t ( 'E-mail' ) ),new TableType\String ( 'dateof', t ( 'Дата' ), array('Sortable' => SORTABLE_BOTH,'href' => $this->router->getAdminPattern ( 'edit', array(':id' => '@id' 
		) ),'LinkAttr' => array('class' => 'crud-edit' 
		) 
		) ),new TableType\String ( 'status', t ( 'Статус' ) ),new TableType\Actions ( 'id', array(new TableType\Action\Edit ( $this->router->getAdminPattern ( 'edit', array(':id' => '~field~' 
		) ), null, array('attr' => array('@data-id' => '@id' 
		) 
		) ) 
		), array('SettingsUrl' => $this->router->getAdminUrl ( 'tableOptions' ) 
		) ) 
		) 
		) ) );
		
		$helper->setFilter ( new Filter\Control ( array('Container' => new Filter\Container ( array('Lines' => array(new Filter\Line ( array('items' => array(new Filter\Type\String ( 'product_id', 'ID товара' ),new Filter\Type\String ( 'email', 'E-mail', array('SearchType' => '%like%' 
		) ),new Filter\Type\String ( 'phone', 'Телефон', array('SearchType' => '%like%' 
		) ),new Filter\Type\User ( 'user_id', 'Пользователь' ) 
		) 
		) ) 
		),'SecContainers' => array(new Filter\Seccontainer ( array('Lines' => array(new Filter\Line ( array('items' => array(new Filter\Type\Date ( 'dateof', 'Дата заказа', array('showType' => true 
		) ),new Filter\Type\String ( 'amount', 'Количество', array('showType' => true 
		) ),new Filter\Type\Select ( 'status', 'Статус', array('' => 'Любой','open' => 'Открыт','close' => 'Закрыт' 
		) ) 
		)
		 
		) ) 
		) 
		) ) 
		) 
		) ) 
		) ) );
		
		return $helper;
	}
}
