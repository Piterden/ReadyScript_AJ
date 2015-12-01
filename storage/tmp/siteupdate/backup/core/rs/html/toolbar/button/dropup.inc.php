<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Html\Toolbar\Button;

class Dropup extends AbstractButton
{
    protected
        $extra_class = 'button rs-list-button rs-up',
        $drop_items = array(),
        $template = 'system/admin/html_elements/toolbar/button/dropup.tpl';
    
    function __construct(array $drop_items, $property = null)
    {
        parent::__construct($property);
        @$this->property['attr']['class'] = $this->extra_class.' '.$this->property['attr']['class'];
        $this->setItems($drop_items);
    }
    
    /**
    * Возвращает выпадающие элементы
    */
    function getDropItems()
    {
        return array_slice($this->drop_items, 1);
    }
    
    /**
    * Возвращает элемент, который будет виден по-умолчанию
    */
    function getFirstItem()
    {
        return reset($this->drop_items);
    }
    
    function getAllItems()
    {
        return $this->drop_items;
    }
    
    function setItems(array $drop_items)
    {
        foreach($drop_items as $key => $item) {
            if ($item) {
                $this->addItem($item, $key);
            }
        }
    }
    
    function addItem($item, $key = null)
    {
        if ($key === null) {
            $this->drop_items[] = $item;
        } else {
            $this->drop_items[$key] = $item;
        }
    }
    
    /**
    * Возвращает строку с атрибутами элемента списка
    */
    function getItemAttrLine(array $item, $is_first = false)
    {
        if ($is_first) {
            @$item['attr']['class'] .= ' rs-active'; //Связано с версткой
        }
        $line = '';
        if (!empty($item['attr'])) {
            foreach($item['attr'] as $key => $value) {
                $quote = $value{0} == '{' ? "'" : '"';
                $line .= $key.'='.$quote.$value.$quote.' ';
            }
        }
        return $line;
    }
}


