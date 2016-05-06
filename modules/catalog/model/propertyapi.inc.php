<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model;

class PropertyApi extends \RS\Module\AbstractModel\EntityList
{
    const
        ECLIPSE_FLOAT_COMPARE = 0.00001; //Погрешность, связанная с особенностями поиска в Float полях mysql
        
    protected
        $pta = 'P', //Product table alias
        $filter_active,
        $post_var = 'prop',
        $name_field = 'title',
        $obj_link = '\Catalog\Model\Orm\Property\Link',
        
        $valueField = array(
            'int' => 'val_int',
            'string' => 'val_str',
            'list' => 'val_str',
            'bool' => 'val_int'
        ),
        $prop_item_table,
        $prop_link_table;
        
    function __construct()
    {
        parent::__construct(new \Catalog\Model\Orm\Property\Item,
        array(
            'multisite' => true,
            'defaultOrder' => 'parent_sortn, sortn',
            'sortField' => 'sortn'
        ));
        
        $link = new Orm\Property\Link();
        $this->prop_link_table = $link->_getTable();        
        $this->prop_item_table = $this->obj_instance->_getTable();
    }
    
    /**
    * Возвращает статическим методом полный список значений характеристик
    * 
    * @param boolean $add_unselected - добавлять к массиву пункт не выбрано.
    */
    static function staticSelectList($add_unselected = false)
    {
        $groups = array(0 => array('title' => t('Без группы'))) + 
            \RS\Orm\Request::make()
            ->from(new \Catalog\Model\Orm\Property\Dir())
            ->orderby('sortn')
            ->where(array(
                'site_id' => \RS\Site\Manager::getSiteId()
            ))
            ->objects(null,'id');
        
        $props = \RS\Orm\Request::make()
            ->from(new \Catalog\Model\Orm\Property\Item())
            ->orderby('sortn')
            ->where(array(
                'site_id' => \RS\Site\Manager::getSiteId()
            ))
            ->objects();
        $list = array();
        if (!empty($props)){
            foreach ($props as $prop){
               $title = $groups[$prop['parent_id']]['title'] ?: 'Без группы'; 
               $list[$title][$prop['id']] = $prop['title'];
            }
        } 
        if ($add_unselected){
           $list = array(0=>'Не выбрано') + $list;
        }
        
        return $list; 
    }
    
    /**
    * Возвращает статическим методом полный список значений характеристик
    * с возможностью указания типа этой характеристики
    * 
    * @param string $type - тип характеристики
    */
    static function staticSelectListByType($type = null)
    {
        $groups = \RS\Orm\Request::make()
            ->from(new \Catalog\Model\Orm\Property\Dir())
            ->orderby('title')
            ->where(array(
                'site_id' => \RS\Site\Manager::getSiteId()
            ))
            ->objects(null,'id');
        
        $props = \RS\Orm\Request::make()
            ->from(new \Catalog\Model\Orm\Property\Item())
            ->orderby('title')
            ->where(array(
                'site_id' => \RS\Site\Manager::getSiteId()
            ));
        if (!empty($type)){
            $props->where(array(
                'type' => $type
            ));
        }
            
        $props = $props->objects();
            
        if (!empty($props)){
            foreach ($props as $prop){
               $title = $groups[$prop['parent_id']]['title'] ?: 'Без группы'; 
               $list[$title][$prop['id']] = $prop['title'];
            }
        } 
        return $list; 
    }
    
    /**
    * Обновляет parent_sortn у всех характеристик группы dir_id
    * 
    * @param integer $dir_id
    */
    function updateParentSort($from, $to)
    {
        if ($from > $to) {
            $tmp = $from;
            $from = $to;
            $to = $tmp;
        }
        
        $q = new \RS\Orm\Request();
        $q->update()
        ->from($this->obj_instance)->asAlias('P')
        ->from(new Orm\Property\Dir())->asAlias('D')
        ->set('P.parent_sortn = D.sortn')
        ->where("P.parent_id = D.id AND D.id >= '#from' AND D.id <= '#to'", array(
            'from' => $from,
            'to' => $to
        ))
        ->exec();
    }
    
