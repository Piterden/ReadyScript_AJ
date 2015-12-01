<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace RS\Orm;

/**
* ORM объект для конфигураций блочных контроллеров
*/
class ControllerParamObject extends AbstractObject
{
    /**
    * Конструктор объекта параметров контроллера
    * 
    * @param PropertyIterator $properties - список свойств, свойство "sectionmodule" зарезервировано.
    * @return ControllerParamObject
    */
    function __construct(PropertyIterator $properties)
    {
        parent::__construct();
        $this->setPropertyIterator($properties);
    }
    
    function _init()
    {
        $this->setClassParameter('storage_class', '\RS\Orm\Storage\Stub');
    }    
}

