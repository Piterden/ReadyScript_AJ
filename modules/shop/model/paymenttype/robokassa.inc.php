<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\PaymentType;
use \RS\Orm\Type;
use \Shop\Model\Orm\Transaction;

/**
* Способ оплаты - Robokassa
*/
class Robokassa extends AbstractType
{
    /**
    * Возвращает название расчетного модуля (типа доставки)
    * 
    * @return string
    */
    function getTitle()
    {
        return t('Робокасса');
    }
    
    /**
    * Возвращает описание типа оплаты. Возможен HTML
    * 
    * @return string
    */
    function getDescription()
    {
        return t('Оплата через агрегатор платежей "Робокасса"');
    }
    
    /**
    * Возвращает идентификатор данного типа оплаты. (только англ. буквы)
    * 
    * @return string
    */
    function getShortName()
    {
        return 'robokassa';
    }
    
    /**
    * Возвращает ORM объект для генерации формы или null
    * 
    * @return \RS\Orm\FormObject | null
    */
    function getFormObject()
    {
        $properties = new \RS\Orm\PropertyIterator(array(
            'testmode' => new Type\Integer(array(
                'maxLength' => 1,
                'description' => t('Тестовый режим'),
                'checkboxview' => array(1,0),
            )),
            'login' => new Type\Varchar(array(
                'description' => t('Логин')
            )),
            'password_1' => new Type\Varchar(array(
                'description' => t('Пароль #1')
            )),
            'password_2' => new Type\Varchar(array(
                'description' => t('Пароль #2')
            )),
            '__help__' => new Type\Mixed(array(
                'description' => t(''),
                'visible' => true,  
                'template' => '%shop%/form/payment/robokassa/help.tpl'
            )),
        ));
        
        return new \RS\Orm\FormObject($properties);
    }
    
    
    /**
    * Возвращает true, если данный тип поддерживает проведение платежа через интернет
    * 
    * @return bool
    */
    function canOnlinePay()
    {
        return true;
    }
    
    /**
    * Возвращает URL для перехода на сайт сервиса оплаты
    * 
    * @param Transaction $transaction
    * @return string
    */
    function getPayUrl(Transaction $transaction)
    {
        $shp_item   = 0;
        $inv_id     = $transaction->id;
        $out_summ   = round($transaction->cost, 2);
        $inv_desc   = $transaction->reason;
        $mrh_login  = $this->getOption('login');
        $mrh_pass1  = $this->getOption('password_1');
        $crc        = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
        
        $culture  = "ru";       // язык
        $encoding = "utf-8";    // кодировка
        $in_curr  = "";         // предлагаемая валюта платежа

        $url = "https://auth.robokassa.ru/Merchant/Index.aspx";
        
        //if($this->getOption('testmode')){
            //$url = "http://test.robokassa.ru/Index.aspx";
        //}
        
        $params = array();
        $params['MrchLogin']        = $mrh_login;
        $params['OutSum']           = $out_summ;
        $params['InvId']            = $inv_id;
        $params['IncCurrLabel']     = $in_curr;
        $params['Desc']             = $inv_desc;
        $params['SignatureValue']   = $crc;
        if($this->getOption('testmode')){
            $params['IsTest'] = 1;
        }
        $params['Shp_item']         = $shp_item;
        $params['Culture']          = $culture;
        $params['Encoding']         = $encoding;
        
        $url = $url."?".http_build_query($params);
        
        return $url;
    }
    
    /**
    * Возвращает ID заказа исходя из REQUEST-параметров соотвествующего типа оплаты
    * Используется только для Online-платежей
    * 
    * @return mixed
    */
    function getTransactionIdFromRequest(\RS\Http\Request $request)
    {
        return $request->request('InvId', TYPE_INTEGER, false);
    }

    private function checkSign($password, \RS\Http\Request $request)
    {
        // Чтение параметров               
        $out_summ   = $request->request("OutSum", TYPE_STRING);
        $inv_id     = $request->request("InvId", TYPE_INTEGER);
        $shp_item   = $request->request("Shp_item", TYPE_STRING);
        $sign       = $request->request("SignatureValue", TYPE_STRING);
        $sign       = strtoupper($sign);
        
        // Вычисление подписи
        $my_sign    = strtoupper(md5("$out_summ:$inv_id:$password:Shp_item=$shp_item"));

        // Проверка корректности подписи
        return $my_sign == $sign;
    }
    
    function onResult(\Shop\Model\Orm\Transaction $transaction, \RS\Http\Request $request)
    {
        // Получение пароля #2 из настроек профиля
        $password_2 = $this->getOption('password_2');

        // Проверка подписи запроса
        if(!$this->checkSign($password_2, $request)){
            $exception = new ResultException(t('Неверная подпись запроса'));
            $exception->setResponse('bad sign'); // Строка направится как ответ серверу
            throw $exception;
        }
        
        // Проверка, соответсвует ли сумма платежа сумме, сохраненной в транзакции
        if($request->request('OutSum', TYPE_STRING) != $transaction->cost){
            $exception = new ResultException(t('Неверная сумма платежа %0', array($request->request('OutSum', TYPE_STRING))));
            $exception->setResponse('bad summ');
            throw $exception;
        }
        
        return 'OK'.$transaction->id;
    }
    
    /**
    * Вызывается при переходе на страницу успеха, после совершения платежа 
    * 
    * @return void 
    */
    function onSuccess(\Shop\Model\Orm\Transaction $transaction, \RS\Http\Request $request)
    {
        // Получение пароля #1 из настроек профиля
        $password_1 = $this->getOption('password_1');
        // Проверка подписи
        if(!$this->checkSign($password_1, $request)){
            throw new \Exception(t('Неверная подпись запроса'));
        }
    }
    
    
}