    /**
    * Получает сведения о характеристиках и группах в которых они состоят в виде массива.
    * Параметром передаются массив с id-шниками характеристик 
    * 
    * @param array $properties_id - массив id-шников характеристик, 
    * для которых нужно получить сведения 
    */
    
    function getPropertiesAndGroup(array $properties_id)
    {
        $properties = \RS\Orm\Request::make()
            ->from(new Orm\Property\Item())
            ->whereIn('id', $properties_id)
            ->orderby('sortn')
            ->objects(null, 'parent_id', true);
        
        $dir_ids = array_keys($properties);
        
        $root = new Orm\Property\Dir();
        $root['id'] = 0;
        
        $dirs = array(0 => $root); //Директории свойств
        if (count($dir_ids)) {
            $dirs += \RS\Orm\Request::make()
                ->from(new Orm\Property\Dir())
                ->whereIn('id', $dir_ids)
                ->orderby('sortn')
                ->objects(null, 'id');
        }
            
        return array(
            'properties' => $properties,
            'groups' => $dirs
        );
    }
    
    
    /**
    * Полностью обрабатывает POST со свойствами
    * 
    * @param int $obj_id
    * @param string group|product $link_type
    */
    function saveProperties($obj_id, $link_type, $property_list)
    {
        $site_id = \RS\Site\Manager::getSiteId();        
        \RS\Orm\Request::make()
            ->delete()
            ->from(new Orm\Property\Link())
            ->where(array(
                'site_id' => $site_id,
                "{$link_type}_id" => $obj_id
            ))
            ->exec();
                    
        if (!empty($property_list))
        {
            $link_sql = array();
            
            $key_type = \RS\Orm\Request::make()
                ->select('id, type')
                ->from(new Orm\Property\Item())
                ->whereIn('id', array_keys($property_list))
                ->exec()
                ->fetchSelected('id', 'type');
            
            
            foreach($property_list as $prop)
            {
                if (!isset($key_type[$prop['id']])) continue;
                
                if (isset($prop['value']) || !empty($prop['usevalue']))
                {
                    $value = (array)$prop['value'];
                    foreach($value as $one_value) {
                        $link = array(
                            'site_id' => $site_id,
                            'prop_id' => $prop['id'],
                            "{$link_type}_id" => $obj_id,
                            'val_str' => 'NULL',
                            'val_int' => 'NULL',
                            'xml_id' => isset($prop['xml_id']) ? $prop['xml_id'] : 'NULL',
                            'public' => isset($prop['public']) ? $prop['public'] : 0
                        );
                        
                        if (!empty($prop['usevalue']) || isset($prop['is_my'])) {
                            $link[ $this->valueField[$key_type[$prop['id']]] ] = "'".\RS\Db\Adapter::escape( $one_value )."'";
                        }
                        $link_sql[] = "(".implode(',', \RS\Helper\Tools::arrayQuote($link, array('val_str','val_int'), "'", true)).")";
                    }
                }
            }

            if (!empty($link_sql))
            {                
                $sql = "INSERT INTO {$this->prop_link_table}(site_id, prop_id, {$link_type}_id, val_str, val_int, xml_id, public) VALUES".implode(',', $link_sql);
                \RS\Db\Adapter::sqlExec($sql);
            }
            
        }
    }
    
    /**
    * Возвращает характеристики возможные вместе с группами в виде массива
    * с ключами group и properties
    * 
    * @param boolean $allow_empty_group - выводить ли группы с отсутвующими характеристиками внутри
    */
    function getAllPropertiesAndGroups($allow_empty_group = false)
    {
        $data = array();
        //Получим группы харакеристик           
        $dirs = \RS\Orm\Request::make()
            ->from(new \Catalog\Model\Orm\Property\Dir())
            ->where(array(
                'site_id' => \RS\Site\Manager::getSiteId()
            ))
            ->orderby('sortn')
            ->objects(null, 'id');

        //Получим все характеристики сгруппированными
        $properties = \RS\Orm\Request::make()
                            ->from(new \Catalog\Model\Orm\Property\Item())
                            ->where(array(
                                'site_id' => \RS\Site\Manager::getSiteId()
                            ))                            
                            ->orderby('sortn')
                            ->objects(null, 'parent_id', true);
            
        if (!empty($dirs)) { //Если группы есть
          foreach($dirs as $dir){
               $data[$dir['id']]['group'] = $dir;
               if (!empty($properties[$dir['id']]) || $allow_empty_group){
                  $data[$dir['id']]['properties'] = $properties[$dir['id']]; 
               }else{
                  unset($data[$dir['id']]);
               }  
          } 
        }

        //Получим характеристики лежащие в корне             
        if (!empty($properties[0]) || $allow_empty_group){
          $data[0]['group'] = array(
            'id'    => 0,
            'title' => 'Все'
          );  
          
          $data[0]['properties'] = $properties[0];
        }

        return $data;
    }
    
