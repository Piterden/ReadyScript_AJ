<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Orm\Storage;

abstract class AbstractStorage
{
    protected
        $Core_Object,
        $options;
    
    function __construct(\RS\Orm\AbstractObject $Core_Object, $options = array())
    {
        $this->Core_Object = $Core_Object;
        $this->options = $options;
        $this->_init();
    }
    
    function _init() 
    {}

    /**
    * Загружает объект по первичному ключу
    * 
    * @param mixed $primaryKey - значение первичного ключа
    * @return object
    */
    public function load($primaryKey = null)
    {}
    
    /**
    * Добавляет объект в хранилище
    * 
    * @return bool
    */    
    public function insert() 
    {}
    
    /**
    * Обновляет объект в хранилище
    * 
    * @param $primaryKey - значение первичного ключа
    * @return bool
    */
    public function update($primaryKey = null) 
    {}

    /**
    * Перезаписывает объект в хранилище
    * 
    * @return bool
    */    
    public function replace()
    {}
    
    /**
    * Удаляет объект из хранилища
    * 
    * @return bool
    */
    public function delete() 
    {}    
    
    /**
    * Возвращает параметр хранилища
    * 
    * @param mixed $key - имя параметра
    * @param mixed $default - возвращаемое значение, если данный ключ не задан
    * @return mixed
    */
    function getOption($key, $default = null)
    {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }
    
}

