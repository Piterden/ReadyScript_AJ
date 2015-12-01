<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Controller\Admin;

use \RS\Html\Table\Type as TableType,
    \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Toolbar,
    \RS\Html\Filter,
    \RS\Html\Table;
    
/**
* Контроллер Управление скидочными купонами
*/
class RegionCtrl extends \RS\Controller\Admin\Crud
{
    protected
        $parent,
        $api;
    
    function __construct()
    {
        parent::__construct(new \Shop\Model\RegionApi());
    }
    
    function helperIndex()
    {
        $this->parent = $this->url->request('pid', TYPE_INTEGER, 0);
        
        $helper = parent::helperIndex();
        $helper->setTopToolbar(new Toolbar\Element( array(
            'Items' => array(
                new ToolbarButton\Add($this->router->getAdminUrl('add', array('pid' => $this->parent)), 'Добавить')
            )
        )));
        
        $helper->addCsvButton('shop-region', array('whereCondition' => ''));
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id'),
                new TableType\String('title', 'Название', array('Sortable' => SORTABLE_BOTH, 'CurrentSort' => SORTABLE_ASC, 'href' => $this->router->getAdminPattern(false, array(':pid' => '@id')), 'LinkAttr' => array('class' => 'call-update') )),
                new TableType\String('id', '№', array('ThAttr' => array('width' => '50'), 'Sortable' => SORTABLE_BOTH)),                
                new TableType\Actions('id', array(
                        new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~')), null, array(
                            'attr' => array(
                                '@data-id' => '@id'
                            ))),
                        new TableType\Action\DropDown(array(
                                array(
                                    'title' => t('Клонировать регион'),
                                    'attr' => array(
                                        'class' => 'crud-add',
                                        '@href' => $this->router->getAdminPattern('clone', array(':id' => '~field~')),
                                    )
                                ),                                 
                        )),
                    )
                ),                
            )        
        )));
        
        $helper->setFilter(new Filter\Control( array(
            'Container' => new Filter\Container( array( 
                                'Lines' =>  array(
                                    new Filter\Line( array('Items' => array(
                                                            new Filter\Type\String('id','№', array('attr' => array('class' => 'w100'))),
                                                            new Filter\Type\String('title', 'Название', array('SearchType' => '%like%')),
                                                        )
                                    ))
                                )
                            )),
            'Caption' => t('Поиск по регионам'),
            'AddParam' => array('hiddenfields' => array('pid' => $this->parent))
        )));
        
        return $helper;
    }
    
    function actionIndex()
    {
        $helper = $this->getHelper();
        if ($this->parent > 0) {
            $parent_item = $this->api->getOneItem($this->parent);
            
            $helper
                ->setTopTitle($parent_item['title'].'. '.t('Регионы доставки'));
            
            $columns = $helper['table']->getTable()->getColumns();    
            $columns[1]->setHref($this->router->getAdminPattern('edit', array(':id' => '@id')));
            $columns[1]->setLinkAttr(array('class' => 'crud-edit'));
            
            $helper['table']->getTable()->insertAnyRow(array(
                new TableType\String(null, null, array('href' => $this->router->getAdminUrl(false, array('pid' => 0)), 'Value' => '.. (к списку стран)', 'LinkAttr' => array('class' => 'call-update'), 'TdAttr' => array('colspan' => 4)))
            ), 0);                     
            
        } else {
            $helper
                ->setTopTitle(t('Страны и регионы доставки'));            
        }
        
        $this->api->setFilter('parent_id', $this->parent);
                
        return parent::actionIndex();
    }
    
    /**
    * Добавление купонов
    */
    function actionAdd($primaryKey = null, $returnOnSuccess = false, $helper = null)
    {
        if ($primaryKey === null) {
            $this->api->getElement()->parent_id = $this->url->request('pid', TYPE_INTEGER);
        }
        if (!$primaryKey) { //0 или null
            $this->getHelper()->setTopTitle(t('Добавить регион'));
        } else {
            $this->getHelper()->setTopTitle(t('Редактировать регион').' {title}');        
        }
        return parent::actionAdd($primaryKey, $returnOnSuccess, $helper);
    }
}