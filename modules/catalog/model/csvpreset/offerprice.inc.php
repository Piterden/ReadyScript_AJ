<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model\CsvPreset;

class OfferPrice extends \RS\Csv\Preset\AbstractPreset
{
    protected
        $link_preset_id,
        $link_foreign_field,
        
        $offer_api,
        $price_delimiter = ";",
        $currencies,
        $currencies_by_name,
        $type_costs,
        $type_costs_by_name;
        
    function __construct($options)
    {        
        $this->offer_api = new \Catalog\Model\OfferApi();
        parent::__construct($options);
    }
    
    function loadCosts()
    {
        if ($this->type_costs === null) {
            $site_id = \RS\Site\Manager::getSiteId();
            $this->type_costs = \RS\Orm\Request::make()
                ->from(new \Catalog\Model\Orm\Typecost())
                ->where(array(
                    'site_id' => $site_id
                ))->objects(null, 'id');
            
            $this->currencies = \RS\Orm\Request::make()
                ->from(new \Catalog\Model\Orm\Currency())
                ->where(array(
                    'site_id' => $site_id
                ))->objects(null, 'id');
        }
    }
    
    /**
    * Определяет foreign key другого объекта
    * 
    * @param string $field
    * @return void
    */
    function setLinkForeignField($field)
    {
        $this->link_foreign_field = $field;
    }
    
    /**
    * Устанавливает номер пресета, к которому линкуется текущий пресет
    * 
    * @param integer $n - номер пресета
    * @return void
    */
    function setLinkPresetId($n)
    {
        $this->link_preset_id = $n;
    }
    
    /**
    * Возвращает колонки, которые добавляются текущим набором 
    * 
    * @return array
    */
    function getColumns()
    {
        return array(
            $this->id.'-offerprice' => array(
                'key' => 'offerprice',
                'title' => t('Цены')
            )
        );
    }
    
    
    /**
    * Возвращает набор колонок с данными для одной строки
    * 
    * @param mixed $n
    */
    function getColumnsData($n)
    {
        $field_data = $this->schema->rows[$n][$this->link_foreign_field];
        $this->loadCosts();
        
        $this->row = array();
        foreach($this->getColumns() as $id => $column) {
            $data = $field_data;
            $value = '';
            if ($data) {
                if (!empty($data['oneprice']['use'])) {
                    @$currency = $data['oneprice']['unit'] == '%' ? '%' : $this->currencies[$data['oneprice']['unit']]['title'];
                    $value = $data['oneprice']['znak'].$data['oneprice']['value'].'('.$currency.')';
                } else {
                    $values = array();
                    foreach($data['price'] as $price_id => $params) {
                        if (isset($this->type_costs[$price_id])) {
                            @$currency = $params['unit'] == '%' ? '%' : $this->currencies[$params['unit']]['title'];
                            $values[] = $this->type_costs[$price_id]['title'].':'.$params['znak'].$params['value'].'('.$currency.')';
                        }
                    }
                    $value = implode($this->price_delimiter."\n", $values);
                }
            }
            $this->row[$id] = $value;
        }
        return $this->row;
    }
    
    
    /**
    * Импортирует данные одной строки текущего пресета в базу
    */
    function importColumnsData()
    {
        if ($this->type_costs_by_name === null) {
            $this->loadCosts();
            $this->type_costs_by_name = array();
            foreach($this->type_costs as $cost) {
                $this->type_costs_by_name[$cost['title']] = $cost['id'];
            }
            
            $this->currencies_by_name = array();
            foreach($this->currencies as $currency) {
                $this->currencies_by_name[$currency['title']] = $currency['id'];
            }
        }
        
        $oneprice_pattern = '/^([+-=])([\d\.]+)\((.*?)\)?$/';
        $pattern = '/^(.*?)\:([+-=])([\d\.]+)\((.*?)\)?$/';
        
        $arr = array();
        if (preg_match($oneprice_pattern, $this->row['offerprice'], $match)) {
            $unit_title = isset($match[3]) ? $match[3] : '';
            $unit = isset($this->currencies_by_name[$unit_title]) ? $this->currencies_by_name[$unit_title] : 0;
            
            $arr['oneprice'] = array(
                'use' => 1,
                'znak' => $match[1],
                'original_value' => $match[2],
                'unit' => $unit
            );
        } else {
            $prices = explode($this->price_delimiter, $this->row['offerprice']);
            foreach($prices as $price) {
                $price = trim($price);
                if (preg_match($pattern, $price, $match)) {
                    if (isset($this->type_costs_by_name[$match[1]])) {
                        $unit_title = isset($match[4]) ? $match[4] : '';
                        $unit = isset($this->currencies_by_name[$unit_title]) ? $this->currencies_by_name[$unit_title] : 0;
                                                
                        $arr['price'][$this->type_costs_by_name[$match[1]]] = array(
                            'znak' => $match[2],
                            'original_value' => $match[3],
                            'unit' => $unit
                        );
                    }
                }
            }
        }
                    
        $preset = $this->schema->getPreset($this->link_preset_id);
        $preset->row[$this->link_foreign_field] = $arr;
    } 
    
}