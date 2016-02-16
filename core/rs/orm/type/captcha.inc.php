<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace RS\Orm\Type;

/**
* Тип - капча. 
* run-time тип.
*/
class Captcha extends AbstractType
{
    public
        $php_type = "", //mixed
        $vis_form = false,
        $runtime = true;
        
    protected
        $form_template = '%system%/coreobject/type/form/captcha.tpl',
        $enabled = true;
    
    function __construct(array $options = null) {
        parent::__construct($options);
        $this->setChecker(array($this, 'dependCheck'), t('Неверно указан код'));
    }
    
    
    public function validate($value)
    {
        //Данный тип позволяет писать любые данные
        return true;
    }
    
    public function cast($value)
    {
        $this->set($value);
    }
    
    public function check()
    {
        $result = parent::check();
        //После проверки стираем значение.
        $this->set('');
        return $result;
    }
    
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    public function setEnable($bool)
    {
        $this->enabled = $bool;
    }
    
    public function dependCheck($coreobj, $value, $errortext)
    {
        if (!$this->isEnabled()) return true; //Если капча отключена, то не проверяем ее значение

        $chk = new \RS\Orm\Type\Checker();
        $callback = array($chk, 'chkcaptcha');        
        return call_user_func_array($callback, array($coreobj, $value, t('Неверно указан код')));        
    }
    
    public function getRandom()
    {
        return rand();
    }
    
    
}
