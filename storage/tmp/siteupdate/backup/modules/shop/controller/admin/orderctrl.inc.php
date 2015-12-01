<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Controller\Admin;

use \RS\Html\Table\Type as TableType,            
    \RS\Controller\Admin\Helper,
    \RS\Html\Toolbar\Button as ToolbarButton,
    \RS\Html\Toolbar,
    \RS\Html\Filter,
    \RS\Html\Table,
    \RS\Html\Tree,
    \Shop\Model;
    
/**
* Контроллер Управление заказами
*/
class OrderCtrl extends \RS\Controller\Admin\Crud
{
    protected
        $status,
        $status_api;
        
    function __construct()
    {
        parent::__construct(new Model\OrderApi());
        $this->status_api = new \Shop\Model\UserStatusApi();
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex();
        
        $edit_href = $this->router->getAdminPattern('edit', array(':id' => '@id'));
        $this->status = $this->url->request('status', TYPE_INTEGER);
        
        if ($this->status>0 && $current_status = $this->status_api->getOneItem($this->status)) {
            $this->api->setFilter('status', $this->status);
        } else {
            $this->status = 0;
            $current_status = null;
        }
        
        $helper
            ->setTopToolbar(new Toolbar\Element( array(
                'Items' => array(
                    new ToolbarButton\Dropdown(array(
                        array(
                            'title' => t('создать заказ'),
                            'attr' => array(
                                'href' => $this->router->getAdminUrl('add'),
                                'class' => 'button add'
                            )
                        ),
                        
                        array(
                            'title' => t('добавить статус'),
                            'attr' => array(
                                'href' => $this->router->getAdminUrl('add', array(), 'shop-userstatusctrl'),
                                'class' => 'crud-add'
                            )
                        )
                    ), array('attr' => array('class' => 'button add'))),
                ))
            ))
            ->viewAsTableTree()
            ->setTopTitle(t('Заказы'))
            ->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Checkbox('id', array('showSelectAll' => true)),
                new TableType\String('order_num', t('Номер'), array('Sortable' => SORTABLE_BOTH, 'href' => $edit_href) ),
                new TableType\Usertpl('user_id', t('Покупатель'), '%shop%/order_user_cell.tpl', array('href' => $edit_href)),
                new TableType\Usertpl('status', t('Статус'), '%shop%/order_status_cell.tpl', array('Sortable' => SORTABLE_BOTH, 'href' => $edit_href)),
                new TableType\Datetime('dateof', t('Дата оформления'), array('Sortable' => SORTABLE_BOTH, 'CurrentSort' => SORTABLE_DESC)),
                new TableType\Usertpl('totalcost', t('Сумма'), '%shop%/order_totalcost_cell.tpl', array('Sortable' => SORTABLE_BOTH)),
                
                new TableType\Actions('id', array(
                    new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~')), null, array(
                        'noajax' => true,
                        'attr' => array(
                            '@data-id' => '@id'
                        ))),
                    ),
                    array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))
                ),
            )
        )));
        
        $helper['topToolbar']->addItem(new ToolbarButton\Dropdown(array(
            array(
                'title' => t('Экспорт/Отчёт'),
                'attr' => array(
                    'class' => 'button',
                    'onclick' => "JavaScript:\$(this).parent().rsDropdownButton('toggle')"
                )
            ),
            array(
                'title' => t('Экспорт заказов в CSV'),
                'attr' => array(
                    'data-url' => \RS\Router\Manager::obj()->getAdminUrl('exportCsv', array('schema' => 'shop-order', 'referer' => $this->url->selfUri()), 'main-csv'),
                    'class' => 'crud-add'
                )
            ),
            array(
                'title' => t('Показать отчёт'),
                'attr' => array(
                    'data-url' => \RS\Router\Manager::obj()->getAdminUrl('ordersReport'),
                    'class' => 'crud-add'
                )
            ),
        )));
        
        if ($this->status>0 && $helper->getTreeViewType() == \RS\Controller\Admin\Helper\CrudCollection::VIEW_CAT_TOP) {
            $path_to_first = array($this->status_api->getOneItem($this->status));            
        } else {
            $path_to_first = array(array(
                'title' => t('Все'),
                'hideInlineButtons' => array('edit', 'delete')
            ));
        }                
        
        $tree = new Tree\Element( array(
            'classField' => '_class',
            'sortIdField' => 'id',
            'activeField' => 'id',
            'activeValue' => $this->status,
            'pathToFirst' => $path_to_first,
            'sortable' => false,
            'noExpandCollapseButton' => true,
            'mainColumn' => new TableType\Usertpl('title', 'Название', '%shop%/order_tree_cell.tpl', array(
                'href' => $this->router->getAdminPattern(false, array(':status' => '@id'))
            )),
            'tools' => new TableType\Actions('id', array(
                new TableType\Action\Edit($this->router->getAdminPattern('edit', array(':id' => '~field~'), 'shop-userstatusctrl'), null, array(
                    'attr' => array(
                        '@data-id' => '@id'
                    )
                )))
            ),
            'inlineButtons' => array(
                'add' => array(
                    'attr' => array(
                        'title' => t('Добавить статус'),
                        'href' => $this->router->getAdminUrl('add', array(), 'shop-userstatusctrl'),
                        'class' => 'add crud-add'
                    )
                ),                
                'edit' => array(
                    'attr' => array(
                        'title' => t('редактировать'),
                        'href' => $this->router->getAdminUrl('edit', array('id' => $this->status), 'shop-userstatusctrl'),
                        'class' => 'edit crud-edit'
                    )
                )) + ((!$current_status || !$current_status->isSystem()) ? array(
                'delete' => array(
                    'attr' => array(
                        'title' => t('удалить'),
                        'href' => $this->router->getAdminUrl('del', array('chk[]' => $this->status), 'shop-userstatusctrl'),
                        'class' => 'delete crud-remove-one'
                    )
                )) : array()
            ),
            'headButtons' => array(
                array(
                    'text' => t('Статус'),
                    'tag' => 'span',
                    'attr' => array(
                        'class' => 'lefttext'
                    )
                ),                        
                array(
                    'attr' => array(
                        'title' => t('Добавить статус'),
                        'href' => $this->router->getAdminUrl('add', array(), 'shop-userstatusctrl'),
                        'class' => 'add crud-add'
                    )
                )
            ),
        ));        
        
        $helper
            ->setTreeListFunction('getAdminTreeList')
            ->setTree($tree, $this->status_api)
            ->setTreeBottomToolbar(new Toolbar\Element( array(
                'Items' => array(
                    new ToolbarButton\Delete(null, null, array('attr' => 
                        array('data-url' => $this->router->getAdminUrl('del', array(), 'shop-userstatusctrl'))
                    )),
            ))));        
        
        $helper->setFilter(new Filter\Control( array(
             'Container' => new Filter\Container( array( 
                                'Lines' =>  array(
                                    new Filter\Line( array('Items' => array(
                                                            new Filter\Type\String('order_num','№', array('attr' => array('class' => 'w50'))),                                    
                                                            new Filter\Type\DateRange('dateof', t('Дата оформления')),
                                                            new Filter\Type\String('totalcost', t('Сумма'), array('showtype' => true))
                                                        )
                                    )),
                                    
                                ),
                                'SecContainer' => new Filter\Seccontainer( array(
                                    'Lines' => array(
                                        new Filter\Line( array('Items' => array(
                                                new Filter\Type\User('user_id','Пользователь'),
                                        )))
                                    )
                                ))
                            )),
            'Caption' => t('Поиск по заказам')
        )));
        
        return $helper;
    } 
    
    /**
    * Отбработка хелпера, подготовка обёртки 
    * 
    */
    function helperEdit()
    {
        $id     = $this->url->request('id', TYPE_STRING, 0);
        $helper = new \RS\Controller\Admin\Helper\CrudCollection($this, $this->api, $this->url, array(
            'bottomToolbar' => $this->buttons(array('save', 'cancel')),
            'viewAs' => 'form',
            'formTitle' => t('Создание заказа')
        ));
        if ($id>0){ //Если заказ уже существует
           $helper['bottomToolbar']->addItem(
                new ToolbarButton\delete( $this->router->getAdminUrl('delOrder', array('id' => $id, 'dialogMode' => $this->url->request('dialogMode', TYPE_INTEGER))), null, array(
                    'noajax' => true,
                    'attr' => array(
                        'class' => 'delete crud-get crud-close-dialog',
                        'data-confirm-text' => t('Вы действительно хотите удалить заказ?')
                    )
                )), 'delete'
            ); 
        }
        
          
        return $helper;
    }
    
    
    /**
    * Обработывает заказ - страница редактирования
    * 
    */
    function actionEdit()
    {
        $helper = $this->getHelper();         
        
        $id           = $this->url->request('id', TYPE_STRING, 0);
        $order_id     = $this->url->request('order_id', TYPE_INTEGER, false); 
        $refresh_mode = $this->url->request('refresh', TYPE_BOOLEAN);
        /**
        * @var Model\Orm\Order
        */
        $order = $this->api->getElement();
        
        if ($id){ 
           $order->load($id); 
        }elseif ($order_id){ //Если идёт только создание
           $order['id'] = $order_id;
        } 
        $show_delivery_buttons = 1; //Флаг показа дополнительных кнопок при смене доставки
              
        if ($this->url->isPost()) {
            //Подготавливаем заказ с учетом правок
            $user_id            = $this->url->request('user_id', TYPE_INTEGER, 0); //id пользователя
            $use_addr           = $this->url->request('use_addr', TYPE_INTEGER, 0); //id адреса доставкм
            $post_address       = $this->url->request('address', TYPE_ARRAY); //Сведения адреса
            $items              = $this->url->request('items', TYPE_ARRAY);  //Товары 
            $warehouse          = $this->url->request('warehouse', TYPE_INTEGER); //Склад
            $delivery_extra     = $this->url->request('delivery_extra', TYPE_ARRAY, false); //Дополнительные данные для доставки
            $order['user_id']   = $user_id;
            $order['use_addr']  = $use_addr;
            
            //Если склад изменили
            if ($order['warehouse'] != $warehouse){ 
               $order['back_warehouse'] = $order['warehouse']; //Запишем склад на который надо вернуть остатки 
            }
            
            if ($delivery_extra){
                $order->addExtraKeyPair('delivery_extra', $delivery_extra);
            }
            $order['warehouse'] = $warehouse;     
            
            $address = new Model\Orm\Address();
            $address->getFromArray($post_address + array('user_id' => $order['user_id']));
            $address->updateRegionTitles();
            
            //Если включено уведомлять пользователя, то сохраним сведения о прошлом адресе, который ещё не перезаписан
            if ($this->url->request('notify_user', TYPE_INTEGER, false)){
               $order->before_address = $order->getAddress();  
            }
            if ($order['use_addr']) { //Если есть заданный адрес
               $order->setAddress($address);
            }
            
            $fields = array(
                            'delivery', 'user_id', 'use_addr', 'is_payed', 'payment', 'contact_person',
                            'status', 'user_delivery_cost', 'admin_comments', 'user_text', 'notify_user', 
                            'user_fio', 'user_email', 'user_phone'
            );
            
            
            $order->checkData(array(), null, null, $fields);
            //Если цена задана уже у заказа
            if ($this->url->post('user_delivery_cost', TYPE_STRING) === '') {
                $order['user_delivery_cost'] = null;
            }
            //Если нужно пересчитать доставку
            if ($refresh_mode && ($this->url->post('user_delivery_cost', TYPE_STRING) === '')){
                $order['delivery_new_query'] = 1;
            }   
            //Если заказ ещё не создан, то считаем доставку всегда
            if ($order['id']<0){
                $order['user_delivery_cost'] = null;
                $order['delivery_new_query'] = 1;
            }
            
            //Если заказ создан установим флаг показа дополнительных кнопок доставки, если они существуют
            if ($order['id']>0){ 
               $show_delivery_buttons = $this->url->post('show_delivery_buttons', TYPE_INTEGER, 1); 
               //Если мы поменяли в доставке что-либо, то тоже запросим внешёнюю доставку, после сохранения
               if (!$show_delivery_buttons){
                   $order['delivery_new_query'] = 1; 
               }
            }   
                                                
            $order->getCart()->updateOrderItems($items);
            
            
            if (!$refresh_mode) {
                //Проверяем права на запись для модуля Магазин
                if ($error = \RS\AccessControl\Rights::CheckRightError($this, ACCESS_BIT_WRITE)) {
                    $this->api->addError($error);
                    return $this->result->setSuccess(false)->setErrors($this->api->getDisplayErrors());
                }
                
                //Обновляем или вставим запись если сменился пользователь
                if ($order['use_addr']) { 
                    //Проверим пользователя у Адреса, и если пользователь поменялся, то задублируем адрес, иначе обновим
                    $address_api = new \Shop\Model\AddressApi();
                    $address_api->setFilter('id', $order['use_addr']);
                    $old_address = $address_api->getFirst();
                    
                    if ($old_address && ($old_address['user_id']!=$user_id)){
                        unset($address['id']);
                        $address->insert(); 
                        $order['use_addr'] = $address['id'];
                    }else{
                       $address['id'] = $order['use_addr'];  
                       $address->update(); 
                    }
                }
                $order['is_exported'] = 0; //Устанавливаем флаг, что заказ нужно заново выгрузить в commerceML
                //Если нужно создать заказ
                if (isset($order['id']) && $order['id']<0) {     
                    if ($order->insert()){ //Перенаправим на ректирование, если создался заказ    
                        $order->getCart()->saveOrderData();   
                        return $this->result->setSuccess(true)
                                        ->setSuccessText(t('Заказ успешно создан'))
                                        ->setHtml(false)
                                        ->addSection('windowRedirect', $this->router->getAdminUrl('edit', array('id'=>$order['id']), 'shop-orderctrl'));  
                    }else{
                        return $this->result->setSuccess(false)
                                          ->addEMessage($order->getErrorsStr()); 
                    }
                }elseif ($order->update( $id )){
                    $order->getCart()->saveOrderData();  
                }     
                
                
                $this->result->setSuccess( true );

                if ($this->url->isAjax()) { //Если это ajax запрос, то сообщаем результат в JSON
                    if (!$this->result->isSuccess()) {
                        $this->result->setErrors($order->getDisplayErrors());
                    } else {
                        $this->result->setSuccessText(t('Изменения успешно сохранены'));
                    }
                    return $this->result->getOutput();
                }
                
                if ($this->result->isSuccess()) {
                    $this->successSave();
                } else {
                    $helper['formErrors'] = $order->getDisplayErrors();
                }
            }
        }
        $user_num_of_order = $this->api->getUserOrdersCount($order['user_id']); 
        
        //Склады
        $warehouses = \Catalog\Model\WareHouseApi::getWarehousesList();

        $user_status_api = new Model\UserStatusApi(); 
        $this->view->assign(array(
            'order_footer_fields' => $order->getForm(null, 'footer', false, null, '%shop%/order_footer_maker.tpl'),
            'catalog_folders' => \RS\Module\Item::getResourceFolders('catalog'),
            'elem' => $order,
            'user_id' => $order['user_id'],
            'warehouse_list' => $warehouses,
            'status_list' => $user_status_api->getGroupedList(),
            'default_statuses' => $user_status_api->getStatusIdByType(),
            'refresh_mode' => $refresh_mode,
            'show_delivery_buttons' => $show_delivery_buttons,
            'user_num_of_order' => $user_num_of_order
        ));
        $helper['form'] = $this->view->fetch('%shop%/orderview.tpl');
        $helper->setTopTitle(t('Редактировать заказ №%0', array($order['order_num'])));
                             
        if ($refresh_mode) { //Если режим обновления
            return $this->result->setHtml( $helper['form'] );
        } else { //Если режим редактирования
            $this->view->assign(array(
                'elements' => $helper->active(),
            ));            
            return $this->result->setTemplate($helper['template']);
        }
    }
    
    /**
    * Возращает объект обёртки для создания
    * 
    */
    function helperAdd()
    {          
        return $this->helperEdit();
    }
    
    /**
    * Создание заказа
    * 
    * @param integer|null $primaryKey - первичный ключ
    * @param \RS\Controller\Admin\Helper\CrudCollection|null $helper - объект Helperа
    * @return Standart
    */
    function actionAdd($primaryKey = null, $helper = null)
    {  
        if ($this->url->isPost()){ //Если был передан POST запрос          
           return $this->actionEdit(); 
        }
        
        $helper = $this->getHelper();        
        $helper->setTopTitle(t('Создание заказа'));
        //Создадим заказ с отрицательным идентификатором
        $order = new \Shop\Model\Orm\Order();
        $order->setTemporaryId();

        //Склады
        $warehouses = \Catalog\Model\WareHouseApi::getWarehousesList();
        
        $user_status_api = new Model\UserStatusApi();
        $this->view->assign(array(
           'elem' => $order,
           'order_footer_fields' => $order->getForm(null, 'footer', false, null, '%shop%/order_footer_maker.tpl'),
           'catalog_folders' => \RS\Module\Item::getResourceFolders('catalog'),
           'warehouse_list' => $warehouses,
           'status_list' => $user_status_api->getGroupedList(),
           'user_num_of_order' => 0,
           'refresh_mode' => false
        ));
        $helper['form'] = $this->view->fetch('%shop%/orderview.tpl');
        return $this->result->setTemplate($helper['template']);
    } 
    
    
    /**
    * Получает диалоговое окно с поиском пользователя для добавления или создания нового пользователя
    */
    function actionUserDialog()
    {
        $helper  = new \RS\Controller\Admin\Helper\CrudCollection($this);
        $helper
            ->setTopTitle(t('Добавление пользователя'))
            ->viewAsForm(); 
            
        $refresh = $this->request('refresh', TYPE_INTEGER, false); //Признак обновления 
        $order   = $this->api->getNewElement();    
        $user    = new \Users\Model\Orm\User();
        
        //Если нужно обновить блок 
        if ($this->url->isPost()){
            $is_reg_user = $this->request('is_reg_user', TYPE_INTEGER, 0); //Смотрим, нужно ли регистривать или указать существующего пользователя
                       
            if ($is_reg_user){ //Если нужно регистрировать
               //Проверим  
               $user['name']       = $this->request('name', TYPE_STRING, false);
               $user['surname']    = $this->request('surname', TYPE_STRING, false);
               $user['midname']    = $this->request('midname', TYPE_STRING, false);
               $user['phone']      = $this->request('phone', TYPE_STRING, false);
               $user['login']      = $this->request('e_mail', TYPE_STRING, false);
               $user['e_mail']     = $this->request('e_mail', TYPE_STRING, false);
               $user['openpass']   = $this->request('pass', TYPE_STRING, false);
               $user['changepass'] = 1;
               if ($user->save()){
                  $user_num_of_order = $this->api->getUserOrdersCount($user['id']); 
                  $this->view->assign(array(
                      'user' => $user,
                      'user_num_of_order' => $user_num_of_order
                  )); 
                  return $this->result->setSuccess(true)
                                    ->addSection('noUpdateTarget', true)
                                    ->addSection('user_id', $user['id'])
                                    ->addSection('insertBlockHTML', $this->view->fetch('%shop%/form/order/user.tpl'));
               }else{
                  foreach ($user->getErrors() as $error){
                     $this->api->addError($error); 
                  }
               }
            }else{ //Если не нужно регистрировать, а указать конкретного пользователя
               $user_id = $this->request('user_id', TYPE_INTEGER, false);
               if ($user_id){
                   $user = new \Users\Model\Orm\User($user_id);
                   $user_num_of_order = $this->api->getUserOrdersCount($user_id);
                   $this->view->assign(array(
                      'user' => $user,
                      'user_num_of_order' => $user_num_of_order
                   ));
                   return $this->result->setSuccess(true)
                                    ->addSection('noUpdateTarget', true)
                                    ->addSection('user_id', $user_id)
                                    ->addSection('insertBlockHTML', $this->view->fetch('%shop%/form/order/user.tpl'));
               }else{
                   $this->api->addError(t('Не выбран пользователь для добавления'));
               } 
            }
            return $this->result->setSuccess(false)
                                ->setErrors($this->api->getDisplayErrors());
        }
        
        $this->view->assign(array(
           'user' => $user,  
           'elem' => $order,  
        ));
        
        $helper
            ->setBottomToolbar(new Toolbar\Element( array(
            'Items' => array(
                'save' => new ToolbarButton\SaveForm(null, t('применить')),
                'cancel' => new ToolbarButton\Cancel(null, t('отмена')),
            )
        )));
        
        $helper['form'] = $this->view->fetch('%shop%/form/order/user_dialog.tpl');
        return $this->result->setTemplate($helper['template']);
    }
    
    
    /**
    * Получает диалоговое окно доставки заказа
    * 
    */
    function actionDeliveryDialog()
    {
        $delivery_id  = $this->url->request('delivery', TYPE_INTEGER); //Тип доставки
        $helper  = new \RS\Controller\Admin\Helper\CrudCollection($this);
        $helper->viewAsForm(); 
        
        $order_id = $this->url->request('order_id', TYPE_INTEGER, 0);
        /**
        * @var \Shop\Model\Orm\Order
        */
        $order = $this->api->getNewElement();
        if ($order_id<0){ //Если заказ только должен создатся 
           $order['id'] = $order_id; 
           $user_id     = $this->request('user_id', TYPE_INTEGER, 0);
           $helper->setTopTitle(t('Добавление доставки'));
        }else{ //Если уже заказ создан
           $order->load($order_id); 
           $user_id = $order['user_id'];
           $helper->setTopTitle(t('Редактирование доставки'));
        }

        $delivery_api = new Model\DeliveryApi();
        $dlist        = $delivery_api->getList();
        
        //Получим список адресов
        $address_list = array();
        $address_api = new Model\AddressApi();
        if ($user_id){ //Если пользователь указан
            $address_api->setFilter('user_id', $user_id);
            $address_list = $address_api->getList(); 
        }else{ //Если есть только сведения о заказе
            $address_api->setFilter('order_id', $order_id);
            $address_list = $address_api->getList(); 
        }
        
        
        //Если задан конкретный адрес
        if (isset($order['use_addr']) && $order['use_addr']){
            $this->view->assign(array(
                'current_address' => $address_api->getOneItem($order['use_addr']),
                'address_part' => $this->actionGetAddressRecord($order['use_addr'])->getHtml()
            ));
        }else{ //Если адресов нет, или они не заданы
            $use_addr = 0; //Выбранный адрес
            if (isset($address_list[0]['id'])){ //Если адреса пользователя существуют
                $use_addr = $address_list[0]['id'];
            }
            $this->view->assign(array(
                'address_part' => $this->actionGetAddressRecord($use_addr)->getHtml()
            ));
        }
        
        if ($this->url->isPost()){ //Если пришёл запрос
            //Получим данные
            $use_addr           = $this->url->request('use_addr', TYPE_INTEGER); //Использовать адрес пользователя
            $post_address       = $this->url->request('address', TYPE_ARRAY);  //Сведения об адресе
            $warehouse          = $this->url->request('warehouse', TYPE_INTEGER, false); //Склад 
            $user_delivery_cost = $this->url->request('user_delivery_cost', TYPE_STRING); //Своя цена доставки
            $delivery_id        = $this->url->post('delivery', TYPE_INTEGER); //Тип доставки
            
            //Назначим значения
            $order['delivery'] = $delivery_id; 
            $order['use_addr'] = $use_addr; 
            
            if ($warehouse){ //Установим склад
                $order['back_warehouse'] = $warehouse; //Запишем склад на который надо вернуть остатки 
                $order['warehouse'] = $warehouse;
            }else{ //Если склад не установлен и складов меньше 2-х, то установим
                $warehouse_orm = \Catalog\Model\WareHouseApi::getDefaultWareHouse();
                $order['warehouse'] = $warehouse_orm['id'];
            }
              
            if (!$use_addr){ //Если нужно создать новый адрес для доставки
               $address = new Model\Orm\Address();
            }else{ //Если используется существующий адрес
               $address = new Model\Orm\Address($use_addr);   
            }  
            
            $address->getFromArray($post_address + array('user_id' => $user_id));
            $address->updateRegionTitles();  
            
            if (!$use_addr){ //Вставим
               if (!$user_id){ //Если пользователь не указан, запишем адрес к заказу
                  $address['order_id'] = $order['id']; 
               }
               $address->insert();
               $use_addr = $address['id'];
            }else{ //Обновим
               $address->update();  
            }
            
            $order->setAddress($address);
            if ($user_delivery_cost === '') {
                $order['user_delivery_cost'] = null;
            }
            
            
            $delivery   = new \Shop\Model\Orm\Delivery($delivery_id); //Назначенная доставка
            $warehouses = \Catalog\Model\WareHouseApi::getWarehousesList();//Склады
            $this->view->assign(array(
                'elem' => $order,
                'delivery' => $delivery,
                'user_id' => $user_id,
                'address' => $address,
                'warehouse_list' => $warehouses,
                'user_delivery_cost' => $user_delivery_cost,
            ));
            
            return $this->result->setSuccess(true)
                    ->setHtml(false)
                    ->addSection('noUpdateTarget', true)
                    ->addSection('delivery', $delivery_id)
                    ->addSection('address', $post_address)
                    ->addSection('use_addr', $use_addr)
                    ->addSection('user_delivery_cost', $user_delivery_cost)
                    ->addSection('insertBlockHTML', $this->view->fetch('%shop%/form/order/delivery.tpl'));
            
        }
        
        $this->view->assign(array(
            'dlist' => $dlist,
            'order' => $order,
            'delivery_id' => $delivery_id,
            'address_list' => $address_list,
        ));
        
        $helper
            ->setBottomToolbar(new Toolbar\Element(array(
            'Items' => array(
                'save' => new ToolbarButton\SaveForm(null, t('применить')),
                'cancel' => new ToolbarButton\Cancel(null, t('отмена')),
            )
        )));
        
        $helper['form'] = $this->view->fetch('%shop%/form/order/delivery_dialog.tpl');
        return $this->result->setTemplate($helper['template']);
    }
    
    
    /**
    * Открывает диалоговое окно оплаты
    */ 
    function actionPaymentDialog()
    {
        $helper  = new \RS\Controller\Admin\Helper\CrudCollection($this);
        $helper->viewAsForm(); 
        
        $order_id = $this->url->request('order_id', TYPE_INTEGER);
        /**
        * @var \Shop\Model\Orm\Order
        */
        $order = $this->api->getNewElement();
        if ($order_id<0){ //Если заказ только должен создатся 
           $order['id'] = $order_id; 
           $helper->setTopTitle(t('Добавление оплаты'));
        }else{ //Если уже заказ создан
           $order->load($order_id); 
           $helper->setTopTitle(t('Редактирование оплаты'));
        }
        
        
         
        if ($this->url->isPost()){ //Если пришёл запрос
            $pay_id = $this->url->request('payment', TYPE_INTEGER);
            $order['payment'] = $pay_id; //Установим оплату
        
            $this->view->assign(array(
                'elem' => $order,
                'payment_id' => $pay_id,
                'pay' => $order->getPayment()
            ));
            
            return $this->result->setSuccess(true)
                            ->setHtml(false)
                            ->addSection('noUpdateTarget', true)
                            ->addSection('payment', $pay_id)
                            ->addSection('insertBlockHTML', $this->view->fetch('%shop%/form/order/payment.tpl'));
        }    
        
        $pay_api = new Model\PaymentApi();
        $plist   = $pay_api->getList();
                
        
        $this->view->assign(array(
            'order' => $order,
            'plist' => $plist
        ));
        
        $helper
            ->setBottomToolbar(new Toolbar\Element(array(
            'Items' => array(
                'save' => new ToolbarButton\SaveForm(null, t('применить')),
                'cancel' => new ToolbarButton\Cancel(null, t('отмена')),
            )
        )));
        
        $helper['form'] = $this->view->fetch('%shop%/form/order/payment_dialog.tpl');
        return $this->result->setTemplate($helper['template']);
    }
    
    /**
    * Удаление заказа
    * 
    */
    function actionDelOrder()
    {
        //Проверяем права на запись для модуля Магазин
        if ($error = \RS\AccessControl\Rights::CheckRightError($this, ACCESS_BIT_WRITE)) {
            return $this->result
                            ->setSuccess(false)
                            ->addSection('noUpdate', true)
                            ->addEMessage($error);
        }
        
        $id = $this->url->request('id', TYPE_INTEGER);
        if (!empty($id))
        {
            $obj = $this->api->getElement();
            $obj->load($id);
            $obj->delete();
        }
        if (!$this->url->request('dialogMode', TYPE_INTEGER)) {
            $this->result->setAjaxWindowRedirect( $this->url->getSavedUrl($this->controller_name.'index') );
        }
        
        return $this->result
            ->setSuccess(true)
            ->addSection('noUpdate', true)            
            ->setNoAjaxRedirect($this->url->getSavedUrl($this->controller_name.'index'));
    }    
    
    
    
    function actionGetAddressRecord($_address_id = null)
    {
        $address_id = $this->url->request('address_id', TYPE_INTEGER, $_address_id);
        $country_list = Model\RegionApi::countryList();
        $address = new Model\Orm\Address($address_id);
        
        if ($address_id == 0) {
            $address['country_id'] = key($country_list);
        }
        
        if ($address['country_id']) {
            $region_api = new Model\RegionApi();
            $region_api->setFilter('parent_id', $address['country_id']);
            $region_list = $region_api->getList();
        } else {
            $region_list = array();
        }
        
        $this->view->assign(array(
            'address' => $address,
            'country_list' => $country_list,
            'region_list' => $region_list
        ));
        
        return $this->result->setTemplate('form/order/order_delivery_address.tpl');
    }    
    
    function actionGetCountryRegions()
    {
        $parent = $this->url->request('parent', TYPE_INTEGER);        
        
        $this->api = new Model\RegionApi();
        $result = array();
        if ($parent) {
            $this->api->setFilter('parent_id', $parent);
            $list = $this->api->getAssocList('id', 'title');
            foreach($list as $key => $value) {
                $result[] = array('key' => $key, 'value' => $value);
            }
        }
        return $this->result->addSection('list', $result);
    }    
    
    function actionGetOfferPrice()
    {
        $product_id     = $this->url->request('product_id', TYPE_INTEGER);        
        $offer_index    = $this->url->request('offer_index', TYPE_INTEGER);        
        
        $product = new \Catalog\Model\Orm\Product($product_id);
        $product->fillCost();
        $cost_ids = array_keys($product['xcost']);
        $offer_costs = array();
        foreach($cost_ids as $cost_id){
            $cost_obj = new \Catalog\Model\Orm\Typecost($cost_id);
            $offer_costs[$cost_id] = array(
                'title' => $cost_obj->title,
                'cost' => $product->getCost($cost_id, $offer_index, false),
            );
        }
        
        return $this->result->addSection('costs', $offer_costs);
    }
    
    function actionGetUserAddresses()
    {
        $parent = $this->url->request('user_id', TYPE_INTEGER);        
        
        $this->api = new Model\AddressApi();
        $result = array();
        if ($parent) {
            $this->api->setFilter('user_id', $parent);
            $list = $this->api->getList();
            foreach($list as $value) {
                $result[] = array('key' => $value->id, 'value' => $value->getLineView());
            }
        }
        return $this->result->addSection('list', $result);
    }    
    
    
    
    /**
    * Печать заказа
    */
    function actionPrintForm()
    {
        $order_id = $this->url->request('order_id', TYPE_INTEGER);
        $type = $this->url->request('type', TYPE_STRING);
        
        if ($order = $this->api->getOneItem($order_id)) {
            $print_form = Model\PrintForm\AbstractPrintForm::getById($type, $order);
            if ($print_form) {
                $this->wrapOutput(false);
                return $print_form->getHtml();
            } else {
                return t('Печатная форма не найдена');
            }
        }
        return t('Заказ не найден');
    }

    
    /**
    * Действие которое вызывает окно с дополнительной информацией в заказе
    * 
    */
    function actionOrderQuery()
    {
       $type = $this->request('type',TYPE_STRING,false);
       
       if (!$type){
          return $this->result
            ->setSuccess(false)
            ->addSection('close_dialog', true)
            ->addEMessage('Не указан параметр запроса - type (delivery или payment)');
       } 
       $order_id = $this->request('order_id',TYPE_STRING,0);
       
       if (!$order_id){
          return $this->result
            ->setSuccess(false)
            ->addSection('close_dialog', true)
            ->addEMessage('id заказа указан неправильно'); 
       }
       
       /**
       * @var \Shop\Model\Orm\Order
       */
       $order = \RS\Orm\Request::make()
                ->from(new \Shop\Model\Orm\Order())
                ->where(array(
                    'order_num' => $order_id 
                ))->object();
       
       if (!$order){
          return $this->result
            ->setSuccess(false)
            ->addSection('close_dialog', true)
            ->addEMessage('Такой заказ не найден'); 
       }
                
       switch($type){
          case "delivery":     
                return $this->result
                            ->setSuccess(true)
                            ->setHtml($order->getDelivery()->getTypeObject()->actionOrderQuery($order));      
                break;
          case "payment":
                return $this->result
                            ->setSuccess(true)
                            ->setHtml($order->getPayment()->getTypeObject()->actionOrderQuery($order));       
                break;  
       }
       
    }
    
    /**
    * Строит отчёт по заказам и выдаёт в отдельном окне 
    * 
    */
    function actionOrdersReport()
    {
        //Получим из сесии сведения по отбору
        $where_conditions = isset($_SESSION['where_conditions']['Shop\Controller\Admin\OrderCtrl_list']) ? clone $_SESSION['where_conditions']['Shop\Controller\Admin\OrderCtrl_list'] : false;
        if ($where_conditions){
            //Получим данные в массив для отчёта
            $order_report_arr = $this->api->getReport($where_conditions);
        
            $this->view->assign(array(
               'order_report_arr' => $order_report_arr,
               'currency' => \Catalog\Model\CurrencyApi::getBaseCurrency(),//Базовая валюта
               'payments' => \Shop\Model\PaymentApi::staticSelectList(),
               'deliveries' => \Shop\Model\DeliveryApi::staticSelectList(),
            ));     
        }
        return $this->result->setTemplate('orders_report.tpl');
    }
    
    
}
