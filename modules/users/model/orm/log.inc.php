<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Users\Model\Orm;
use \RS\Orm\Type;

class Log extends \RS\Orm\OrmObject
{
    protected static
        $table = 'users_log';
    
    function _init()
    {
        parent::_init();

        $this->getPropertyIterator()->append(array(
            'site_id' => new Type\CurrentSite(),
            'dateof' => new Type\Datetime(array(
                'description' => 'Дата',
            )),
            'class' => new Type\String(array(
                'maxLength' => '255',
                'description' => 'Класс события',
            )),
            'oid' => new Type\Integer(array(
                'description' => 'ID объекта над которым произошло событие',
            )),
            'group' => new Type\Integer(array(
                'description' => 'ID Группы (перезаписывается, если событие происходит в рамках одной группы)',
            )),
            'user_id' => new Type\Bigint(array(
                'description' => 'ID Пользователя',
            )),
            '_serialized' => new Type\String(array(
                'maxLength' => '4000',
                'description' => 'Дополнительные данные (скрыто)',
                'visible' => false,
            )),
            'data' => new Type\ArrayList(array(
                'description' => 'Дополнительные данные',
            )),
        ));
        
        $this
            ->addIndex(array('class', 'user_id', 'group'), self::INDEX_UNIQUE)
            ->addIndex(array('site_id', 'class'));
    }
    
    function beforeWrite($flag)
    {
        $this['_serialized'] = serialize($this['data']);
    }
    
    function afterObjectLoad()
    {
        $this['data'] = @unserialize($this['_serialized']);
    }
}

