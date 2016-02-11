<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace RS\Orm\Type;

class UserTemplate extends AbstractType
{
	protected $php_type = '', $me_visible = false, $runtime = true;
	function __construct($template, $meTemplate = null, $options = null)
	{
		$this->setTemplate ( $template );
		$this->setMeTemplate ( $meTemplate );
		parent::__construct ( $options );
	}
	function formView($view_options = null)
	{
		$sm = new \RS\View\Engine ();
		$sm->assign ( array('field' => $this,'view_options' => $view_options !== null ? array_combine ( $view_options, $view_options ) : null 
		) );
		
		return $sm->fetch ( $this->getRenderTemplate () );
	}
}
?>
