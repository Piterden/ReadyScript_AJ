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
    \RS\Html\Table;

class Propctrl extends \RS\Controller\Admin\Crud
{
    protected
        $form_tpl = 'form_prop.tpl',
        $me_form_tpl = 'me_prop.tpl',
        $action_var = 'do',
        $dir_api,
        $dir,
        $api;
        
    function __construct()
    {
        parent::__construct(new \Catalog\Model\PropertyApi());
        $this->dir_api = new \Catalog\Model\PropertyDirApi();
    }
    
    function addResource()
    {
        $this->app->addCss($this->mod_css.'property.css', null, BP_ROOT);
    }
    
    function actionIndex()
    {
        if (!$this->dir_api->getOneItem($this->dir)) {
            $this->dir = 0; //Если категории не существует, то выбираем пункт "Все"
        }
        $this->api->setFilter('parent_id', $this->dir);            
        
        return parent::actionIndex();
    }
    
    function helperIndex()
    {        
        $helper = parent::helperIndex();
        $helper->setTopTitle(t('Характеристики'));
        $this->dir = $this->url->request('dir', TYPE_INTEGER);
        $helper->setTopToolbar($this->buttons(array('add'), array('add' => t('добавить характеристику'))));
        $helper->addCsvButton('catalog-property');
        
        $helper->setTopHelp($this->view->fetch('help/propctrl_index.tpl'));
        $helper->setBottomToolbar($this->buttons(array('multiedit', 'delete')));
        $helper->viewAsTableTree();
        
        $helper->setTable(new Table\Element(array(
                'Columns' => array(
                    new TableType\Checkbox('id', array('showSelectAll' => true)),
                    new TableType\Sort('sortn', t('Порядок'), array('sortField' => 'id', 'Sortable' => SORTABLE_ASC,'CurrentSort' => SORTABLE_ASC,'ThAttr' => array('width' => '20'))),                    
                    new TableType\Text('title', t('Название'), array('LinkAttr' => array('class' => 'crud-edit'), 'Sortable' => SORTABLE_BOTH, 'href' => $this->router->getAdminPattern('edit', array(':id' => '@id', 'dir' => $this->dir)))),
                    new TableType\Text('unit', t('Ед. изм')),
                    new TableType\Text('type', t('Тип')),
                    new TableType\Text('id', '№', array('TdAttr' => array('class' => 'cell-sgray'))),
                    new TableType\Actions('id', array(
                        new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~', 'dir' => $this->dir)), null, 
                        array(
                            'attr' => array(
                            '@data-id' => '@id'
                        ))),
                        new TableType\Action\DropDown(array(
                            array(
                                'title' => t('Клонировать характеристику'),
                                'attr' => array(
                                    'class' => 'crud-add',
                                    '@href' => $this->router->getAdminPattern('clone', array(':id' => '~field~')),
                                )
                            ),  
                            array(
                                'title' => t('удалить'),
                                'class' => 'crud-get',
                                'attr' => array(
                                    '@href' => $this->router->getAdminPattern('del', array(':chk[]' => '@id')),
                                )
                            ),
                        ))),
                        array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))
                        ),                                        
                ),
            'TableAttr' => array(
                'data-sort-request' => $this->router->getAdminUrl('move')
            )                
        )));
        
        if ($this->dir>0 && $helper->getTreeViewType() == \RS\Controller\Admin\Helper\CrudCollection::VIEW_CAT_TOP) {
            $path_to_first = array($this->dir_api->getOneItem($this->dir));
        } else {
            $path_to_first = array(array(
                'title' => t('Без группы'),
                'hideInlineButtons' => array('edit', 'delete')
            ));
        }        
        
        $helper->setTreeListFunction('selectTreeList');
        $helper->setTree(new Tree\Element( array(        
            'sortIdField' => 'id',
            'activeField' => 'id',
            'disabledField' => 'hidden',
            'disabledValue' => '1',
            'activeValue' => $this->dir,
            'noExpandCollapseButton' => true,
            'sortable' => true,
            'sortUrl'       => $this->router->getAdminUrl('move_dir'),
            'pathToFirst' => $path_to_first,
            'mainColumn' => new TableType\Text('title', 'Название', array('href' => $this->router->getAdminPattern(false, array(':dir' => '@id')) )),
            'tools' => new TableType\Actions('id', array(
                new TableType\Action\Edit($this->router->getAdminPattern('edit_dir', array(':id' => '~field~')), null, array(
                    'attr' => array(
                        '@data-id' => '@id'
                ))),
                new TableType\Action\DropDown(array(
                        array(
                            'title' => t('Клонировать категорию'),
                            'attr' => array(
                                'class' => 'crud-add',
                                '@href' => $this->router->getAdminPattern('clonedir', array(':id' => '~field~')),
                            )
                        ),                              
                )),
            )),
            'inlineButtons' => array(
                'add' => array(
                    'attr' => array(
                        'title' => t('Создать категорию'),
                        'href' => $this->router->getAdminUrl('add_dir'),
                        'class' => 'add crud-add'
                    )
                ),
                'edit' => array(
                    'attr' => array(
                        'title' => t('редактировать'),
                        'href' => $this->router->getAdminUrl('edit_dir', array('id' => $this->dir)),
                        'class' => 'edit crud-edit'
                    )
                ),
                'delete' => array(
                    'attr' => array(
                        'title' => t('удалить'),
                        'href' => $this->router->getAdminUrl('del_dir', array('chk[]' => $this->dir)),
                        'class' => 'delete crud-remove-one'
                    )
                ),
            ),            
            'headButtons' => array(
                array(
                    'text' => t('Название группы'),
                    'tag' => 'span',
                    'attr' => array(
                        'class' => 'lefttext'
                    )
                ),            
                array(
                    'attr' => array(
                        'title' => 'Создать категорию',
                        'href' => $this->router->getAdminUrl('add_dir'),
                        'class' => 'add crud-add'
                    )
                ),
            ),
        )), $this->dir_api);
        
        $helper->setTreeBottomToolbar(new Toolbar\Element( array(
            'Items' => array(
                new ToolbarButton\Delete(null, null, array('attr' => 
                    array('data-url' => $this->router->getAdminUrl('del_dir'))
                )),
        ))));        
        
        return $helper;
    }
    
    function actionAdd_dir($primaryKey = null)
    {
        return parent::actionAdd($primaryKey);
    }
    
    function helperAdd_Dir()
    {
        $this->api = $this->dir_api;
        return parent::helperAdd();
    }
    
    function actionEdit_dir()
    {
        $id = $this->url->get('id', TYPE_INTEGER, 0);
        if ($id) $this->dir_api->getElement()->load($id);
        return $this->actionAdd_dir($id);        
    }

    function helperEdit_Dir()
    {
        return $this->helperAdd_dir();
    }        
    
    function actionAdd($primaryKey = null, $returnOnSuccess = false, $helper = null)
    {
        $dir = $this->url->request('dir', TYPE_INTEGER);
        if ($primaryKey === null) {
            $elem = $this->api->getElement();
            $elem['parent_id'] = $dir;
        }
        return parent::actionAdd($primaryKey, $returnOnSuccess, $helper);
    }
    
    
    /**
    * AJAX
    */
    function actionSaveProperty()
    {
        return $this->result->setSuccess( $this->api->saveProperty() )->getOutput();
    }
    
    /**
    * AJAX
    */
    function actionDelProperty()
    {
        $aliases = $this->url->request('aliases', TYPE_ARRAY, array());
        return $this->result->setSuccess( $this->api->del($aliases) )->getOutput();
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
    
    function actionMove_dir()
    {
        $from = $this->url->request('from', TYPE_INTEGER);
        $to = $this->url->request('to', TYPE_INTEGER);
        $direction = $this->url->request('flag', TYPE_STRING);
        $result = $this->dir_api->moveElement($from, $to, $direction);
        if ($result) {
            $prop_api = new \Catalog\Model\Propertyapi();
            $prop_api->updateParentSort($from, $to);
        }
        $this->result->setSuccess($result);
        return $this->result->getOutput();
    }
    
    function actionDel_dir()
    {
        $ids = $this->url->request('chk', TYPE_ARRAY, array(), false);
        $this->dir_api->del($ids);
        return $this->result->setSuccess(true)->getOutput();
    }
    
    /**
    * Возвращает список категорий характеристик
    */
    function actionAjaxGetPropertyList()
    {
        $dir_api = new \Catalog\Model\PropertyDirApi();
        $groups = array(0 => array('title' => 'Без группы')) + $dir_api->getListAsResource()->fetchSelected('id');

        $propapi = new \Catalog\Model\PropertyApi();
        $propapi->setOrder('parent_id, sortn');
        $proplist = $propapi->getListAsResource()->fetchAll();

        $result = array(
            'properties_sorted' => $proplist,
            'groups' => $groups
        );
        
        return json_encode($result);
    }
    
    /**
    * Создает или обноляет характеристику
    */
    function actionAjaxCreateOrUpdateProperty()
    {
        $item = $this->url->request('item', TYPE_ARRAY);
        
        $prop_api = new \Catalog\Model\PropertyApi();
        $this->result->setSuccess( $result = $prop_api->createOrUpdate($item) );
        
        if ($result) {
            $this->view->assign('group', array(
                'group' => $result['group'],
            ));
            $this->view->assign(array(
                'properties' => array($result['property']),
                'owner_type' => $item['owner_type']
            ));
            
            $this->result->addSection('group', $result['group']->getValues());
            $this->result->addSection('prop', $result['property']->getValues());
            $this->result->addSection('group_html', $this->view->fetch('property_group_product.tpl'));
            $this->result->addSection('property_html', $this->view->fetch('property_product.tpl'));
        } else {
            $this->result->setErrors($prop_api->getElement()->getDisplayErrors());
        }
        return $this->result->getOutput();
    }
    
    /**
    * Возвращает список подготовленных свойств для вставки на страницу
    */
    function actionAjaxGetSomeProperties()
    {
        $ids = $this->url->request('ids', TYPE_ARRAY);
        
        $prop_api = new \Catalog\Model\PropertyApi();
        $list = $prop_api->getPropertiesAndGroup($ids);
        
        $result = array();
        foreach($list['groups'] as $group_id => $group) {
            if (isset($list['properties'][$group_id])) {
                
                $this->view->assign(array(
                    'group' => array('group' => $group),
                    'owner_type' => 'group'
                ));
                $group_html = $this->view->fetch('property_group_product.tpl');
                
                foreach($list['properties'][$group_id] as $property) {
                    $property['is_my'] = true;
                    $this->view->assign(array(
                        'properties' => array($property)
                    ));
                    
                    $property_html = $this->view->fetch('property_product.tpl');
                    
                    $result[] = array(
                        'group' => $group->getValues(),
                        'prop' => $property->getValues(),
                        'group_html' => $group_html,
                        'property_html' => $property_html
                    );
                }

            }
        }
        $this->result->addSection('result', $result);
        return $this->result->getOutput();
    }
    
    function actionAjaxGetValues()
    {
        $property_id = $this->url->request('property_id', TYPE_INTEGER);
        $values = $this->api->getExistsValues($property_id);
        return $this->result->addSection('values', implode("\n", $values));
    }

    
    /**
    * Клонирование директории
    * 
    */
    function actionCloneDir()
    {
        $this->setHelper( $this->helperAdd_dir() );
        $id = $this->url->get('id', TYPE_INTEGER);
        $elem = $this->dir_api->getElement();
        
        if ($elem->load($id)) {
            $clone = $elem->cloneSelf();
            $this->dir_api->setElement($clone);
            $clone_id = $clone['id'];

            return $this->actionAdd_dir($clone_id);
        } else {
            return $this->e404();
        }
    }
}

