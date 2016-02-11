<?php

namespace Optlists\Controller\Admin;

use \RS\Html\Table\Type as TableType, \RS\Html\Toolbar\Button as ToolbarButton, \RS\Html\Table, \RS\Html\Filter;

class Ctrl extends \RS\Controller\Admin\Crud
{
	protected $api;
	function __construct()
	{
		parent::__construct ( new \Optlists\Model\Api () );
	}
	function helperIndex()
	{
		$helper = parent::helperIndex ();
		$helper->setTopTitle ( t ( 'Опции' ) );
		$helper->setTable ( new Table\Element ( array('Columns' => array(new TableType\Checkbox ( 'id' ),new TableType\String ( 'title', t ( 'Название опции' ), array('Sortable' => SORTABLE_BOTH,'href' => $this->router->getAdminPattern ( 'edit', array(':id' => '@id' 
		) ),'LinkAttr' => array('class' => 'crud-edit' 
		) 
		) ),new TableType\Actions ( 'id', array(new TableType\Action\Edit ( $this->router->getAdminPattern ( 'edit', array(':id' => '~field~' 
		) ), null, array('attr' => array('@data-id' => '@id' 
		) 
		) ),new TableType\Action\DropDown ( array(array('title' => t ( 'клонировать опцию' ),'attr' => array('class' => 'crud-add','@href' => $this->router->getAdminPattern ( 'clone', array(':id' => '~field~' 
		) ) 
		) 
		),array('title' => t ( 'удалить' ),'attr' => array('class' => 'crud-get','data-confirm-text' => t ( 'Вы действительно хотите удалить данную опцию?' ),'@href' => $this->router->getAdminPattern ( 'del', array(':chk[]' => '@id' 
		) ) 
		) 
		) 
		) ) 
		), array('SettingsUrl' => $this->router->getAdminUrl ( 'tableOptions' ) 
		) ) 
		) 
		) ) );
		return $helper;
	}
}


