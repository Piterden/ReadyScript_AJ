<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Comments\Controller\Admin;
use \RS\Html\Table\Type as TableType,
    \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Table,
    \RS\Html\Filter;

class Ctrl extends \RS\Controller\Admin\Crud
{
    protected
        /**
        * @var \Comments\Model\Api
        */
        $api;
    
    function __construct()
    {
        parent::__construct(new \Comments\Model\Api());
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex();
        $helper->setTopTitle(t('Комментарии'));
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id', array('ThAttr' => array('width' => 20))),                
                new TableType\Text('message', 'Комментарий'),                
                new TableType\Text('aid', 'Связь №', array('Sortable' => SORTABLE_BOTH)),
                new TableType\Text('ip', 'IP'),                
                new TableType\StrYesno('moderated', 'Промодерированно'),                
                new TableType\Text('type_obj_title', 'Тип', array('TdAttr' => array('class' => 'cell-sgray'))),                
                new TableType\Text('id', '№', array('ThAttr' => array('width' => '50'), 'Sortable' => SORTABLE_BOTH, 'CurrentSort' => SORTABLE_DESC)),                
                new TableType\Actions('id', array(
                                new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~'))),
                ), array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))),
        ))));
        
        $typelist = array('' => 'Любой тип') + $this->api->getTypeList();
        $helper->setFilter(new Filter\Control( array(
            'container' => new Filter\Container( array( 
                                'lines' =>  array(
                                    new Filter\Line( array('items' => array(
                                                            new Filter\Type\Text('id','№', array('attr' => array('class' => 'w50'))),
                                                            new Filter\Type\Select('type','Категория', $typelist ),
                                                            new Filter\Type\Select('moderated','Промодерированно?',array(
                                                                0 => 'Нет',
                                                                1 => 'Да',
                                                            )),
                                                            new Filter\Type\Text('aid','Связь №'),
                                                            new Filter\Type\Date('dateof','Дата', array('showtype' => true)),
                                                        )
                                    ))
                                ),
                                'open' => true
                            )),
            'field_prefix' => $this->api->getElementClass()
        )));
        
        $helper->setBottomToolbar($this->buttons(array('delete')));
        return $helper;
    }
    
    function actionEdit()
    {
        $elem = $this->api->getElement();
        $elem['__type']->setReadOnly();
        return parent::actionEdit();
    }
    
    function helperEdit($primaryKey = null)
    {
        $helper = parent::helperEdit();
                
        $id = $this->url->get('id', TYPE_STRING, 0);
        $helper['bottomToolbar']->addItem(
            new ToolbarButton\delete( $this->router->getAdminUrl('delComment', array('id' => $id, 'dialogMode' => $this->url->request('dialogMode', TYPE_INTEGER))), null, array(
                'noajax' => true,
                'attr' => array(
                    'class' => 'delete crud-get crud-close-dialog',
                    'data-confirm-text' => t('Вы действтельно хотите удалить данный комментарий?')
                )
            )), 'delete'
        );
        
        return $helper;
    }
    
    function actionDelComment()
    {
        $id = $this->url->request('id', TYPE_INTEGER);
        if (!empty($id)) {
            $comment = new \Comments\Model\Orm\Comment($id);
            $comment->delete();
        }
        
        if (!$this->url->request('dialogMode', TYPE_INTEGER)) {
            $this->result->setAjaxWindowRedirect($this->url->getSavedUrl($this->controller_name.'index'));
        }
        
        return $this->result
            ->setSuccess(true)
            ->setNoAjaxRedirect($this->url->getSavedUrl($this->controller_name.'index'));
    }
    
    
}