    /**
    * Возвращает характеристики, привязанные к категории товаров
    * 
    * @param integer|array $group_id     - id или массив с id категорий товаров, у которых запрашиваем свойства
    * @param true|false $include_parent  - true - будут в результат включатся ещё и унаследованные свойства 
    * от родительских категорий. По умолчанию true.
    * @param null|integer $public        - null - не учитывать публичное это свойство или нет. 
    * integer - значение поля public свойства. По умолчанию null.
    */
    function getGroupProperty($group_id, $include_parent = true, $public = null)
    {        
        if ($include_parent) {
            $dirapi      = Dirapi::getInstance();
            $groups_id   = array_keys($dirapi->getPathToFirst($group_id));
            $groups_id[] = 0; //Включаем в список корневой элемент. Содержащий свойства для всех элементов дерева
        } else {
            $groups_id   = (array)$group_id;
        }
        
        $public_where = ($public !== null) ? "public='".(int)$public."' " : null;
        $res = \RS\Orm\Request::make()
            ->select('T.*,  TL.val_str, TL.val_int, TL.group_id, TL.public')
            ->from($this->prop_item_table)->asAlias('T')
            ->join($this->prop_link_table, 'T.id=TL.prop_id', 'TL')
            ->where(array(
                'TL.site_id' => \RS\Site\Manager::getSiteId()
            ))
            ->whereIn('TL.group_id', $groups_id)
            ->where($public_where)
            ->orderby('T.parent_id, T.sortn')
            ->exec();
                            
        $tmp = array();
        $all_property_parents = array();
        while($row = $res->fetchRow()) 
        {
            $row['value'] = ($row['type'] == 'int' || $row['type'] == 'bool') ? $row['val_int'] : $row['val_str'];
            
            //Устанавливаем унаследованное или нет данное свойство
            if (isset($tmp[$row['parent_id']][$row['id']])) {
                $tmp[$row['parent_id']][$row['id']]['is_my'] = $tmp[$row['parent_id']][$row['id']]['is_my'] && ($row['group_id'] == $group_id);
            } else {
                $row['is_my'] = ($row['group_id'] == $group_id);
            }
            
            //Если это непосредственно запись для группы
            if ($row['group_id'] == $group_id) {
                $row['useval'] = true;
            } else {
                //Убираем перезапись собственного значения
                if ( isset($tmp[$row['parent_id']][$row['id']]) && $tmp[$row['parent_id']][$row['id']]['useval']==true ) {
                    $row['useval'] = true;
                    $row['value'] = $tmp[$row['parent_id']][$row['id']]['value'];
                    $row['public'] = $tmp[$row['parent_id']][$row['id']]['public'];
                }                
            }
            
            $item = new Orm\Property\Item();
            $item->getFromArray($row);  
            
            //Если значение - массив
            if ($row['type'] == 'list' && isset($row['value'])) {
                if (isset($tmp[$row['parent_id']][$row['id']])) {
                    $item = $tmp[$row['parent_id']][$row['id']];
                    
                    $item['useval'] = !empty($row['useval']);
                    $item['public'] = $row['public'];
                    
                    $arr = $item['value'];
                } else {
                    $arr = array();
                }
                if ($row['value'] !== null) {
                    $arr[] = $row['value'];
                }
                $item['value'] = $arr;
            }                
            
            $tmp[$row['parent_id']][$row['id']] = $item;
            $all_property_parents[$row['parent_id']] = $row['parent_id'];            
        }
        
        $dirs = array(0 => new Orm\Property\Dir()); //Директории свойств
        if (count($all_property_parents)) {
            $dirs += \RS\Orm\Request::make()
                ->from(new Orm\Property\Dir())
                ->whereIn('id', $all_property_parents)
                ->orderby('sortn')
                ->objects(null, 'id');
        }
        
        $result = array();
        foreach($dirs as $dir_id => $dir_object) {
            if (isset($tmp[$dir_id])) {
                $result[$dir_id]['group'] = $dir_object;
                $result[$dir_id]['properties'] = $tmp[$dir_id];
            }
        }
        
        return $result;
    }
    
