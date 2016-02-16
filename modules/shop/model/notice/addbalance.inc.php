<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\Notice;

/**
* Уведомление - пополнение счёта
*/
class AddBalance extends \Alerts\Model\Types\AbstractNotice
    implements \Alerts\Model\Types\InterfaceEmail, \Alerts\Model\Types\InterfaceSms
{
    public
        $transaction, //Объект транзакции
        $user;
    
    /**
    * Возвращает заголовок сообщения   
    */
    public function getDescription()
    {
        return t('Баланс лиц. счета пополнен');
    } 

    /**
    * Инициализация класса
    * 
    * @param \Shop\Model\Orm\Transaction $transaction - объект транзакции со всеми полями
    * @param \Users\Model\Orm\User $user              - объект пользователя полонившего балан
    */
    function init(\Shop\Model\Orm\Transaction $transaction,\Users\Model\Orm\User $user)
    {
        $this->transaction = $transaction;
        $this->user        = $user;
    }
    
    function getNoticeDataEmail()
    {
        $system_config = \RS\Config\Loader::getSystemConfig();
        $config        = \RS\Config\Loader::getSiteConfig();
        
        $notice_data = new \Alerts\Model\Types\NoticeDataEmail();
        
        $notice_data->email     = $config['admin_email'];
        $notice_data->subject   = t('Баланс пользователя пополнен на сайте %0',array(\RS\Http\Request::commonInstance()->getDomainStr()));
        $notice_data->vars      = $this;
        
        return $notice_data;
    }
    
    function getTemplateEmail()
    {
        return '%shop%/notice/toadmin_addbalance.tpl';
    }
    
    function getNoticeDataSms()
    {
        $system_config = \RS\Config\Loader::getSystemConfig();
        $config        = \RS\Config\Loader::getSiteConfig();
        
        $notice_data = new \Alerts\Model\Types\NoticeDataEmail();
        
        $notice_data->phone     = $config['admin_phone'];
        $notice_data->vars      = $this;
        
        return $notice_data;
    }
    
    function getTemplateSms()
    {
        return '%shop%/notice/toadmin_addbalance_sms.tpl';
    }
    
}
?>
