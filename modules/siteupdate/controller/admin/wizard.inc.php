<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace SiteUpdate\Controller\Admin;
use \RS\Html\Table\Type as TableType,
    \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Toolbar;

              
class Wizard extends \RS\Controller\Admin\Front
{
    protected
        $helper,
        $api;
    
    function init()
    {
        $this->api = new \SiteUpdate\Model\Api();
        
        $this->helper = new \RS\Controller\Admin\Helper\CrudCollection($this);
        $this->helper
            ->viewAsAny()
            ->setTopTitle(t('Центр обновления'));
            
        $this->view->assign(array(
            'elements' => $this->helper,        
        ));
    }
    
    function actionIndex()
    {
        $can_update = $this->api->canCheckUpdate();
        //Проверяем права у модуля
        if ($error = \RS\AccessControl\Rights::CheckRightError($this, ACCESS_BIT_WRITE)) {
            $can_update = false;
            $this->api->addError($error);
        }         
        
        if ($this->url->isPost()) {
            $count = null;
            if ($this->api->prepareProductsForUpdate($count)) {
                if ($count>1) {
                    $this->result->setSuccess(true)->setAjaxWindowRedirect( $this->router->getAdminUrl('selectProduct') );
                } else {
                    $this->result->setSuccess(true)->setAjaxWindowRedirect( $this->router->getAdminUrl('update') );
                }
            } else {
                $this->result->setSuccess(false)->setErrors($this->api->getDisplayErrors());
            }
            return $this->result;
        }
        
        $this->view->assign(array(
            'canUpdate' => $can_update,
            'errors' => $this->api->getDisplayErrors(),
            'currentStep' => '1'
        ));
        
        $this->helper['form'] = $this->view->fetch('checkupdate.tpl');
        return $this->result->setTemplate( $this->helper['template'] );                
    }
    
    function actionSelectProduct()
    {
        if ($this->url->isPost()) {
            $product = $this->url->post('update_product', TYPE_STRING);
            
            if ($this->api->prepareUpdateInfo( $product )) {
                $this->result->setSuccess(true)->setAjaxWindowRedirect( $this->router->getAdminUrl('update') );
            } else {
                $this->result->setSuccess(false)->setErrors($this->api->getDisplayErrors());
            }
            return $this->result;
        }
        
        $this->view->assign(array(
            'currentStep' => '2',
            'data' => $this->api->getPrepearedData()
        ));       
                
        $this->helper['form'] = $this->view->fetch('selectproduct.tpl');
        return $this->result->setTemplate( $this->helper['template'] ); 
    }
    
    
    function actionUpdate()
    {
        if ($this->url->isPost()) {
            $modules = $this->url->post('chk', TYPE_ARRAY);
            $is_start = $this->url->post('start', TYPE_INTEGER);
            
            if ($is_start) {
                $data = $this->api->prepareInstallUpdate($modules);
            } else {
                $data = $this->api->doUpdate();
                if (!empty($data['complete'])) {
                    $_SESSION['SUCCESS_INSTALL_TEXT'] = t('Обновления успешно установлены');                    
                }
            }
            
            if (isset($data['errors']) && $this->api->canRestore()) {
                $this->api->restoreSystem();
            }
            
            return json_encode($data);
        }
        
        $table = new \RS\Html\Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('module', array(
                    'cellAttrParam' => 'checkbox_attr'
                )),
                new TableType\Usertpl('title', t('Название модуля'), '%siteupdate%/module_col.tpl'),
                new TableType\String('my_version', t('Текущая версия')),
                new TableType\String('new_version', t('Доступная версия')),
                new TableType\Usertpl('module', '', '%siteupdate%/changelog_col.tpl')
            )
        ));
        
        $this->api->compareVersions();
        $data = $this->api->getPrepearedData();
        $table->setData( $data['updateData'] );

        $button = !count($data['updateData']) ? 
                    new ToolbarButton\Cancel($this->router->getAdminUrl(false) , t('назад')) 
                    : new ToolbarButton\SaveForm($this->router->getAdminUrl('update'), t('установить выбранные обновления'), array('noajax' => true));
        
        
        $this->helper->setBottomToolbar(new Toolbar\Element( array(
            'Items' => array($button)
        )));        
        
        $this->view->assign(array(
            'success_text' => isset($_SESSION['SUCCESS_INSTALL_TEXT']) ? $_SESSION['SUCCESS_INSTALL_TEXT'] : false,
            'table' => $table,
            'data' => $data,
            'currentStep' => '3'
        ));
        
        unset($_SESSION['SUCCESS_INSTALL_TEXT']);
        $this->helper['form'] = $this->view->fetch('update.tpl');
        return $this->result->setTemplate( $this->helper['template'] );
    }
    
    function actionViewChangelog()
    {
        $module = $this->url->request('module', TYPE_STRING);
        $data = $this->api->getPrepearedData();
        $module_name = $data['updateData'][$module]['title'];
        
        $this->helper
            ->viewAsForm()
            ->setTopTitle(t('Изменения в модуле {name}'), array('name' => $module_name) )
            ->setBottomToolbar(new Toolbar\Element( array(
                'Items' => array(
                    new ToolbarButton\Cancel($this->router->getAdminUrl('update'))
                )
            )));
        
        $this->view->assign('changelog', $this->api->getChangelog($module));
        $this->helper['form'] = $this->view->fetch('view_changelog.tpl');
        
        return $this->result->setTemplate( $this->helper['template'] );
    }

    
}
?>
