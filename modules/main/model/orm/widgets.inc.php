<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Main\Model\Orm;
use \RS\Orm\Type;

class Widgets extends \RS\Orm\OrmObject
{
    protected static
        $table = 'widgets';
        
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'user_id' => new Type\Integer(array(
                'maxLength' => '11',
            )),
            'col' => new Type\Varchar(array(
                'maxLength' => '20',
            )),
            'position' => new Type\Integer(array(
                'maxLength' => '5',
            )),
            'class' => new Type\Varchar(array(
                'maxLength' => '255',
            )),
            'vars' => new Type\Text(array(
            )),
        ));
        
        $this->addIndex(array('site_id', 'user_id', 'class'), self::INDEX_UNIQUE);
    }
    
    function beforeWrite($flag)
    {
        if ($this['position'] === null) {
            //Если не задана позиция, то добавляем в конец
            $this['position'] = \RS\Orm\Request::make()->from($this)->where(array(
                'site_id' => $this['__site_id']->get(),
                'user_id' => $this['user_id'],
                'col' => $this['col']
            ))->count();
        }
    }    
    
    /**
    * Возвращает полное имя класса виджета для текущего объекта
    * 
    * @return string
    */
    function getFullClass()
    {
        return self::staticGetFullClass($this['class']);
    }
    
    /**
    * Возвращает полное имя класса виджета
    * 
    * @param string $short_classname - сокращенное имя класса контроллера виджета
    * @return string
    */    
    public static function staticGetFullClass($short_classname)
    {
        $classname = preg_replace('/^(.*?)-(.*)$/', '$1-controller-admin-$2',$short_classname);
        return str_replace('-','\\', $classname);        
    }
}

