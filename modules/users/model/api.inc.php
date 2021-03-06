<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Users\Model;

class Api extends \RS\Module\AbstractModel\EntityList
{
    CONST
        RECOVER_PASSWORD_EMAIL_TPL = '%users%/email/recover_pass.tpl';
        
    protected
        $group_obj = '\Users\Model\Orm\UserInGroup';
    
    function __construct()
    {
        parent::__construct(new \Users\Model\Orm\User);
    }
        
    public function setFilter($key, $value = '', $type = '=', $prefix = 'AND', array $options = array())
    {
        if ($key == 'group')
        {
            $q = $this->queryObj();
            if (!$q->issetTable(new Orm\UserInGroup)) {
                $q->leftjoin(new Orm\UserInGroup, "{$this->def_table_alias}.id = X.user", 'X');
                $q->groupby("{$this->def_table_alias}.id");
            }
                
            parent::setFilter('X.group', $value, $type, 'AND');
            return $this;
        }
        
        return parent::setFilter($key, $value, $type, $prefix, $options);
    }
	
	/**
	* @return CDb_Result
	*/
	public function getLike($term, array $fields)
	{
        $q = \RS\Orm\Request::make();
		foreach ($fields as $field) {
            $q->where("$field like '%#term%'", array('term' => $term), 'OR');
		}
        $q->from($this->obj_instance)->exec();
        return $q->objects();
	}
	
	public function getByHash($hash)
	{
		$this->clearFilter();
    	$this->setFilter('hash', $hash);
    	return $this->getFirst();
	}
    
    /**
    * Возвращает уникальный ключ пользователя, основанный на его логине, пароле и id
    * 
    * @param \Users\Model\Orm\User $user
    * @return string
    */
    public static function getUserUniq(Orm\User $user, $xor_key = '')
    {
        $str = $user['login'].$user['id'].\Setup::$SECRET_SALT.$user['pass'];
        return sha1(self::applyXOR($str, $xor_key));
    }
    
    
    /**
    * Искажает строку используя ключ
    * 
    * @param string $string - строка
    * @param string $key - ключ
    * 
    * @return string
    */
    protected static function applyXOR($string, $key)
    {
        for($i = 0; $i < strlen($string); $i++)
            for($j = 0; $j < strlen($key); $j++)
                $string[$i] = $string[$i] ^ $key[$j];
        return $string;
    }
    
    /**
    * Отправляет письмо с инструкцией по восстановлению данных
    * 
    * @param string $login - Логин пользователя
    * @return boolean
    */
    public function sendRecoverEmail($login, $admin = false)
    {
        $user = \RS\Orm\Request::make()
            ->from(new Orm\User())
            ->where(array(
                'login' => $login
            ))->object();
        
        if ($user) {
            $uniq = $user['hash'];
            if ($admin) {
                $recover_href = \RS\Router\Manager::obj()->getUrl('main.admin', array('Act' => 'changePassword', 'uniq' => $uniq), true);
            } else {
                $recover_href = \RS\Router\Manager::obj()->getUrl('users-front-auth', array('Act' => 'changePassword', 'uniq' => $uniq), true);
            }
            $host = \RS\http\Request::commonInstance()->getDomainStr();
            $tpl = new \RS\View\Engine();
            $data = array(
                'host' => $host,
                'user' => $user,
                'recover_href' => $recover_href
            );
            
            $mailer = new \RS\Helper\Mailer();
            $mailer->Subject = t('Восстановление пароля на сайте %0', array($host));
            $mailer->addEmails($user['e_mail']);
            $mailer->renderBody(self::RECOVER_PASSWORD_EMAIL_TPL, $data);
            $mailer->send();
            
            return true;            
        }
        $this->addError(t('Пользователя с таким E-mail адресом не найдено'));
        return false;
    }
    
    /**
    * Изменяет пароль пользователя 
    * 
    * @param mixed $hash
    * @param mixed $new_pass
    * @param mixed $new_pass_confirm
    */
    public function changeUserPassword(Orm\User $user, $new_pass, $new_pass_confirm)
    {
        if ($new_pass !== $new_pass_confirm) {
            $this->addError(t('Повтор пароля не соответствует паролю'));
            return false;
        }
        $check_result = Orm\User::checkPassword($new_pass);
        if ( $check_result !== true) {
            $this->addError($check_result);
            return false;
        }
        
        $user['openpass'] = $new_pass;
        if ($user->update()) {
            
            // Уведомление пользователю
            $notice = new \Users\Model\Notice\UserRecoverPassUser;
            $notice->init($user, $new_pass);
            \Alerts\Model\Manager::send($notice); 
            
            // Уведомление администратору
            $notice = new \Users\Model\Notice\UserRecoverPassAdmin;
            $notice->init($user, $new_pass);
            \Alerts\Model\Manager::send($notice); 

            return true;
        } else {
            $this->addError( implode(',', $user->getErrors()) );
            return false;
        }
    }   
    
    /**
    * Обработчик, который вызывается во время фильтрации данных на странице со списком 
    * в админ. панели
    * 
    * @param array of \RS\Html\Filter\Type\AbstractType $items
    * @param \RS\Html\Filter\Control $filter_control
    * @return array
    */
    function beforeSqlWhereCallback($items, $filter_control)
    {
        //Фильтруем по группе
        $group = $items['group']->getValue();
        if ($group !== '') {
            if ($group == 'NULL') $group = null;
            $this->setFilter('group', $group);
        }
        
        //Фильтруем по начальной дате регистрации
        $dateofreg_from = $items['dateofreg_from']->getValue();
        if ($dateofreg_from) {
            $this->setFilter(array(
                'dateofreg:>=' => $dateofreg_from
            ));
        }

        //Фильтруем по конечной дате регистрации
        $dateofreg_to = $items['dateofreg_to']->getValue();
        if ($dateofreg_to) {
            $this->setFilter(array(
                'dateofreg:<=' => $dateofreg_to.' 23:59:59'
            ));
        }               
                
        return array('group', 'dateofreg_from', 'dateofreg_to');        
    }   
    
    /**
    * Генерирует новые пароли для пользователей и отправляет соответствующее 
    * уведомление на почту пользователей
    * 
    * @param array $ids
    * @return bool
    */
    function generatePasswords($ids)
    {
        if ($this->noWriteRights()) return false;
        
        $config = \RS\Config\Loader::byModule($this);
        $user = new $this->obj_instance();
        foreach($ids as $id) {
            if ($user->load($id)) {
                $user['changepass'] = 1;
                $user['openpass'] = \RS\Helper\Tools::generatePassword($config['generate_password_length'], $config['generate_password_symbols']);
                $user->update();
                
                //Отправляем уведомление
                $notice = new \Users\Model\Notice\UserGeneratePassword();
                $notice->init($user, $user['openpass']);
                \Alerts\Model\Manager::send($notice);
            }
        }
        
        return true;
    }
}

