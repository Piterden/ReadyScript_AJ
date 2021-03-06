<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model\Filter;

/**
* Фильтр по характеристикам в административной панели.
*/
class PropertyFilter extends \RS\Html\Filter\Type\AbstractType
{
    protected
        $property_api,
        $properties = array(),
        $abstract_tpl = '%catalog%/filter/property_filter.tpl';
    
    /**
    * Конструктор для фильтра по полю характеристика
    * 
    * @param array of \Catalog\Orm\Property\Item $property_ids
    * @param array $options
    * @return PropertyFilter
    */
    function __construct($properties = array(), $options = array())
    {
        $this->properties = $properties;
        $this->property_api = new \Catalog\Model\PropertyApi();
        parent::__construct('_p', '', $options);
    }
    
    /**
    * Возвращает свойства, которые должны отображаться в фильтре
    * @return array();
    */
    public function getProperties()
    {
        return $this->properties;
    }
    
    /**
    * Возвращает массив установленных фильтров
    * 
    * @return array
    */
    function getValue() {
        return (array)$this->value;
    }
    
    /**
    * Не возвращает условие для выборки, т.к. это делает modificateQuery
    * 
    * @return string возвращает пустую строку
    */
    function getWhere()
    {
        return '';
    }
    
    /**
    * Модифицирует запрос с учетом выбранных фильтров
    * 
    * @param \RS\Orm\Request $q - объект выборки данных из базы
    * @return \RS\Orm\Request
    */
    function modificateQuery(\RS\Orm\Request $q)
    {
        $q = $this->property_api->getFilteredQuery($this->getValue(), 'A', $q);
    }
    
    /**
    * Возвращает количество активных фильтров
    * 
    * @return int;
    */
    function isActiveFilter()
    {
        return count($this->property_api->cleanNoActiveFilters($this->getValue()));
    }

    /**
    * Возвращает массив с данными, об установленых фильтрах для визуального отображения частиц
    * 
    * @return array
    */
    public function getParts($current_filter_values)
    {
        $parts = array();
        
        $filters = $this->property_api->cleanNoActiveFilters($this->getValue());
        
        foreach($filters as $prop_id => $value) {

            $without_this = $current_filter_values;
            unset($without_this[$this->getKey()][$prop_id]);
            
            $property = $this->properties[$prop_id];
            switch($property['type']) {
                
                case 'int': {
                    $tmp = '';
                    if (!empty($value['from'])) {
                        $tmp = 'от '.$value['from'].' ';
                    }
                    if (!empty($value['to'])) {
                        $tmp .= 'до '.$value['to'];
                    }
                    $value = $tmp;                    
                    break;
                }
                
                case 'list': {
                    $value = implode(t(' или '), $value);
                    break;
                }
                
                case 'bool': {
                    $value = !empty($value) ? t('Есть') : t('Нет');
                }
            }
            
            $parts[] = array(
                'title' => $property->title,
                'value' => $value,
                'href_clean' => \RS\Http\Request::commonInstance()->replaceKey(array($this->wrap_var => $without_this)) //Url, для очистки данной части фильтра
            );
        }
        return $parts;
    }    
    
}

