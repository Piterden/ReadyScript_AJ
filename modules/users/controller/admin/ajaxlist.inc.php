<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Users\Controller\Admin;

/**
* Контроллер отдает список пользователей для компонента JQuery AutoComplete
* @ingroup Users
*/
class AjaxList extends \RS\Controller\Admin\Front
{
	function actionAjaxEmail()
	{
		$term = $this->url->request('term', TYPE_STRING);
		$api = new \Users\Model\Api();
		$list = $api->getLike($term, array('login', 'surname', 'name', 'company', 'company_inn'));
		$json = array();
		foreach ($list as $user)
		{
			$json[] = array(
				'label' => $user['surname'].' '.$user['name'].' '.$user['midname'],
                'id' => $user['id'],
				'email' => $user['e_mail'],
				'desc' => t('Логин').':'.$user['login']. 
                    ($user['company'] ?  " ; {$user['company']}(ИНН:{$user['company_inn']})" : '')
			);
		}
		
		return json_encode($json);
	}
}