    /**
    * Создает или обновляет характеристику
    * 
    * @param array $item
    */
    function createOrUpdate(array $item)
    {
        $id = !empty($item['id']) ? $item['id'] : null;
        
        //Создаем группу, если необходимо
        if ($this->getElement()->checkData($item)) {
            $dir = new Orm\Property\Dir();            
            $dir['id'] = 0;
            if (!empty($item['new_group_title'])) {
                $dir['title'] = $item['new_group_title'];
                $dir->insert();
                $item['parent_id'] = $dir['id'];
            } else {
                $dir->load($item['parent_id']);
            }
        } else {
            return false;
        }
        if ($id) $this->getElement()->load($id);
        $this->getElement()->getFromArray($item);
        
        if ($id) {
            $this->getElement()->update($id);
        } else {
            $this->getElement()->insert();
        }
        
        return array(
            'group' => $dir,
            'property' => $this->getElement()
        );
    }
    
    
    /**
    * Загружает характеристики для списка товаров
    * @param array | Orm\Product $obj
    */
    function getProductProperty($obj)
    {
        $obj_list = ($obj instanceof Orm\Product) ? array($obj) : $obj;
                
        $query_cache = array();
        $all_property_parents = array();
        $propertyByProduct = array();  //[product_id][property_id] => array Property
        
        //Загружаем свойства и значения, заданные непосредственно у товаров (одним запросом)
        $product_list_sql = array();
        foreach($obj_list as $product) {
            if (!is_null($product['id']))
                $product_list_sql[] = $product['id'];
        }
        
        if (!empty($product_list_sql)) {
            $res = \RS\Orm\Request::make()
                ->select('T.*, L.val_str, L.val_int, L.product_id')
                ->from($this->prop_link_table)->asAlias('L')
                ->join($this->prop_item_table, 'T.id = L.prop_id', 'T')
                ->whereIn('L.product_id', $product_list_sql)
                ->exec();
                
            while($row = $res->fetchRow()) {

                $row['value'] = ($row['type'] == 'int' || $row['type'] == 'bool') ? $row['val_int'] : $row['val_str'];
                $row['is_my'] = true; //Это свойство создано у товара
                $item = new Orm\Property\Item();
                $item->getFromArray($row);

                //Если значение - массив
                if ($row['type'] == 'list') {
                    if (isset($propertyByProduct[$row['product_id']][$row['parent_id']][$row['id']])) {
                        $item = $propertyByProduct[$row['product_id']][$row['parent_id']][$row['id']];
                        $arr = $item['value'];
                    } else {
                        $arr = array();
                    }
                    if ($row['value'] !== '') {
                        $arr[] = $row['value'];
                    }
                    $item['value'] = $arr;
                }

                $propertyByProduct[$row['product_id']][$row['parent_id']][$row['id']] = $item;
                
                $all_property_parents[$row['parent_id']] = $row['parent_id'];
            }
        }
        
        //Загружам свойства, заданные у категорий (кол-во запросов = количеству разных категорий)
        foreach($obj_list as $product)
        {
            $product_id = $product['id'];
            $groups_id = $product['xdir'];
            
            $group_flags = array();
            $group_list = array(); //Полный список id групп, для которых нужно запросить характеристики
            $dirapi = Dirapi::getInstance();
            if (!empty($groups_id)) {
                foreach($groups_id as $group) {
                    $group_list = array_merge($group_list, array_keys($dirapi->getPathToFirst($group)));
                }
            }
            $group_list[] = 0; //Включаем в список корневой элемент. Содержащий свойства для всех элементов дерева
            $group_list = array_unique($group_list);
            
            $group_list_sql = implode(',', $group_list);
            if (!empty($group_list))
            {
                if (!isset($query_cache[$group_list_sql])) //Не выполняем одинаковые запросы
                {
                    $res = \RS\Orm\Request::make()
                        ->from($this->prop_link_table)->asAlias('L')
                        ->join($this->prop_item_table, 'T.id = L.prop_id', 'T')
                        ->where(array(
                            'L.site_id' => \RS\Site\Manager::getSiteId()
                        ))                        
                        ->whereIn('L.group_id', $group_list)
                        ->exec();

                    while ($row = $res->fetchRow()) {

                        $row['value'] = ($row['type'] == 'int' || $row['type'] == 'bool') ? $row['val_int'] : $row['val_str'];
                        
                        $item = new Orm\Property\Item();
                        $item->getFromArray($row);
                        
                        //Если значение - массив
                        if ($row['type'] == 'list' && isset($row['value']) ) {
                            if (isset($query_cache[$group_list_sql][$row['parent_id']][$row['id']])) {
                                $item = $query_cache[$group_list_sql][$row['parent_id']][$row['id']];
                                $arr = $item['value'];
                            } else {
                                $arr = array();
                            }
                            if ($row['value'] !== '') {
                                $arr[] = $row['value'];
                            }
                            $item['value'] = $arr;
                        }
                        
                        $query_cache[$group_list_sql][$row['parent_id']][$row['id']] = $item;
                        
                        $all_property_parents[$row['parent_id']] = $row['parent_id'];
                    }
                    
                }
                if (!isset($propertyByProduct[$product_id])) $propertyByProduct[$product_id] = array();
                if (isset($query_cache[$group_list_sql])) {
                    $to_merge = array();
                        foreach($query_cache[$group_list_sql] as $dir => $props) {
                            foreach($props as $id => $prop) {
                                 if (isset($propertyByProduct[$product_id][$dir][$id])) {                      
                                        $prop = clone $prop;
                                        $prop['value'] = $propertyByProduct[$product_id][$dir][$id]['value'];
                                        $prop['useval'] = true;
                                    }
                                $to_merge[$dir][$id] = $prop;
                            }
                        }
                    
                    $propertyByProduct[$product_id] = array_replace_recursive($propertyByProduct[$product_id], $to_merge);
                }
            }
        }
        
        $dirs = array(0 => new Orm\Property\Dir());
        
        if (count($all_property_parents)) {
            $dirs += \RS\Orm\Request::make()
                ->from(new Orm\Property\Dir())
                ->whereIn('id', $all_property_parents)
                ->orderby('sortn')
                ->objects(null, 'id');
        }
        
        $result = array();
        
        foreach($propertyByProduct as $product_id => $propertiesByDirs)
        {
            $result[$product_id] = array();
            foreach($dirs as $dir_id => $dir_object) {
                if (isset($propertiesByDirs[$dir_id])) {
                    //Сортируем свойства
                    uasort($propertiesByDirs[$dir_id], array($this, 'sortFunc'));
                    
                    $result[$product_id][$dir_id]['group'] = $dir_object;
                    $result[$product_id][$dir_id]['properties'] = $propertiesByDirs[$dir_id];                    
                }
            }
        }
        
        return ($obj instanceof Orm\Product) ? $result[$product_id] : $result;
    }
    
