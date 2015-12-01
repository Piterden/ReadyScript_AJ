<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Search\Model\Engine;

/**
* Полнотекстовый поиск средствами Mysql
* @ingroup Search
*/
class Mysql
{   
    const
        ORDER_RELEVANT = 'relevant',
        ORDER_FIELD = 'field';
        
    protected
        $config,
        $order,
        $orderType = self::ORDER_RELEVANT,
        $errors = array(),
        $query,
        $offset,
        $pageSize,
        $filters,
        $total = 0;
    
    function __construct()
    {
        $this->config = \RS\Config\Loader::byModule($this);
    }
        
    
    function setOrderByRelevant()
    {
        $this->orderType = self::ORDER_RELEVANT;
        return $this;
    }
    
    function setOrderByField($field)
    {
        $this->orderType = self::ORDER_FIELD;
        $this->order = $field;
        return $this;
    }
    
    /**
    * Устанавливает поисковый запрос для поиска
    * 
    * @param string $query
    */
    function setQuery($query)
    {
        $this->query = trim($query);
        return $this;
    }
    
    function getQueryForLike()
    {
        return '%'.str_replace('%', '', $this->query).'%';
    }
    
    /**
    * Возвращает поисковый запрос, подготовленный для отображения в HTML
    */
    function getQueryView()
    {
        return htmlspecialchars($this->query);
    }
    
    /**
    * Возвращает поисковый запрос в нужной форме для поиска без учета окончаний
    */
    protected function getStemmedQuery()
    {
        //Если в поисковой строке найдены кавычки,
        //не применяем эвристических методов улучшения результатов.
        //Считаем, что пользователь опытный, сам составляет запрос.
        if (strpos($this->query, "\"") !== false) return $this->query;
        
        
        $words = preg_split('/[\s,]+/u', $this->query, -1, PREG_SPLIT_NO_EMPTY);
        $stemmer = new \RS\Stem\Ru();
        
        $query = $this->query;
        foreach($words as $word) {
            //Если перед словом не будет задан спец-символ, ставим + (слово обязательно должно присутствовать в результате)
            if (!preg_match('/[+\-"~(<>]/', mb_substr($word,0,1))) {
                $query = str_replace($word, '+'.$word, $query);
            }
            
            $stemmed = $stemmer->stemWord($word);
            if (mb_strlen($stemmed)>3) {//Если после стеминга слово не стало менее 4-х символов, то 
                $query = str_replace($word, $stemmed.'*', $query);
            }
        }

        return $query;
    }
    
    /**
    * Устанавливает страницу для результатов поиска
    * 
    * @param integer $page
    */
    function setPage($page)
    {
        $this->page = $page;
        return $this;
    }
    
    /**
    * Устанавливает количество результатов на странице
    * 
    * @param integer $pageSize
    */
    function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }
    
    /**
    * Устанавливает дополнительные фильтры, которые будут применены к объекту MSearch_Model_CoreObject_Index
    * 
    * @param string $key
    * @param mixed $value
    */
    function setFilter($key, $value)
    {
        if ($value === null) unset($this->filters[$key]);
            else $this->filters[$key] = $value;
        return $this;
    }
    
    /**
    * Возвращает общее количество результатов поиска
    */
    function getTotal()
    {
        return $this->total;
    }
    
    protected function getBaseRequest($q = null)
    {
        if ($q == null) $q = new \RS\Orm\Request();
            $q->from(new \Search\Model\Orm\Index())->asAlias('A')
            ->where("MATCH(A.`title`, A.`indextext`) AGAINST('#query' IN BOOLEAN MODE)", array(
                'query' => $this->getStemmedQuery()
            ));
        if (!empty($this->filters)) $q->where($this->filters);        
        return $q;
    }
    
    /**
    * Выполняет поиск по заранее заданным параметрам
    * @return array of MSearch_Model_CoreObjectIndex - если поиск выполнен, в случае ошибки false
    */
    function search(\RS\Orm\Request $q = null)
    {
        if (empty($this->query)) {
            $this->addError('Введите поисковый запрос');
            return false;
        }
        
        $q = $this->getBaseRequest($q);
        $this->total = $q->count();
        $results = new \RS\Orm\Request();
        if ($this->total) {
            if ($this->pageSize) {
                $offset = (($this->page-1)*$this->pageSize);
                $q->limit($offset, $this->pageSize);
            }
            
            if ($this->orderType == self::ORDER_RELEVANT) {
                $q->select("*, MATCH(A.`title`, A.`indextext`) AGAINST('".\RS\Db\Adapter::escape($this->query)."' IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) as rank")
                    ->orderby('rank DESC');
            } else {
                $q->orderby($this->order);
            }
            
            $results = $q->objects();
        }
        
        return $results;
    }
    
    /**
    * Модифицирует объект запроса $q, добавляя в него условия для поиска
    * 
    * @param \RS\Orm\Request $q - объект запроса
    * @param mixed $alias_product - псевдоним для таблицы товаров
    * @param mixed $alias - псевдоним для индексной таблицы 
    */
    function joinQuery(\RS\Orm\Request $q, $alias_product = 'A', $alias = 'B')
    {
        if ($this->config['searchtype'] == 'like') {
            $q->join(new \Search\Model\Orm\Index(), "$alias.entity_id = $alias_product.id", $alias)
                ->where("($alias.`title` like '#query' OR $alias.`indextext` like '#query')", array(
                    'query' => $this->getQueryForLike()
                ));            
        } else {
            $q->join(new \Search\Model\Orm\Index(), "$alias.entity_id = $alias_product.id", $alias)
                ->where("MATCH($alias.`title`, $alias.`indextext`) AGAINST('#query' IN BOOLEAN MODE)", array(
                    'query' => $this->getStemmedQuery()
                ));
        }
        
        if ($this->orderType == self::ORDER_RELEVANT && $this->config['searchtype'] != 'like') {
            $q->select("MATCH($alias.`title`, $alias.`indextext`) AGAINST('".\RS\Db\Adapter::escape($this->query)."' IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) as rank")
                ->orderby('rank DESC');
        }
        
        if (!empty($this->filters)) $q->where($this->filters);        
        
    }
    
    /**
    * Добавляет сведения об ошибке
    * 
    * @param string $errorText текст ошибки
    */
    function addError($errorText)
    {
        $this->errors[] = $errorText;
        return $this;
    }
    
    /**
    * Возвращает ошибки, произошедшие во время поиска
    */
    function getErrors()
    {
        return $this->errors;
    }
    
}