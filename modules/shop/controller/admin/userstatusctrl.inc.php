<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Controller\Admin;
use \RS\Html\Table\Type as TableType,
    \RS\Html\Table;

class UserStatusCtrl extends \RS\Controller\Admin\Crud
{
    function __construct()
    {
        parent::__construct(new \Shop\Model\UserStatusApi());
    }
    
    function helperIndex()
    {     
        $helper = parent::helperIndex();
        $helper->setTopToolbar($this->buttons(array('add'), array('add' => t('Добавить статус заказа'))));
        $helper->setTopTitle(t('Статусы заказа'));
        $helper->setListFunction('tableData');
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id', array('cellAttrParam' => 'checkbox_attribute')),
                new TableType\Text('title', t('Название'), array('Sortable' => SORTABLE_BOTH, 'href' => $this->router->getAdminPattern('edit', array(':id' => '@id') ), 'LinkAttr' => array('class' => 'crud-edit') )),
                new TableType\Usertpl('bgcolor', t('Цвет'), '%shop%/color_cell.tpl'),
                new TableType\Text('id', 'ID', array('Sortable' => SORTABLE_BOTH, 'CurrentSort' => SORTABLE_ASC, 'TdAttr' => array('class' => 'cell-sgray'))),
                new TableType\Text('type', t('Тип'), array('Sortable' => SORTABLE_BOTH, 'TdAttr' => array('class' => 'cell-sgray'))),
                
                new TableType\Actions('id', array(
                        new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~')), null, array(
                            'attr' => array(
                                '@data-id' => '@id'
                            ))),                        
                    ),
                    array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))
                ),
            ),
            ''
        )));
        
        return $helper;
    }
    
    public function actionAdd($primaryKeyValue = null, $returnOnSuccess = false, $helper = null)
    {
        if ($primaryKeyValue && $this->api->getElement()->isSystem()) {
            $this->api->getElement()->__type->setAttr(array('disabled' => true));
        }
        
        $this->getHelper()->setTopTitle($primaryKeyValue ? t('Редактировать статус').' {title}' : t('Добавить статус для заказов'));
        
        return parent::actionAdd($primaryKeyValue, $returnOnSuccess, $helper);
    }
    
}
?>
