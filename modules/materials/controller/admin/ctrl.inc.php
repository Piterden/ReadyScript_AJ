<?php
namespace Materials\Controller\Admin;

use \RS\Html\Table\Type as TableType,
    \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Table,
    \RS\Html\Filter;

class Ctrl extends \RS\Controller\Admin\Crud
{
    protected
        $api;
    
    function __construct()
    {
        parent::__construct(new \Materials\Model\Api());
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex();
        $helper->setTopTitle(t('Материалы'));
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id'),
                new TableType\String('title', t('Название материала'), array(
                    'Sortable' => SORTABLE_BOTH, 
                    'href' => $this->router->getAdminPattern('edit', array(':id' => '@id')), 
                    'LinkAttr' => array('class' => 'crud-edit') 
                )),
                new TableType\Actions('id', array(
                        new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~'))),
                    ),
                    array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))
                ),
            )
        )));
        return $helper;
    }

}


