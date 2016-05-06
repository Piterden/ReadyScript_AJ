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
    \RS\Html\Table,
    \Shop\Model;
    
/**
* Контроллер Управление заказами
*/
class TransactionCtrl extends \RS\Controller\Admin\Crud
{
    function __construct()
    {
        parent::__construct(new Model\TransactionApi());
        $this->setCrudActions('index', 'edit', 'add', 'tableOptions');
    }
    
    function helperIndex()
    {
        $helper = parent::helperIndex();        
        $helper->setTopToolbar(null);
        $helper->setBottomToolbar(null);
        $helper->setTopTitle(t('Транзакции'));
        $edit_href = $this->router->getAdminPattern('edit', array(':id' => '@id'));
        $helper->setTable(new Table\Element(array(
            'Columns' => array(
                new TableType\Text('id', t('№'), array('Sortable' => SORTABLE_BOTH, 'CurrentSort' => SORTABLE_DESC) ),
                new TableType\Usertpl('user_id', t('Пользователь'), '%shop%/order_user_cell.tpl', array(
                    'href' => $this->router->getAdminPattern('edit', array(':id' => '~field~'), 'users-ctrl'),
                    'linkAttr' => array(
                        'class' => 'crud-edit'
                    )
                )),
                new TableType\Text('reason', t('Назначение')),
                new TableType\Userfunc('payment', t('Тип оплаты'), function($value, $field){
                    return $field->getRow()->getPayment()->title;
                }),
                new TableType\Datetime('dateof', t('Дата'), array('Sortable' => SORTABLE_BOTH)),
                new TableType\Usertpl('cost', t('Сумма'), '%shop%/transaction_cost_cell.tpl', array('Sortable' => SORTABLE_BOTH)),
                new TableType\Text('status', t('Статус')),
                new TableType\Usertpl('__actions__', t('Действия'), '%shop%/transaction_actions_cell.tpl'),
                new TableType\Actions('id', array(),
                    array('SettingsUrl' => $this->router->getAdminUrl('tableOptions'))
                ),                 
            )
        )));
        
        $transaction = new \Shop\Model\Orm\Transaction();
        $status_list = array('' => t('Любой')) + $transaction->__status->getList();
        
        $helper->setFilter(new Filter\Control( array(
             'Container' => new Filter\Container( array( 
                                'Lines' =>  array(
                                    new Filter\Line( array('Items' => array(
                                            new Filter\Type\Text('id','№', array('attr' => array('class' => 'w50'))),                                    
                                            new Filter\Type\User('user_id', t('Пользователь')),
                                            new Filter\Type\Select('status', t('Статус'), $status_list),
                                            new Filter\Type\Text('cost', t('Сумма'), array('showtype' => true))
                                        )
                                    )),
                                    
                                ),
                                'SecContainer' => new Filter\Seccontainer( array(
                                    'Lines' => array(
                                        new Filter\Line( array('Items' => array(
                                            new Filter\Type\Date('dateof', t('Дата'), array('showtype' => true)),
                                        )))
                                    )
                                ))
                            )),
            'Caption' => t('Поиск по транзакциям')
        )));
        
        return $helper;
    }
    
    function actionSetTransactionSuccess()
    {
        if ($error = \RS\AccessControl\Rights::CheckRightError($this, ACCESS_BIT_WRITE)) {
            return $this->result->setSuccess(false)
                                ->addEMessage($error);
        }
        
        $transaction_id = $this->url->get('id', TYPE_INTEGER);
        $transaction = new \Shop\Model\Orm\Transaction($transaction_id);
        
        try{
            $transaction->status = \Shop\Model\Orm\Transaction::STATUS_SUCCESS;
            $transaction->update();
        }
        catch(\Exception $e){
            $this->result->setSuccess(false);
            $this->result->addEMessage($e->getMessage());
            return $this->result;
        }
        
        $this->result->setSuccess(true);
        $this->result->addMessage(t("Средства успешно зачислены.<br> %0<br> Сумма: %1", array($transaction->getUser()->getFio(), $transaction->cost)));
        return $this->result;
    }
}