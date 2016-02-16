<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Html\Table\Type;

class Tools extends AbstractType
{
    const
        AUTO_WIDTH = 22;
        
    public 
        $property = array(
            'ThAttr' => array('class' => 'settings'),
            'customizable' => false
        );

    protected
        $tools,
        $head_template = 'system/admin/html_elements/table/coltype/tools_head.tpl',
        $body_template = 'system/admin/html_elements/table/coltype/tools.tpl';        
        
    function __construct($field, $title = null, array $tools, $property = null)
    {
        parent::__construct($field, $title, $property);
        $this->setTools($tools);
        if (!isset($this->property['TdAttr']))  $this->property['TdAttr'] = array('align' => 'center');
        if (!isset($property['noAutoWidth'])) $this->setAutoWidth();
    }
    
    function setAutoWidth()
    {
        if (!isset($this->property['ThAttr']['style'])) {
            $this->property['ThAttr']['style'] = '';
        }
        if (!isset($this->property['TdAttr']['style'])) {
            $this->property['TdAttr']['style'] = '';
        }
        $this->property['ThAttr']['style'] .= "width:".(self::AUTO_WIDTH * count($this->tools))."px;";
        $this->property['TdAttr']['style'] .= "width:".(self::AUTO_WIDTH * count($this->tools))."px;";
    }
    
    function setTools($tools)
    {
        $this->tools = $tools;
    }
    
    function getTools()
    {
        return $this->tools;
    }

}

