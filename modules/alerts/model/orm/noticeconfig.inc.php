<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Alerts\Model\Orm;
use \RS\Orm\Type,
    \Shop\Model\Orm\UserStatus,
    \Shop\Model\UserStatusApi;


class NoticeConfig extends \RS\Orm\OrmObject
{
    protected static
        $table = 'notice_config';
 
 
    function _init()
    {
        parent::_init()->append(array(
                'site_id' => new Type\CurrentSite(),
                'enable_email' => new Type\Integer(array(
                    'description' => t('Отправка E-mail'),
                    'maxLength' => '1',
                    'checkBoxView' => array('on' => 1, 'off' => 0),
                )),
                'enable_sms' => new Type\Integer(array(
                    'description' => t('Отправка SMS'),
                    'maxLength' => '1',
                    'checkBoxView' => array('on' => 1, 'off' => 0),
                )),
                'class' => new Type\Varchar(array(
                    'maxLength' => '255',
                    'description' => t('Класс уведомления'),
                    'visible' => false,
                )),               
                'description' => new Type\Varchar(array(
                    'runtime' => true,
                    'maxLength' => '255',
                    'description' => t('Описание'),
                    'visible' => false,
                )),               
                'template_email' => new Type\Varchar(array(
                    'maxLength' => '255',
                    'description' => t('E-Mail шаблон'),
                    'visible' => true,
                )),               
                'template_sms' => new Type\Varchar(array(
                    'maxLength' => '255',
                    'description' => t('SMS шаблон'),
                    'visible' => true,
                )),               
        ));
        
        $this->addIndex(array('site_id', 'class'), self::INDEX_UNIQUE);
    }
    
    
    /**
     * Вызывается после загрузки объекта
     * 
     * @return void
     */
    public function afterObjectLoad()
    {
        if(class_exists($this['class'])){
            $inst = new $this['class'];
            $this['description'] = $inst->getDescription();
            if($inst instanceof \Alerts\Model\Types\InterfaceEmail && !$this['template_email']) 
                $this['template_email'] = $inst->getTemplateEmail();
            if($inst instanceof \Alerts\Model\Types\InterfaceSms && !$this['template_sms']) 
                $this['template_sms'] = $inst->getTemplateSms();
        }
    }
    
    public function beforeWrite($flag)
    {
        if(class_exists($this['class'])){
            $inst = new $this['class'];
            if($inst instanceof \Alerts\Model\Types\InterfaceEmail && $this['template_email'] == $inst->getTemplateEmail())
                $this['template_email'] = '';
            if($inst instanceof \Alerts\Model\Types\InterfaceSms && $this['template_sms'] == $inst->getTemplateSms())
                $this['template_sms'] = '';
        }
    }
    
    
    public function hasEmail()
    {
        if(!class_exists($this['class'])) return false;
        $inst = new $this['class'];
        return $inst instanceof \Alerts\Model\Types\InterfaceEmail;
    }    

    public function hasSms()
    {
        if(!class_exists($this['class'])) return false;
        $inst = new $this['class'];
        return $inst instanceof \Alerts\Model\Types\InterfaceSms;
    }    
    
    public function getDefaultTemplates()
    {
        $templates = array();
        if(class_exists($this['class'])){
            $inst = new $this['class'];
            if($inst instanceof \Alerts\Model\Types\InterfaceEmail) {
                $templates['email'] = $inst->getTemplateEmail();
            }
            
            if($inst instanceof \Alerts\Model\Types\InterfaceSms) {
                $templates['sms'] = $inst->getTemplateSms();
            }
        }
        return $templates;
    }
}