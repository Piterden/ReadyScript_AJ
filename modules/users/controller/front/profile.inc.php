<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Users\Controller\Front;

class Profile extends \RS\Controller\AuthorizedFront
{
    function actionIndex()
    {
        $this->app->title->addSection(t('Профиль'));
        $this->app->breadcrumbs->addBreadCrumb(t('Профиль пользователя'));
        
        $user = clone \RS\Application\Auth::getCurrentUser();
        $user->usePostKeys(array('is_company', 'company', 'company_inn', 
                            'name', 'surname', 'midname', 'phone', 
                            'e_mail', 'openpass', 'captcha', 'data', 'changepass'));
        
        if ( $this->isMyPost() )
        {
            //В новых версиях шаблона обязательно проверяем на CSRF.
            $theme = new \RS\Theme\Item( \RS\Config\Loader::getSiteConfig()->theme );
            $version = @$theme->getThemeXml()->general->version;
            if (($theme->getName() == 'default' && \RS\Helper\Tools::compareVersion('3.0.0.42', $version)) || 
                ($theme->getName() != 'default' && \RS\Helper\Tools::compareVersion('1.0.0.16', $version))) 
            {
                $this->url->checkCsrf();
            }
            
            $user->checkData();
            
            //Изменяем пароль
            if ($user['changepass']) {
                $current_pass = $this->url->request('current_pass', TYPE_STRING);
                $crypt_current_pass = $user->cryptPass($current_pass);
                if ($crypt_current_pass === $user['pass']) {
                    $user['pass'] = $crypt_current_pass;
                } else {
                    $user->addError(t('Неверно указан текущий пароль'), 'pass');
                }
                
                $password = $this->url->request('openpass', TYPE_STRING);
                $password_confirm = $this->url->request('openpass_confirm', TYPE_STRING);
                
                if (strcmp($password, $password_confirm) != 0) {
                    $user->addError(t('Пароли не совпадают'), 'openpass');
                }                            
            }

            if (!$user->hasError() && $user->save($user['id'])) {
                $_SESSION['user_profile_result'] = 'Изменения сохранены';
                \RS\Application\Auth::setCurrentUser($user); //Обновляем в пользователя в текущей сессии
                $this->refreshPage();
            }            
        }
        $conf_userfields = $user->getUserFieldsManager();
                              
        $this->view->assign(array(
            'conf_userfields' => $conf_userfields,
            'user' => $user
        ));
        
        if (isset($_SESSION['user_profile_result'])) {
            $this->view->assign('result', $_SESSION['user_profile_result']);
            unset($_SESSION['user_profile_result']);
        }
        
        return $this->result->setTemplate('profile.tpl');
    }
}
?>
