<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Main\Controller\Admin;
use \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Table\Type as TableType,
    \RS\Html\Table;

class License extends \RS\Controller\Admin\Crud
{
    protected
        $api;
    
    function __construct()
    {
        parent::__construct(new \Main\Model\LicenseApi());
        $this->setCrudActions('index', 'del');
    }
    
    function init()
    {
        if (defined('CANT_MANAGE_LICENSE')) return '';
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex()
            ->setAppendModuleOptionsButton(false)
            ->removeSection('paginator')
            ->setListFunction('getTableList')
            ->setTopTitle(t('Управление лицензиями'))
            ->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('license_key'),
                new TableType\Usertpl('license_key', 'Лицензионный номер', '%main%/license_col_number.tpl'),
                new TableType\Text('license_type_str', 'Тип'),
                new TableType\Usertpl('object', 'Объект лицензирования', '%main%/license_col_object.tpl', array('TdAttr' => array('class' => 'cell-small'))),
                new TableType\Datetime('date_of_activation', 'Дата активации', array('format' => 'd.m.Y')),
                new TableType\Text('domain', 'Домен'),
            ))));
            
        $helper['topToolbar']
            ->addItem(new ToolbarButton\Add($this->url->replaceKey(array($this->action_var => 'addLic')), t('Добавить лицензию')), 'add')
            ->addItem(new ToolbarButton\Button($this->api->getBuyLicenseUrl(), t('Купить лицензию'), array('attr' => array('target' => '_blank'))));
        $helper['beforeTableContent'] = $this->view->fetch('license_notice.tpl');        
        return $helper;
    }
    
    function helperAddLic()
    {
        $helper = parent::helperAdd()
            ->setTopTitle(t('Введите номер лицензии'));
        
        return $helper;
    }
    
    /**
    * Добавляет лицензионный ключ
    */
    function actionAddLic($is_activate = false)
    {
        $orm_object = $this->api->getElement();
        $helper = $this->getHelper();
        
        if ($is_activate) {
            $key = $this->url->request('key', TYPE_STRING);
            $type = $this->url->request('type', TYPE_STRING);
            $elem = $this->api->getElement();
            $helper->setTopTitle(t('Активация лицензии'));
            $helper->setFormSwitch($type != 'script' ? 'extra' : 'activation');
            $elem->fillDefaultActivationValue();
            $elem['license'] = $key;
            $elem['is_activation'] = 1;
            $elem['check_domain'] = 1;
        }
    
        $orm_object->fillDefaults();
        
        //Если пост идет для текущего модуля
        if ($this->url->isPost()) 
        {
            $this->result->setSuccess( $this->api->save(null, $this->user_post_data) );
            
            //Если требуется активация ключа
            if ($this->api->getElement()->isNeedActivation()) {
                return $this->result->setSuccess(true)->addSection('callCrudAdd', $this->router->getAdminUrl('activateLicense', array('key' => $this->api->getElement()->license, 'type' => $this->api->getElement()->getLicenseType() )));
            }

            if ($this->url->isAjax()) { //Если это ajax запрос, то сообщаем результат в JSON
                if (!$this->result->isSuccess()) {
                    $this->result->setErrors($orm_object->getDisplayErrors());
                } else {
                    $this->result->setSuccessText(t('Изменения успешно сохранены'));
                    if (!$this->url->request('dialogMode', TYPE_INTEGER)) {
                        $this->result->setAjaxWindowRedirect( $this->url->getSavedUrl($this->controller_name.'index') );
                    }
                }
                return $this->result->getOutput();
            }
            
            if ($this->result->isSuccess()) {
                $this->successSave();
            } else {
                $helper['formErrors'] = $orm_object->getDisplayErrors();
            }
            
            return $this->result;
        } 
        
        $this->view->assign(array(
            'elements' => $helper->active(),
        ));
        return $this->result->setHtml($this->view->fetch( $helper['template'] ))->getOutput();
    }
    
    /**
    * Активирует лицензионный ключ
    */
    function actionActivateLicense()
    {
        return $this->actionAddLic(true);
    }
    
    /**
    * Активирует лицензионный ключ
    */
    function helperActivateLicense()
    {
        return $this->helperAddLic();
    }    
    
}
?>
