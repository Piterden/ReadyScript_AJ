<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace RS\Html\Table;

/**
* Класс управления таблицей
*/
class Control extends \RS\Html\AbstractHtml
{
    protected
        $id,
        $auto_fill = true,
        $table_var = 'table',
        $sort_column_var = 'sort',
        $sort_direction_var = 'direction',
        $table;
        
    function __construct(array $options)
    {
        parent::__construct($options);
        if ($this->auto_fill) $this->fill();
    }    
    
    /**
    * Устанавливает уникальный идентификатор таблицы, чтобы сохранять для неё параметры.
    */
    function setId($id)
    {
        $this->id = $id;
    }
    
    /**
    * Возвращает уникальный идентификатор таблицы
    */
    function getId()
    {
        return 'table-'.$this->id;
    }

    function setAutoFill($autofill)
    {
        $this->auto_fill = $autofill;
    }        
    
    /**
    * Устанавливает таблице значения
    */
    function fill()
    {
        $my_cookie = $this->url->cookie($this->getId(), TYPE_ARRAY, false);
        $params = $this->url->request($this->table_var, TYPE_ARRAY, array());
        if (isset($params[ $this->sort_column_var ])) {
            //Устанавливаем текущую сортировку
            $this->table->setSortColumn( $params[ $this->sort_column_var ], $params[ $this->sort_direction_var ] );
        } else {
            //Устанавливаем сортировку, выставленную в настройках
            if (isset($my_cookie['sort'])) {
                list($column, $direction) = explode('=', $my_cookie['sort']);
                $direction = ($direction === 'asc' ? 'asc' : 'desc');
                $this->table->setSortColumn( (int)$column, strtoupper($direction) );
            }
        }
        
        $hidden = array();    
        if ($this->getId() !== null && $my_cookie) {
            if (isset($my_cookie['columns'])) {
                foreach(explode(',', $my_cookie['columns']) as $column_value) {
                    list($column, $value) = explode('=', $column_value);
                    $hidden[(int)$column] = ($value === 'N');
                }
            }
        }
        
        foreach($this->table->getColumns() as $n => $obj)
        {
            //Устанавливаем url для сортировки            
            if (!empty($obj->property['Sortable']))
            {
                if (isset($obj->property['CurrentSort'])) {
                    $direction =  ($obj->property['CurrentSort'] == SORTABLE_ASC) ? SORTABLE_DESC : SORTABLE_ASC;
                } else {
                    $direction = ($obj->property['Sortable'] == SORTABLE_BOTH) ? SORTABLE_ASC : $obj->property['Sortable'];
                }
                
                $obj->sorturl = $this->url->replaceKey(array(
                    $this->table_var => array(
                        $this->sort_column_var => $n,
                        $this->sort_direction_var => $direction
                    )
                ));
            }
            
            //Скрываем колонки, которые отключены пользователем.
            if (isset($hidden[$n])) {
                $obj->property['hidden'] = $hidden[$n];
            }
            
            //Снимаем флаг сортировки, со скрытых полей
            if (!empty($obj->property['hidden']) && isset($obj->property['CurrentSort'])) {
                unset($obj->property['CurrentSort']);
            }
        }
    }
    
    function getSqlOrderBy()
    {
        $orderby = false;
        if ($obj = $this->table->getSortColumn()) {
            $orderby = $obj->getField().' '.$obj->property['CurrentSort'];
        }
        return $orderby;
    }
    
    function setTable(Element $table)
    {
        $this->table = $table;
    }
    
    function getTable()
    {
        return $this->table;
    }
    
    function getView()
    {
        return $this->table->getView();
    }
}

