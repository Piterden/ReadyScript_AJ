<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Support\Model\Orm;
use \RS\Orm\Type;

/**
* Тема, группирующая сообщения в поддержку
*/
class Topic extends \RS\Orm\OrmObject
{
    protected static
        $table = 'support_topic';
        
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'title'   => new Type\Varchar(array(
                'description' => 'Тема',
                'checker' => array('chkEmpty', t('Не указана тема'))
            )),
            'user_id' => new Type\User(array(
                'description' => 'Пользователь',
                'checker' => array('chkEmpty', t('Не выбран пользователь'))
            )),
            'updated' => new Type\Datetime(array(
                'visible' => false,
                'description' => 'Дата обновления',
            )),
            'msgcount' => new Type\Integer(array(
                'visible' => false,
                'description' => 'Всего сообщений',
            )),
            'newcount' => new Type\Integer(array(
                'visible' => false,
                'description' => 'Новых сообщений',
            )),
            'newadmcount' => new Type\Integer(array(
                'visible' => false,
                'description' => 'Новых для администратора',
            )),
            '_first_message_' => new Type\Text(array(
                'description' => 'Сообщение',
                'runtime' => true,
            ))
        ));
    }
    
    
    function afterWrite($flag)
    {
        if($this['_first_message_']){
            $support_message = new \Support\Model\Orm\Support;
            $support_message->topic_id  = $this->id;
            $support_message->user_id = $this->user_id;
            $support_message->message = $this['_first_message_'];
            $support_message->dateof  = date('Y-m-d H:i:s');
            $support_message->insert();
        }
    }
    
    
    
    function delete()
    {
        $q = new \RS\Orm\Request();
        $q->delete()
            ->from(new Support())
            ->where( array('topic_id' => $this['id']) )
            ->exec();
        
        return parent::delete();
    }
    
    function getUser()
    {
        return new \Users\Model\Orm\User($this['user_id']);
    }
}