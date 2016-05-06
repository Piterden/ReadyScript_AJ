<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Controller\Admin;
use \RS\Html\Table\Type as TableType,
    \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Toolbar,
    \RS\Html\Tree,
    \RS\Html\Filter,
    \RS\Html\Table;

/**
* Контроллер покупки в 1 клик в админ. панели   
*/
class OneClickCtrl extends \RS\Controller\Admin\Crud
{
    
    function __construct()
    {
        parent::__construct(new \Catalog\Model\OneClickItemApi());
    }
    
    function actionIndex()
    {          
        $this->getHelper()->setTopTitle(t('Покупки в 1 клик')); //Заголовок

        return parent::actionIndex();
    }
    
    function helperIndex()
    {
        
        $collection = parent::helperIndex();
        $collection->setTopToolbar(null);
        
        //Параметры таблицы в админке 
        $collection->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id', array('ThAttr' => array('width' => '20'), 'TdAttr' => array('align' => 'center'))),
                new TableType\Text('title', 'Название', array(
                                    'href' => $this->router->getAdminPattern('edit', array(':id' => '@id')), 
                                    'LinkAttr' => array('class' => 'crud-edit'),
                                    'Sortable' => SORTABLE_BOTH, 
                                    'CurrentSort' => SORTABLE_ASC
                )),
                new TableType\Datetime('dateof', 'Дата создания', array('Sortable' => SORTABLE_BOTH, 'CurrentSort' => SORTABLE_ASC)),
                new TableType\Text('status', 'Статус', array('Sortable' => SORTABLE_BOTH, 'CurrentSort' => SORTABLE_ASC)),
                new TableType\Text('id', '№', array(
                    'TdAttr' => array('class'=> 'cell-sgray'),
                    'Sortable' => SORTABLE_BOTH, 
                    'CurrentSort' => SORTABLE_ASC
                )),
                new TableType\Actions('id', array(
                            new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~')),null, array(
                                'attr' => array(
                                    '@data-id' => '@id'
                                )
                            ))
                        ),
                        array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))
                    ),                 
            )
        )));
        
        //Параметры фильтра
        $collection->setFilter(new Filter\Control( array(
            'Container' => new Filter\Container( array( 
                                'Lines' =>  array(
                                    new Filter\Line( array('items' => array(
                                                            new Filter\Type\Text('id','№', array('Attr' => array('size' => 4))),
                                                            new Filter\Type\Text('title',t('Название'),array(
                                                                'SearchType' => '%like%'
                                                            )),
                                                            new Filter\Type\Datetime('dateof',t('Дата создания')),
                                                            new Filter\Type\Select('status',t('Статус'),array(
                                                                'new'    => 'Новый',
                                                                'viewed' => 'Закрыт',
                                                            )),
                                                        )
                                    ))
                                )
                            )),
            'ToAllItems' => array('FieldPrefix' => $this->api->defAlias())
        )));
        
        
            
        $collection->viewAsTable();
        return $collection;
    }
       
    
    /**
    * AJAX
    */
    function actionMove()
    {
        $from = $this->url->request('from', TYPE_INTEGER);
        $to = $this->url->request('to', TYPE_INTEGER);
        $direction = $this->url->request('flag', TYPE_STRING);
        return $this->result->setSuccess( $this->api->moveElement($from, $to, $direction) )->getOutput();
    }
}


