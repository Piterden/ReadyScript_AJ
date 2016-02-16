<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Users\Controller\Block;

/**
* Блок авторизации
*/
class AuthBlock extends \RS\Controller\StandartBlock
{
    protected static
        $controller_title = 'Блок авторизации',        //Краткое название контроллера
        $controller_description = 'Отображает ссылки войти/зарегистрироваться или имя текущего пользователя и персональным меню';  //Описание контроллера        
        
    protected
        $default_params = array(
            'indexTemplate' => 'blocks/authblock/authblock.tpl', //Должен быть задан у наследника
        );    
    
    function actionIndex()
    {
        if (\RS\Module\Manager::staticModuleExists('shop')) {
            $this->view->assign('use_personal_account', \RS\Config\Loader::byModule('shop')->use_personal_account);
        }
        return $this->result->setTemplate( $this->getParam('indexTemplate') );
    }
}
?>
