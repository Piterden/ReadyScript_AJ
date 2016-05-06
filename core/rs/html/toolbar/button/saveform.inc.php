<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Html\Toolbar\Button;

class SaveForm extends Save
{
    protected
        $class_ajax = 'crud-form-save',
        $property = array(
            'attr' => array(
                'class' => 'saveform'
            )
        );
        
    function __construct($href = null, $title = null, $property = null)
    {
        if ($title === null) {
            $title = t('сохранить');
        }
        parent::__construct($href, $title, $property);
    }
}
