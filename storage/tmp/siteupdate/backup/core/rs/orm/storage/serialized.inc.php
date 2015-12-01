<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace RS\Orm\Storage;

/**
* Класс обеспечивающий хранение orm объекта в базе данных в сериализованном виде
*/
class Serialized extends AbstractStorage
{
    protected
        $table;

    function _init()
    {
        $this->table = $this->Core_Object->_getTable();
    }
    
    public function load($primaryKeyValue = null)
    {
        $row = \RS\Orm\Request::make()
            ->from($this->table)
            ->where($this->getOption('primary', array()))
            ->exec()->fetchRow();
        
        if ($row === false) return false;
        
        $data = @unserialize($row[$this->getOption('data_field', 'data')]);

        $this->Core_Object->getFromArray($data);
        return true;
    }
    
    public function exists($primaryKeyValue)
    {
        $row = \RS\Orm\Request::make()
            ->from($this->table)
            ->where($this->getOption('primary', array()))
            ->count();
        return ($row > 0);
    }
    
    protected function prepareForDB()
    {
        $query = array();
        $properties = $this->Core_Object->getProperties();
        $data = array();
        foreach ($properties as $key => $property)
        {
            if ($property->beforesave()) {
                $this->Core_Object[$key] = $property->get();
            }            
            if (!$property->isUseToSave()) continue;
            if (!$property->isRuntime()) {
                $data[$key] = $property->get();
            }
        }
        return serialize($data);
    }

    public function insert()
    {
        return $this->replace();
    }
    
    public function update($primaryKey = null)
    {
        return $this->insert();
    }
    
    public function replace()
    {
        $sql = "replace into {$this->table} set ";
        
        $arr_data = $this->getOption('primary', array());
        $arr_data[$this->getOption('data_field', 'data')] = $this->prepareForDB();

        $fields = array();
        foreach($arr_data as $key => $value) {
            $fields[] = "`$key` = '".\RS\Db\Adapter::escape($value)."'";
        }
        
        $sql .= implode(",", $fields);
        try {
            $dbresult = \RS\Db\Adapter::sqlExec($sql);
        } catch (\RS\Db\Exception $e) {
            if ($e->getCode() == 1062) {
                $this->Core_Object->addError(t('Запись с таким уникальным идентификатором уже присутствует'));
            } else {
                throw new \RS\Db\Exception($e->getMessage(), $e->getCode());
            }
          return false;
        }
        
        return true;
    }
    
    public function delete()
    {
        return \RS\Orm\Request::make()
            ->delete()
            ->from($this->table)
            ->where($this->getOption('primary', array()))
            ->exec()->affectedRows();
    }
    
}