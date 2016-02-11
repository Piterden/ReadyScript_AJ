<?php

namespace CodeGen\Model;

use RS\Orm\FormObject;
use RS\Orm\Type\String;
use RS\Orm\Type\Text;

class GenerateModuleForm extends FormObject
{
	function __construct()
	{
		$properties = new \RS\Orm\PropertyIterator ( array('name' => new String ( array('maxLength' => 25,'description' => t ( 'Имя модуля (только английские буквы)' ),'checker' => array('chkEmpty','Укажите имя дополнения' 
		),' checker' => array('chkPattern','Неверные символы в имени дополнения','/^[a-z0-9]+$/i' 
		),'  checker' => array('chkPattern','Не должно начинаться с цифры','/^[^0-9]+[a-z0-9]+$/i' 
		),'   checker' => array(function ($form, $val) {
			if (is_dir ( \Setup::$PATH . \Setup::$MODULE_FOLDER . '/' . strtolower ( $val ) )) {
				return t ( 'Папка модуля уже существует. Пожалуйста, удалите папку или придумайте другое имя' );
			}
			return true;
		} 
		),'attr' => array(array('placeholder' => 'MyModule' 
		) 
		) 
		) ),'title' => new String ( array('maxLength' => 255,'description' => t ( 'Отображаемое название модуля' ),'checker' => array('chkEmpty','Поле должно быть заполнено' 
		),'attr' => array(array('placeholder' => 'Мой модуль' 
		) 
		) 
		) ),'description' => new Text ( array('description' => t ( 'Краткое описание модуля' ),'checker' => array('chkEmpty','Поле должно быть заполнено' 
		),'attr' => array(array('placeholder' => 'Описание моего модуля' 
		) 
		) 
		) ),'author' => new String ( array('description' => t ( 'Автор' ),'checker' => array('chkEmpty','Поле должно быть заполнено' 
		),'attr' => array(array('placeholder' => 'My Company' 
		) 
		) 
		) ) 
		) );
		
		parent::__construct ( $properties );
	}
}