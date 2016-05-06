<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Shop\Controller\Admin;
use \RS\Html\Table\Type as TableType,
    \RS\Html\Filter,
    \RS\Html\Table;
use Shop\Model\TransactionApi;
use Users\Model\Orm\User;

/**
* Контроллр пользователей
* @ingroup Users
*/
class BalanceCtrl extends \RS\Controller\Admin\Crud
{
        
    function __construct()
    {
        parent::__construct(new \Users\Model\Api());
    }
    
    function helperAddFunds()
    {
        $writeoff = $this->url->get('writeoff', TYPE_STRING);
        $helper = new \RS\Controller\Admin\Helper\CrudCollection($this);       
        
        $buttons = new \RS\Html\Toolbar\Element(array());
        $buttons->addItem(new \RS\Html\Toolbar\Button\SaveForm(null, $writeoff ? t('списать') : t('пополнить')));
        $buttons->addItem(new \RS\Html\Toolbar\Button\Cancel($this->url->getSavedUrl($this->controller_name.'index'), t('отмена')));
        
        return $helper
            ->viewAsForm()
            ->setBottomToolbar( $buttons )
            ->setTopTitle($writeoff ? t('Списание с баланса') : t('Пополнение баланса'));
    }
    
    function actionAddFunds()
    {        
        $user_id    = $this->url->get('id', TYPE_INTEGER);
        $writeoff   = $this->url->get('writeoff', TYPE_INTEGER); // Флаг списания, 1 - списание, 0 - пополнение
        
        $helper = $this->getHelper();
        if ($this->url->isPost()) 
        {
            $amount     = $this->url->post('amount', TYPE_STRING);
            $reason     = $this->url->post('reason', TYPE_STRING);
            
            $transApi = new \Shop\Model\TransactionApi();
            $transApi->addFunds($user_id, $amount, $writeoff, $reason);
            
            if($transApi->hasError()){
                $this->result->setSuccess(false)->setErrors($transApi->getDisplayErrors());
                return $this->result;
            }
            
            $this->result->setSuccess(true);     
            if($writeoff){
                $this->result->addMessage(t('Со счета успешно списана сумма %0', array($amount)));
            }
            else{       
                $this->result->addMessage(t('Счет успешно пополнен на сумму %0', array($amount)));
            }
            return $this->result;
        }
        $this->view->assign('user', new \Users\Model\Orm\User($user_id));
        $this->view->assign('writeoff', $writeoff);
        $this->view->assign('default_reason', $writeoff ? t('Списание оператором') : t('Пополнение оператором'));
        $helper->setForm( $this->view->fetch('form/balance/addfunds.tpl') );
        return $this->result->setTemplate( $helper['template'] );
    }

    function actionFixBalance()
    {
        $user_id    = $this->url->get('id', TYPE_INTEGER);
        $user = new User($user_id);
        $transApi = new TransactionApi();

        $user->balance = $transApi->getBalance($user->id);
        $user->balance_sign = $transApi->getBalanceSign($user->balance, $user->id);
        $user->update();

        return $this->result->addMessage(t('Баланс и его подпись исправлены в соответсвии с историей транзакций'));
    }
    
}

