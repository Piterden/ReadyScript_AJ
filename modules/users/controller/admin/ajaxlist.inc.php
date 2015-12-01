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
		$list = $api->getLike($term, array('login', 'surname', 'name'));
		$json = array();
		foreach ($list as $user)
		{
			$json[] = array(
				'label' => $user['surname'].' '.$user['name'].' '.$user['midname'],
                'id' => $user['id'],
				'email' => $user['e_mail'],
				'desc' => t('E-mail:').$user['e_mail'].' ; '.t('Логин').':'.$user['login']
			);
		}
		
		return json_encode($json);
	}
}

