<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Support\Controller\Block;

class NewMessages extends \RS\Controller\Block
{
	function actionIndex()
	{
		$api = new \Support\Model\Api ();
		$user_id = \RS\Application\Auth::getCurrentUser ()->id;
		$this->view->assign ( 'new_count', $api->getNewMessageCount ( $user_id ) );
		return $this->result->setTemplate ( 'blocks/new_messages.tpl' );
	}
}