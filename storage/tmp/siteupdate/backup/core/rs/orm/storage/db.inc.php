<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace RS\Orm\Storage;

/**
* Класс обеспечивающий хранение core объекта в базе данных
*/
class Db extends AbstractStorage
{
    protected
        $table;

    function _init()
    {
        $this->table = $this->Core_Object->_getTable();
    }
    
    public function load($primaryKeyValue = null)
    {
        if (!isset($primaryKeyValue)) return false;

        $primary = $this->Core_Object->getPrimaryKeyProperty();        
        $dbresult = \RS\Db\Adapter::sqlExec("select * from {$this->table} where `$primary`='".\RS\Db\Adapter::escape($primaryKeyValue)."'");
        $row = $dbresult -> fetchRow();        
        if ($row === false) return false;
        $this->Core_Object->getFromArray($row);
        return true;
    }
    
    public function exists($primaryKeyValue)
    {
        $primary = $this->Core_Object->getPrimaryKeyProperty();
        $dbresult = \RS\Db\Adapter::sqlExec("select count(*) as cnt from {$this->table} where `$primary`='".\RS\Db\Adapter::escape($primaryKeyValue)."'");
        $row = $dbresult -> fetchRow();
        return ($row["cnt"] == "1");
    }
    
    protected function prepareForDB($only_modified = false)
    {
        $query = array();
        $properties = $this->Core_Object->getProperties();
        
        foreach ($properties as $key=>$property)
        {
            if ($property->beforesave()) {
                $this->Core_Object[$key] = $property->get();
            }
            
            if ($only_modified && !$this->Core_Object->isModified($key)) continue;
            if (!$property->isUseToSave()) continue;
            
            if (!$property->isRuntime() && ($this->Core_Object->isModified($key) || $this->Core_Object->offsetExists($key)) ) {
                if (is_null($property->get()) && $property->isAllowEmpty()) {
                    $query[] = "`$key` = NULL";
                } else {
                    $query[] = "`$key` = '".\RS\Db\Adapter::escape($property->get())."'";
                }
            }
        }
        return $query;
    }

    /**
    * Вставляет объект в БД
    * 
    * @param string $type - тип вставки insert или replace
    * @param array $on_duplicate_update_keys - поля, которые необходимо обновить в случае если запись уже существует
    * @param array $on_duplicate_uniq_fields - поля, которые точно идетифицируют текущаю запись, для подгрузки id объекта при обновлении
    * @return bool
    */
    public function insert($type = 'insert', $on_duplicate_update_keys = array(), $on_duplicate_uniq_fields = array())
    {
        $sql = "$type into {$this->table} set ";
        $query = $this->prepareForDB();
        if (empty($query)) return true; //Ни одно свойство не изменилось, запрос выполнять не нужно
        
        $sql .= implode(",",$query);
        
        if ($on_duplicate_update_keys) {
            if (!$on_duplicate_uniq_fields) {
                throw new \RS\Orm\Exception(t('Не задан параметр on_duplicate_uniq_fields'));
            }
            $on_duplicate = array();
            foreach($on_duplicate_update_keys as $field) {
                $on_duplicate[] = "`$field` = VALUES(`$field`)";
            }
            $sql .= ' ON DUPLICATE KEY UPDATE '.implode(',', $on_duplicate);
        }
        
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
        if ($primary_key = $this->Core_Object->getPrimaryKeyProperty()) {
            $primary_key_value = $this->Core_Object[$this->Core_Object->getPrimaryKeyProperty()];            
        }
        
        if ($on_duplicate_update_keys) {
            //Сообщаем ORM объекту, что он был обновлен а не создан.
            $updated = $dbresult->affectedRows()!=1;
            $this->Core_Object->setLocalParameter('duplicate_updated', $updated );
            
            if ($updated && $primary_key !==false && empty($primary_key_value)) {
                $expr = array();
                foreach($on_duplicate_uniq_fields as $field) {
                    $expr[] = "`$field` = '".\RS\Db\Adapter::escape($this->Core_Object[$field])."'";
                }
            
                $sql = "SELECT $primary_key FROM {$this->table} WHERE ".implode(' AND ', $expr)." LIMIT 1";
                $primary_key_value = \RS\Db\Adapter::sqlExec($sql)->getOneField($primary_key);
                $this->Core_Object[$primary_key] = $primary_key_value;
            }
        }
        
        if ($primary_key !==false && empty($primary_key_value)) $this->Core_Object[$primary_key] = \RS\Db\Adapter::lastInsertId();
        return true;
    }
    
    public function update($primaryKey = null)
    {
        $sql = "update {$this->table} set ";
        $query = $this->prepareForDB(true);
        if (empty($query)) return true; //Ни одно свойство не изменилось, запрос выполнять не нужно

        $prop = $this->Core_Object->getPrimaryKeyProperty();
        if (!isset($primaryKey)) $primaryKey = $this->Core_Object[$prop];
        
        $sql .= implode(",",$query) . " where $prop='" . $primaryKey."'";

        try {
            \RS\Db\Adapter::sqlExec($sql);
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
    
    public function replace()
    {
        return $this->insert('replace');
    }
    
    public function delete()
    {
        $prop = $this->Core_Object->getPrimaryKeyProperty();
        $sql = "delete from {$this->table} where $prop='" . \RS\Db\Adapter::escape($this->Core_Object[$prop]) . "'";
        \RS\Db\Adapter::sqlExec($sql);
        return \RS\Db\Adapter::affectedRows();
    }
    
}
