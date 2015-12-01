<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model\CsvPreset;

class Property extends \RS\Csv\Preset\AbstractPreset
{
    protected static
        $props,
        $groups,
        $index;
    
    protected
        $delimiter = ";",
        $value_delimiter = "|",
        $id_field = 'id',
        $link_id_field,
        $link_preset_id,
        $mask,
        $mask_fields = array(),
        $mask_pattern,
        $title,
        $array_field = 'prop',
        $manylink_foreign_id_field = 'prop_id',
        $manylink_id_field = 'product_id',
        $manylink_orm;
    
    function __construct($options)
    {
        $defaults = array(
            'ormObject' => new \Catalog\Model\Orm\Property\Item(),
            'manylinkOrm' => new \Catalog\Model\Orm\Property\Link(),                    
            'mask' => '({group_name}){property}:{value}',
            'multisite' => true
        );
        parent::__construct($options + $defaults);
        $this->loadProperty();        
    }
    
    
    /**
    * Добавляет дополнительное условие в виде site_id = ТЕКУЩИЙ САЙТ, если задано true
    * 
    * @param bool $bool
    * @return void
    */
    function setMultisite($bool)
    {
        $this->is_multisite = $bool;
    }
    
    /**
    * Возвращает условие для добавления к Where, если установлено свойство multisite => true
    * 
    * @return array
    */
    function getMultisiteExpr()
    {
        return $this->is_multisite ? array('site_id' => \RS\Site\Manager::getSiteId()) : array();
    }        
    
    function loadProperty()
    {
        if (!isset(self::$props)) {
            self::$props = \RS\Orm\Request::make()
                ->from(new \Catalog\Model\Orm\Property\Item())
                ->where($this->getMultisiteExpr() ?: null)
                ->objects(null, 'id');
            
            self::$groups = \RS\Orm\Request::make()
                ->from(new \Catalog\Model\Orm\Property\Dir())
                ->where($this->getMultisiteExpr() ?: null)
                ->objects(null, 'id');
            
            foreach(self::$props as $prop) {
                $group_name = isset(self::$groups[$prop['parent_id']]) ? self::$groups[$prop['parent_id']]['title'] : '';
                self::$index["($group_name)".$prop['title']] = $prop['id'];
            }
        }
    }
            
    /**
    * Устанавливает ORM объект связки многие ко многим
    * 
    * @param \RS\Orm\AbstractObject $orm
    * @return void
    */
    protected function setManylinkOrm(\RS\Orm\AbstractObject $orm)
    {
        $this->manylink_orm = $orm;
    }
    
    function setLinkIdField($field)
    {
        $this->link_id_field = $field;
    }
    
    function setLinkPresetId($id)
    {
        $this->link_preset_id = $id;
    }
    
    /**
    * Устанавливает маску для формирования строки из данных в CSV файле
    * 
    * @param string $mask
    * @return void
    */
    protected function setMask($mask)
    {
        $this->mask = $mask;
        $this->mask_fields = array();
        if (preg_match_all('/\{(.*?)\}/', $this->mask, $match)) {
            foreach($match[1] as $field) {
                $this->mask_fields[] = $field;
            }
        }
        $pattern = preg_replace_callback('/(\{.*?\})/', function($match) {
                    $field = trim($match[1], '{}');
                    return "(?P<{$field}>.*?)";
                }, quotemeta($this->mask));
        $this->mask_pattern = '/^'.$pattern.'$/';        
    }
    
    /**
    * Устанавливает название экспортной колонки
    * 
    * @param mixed $title
    */
    function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
    * Загружает связанные данные
    * 
    * @return void
    */
    function loadData()
    {
        $this->row = array();
        if ($this->schema->ids) {
            $this->rows = \RS\Orm\Request::make()
                ->from($this->manylink_orm)
                ->whereIn($this->manylink_id_field, $this->schema->ids)
                ->objects(null, $this->manylink_id_field, true);
        }
        
    }
    
    function getColumns()
    {
        return array(
            $this->id.'-properties' => array(
                'key' => 'properties',
                'title' => $this->title
            )
        );
    }
    
    function getColumnsData($n)
    {
        $id = $this->schema->rows[$n][$this->link_id_field];
        $data = array();
        if (isset($this->rows[$id])) {
            foreach($this->rows[$id] as $property_link) {
                $property = isset(self::$props[ $property_link['prop_id'] ]) ? self::$props[ $property_link['prop_id'] ] : false;
                if ($property) {
                    $group_title = @self::$groups[$property['parent_id']]['title'];
                    $property_name = '('.$group_title.')'.$property['title'];
                    $value = $property_link[ $property->getValueLinkField() ];
                    if ($value == '') continue;
                    if (isset($data[$property['id']])) {
                        $data[$property['id']] .= $this->value_delimiter.$value;
                    } else {
                        $data[$property['id']] = $property_name.':'.$value;
                    }                    
                }
            }
        }
        return array($this->id.'-properties' => implode(";\n", $data));
    }
    
    function importColumnsData()
    {
        if (isset($this->row['properties'])) {
            $items = explode($this->delimiter, $this->row['properties']);
            $result_array = array();
            foreach($items as $item) {
                $item = trim($item);
                if (preg_match($this->mask_pattern, $item, $match)) {
                    
                    $index_name = "({$match['group_name']}){$match['property']}";
                    if (isset(self::$index[$index_name])) {
                        if (strpos($match['value'], $this->value_delimiter) !== false) {
                            $match['value'] = explode($this->value_delimiter, $match['value']);
                            $match['value'] = array_map("trim", $match['value']);
                        }
                        
                        $prop_id = self::$index[$index_name];
                        $result_array[$prop_id] = array(
                            'id' => $prop_id,
                            'is_my' => 1,
                            'value' => $match['value']
                        );
                    }
                }
            }

            $this->schema->getPreset($this->link_preset_id)->row[$this->array_field] = $result_array;
        }
    }    
    
}
?>
