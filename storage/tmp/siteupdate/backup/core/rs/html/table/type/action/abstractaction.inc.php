<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Html\Table\Type\Action;

abstract class AbstractAction 
{
    public
        $options = array();
    
    protected
        $class_ajax = '',
        $class_action = '',
    
        $href_pattern,
        $title,
        $body_template = 'system/admin/html_elements/table/coltype/action/edit.tpl';
        
    function __construct($href_pattern, $title = null, array $options = null)
    {
        $this->options = $options;
        if ($this->options) {
            foreach($this->options as $option => $value)
                foreach(array('add', 'set') as $prefix)
                {
                    $method_name = $prefix.$option;
                    if (method_exists($this, $method_name)) {
                        $this->$method_name($value);
                        break;
                    }
                }
        }
        
        $this->href_pattern = $href_pattern;
        $this->title = $title;
    }
    
    function getTitle()
    {
        return $this->title;
    }
    
    function getTemplate()
    {
        return $this->body_template;
    }
    
    function getHrefPattern()
    {
        return $this->href_pattern;
    }
    
    function setClass($action_class)
    {
        $this->class_action = $action_class;
    }
    
    function setAttr(array $attr)
    {
        $this->options['attr'] = $attr;
    }
    
    function setDisableAjax($bool)
    {
        $this->options['noajax'] = $bool;
    }
    
    function getClass()
    {
        return $this->class_action.(empty($this->options['noajax']) ? ' '.$this->class_ajax : '' );
    }
}

