<?php
namespace Colors\Controller\Admin;

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
        parent::__construct(new \Colors\Model\Api());
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex();
        $helper->setTopTitle(t('Цвета'));
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id'),
                new TableType\String('title', t('Название цвета'), array(
                    'Sortable' => SORTABLE_BOTH, 
                    'href' => $this->router->getAdminPattern('edit', array(':id' => '@id')), 
                    'LinkAttr' => array('class' => 'crud-edit')
                )),
                new TableType\Usertpl('color1', t('Код первого цвета'), $this->mod_tpl.'color_column.tpl'),
                new TableType\Usertpl('color2', t('Код второго цвета'), $this->mod_tpl.'color_column.tpl'),
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


