<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Orm\Type;

class String extends AbstractType
{
    protected
        $php_type = 'string',
        $sql_notation = 'varchar',
        $auto_increment = false,
        $max_len = 255;
        
    function __construct(array $options = null)
    {        
        $k = 0.25;
        $maxLength = isset($options['maxLength']) ? $options['maxLength'] : $this->max_len;
        $size = (ceil($k*$maxLength)< 10) ? 10 : ceil($k*$maxLength);
        if ($size>85) $size = 85;
        
        $this->setAttr(array('size' => $size, 'type' => 'text'));
        parent::__construct($options);        
    }
    
    /**
    * Устанавливает вид формы в виде textarea
    * @return String
    */
    function setViewAsTextarea()
    {
        $this->form_template = '%system%/coreobject/type/form/textarea.tpl';
        $this->view_attr += array(
            'rows' => 3,
            'cols' => 80
        );
        return $this;
    }
}  