    function sortFunc($a, $b)
    {
        $_a = $a['sortn'];
        $_b = $b['sortn'];
        if ($_a == $_b) {
            return 0;
        }
        return ($_a < $_b) ? -1 : 1;
    }
    
    /*
    function getSelectedFilters()
    {
        $filter = $this->url->request('filter', TYPE_ARRAY);
        foreach($filter as &$v) {
            if (isset($v['fromto']))
                @list($v['from'], $v['to']) = explode(';', $v['fromto']);
        }
        return $filter;
    }
    */
    
    function cleanNoActiveFilters(array $filters)
    {
        //Чистим неактивные фильтры
        foreach($filters as $key => $filter) {
            if ($filter === ''  || (is_array($filter) && isset($filter['from']) && empty($filter['from']) && empty($filter['to'])) ) 
            {
                unset($filters[$key]);
            }
        }                
        return $filters;
    }
    
    
    /**
    * Возвращает объект $q, в котором выставлены условия для фильтрации
    * 
    * @param array $filters - массив с установленными фильтрами
    * @param string $product_table_alias - alias таблицы с товарами, установленный в $q
    * @return \RS\Orm\Request
    */
    function getFilteredQuery(array $filters, $product_table_alias, \RS\Orm\Request $q)
    {
        $this->pta = $product_table_alias;
        $this->filter_active = false;
        
        $filters = $this->cleanNoActiveFilters($filters);
        
        $ids = array_keys($filters);
        if (empty($ids)) return false;
        
        $this->clearFilter();
        $this->setFilter('id', $ids, 'in');
        $list = $this->getList(); //Список свойств
        
        $this->prop_link_table = \RS\Orm\Tools::getTable(new Orm\Property\Link());
        
        foreach($list as $n=>$prop) {
            $pn = "p{$n}"; //Псевдоним join'a
            $value = $filters[$prop['id']];

            //Экранируем значения, используемые в запросе
            if (is_string($value)) $value = \RS\Db\Adapter::escape($value);
            if (is_array($value) && isset($value['from']) && isset($value['to'])) {
                if ($prop['interval_from'] == $value['from'] 
                        && $prop['interval_to'] == $value['to']) {
                    continue; //Пропускаем фильтр, если установленные значения равны крайним взможным значениям
                }
                
                foreach($value as $k=>$v) {
                    $value[$k] = \RS\Db\Adapter::escape($v);
                }            
            }
            
            $this->filter_active = true;
            $func = $prop['type'].'Filter';
           
            $this->$func($pn, $prop, $value, $q);
        }        

        return $q;
    }
    

