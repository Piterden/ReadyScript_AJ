<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace SiteUpdate\Controller\Admin\Widget;

class CheckUpdates extends \RS\Controller\Admin\Widget
{
    protected
        $info_title = 'Проверка обновлений',
        $info_description = 'Информирует о доступности обновлений для платформы ReadyScript',
        $action_var = 'cudo';
        
    public
        $api;
    
    function init()
    {
        $this->api = new \SiteUpdate\Model\Api();
        $this->api->writeDataToFile(false);
    }
    
    function actionIndex()
    {
        if ($this->api->canCheckUpdate() === true) {
            if (($expire_days = $this->api->getUpdateExpireDays())>0) {
                $update_data = $this->api->getCachedUpdateData();
                if ($update_data === false) {
                    $state = 'checking'; //Будет вызвана повторная проверка
                } else {
                    if ($update_data['error']) {
                        $state = 'error';
                        $this->view->assign('msg', $update_data['error']);
                    } else {
                        $state = $update_data['has_updates'] ? 'needupdate' : 'actual';
                    }
                }
            } else {
                $state = 'expirelicense';
            }
        } else {
            $expire_days = 0;
            $state = 'nolicense';
        }
        
        $this->view->assign(array(
            'expire_days' => $expire_days,
            'state' => $state
        ));
        return $this->result->setTemplate('widget/checkupdates.tpl');
    }
    
    function actionCheckUpdates()
    {
        $this->api->checkUpdates();
        return $this->actionIndex();
    }
} 
?>