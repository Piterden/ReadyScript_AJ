<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Orm;

class ObjectList implements \ArrayAccess, \Iterator, \Countable
{
    private 
        $data = array(),
        $valid = false;
        
    
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if ($offset===null) $offset=count($this->data);
        if (!($value instanceof \RS\Orm\AbstractObject)) throw new Exception("Неправильно установлено свойство $offset");
        $this->data[$offset] = $value;
        return $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
    
    public function __toString()
    {
        $result = "";
        foreach ($this as $offset=>$element)
        {
            $result .= "<br>" . $offset . "<br>" . $element;
        }
        return $result;
    }
    
    public function findOne($f_key, $f_value)
    {
        foreach($this->data as $key=>$value)
            if ($value[$f_key] == $f_value) return $value;
        return null;
    }
    
    public function count() {return count($this->data);}
    
    public function current() {return current($this->data);}
    
    public function key() {return key($this->data);}
        
    public function next() {return $this->valid = next($this->data);}

    public function rewind() {return $this->valid = reset($this->data);}

    public function valid() {return $this->valid;}

    
    
    
}  