    /**
    * Возвращает id товаров, удовлетворяющих установленым фильтрам.
    * 
    * @param array $filters - массив с установленными фильтрами.
    * @return array
    */
    function getFilteredProductIds(array $filters)
    {
        $q = new \RS\Orm\Request();
        $q->select('id')
            ->from(new Orm\Product())
            ->asAlias('P');
            
        $q = $this->getFilteredQuery($filters, 'P', $q);
        
        if ($q) {
            $ids = $q->exec()->fetchSelected(null, 'id');
             //Если не существует ни одного товара, то возвращаем заведомо невыполнимое условие - у товара должен быть id = 0
            return empty($ids) ? array(0) : $ids;            
        }
        return false;
    }
    
    
    function isFilterActive()
    {
        return $this->filter_active;
    }
    
    /**
    * Устанавливает фильтр для числового свойства
    */
    protected function intFilter($pn, $prop, $value, \RS\Orm\Request $q)
    {
        if (!is_array($value)) {
            $value = array('from' => $value, 'to' => $value);
        }
        if (isset($value['from']) || isset($value['to'])) {
            $this->addPropertyJoin($pn, $prop, $q);
            $expr = "$pn.prop_id = '{$prop['id']}'"; //Выражение
            $vars = ""; //Переменные выражения
            $filter_arr = array(); //Массив с заменами
            if (isset($value['from'])) {
                $vars[]     = "$pn.val_int >= '#from'";
                $filter_arr += array('from' => $value['from']-self::ECLIPSE_FLOAT_COMPARE);
            }
            if (isset($value['to'])) {
                $vars[]   = "$pn.val_int <= '#to'";
                $filter_arr += array('to' => $value['to']+self::ECLIPSE_FLOAT_COMPARE);
            }
            //Склеим всё.
            $vars = implode(" AND ",$vars);
            $q->where("(".$expr." AND (".$vars."))",$filter_arr);
        }
        
    }
    
    
    /**
    * Устанавливает фильтр для спискового свойства
    */
    protected function listFilter($pn, $prop, $value, \RS\Orm\Request $q)
    {
        if (empty($value)) return;

        $this->addPropertyJoin($pn, $prop, $q)
        ->openWGroup()
        ->where("$pn.prop_id = '{$prop['id']}'")
        ->whereIn("$pn.val_str", (array)$value)
        ->closeWGroup();
    }
    
    /**
    * Устанавливает фильтр для строкового свойства
    */
    protected function stringFilter($pn, $prop, $value, \RS\Orm\Request $q)
    {
        $expr = "$pn.prop_id = '{$prop['id']}'"; //Выражение
        $this->addPropertyJoin($pn, $prop, $q)                
        ->where("(".$expr." AND $pn.val_str LIKE '#value%')", array('value' => $value));
    }
    
