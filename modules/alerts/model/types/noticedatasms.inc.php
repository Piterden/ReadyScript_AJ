<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Alerts\Model\Types;

class NoticeDataSms
{
	/**
	 * Телефон получателя
	 * 
	 * @var string
	 */
	public $phone;
	
	/**
	 * Данные, передаваемы в шаблон
	 * 
	 * @var mixed
	 */
	public $vars;
}

