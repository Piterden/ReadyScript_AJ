<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Orm\Type;

class Richtext extends Text
{
    protected
        $editor_options = array(),
        $escape_type = self::ESCAPE_TYPE_HTML,
        $form_template = '%system%/coreobject/type/form/richtext.tpl';
    
    function setEditorOptions($options)
    {
        $this->editor_options = $options;
    }
    
    function getEditorOptions()
    {
        return $this->editor_options;
    }
    
    function formView($view_options = null)
    {
        $this->tinymce = new \RS\Html\Tinymce(array(
            'id' => $this->getFormName(),
            'name' => $this->getFormName(),
        ) + $this->getEditorOptions(), $this->get());
        
        return parent::formView($view_options);
    }
    
    function getRenderTemplate($multiedit = false)
    {
        $this->tinymce = new \RS\Html\Tinymce(array(
            'id' => $this->getFormName(),
            'name' => $this->getFormName(),
        ) + $this->getEditorOptions(), $this->get());
                
        return parent::getRenderTemplate($multiedit);
    }
}  
