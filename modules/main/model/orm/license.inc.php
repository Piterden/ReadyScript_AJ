<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Main\Model\Orm;
use \RS\Orm\Type;

class License extends \RS\Orm\AbstractObject
{
    const
        HASHSTORE_ACTIVATION_DATA = 'ACTIVATION_DATA',
    
        ERROR_NO_LICENSE_KEY    = 1,
        ERROR_LICENSE_NOT_FOUND = 2,
        ERROR_WRONG_COPY_ID     = 3,
        ERROR_WRONG_INSTALL_ID  = 4,
        ERROR_KEY_NEED_ACTIVATE = 5,
        ERROR_ACTIVATE          = 6,
        ERROR_CHECK_DOMAIN      = 7,
        ERROR_EXTRA_ACTIVATE    = 8,
        ERROR_SCRIPT_TYPE       = 9;
    
    protected static
        $table = 'license';
        
    protected
        $license_type,
        $license_expire_month,
        $need_type,
        $need_activation;        
        
    function _init()
    {
        $this->getPropertyIterator()->append(array(
            'license' => new Type\String(array(
                'maxLength' => 24,
                'attr' => array(array(
                    'size' => 50
                )),
                'description' => t('Лицензионный номер'),
                'primaryKey' => true
            )),
                'is_activation' => new Type\Integer(array(
                    'description' => 'Активировать ключ',
                    'runtime' => true,
                    'visible' => false,
                    'actmode' => true
                )),
                //Сведения для активации ключа
                'person' => new Type\String(array(
                    'description' => 'Контактное лицо',
                    'activationVisible' => true,
                    'visible' => false,
                    'runtime' => true,
                    'actmode' => true                    
                )),
                'company_name' => new Type\String(array(
                    'description' => 'Наименование компании',
                    'activationVisible' => true,
                    'visible' => false,
                    'runtime' => true,
                    'actmode' => true                    
                )),
                'inn' => new Type\String(array(
                    'maxLength' => 12,                
                    'description' => 'ИНН',
                    'activationVisible' => true,
                    'visible' => false,
                    'runtime' => true,
                    'actmode' => true                    
                )),
                'phone' => new Type\String(array(
                    'maxLength' => 100,
                    'description' => 'Телефон',
                    'activationVisible' => true,
                    'visible' => false,
                    'runtime' => true,
                    'actmode' => true                    
                )),            
                'email' => new Type\String(array(
                    'maxLength' => 120,
                    'description' => 'E-mail',
                    'activationVisible' => true,
                    'visible' => false,
                    'runtime' => true,
                    'actmode' => true                    
                )),
                'domain' => new Type\String(array(
                    'description' => 'Домен',
                    'activationVisible' => true,
                    'hint' => t('Сайт обязательно должен быть доступен на данном доменном имени'),
                    'visible' => false,
                    'runtime' => true,
                    'actmode' => true                    
                )),
                'check_domain' => new Type\Integer(array(
                    'description' => 'Проверить отклик магазина по доменному имени',
                    'hint' => t('Это поможет исключить активацию ключа на неверное доменное имя. Снимайте эту отметку, только если уверены в корректности написания доменного имени'),
                    'checkboxView' => array(1,0),
                    'activationVisible' => true,
                    'default' => 1,
                    'visible' => false,
                    'runtime' => true,
                    'actmode' => true
                )),
            'data' => new Type\Blob(array(
                'visible' => false
            )),
            'type' => new Type\String(array(
                'maxLength' => 50,
                'description' => 'Тип лицензии',
                'visible' => false
            ))
        ));
    }
    
    function getPrimaryKeyProperty()
    {
        return 'license';
    }
    
    function beforeWrite($flag)
    {
        $error = null;
        $this['license'] = self::normalizeLicenseKey($this['license']);
        $activation_data = null;
        if ($this['is_activation']) {
            if ($this['is_extra_activation']) {
               $activation_data['domain'] = $this['domain']; 
            } else {
                foreach($this as $key => $prop) {
                    if (!empty($prop->actmode)) {
                        $activation_data[$key] = $prop->get();
                    }
                }
            }
        }
        
        $data = __GET_LICENSE_DATA($this['license'], $error, $activation_data, $this->license_type, $this->license_expire_month);
        
        //Проверяем не установлен ли другой основной ключ(не временный).
        if ($this->license_type == 'script' && !$this->license_expire_month) {
            $list = __GET_LICENSE_LIST();
            $has_script_type = false;
            foreach($list as $license) {
                if ($license['type'] == 'script' && empty($license['expire_month'])) {
                    $has_script_type = true;
                    break;
                }
            }
                
            if ($has_script_type) {
                return $this->addError(t('Основная лицензия уже установлена. Удалите её перед повторной установкой.'));
            }
        }
        
        if ($data && $this->need_type && $this->need_type != $this->license_type) {
            return $this->addError(t('Неверный тип лицензии'));
        }
        
        if ($data) {
            $this['type'] = $this->license_type;
            $this['data'] = $data;
            if ($this['is_activation']) {
                \RS\HashStore\Api::set(self::HASHSTORE_ACTIVATION_DATA, $activation_data);
            }
        } else {
            $this->need_activation = (!$this['is_activation'] && $error['code'] == self::ERROR_KEY_NEED_ACTIVATE );
            
            if ($error['code'] == self::ERROR_ACTIVATE) {
                //Переносим ошибки активации на соответствующие поля
                foreach($error['message'] as $form => $message) {
                    $this->addError($message, $form);
                }
            } else {
                if ($error['code']<-1 || $error['code'] == self::ERROR_CHECK_DOMAIN) {
                    $this->addError($error['message']);
                } else {
                    $this->addError($error['message'], 'license');
                }
            }
            return false;
        }    
    }
    
    /**
    * Приводит лицензионный номер к стандартному виду
    * 
    * @param string $license_key - лицензионный ключ
    * @return string
    */
    public static function normalizeLicenseKey($license_key)
    {
        $license_key = strtoupper(str_replace('-', '', $license_key));
        return wordwrap($license_key, 4, '-', true);
    }
    
    /**
    * Возвращает true, если при попытке сохранения объекта сервер 
    * лицензирования вернул ответ, что ключ не активирован
    * 
    * @return bool
    */
    function isNeedActivation()
    {
        return $this->need_activation;
    }
    
    /**
    * Возвращает тип сохраняемой лицензии.
    */
    function getLicenseType()
    {
        return $this->license_type;
    }
    
    function fillDefaultActivationValue()
    {
        //Подставляем сохраненные раннее данные, если таковые имеются
        if ( !($data = \RS\HashStore\Api::get(self::HASHSTORE_ACTIVATION_DATA)) ) {
            //Подставляем имеющиеся сведения из системы
            $data = array(
                'domain' => \RS\Http\Request::commonInstance()->server('HTTP_HOST', TYPE_STRING)
            );
        }
        $this->getFromArray($data);
    }
    
    /**
    * Устанавливает требуемый тип лицензии
    * 
    * @param string $type
    * @return void
    */
    function setNeedType($type)
    {
        $this->need_type = $type;
    }
    
    /**
    * Возвращает true, если требуемый тип лицензии не соответствует указанной в license
    * @return bool;
    */
    function isTypeError()
    {
        return $this->license_type != $this->need_type;
    }
}
?>