    /**
    * Устанавливает фильтр для свойства типа Да/Нет
    */    
    protected function boolFilter($pn, $prop, $value, \RS\Orm\Request $q)
    {
        if ($value == 0) {
            //Если нужно найти товары, для которых нет записи в таблице свойств.
            $q->leftjoin($this->prop_link_table, 
                "$pn.product_id = {$this->pta}.id", $pn)
            ->where("($pn.prop_id = '{$prop['id']}' AND ($pn.val_int IS NULL OR $pn.val_int = 0)) ");
        } else {
            $this->addPropertyJoin($pn, $prop, $q)
            ->where("($pn.prop_id = '{$prop['id']}' AND $pn.val_int > ".(1-self::ECLIPSE_FLOAT_COMPARE).")" );
        }
    }
    
    /**
    * Добавляет стандартный join к выборке
    * 
    * @param string $pn - alias join'a
    * @param \Catalog\Model\Orm\Property\Item $prop - характеристика
    * @param \RS\Orm\Request $q - объект модифицируемого запроса
    * @return \RS\Orm\Request
    */
    protected function addPropertyJoin($pn, $prop, $q)
    {
        $q->join($this->prop_link_table,
            "$pn.product_id = {$this->pta}.id", $pn);
        return $q;
    }
    
    /**
    * Возвращает URL для админ панели с очищенным фильтром
    * 
    * @return string
    */
    function getCleanFilterUrl()
    {
        return \RS\Http\Request::commonInstance()->replaceKey(array('filter' => null, 'bfilter' => null));
    }
    
    static public function getAllGroups()
    {
        $groups = \RS\Orm\Request::make()
            ->from(new Orm\Property\Dir())
            ->objects();

        array_unshift($groups, new Orm\Property\Dir());
        return $groups;    
    }
    
    /**
    * Удаляет характеристики из системы, которые не связаны с товарами или категориями товаров текущего сайта
    * 
    * @return bool
    */
    function cleanUnusedProperty()
    {
        $link_table = \RS\Orm\Tools::getTable(new Orm\Property\Link);
        $item_table = \RS\Orm\Tools::getTable(new Orm\Property\Item);
        $site_id = \RS\Site\Manager::getSiteId();
        
        \RS\Orm\Request::make()
            ->delete('L')
            ->from(new Orm\Property\Link, 'L')
            ->leftjoin(new Orm\Product, 'P.id = L.product_id', 'P')
            ->where("L.site_id = '#site_id' AND L.product_id > 0 AND P.id IS NULL", array('site_id' => $site_id))
            ->exec();

        \RS\Orm\Request::make()
            ->delete('L')
            ->from(new Orm\Property\Link, 'L')
            ->leftjoin(new Orm\Dir, 'D.id = L.group_id', 'D')
            ->where("L.site_id = '#site_id' AND L.group_id > 0 AND D.id IS NULL", array('site_id' => $site_id))
            ->exec();
        
        $count = \RS\Orm\Request::make()
            ->delete()
            ->from(new Orm\Property\Item)
            ->where(array(
                'site_id' => $site_id
            ))
            ->where("id NOT IN (SELECT prop_id FROM $link_table WHERE site_id='$site_id')")
            ->exec()->affectedRows();
        
        $count +=\RS\Orm\Request::make()
            ->delete()
            ->from(new Orm\Property\Dir)
            ->where(array(
                'site_id' => $site_id
            ))            
            ->where("id NOT IN (SELECT parent_id FROM $item_table WHERE site_id='$site_id')")
            ->exec()->affectedRows();
        return $count;
    }
    
    /**
    * Возвращает все имеющиеся у товаров значения данного свойства
    * 
    * @param integer $property_id  - ID свойства
    * @param integer $site_id      - ID сайта
    * @return array
    */
    function getExistsValues($property_id, $site_id = null)
    {
        $values = \RS\Orm\Request::make()
            ->select('DISTINCT val_str')
            ->from(new Orm\Property\Link())
            ->where(array(
                'site_id' => \RS\SIte\Manager::getSiteId(),
                'prop_id' => $property_id
            ))
            ->orderby('val_str')
            ->exec()->fetchSelected('val_str', 'val_str');
        unset($values['']);
        return $values;
    }
}
