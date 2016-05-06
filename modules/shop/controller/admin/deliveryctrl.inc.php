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
    \RS\Html\Filter,
    \RS\Html\Table;
    
/**
* Контроллер Управление скидочными купонами
*/
class DeliveryCtrl extends \RS\Controller\Admin\Crud
{
    function __construct()
    {
        parent::__construct(new \Shop\Model\DeliveryApi());
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex();
        $helper->setTopToolbar($this->buttons(array('add'), array('add' => t('Добавить способ доставки'))));
        $helper->addCsvButton('shop-delivery');
        $helper->setTopTitle(t('Способы доставки'));
        $helper->setTopHelp($this->fetch('delivery/top_help.tpl'));
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id'),
                new TableType\Sort('sortn', t('Порядок'),array('sortField' => 'id', 'Sortable' => SORTABLE_ASC,'CurrentSort' => SORTABLE_ASC,'ThAttr' => array('width' => '20'))),
                new TableType\Text('title', t('Название'), array('Sortable' => SORTABLE_BOTH, 'href' => $this->router->getAdminPattern('edit', array(':id' => '@id') ), 'LinkAttr' => array('class' => 'crud-edit') )),
                new TableType\Text('description', t('Описание'), array('Hidden' => true)),
                new TableType\Text('class', t('Тип рассчета')),
                new TableType\Text('user_type', t('Доступен для')),
                new TableType\Yesno('public', 'Видим.', array('Sortable' => SORTABLE_BOTH, 'toggleUrl' => $this->router->getAdminPattern('ajaxTogglePublic', array(':id' => '@id'))
                )),
                new TableType\Actions('id', array(
                      new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~')), null, array(
                            'attr' => array(
                                '@data-id' => '@id'
                            ))
                      ),
                      new TableType\Action\DropDown(array(
                                array(
                                    'title' => t('Клонировать способ доставки'),
                                    'attr' => array(
                                        'class' => 'crud-add',
                                        '@href' => $this->router->getAdminPattern('clone', array(':id' => '~field~')),
                                    )
                                ),                                 
                      )),
                  ),
                  array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))
                )
            ),
            
            'TableAttr' => array(
                'data-sort-request' => $this->router->getAdminUrl('move')
            )
        )));
        
        return $helper;
    }
    
    function actionAdd($primaryKey = null, $returnOnSuccess = false, $helper = null)
    {
        if ($primaryKey === null) {
            $types = $this->api->getTypes();
            if ($first = reset(array_keys($types))) {
                $this->api->getElement()->class = $first;
            }
        }
        else{
            $this->api->getElement()->fillZones();
        }
        
        if ($primaryKey == 0 ) $primaryKey = null;
        
        return parent::actionAdd($primaryKey, $returnOnSuccess, $helper);
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
    
    /**
    * Получает форму для типа доставки
    * 
    */
    function actionGetDeliveryTypeForm()
    {
        $type = $this->url->request('type', TYPE_STRING);
        if ($type_object = $this->api->getTypeByShortName($type)) {
            $this->view->assign('type_object', $type_object);
            $this->result->setTemplate( 'form/delivery/type_form.tpl' );
        }
        return $this->result;
    }
    
    /**
    * Выполняет пользовательский метод доставки, возвращая полученный ответ
    * 
    */
    function actionUserAct(){                                        
       $act          = $this->request('userAct', TYPE_STRING, false); 
       $delivery_obj = $this->request('deliveryObj', TYPE_STRING, false); 
       $params       = $this->request('params', TYPE_ARRAY, array()); 
       $module       = $this->request('module', TYPE_STRING, 'Shop');
       
       if ($act && $delivery_obj){
          $delivery = '\\'.$module.'\Model\DeliveryType\\'.$delivery_obj;
          $data = $delivery::$act($params);
          
          return $this->result->setSuccess(true)
                    ->addSection('data',$data); 
       }else{
          return $this->result->setSuccess(false)
                    ->addEMessage('Не установлен метод или объект доставки'); 
       }
    }
    
    /**
    * Включает/выключает флаг "публичный"
    */
    function actionAjaxTogglePublic()
    {
        if ($access_error = \RS\AccessControl\Rights::CheckRightError($this, ACCESS_BIT_WRITE)) {
            return $this->result->setSuccess(false);
        }
        $id = $this->url->get('id', TYPE_STRING);
        
        $delivery = $this->api->getOneItem($id);
        if ($delivery) {
            $delivery['public'] = !$delivery['public'];
            $delivery->update();
        }
        return $this->result->setSuccess(true);
    }
    
    /**
    * Метод для клонирования
    * 
    */ 
    function actionClone()
    {
        $this->setHelper( $this->helperAdd() );
        $id = $this->url->get('id', TYPE_INTEGER);
        
        $elem = $this->api->getElement();
        
        if ($elem->load($id)) {
            $clone_id = 0;
            if (!$this->url->isPost()) {
                $clone = $elem->cloneSelf();
                $this->api->setElement($clone);
                $clone_id = (int)$clone['id']; 
            }
            unset($elem['id']);
            return $this->actionAdd($clone_id);
        } else {
            return $this->e404();
        }
    }
}
