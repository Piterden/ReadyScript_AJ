<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace RS\Application;

/**
* Класс, отвечающий за содержимое тега title в head части страницы
*/
class Title
{
    const
        DELIMITER = ' :: ';
        
    protected 
        $sections = array();
        
    public
        $enabled = true;
    
    /**
    * Добавляет секцию в заголовок
    * 
    * @param string $str - заголовок
    * @param string $key - идентификатор секции, для последующей перезаписи
    * @return Title
    */
    function addSection($str, $key = null)
    {
        if ($str == '') return $this;
        if ($this->enabled) {
            if ($key !== null) {
                $this->sections[$key] = $str;
            } else {
                $this->sections[] = $str;                
            }
        }
        return $this;
    }
    
    /**
    * Удаляет секцию из заголовка
    * 
    * @param string $key - идентификатор секции
    * @return Title
    */
    function removeSection($key)
    {
        unset($this->sections[$key]);
        return $this;
    }
    
    /**
    * Очищает заголовок
    * 
    * @return Title
    */
    function clean()
    {
        $this->sections = array();
        return $this;
    }
    
    /**
    * Возвращает заголовок
    * @return string
    */
    function get()
    {
        $sec = $this->sections;
        return implode(self::DELIMITER,array_reverse($sec));
    }
    
}

