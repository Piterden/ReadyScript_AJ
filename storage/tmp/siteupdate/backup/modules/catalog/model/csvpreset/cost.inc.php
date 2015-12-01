<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model\CsvPreset;
use \Catalog\Model\Orm;

/**
* Набор колонок описывающих связь товара с ценами
*/
class Cost extends \RS\Csv\Preset\AbstractPreset
{
    protected static
        $type_cost = array(),
        $type_cost_by_title = array(),
        $currencies = array(),
        $currencies_by_title = array();
        
    protected
        $delimiter = ';',
        $id_field,
        $link_preset_id,
        $link_id_field,
        $manylink_orm,
        $orm_object;
        
    
    function __construct($options)
    {
        parent::__construct($options);
        $this->orm_object = new Orm\Typecost();
        $this->id_field = 'id';
                
        $this->manylink_orm = new Orm\Xcost();
        $this->manylink_foreign_id_field = 'cost_id';
        $this->manylink_id_field = 'product_id';
        
        $this->link_id_field = 'id';
        $this->link_preset_id = 0;
        $this->array_field = 'excost';
        $this->loadCurrencies();
    }
    
    function loadCurrencies()
    {
        $api = new \Catalog\Model\CurrencyApi();
        $list = $api->getList();
        foreach($list as $cost) {
            self::$currencies[$cost['id']] = $cost['title'];
        }
        self::$currencies_by_title = array_flip(self::$currencies);
        
        $type_api = new \Catalog\Model\CostApi();
        $list = $type_api->getList();
        foreach($list as $typecost) {
            self::$type_cost[$typecost['id']] = $typecost['title'];
        }
        self::$type_cost_by_title = array_flip(self::$type_cost);
    }

    /**
    * Загружает связанные данные
    * 
    * @return void
    */
    function loadData()
    {
        $ids = array();
        foreach($this->schema->rows as $row) {
            $ids[] = $row[$this->link_id_field];
        }
        $this->row = array();
        if ($ids) {
            $this->row = \RS\Orm\Request::make()
                ->from($this->manylink_orm, 'X')
                ->whereIn($this->manylink_id_field, $ids)
                ->objects(null, $this->manylink_id_field, true);
        }
    }    
    
    /**
    * Возвращает ассоциативный массив с одной строкой данных, где ключ - это id колонки, а значение - это содержимое ячейки
    * 
    * @param integer $n - индекс в наборе строк $this->rows
    * @return array
    */
    function getColumnsData($n)
    {
        $id = $this->schema->rows[$n][$this->link_id_field];
        $line = '';
        if (isset($this->row[$id])) {
            $lines = array();
            foreach($this->row[$id] as $n => $item) {
                $curr = isset(self::$currencies[$item['cost_original_currency']]) ? ' '.self::$currencies[$item['cost_original_currency']] : '';
                if (isset(self::$type_cost[$item['cost_id']])) {
                    $cost_title = self::$type_cost[$item['cost_id']];
                    $lines[$n] = "{$cost_title}:{$item['cost_original_val']}".$curr;
                }
            }
            $line = implode($this->delimiter."\n", $lines);
        }
        return array(
            $this->id.'-costlist' => $line
        );        
        
    }
    
    /**
    * Импортирует одну строку данных
    * 
    * @return void
    */
    function importColumnsData()
    {
        if (isset($this->row['costlist'])) {
            $items = explode($this->delimiter, $this->row['costlist']);
            $excost = array();            
            foreach($items as $item) {
                $item = trim($item);
                if (preg_match('/^(.*?):([0-9.]+)(.*?)?$/', $item, $match)) {
                    $cost = trim($match[1]);
                    if (isset(self::$type_cost_by_title[$cost])) {
                        $cost_id = self::$type_cost_by_title[$cost];                        
                        $original_val = trim($match[2]);
                        $currency_id = 0;
                        $currency = trim($match[3]);
                        if (!empty($match[3]) && isset(self::$currencies_by_title[$currency])) {
                            $currency_id =  self::$currencies_by_title[$currency];
                        }
                        $excost[$cost_id] = array(
                            'cost_original_val' => $original_val,
                            'cost_original_currency' => $currency_id
                        );
                    }
                }
            }
            $this->schema->getPreset($this->link_preset_id)->row[$this->array_field] = $excost;
        }
    }
    
    /**
    * Возвращает колонки, которые добавляются текущим набором 
    * 
    * @return array
    */
    function getColumns() {
        return array(
            $this->id.'-costlist' => array(
                'key' => 'costlist',
                'title' => t('Цены')
            )
        );
    }
    
}
?>