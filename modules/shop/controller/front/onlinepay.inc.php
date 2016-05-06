<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Controller\Front;
use \RS\Application\Auth as AppAuth;

/**
* Контроллер для обработки Online-платежей
*/
class OnlinePay extends \RS\Controller\Front
{
    /**
    * Шаг 6. Редирект на страницу оплаты (переход к сервису online-платежей)
    * Вызывается только в случае Online типа оплаты.
    * Данный action выполняется при нажатии на кнопку "Перейти к оплате"
    * Перед редиректом создается новая транзакция со статусом 'new'. Её идентификатор будет фигурировать в URL оплаты
    * 
    */
    function actionDoPay()
    {
        $request = $this->url;
        $this->wrapOutput(false);
        $order_id       = $this->url->request('order_id', TYPE_STRING);
        $transactionApi = new \Shop\Model\TransactionApi();
        $transaction    = $transactionApi->createTransactionFromOrder($order_id);
        
        if ($transaction->getPayment()->getTypeObject()->isPostQuery()){ //Если нужен пост запрос
           $url  = $transaction->getPayUrl();
           $this->view->assign(array(
              'url' => $url,
              'transaction' => $transaction
           )); 
           $this->wrapOutput(false);
           return $this->result->setTemplate("%shop%/onlinepay/post.tpl");
        }else{    
           $this->redirect($transaction->getPayUrl()); 
        }
    }
    
    /**
    * Особый action, который вызвается с сервера online платежей
    * В REQUEST['PaymentType'] должен содержаться строковый идентификатор типа оплаты
    * 
    * http://САЙТ.РУ/onlinepay/{PaymentType}/result/
    */
    function actionResult()
    {
        $request = $this->url;
        $this->wrapOutput(false);
        $payment_type   = $this->url->get('PaymentType', TYPE_STRING);
        $transactionApi = new \Shop\Model\TransactionApi();
        $response       = null;
        try{
            $transaction    = $transactionApi->recognizeTransactionFromRequest($payment_type, $this->url);
            $response       = $transaction->onResult($this->url);
        }
        catch(\Exception $e){
            return $e->getMessage();       // Вывод ошибки
        }
        return $response;
    }

    /**
    * Страница извещения об успешном совершении платежа
    * 
    * http://САЙТ.РУ/onlinepay/{PaymentType}/success/
    */
    function actionSuccess()
    {
        $request = $this->url;
        $payment_type = $this->url->get('PaymentType', TYPE_STRING);
        $transactionApi = new \Shop\Model\TransactionApi();
        try{
            $transaction = $transactionApi->recognizeTransactionFromRequest($payment_type, $this->url);
            $transaction->getPayment()->getTypeObject()->onSuccess($transaction, $this->url);
        }
        catch(\Exception $e){
            return $e->getMessage();       // Вывод ошибки
        }
        $this->view->assign('transaction', $transaction);
        return $this->result->setTemplate( 'onlinepay/success.tpl' );
    }
    
    /**
    * Страница извещения о неудачи при проведении платежа (например если пользователь отказался от оплаты)
    * 
    * http://САЙТ.РУ/onlinepay/{PaymentType}/fail/
    */
    function actionFail()
    {
        $request = $this->url;
        $payment_type = $this->url->get('PaymentType', TYPE_STRING);
        $transactionApi = new \Shop\Model\TransactionApi();
        try{
            $transaction = $transactionApi->recognizeTransactionFromRequest($payment_type, $this->url);
            $transaction->getPayment()->getTypeObject()->onFail($transaction, $this->url);
        }
        catch(\Exception $e){
            return $e->getMessage();       // Вывод ошибки
        }
        $this->view->assign('transaction', $transaction);
        return $this->result->setTemplate( 'onlinepay/fail.tpl' );
    }

}

?>
