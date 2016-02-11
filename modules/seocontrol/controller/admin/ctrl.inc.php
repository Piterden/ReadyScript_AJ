<?php

namespace SeoControl\Controller\Admin;

use \RS\Html\Table\Type as TableType, \RS\Html\Table;

class Ctrl extends \RS\Controller\Admin\Crud
{
	function __construct()
	{
		parent::__construct ( new \SeoControl\Model\Api () );
	}
	function helperIndex()
	{
		$helper = parent::helperIndex ();
		$helper->setTopTitle ( t ( 'Управление SEO' ) );
		$helper->setTopHelp ( t ( 'Этот модуль может переназначить заголовки, ключевые слова и описания для определенной маски страниц.' ) );
		$helper->setBottomToolbar ( $this->buttons ( array('multiedit','delete' 
		) ) );
		
		$helper->setTable ( new Table\Element ( array('Columns' => array(new TableType\Checkbox ( 'id' ),new TableType\String ( 'url_pattern', t ( 'Маска URL' ) ),new TableType\String ( 'meta_title', t ( 'Заголовок' ) ),new TableType\String ( 'meta_keywords', t ( 'Ключевые слова' ), array('hidden' => true 
		) ),new TableType\String ( 'meta_description', t ( 'Описание' ), array('hidden' => true 
		) ),new TableType\Actions ( 'id', array(new TableType\Action\Edit ( $this->router->getAdminPattern ( 'edit', array(':id' => '~field~' 
		) ), null, array('attr' => array('@data-id' => '@id' 
		) 
		) ) 
		), array('SettingsUrl' => $this->router->getAdminUrl ( 'tableOptions' ) 
		) ) 
		) 
		) ) );
		return $helper;
	}
}


