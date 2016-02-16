<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Html\Table\Type;

class Userdefine extends AbstractType
{
    protected
        $body_template = 'system/admin/html_elements/table/coltype/string.tpl';
    
    function _init()
    {
        if (!empty($this->property['Sortable'])) {
            @$this->property['ThAttr']['class'] .= ' sortable-column';
        }
    }
    
    function getValue()
    {
        return eval(' return "'.$this->property['text'].'";');
    }
}

