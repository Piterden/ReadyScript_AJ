<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace RS\Html\Toolbar\Button;

class ModuleConfig extends Button
{
    protected
        $template = 'system/admin/html_elements/toolbar/button/moduleconfig.tpl';
        
    function __construct($href, $title = null, $property = null)
    {
        $this->property = array(
            'attr' => array(
                'title' => t('Настройка модуля'),
                'class' => 'mod-config'
            )
        );
        parent::__construct($href, $title, $property);
    }
}
?>
