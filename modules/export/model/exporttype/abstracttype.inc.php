<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Export\Model\ExportType;

/**
* Абстрактный класс типа экспорта.
*/
abstract class AbstractType extends \RS\Orm\AbstractObject
{
    function _init()
    {
        return $this->getPropertyIterator();
    }  
    
    /**
    * Возвращает название расчетного модуля (типа экспорта)
    * 
    * @return string
    */
    abstract public function getTitle();
    
    /**
    * Возвращает описание типа экспорта. Возможен HTML
    * 
    * @return string
    */
    abstract public function getDescription();
    
    /**
    * Возвращает идентификатор данного типа экспорта. (только англ. буквы)
    * 
    * @return string
    */
    abstract public function getShortName();
    
    /**
    * Возвращает экспортированные данные (XML, CSV, JSON и т.п.)
    * 
    * @param \Export\Model\Orm\ExportProfile $profile Профиль экспорта
    * @return string
    */
    abstract public function export(\Export\Model\Orm\ExportProfile $profile);
    
    
    /**
    * Если для экспорта нужны какие-то специфические заголовки, то их нужно отправлять в этом методе
    */
    public function sendHeaders()
    {
    }
    
    /**
    * Возвращает объект хранилища
    * 
    * @return \RS\Orm\Storage\AbstractStorage
    */
    protected function getStorageInstance()
    {
        return new \RS\Orm\Storage\Stub($this);
    }
}
?>
