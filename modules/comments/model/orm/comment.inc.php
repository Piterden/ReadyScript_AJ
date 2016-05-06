<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Comments\Model\Orm;
use \RS\Orm\Type;

class Comment extends \RS\Orm\OrmObject
{
	protected static
		$table = 'comments';
    
    protected
        $rate_text = array(
            'нет оценки',
            'ужасно',
            'плохо',
            'нормально',
            'хорошо',
            'отлично'
        ),
        $user = null; //Объект пользователя
    
    protected static $type_instances = array();
		
	function _init()
	{
        if (\RS\Debug\Mode::isEnabled()) {        
            $this->addDebugActions(array(
                new \RS\Debug\Action\Edit(\RS\Router\Manager::obj()->getAdminPattern('edit', array(':id' => '{id}'), 'comments-ctrl')),
                new \RS\Debug\Action\Delete(\RS\Router\Manager::obj()->getAdminPattern('del', array(':chk[]' => '{id}'), 'comments-ctrl'))
            ));
        }        
        
        $api = new \Comments\Model\Api();
        
		parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'type' => new Type\Varchar(array(
                'maxLength' => '150',
                'index' => true,
                'description' => t('Класс комментария'),
                'list' => array(array($api, 'getTypeList'))
            )),
            'aid' => new Type\Integer(array(
                'maxLength' => '12',
                'description' => t('Идентификатор объект'),
            )),
            '__url__' => new Type\Mixed(array(
                'visible' => true,
                'description' => t('Ссылка на объект'),
                'template' => '%comments%/form/comment/url.tpl'
            )),
            'dateof' => new Type\Datetime(array(
                'description' => t('Дата'),
            )),
            'user_id' => new Type\Integer(array(
                'maxLength' => '11',
                'description' => t('Пользователь'),
            )),
            '__url_user__' => new Type\Mixed(array(
                'visible' => true,
                'description' => t('Ссылка на пользователя'),
                'template' => '%comments%/form/comment/user.tpl'
            )),
            'user_name' => new Type\Varchar(array(
                'maxLength' => '100',
                'Checker' => array('chkEmpty','Напишите, пожалуйста, Ваше имя'),
                'description' => t('Имя пользователя'),
            )),
            'message' => new Type\Text(array(
                'description' => t('Сообщение'),
                'Checker' => array('chkEmpty','Напишите, пожалуйста, отзыв'),
            )),
            'moderated' => new Type\Integer(array(
                'maxLength' => '1',
                'description' => t('Проверено'),
                'CheckboxView' => array(1,0),
            )),
            'rate' => new Type\Integer(array(
                'maxLength' => '5',
                'description' => t('Оценка (от 1 до 5)'),
            )),
            'help_yes' => new Type\Integer(array(
                'maxLength' => '11',
                'description' => t('Ответ помог'),
            )),
            'help_no' => new Type\Integer(array(
                'maxLength' => '11',
                'description' => t('Ответ не помог'),
            )),
            'ip' => new Type\Varchar(array(
                'maxLength' => '15',
                'description' => t('IP адрес'),
            )),
            'useful' => new Type\Integer(array(
                'maxLength' => '11',
                'description' => t('Полезность'),
            )),
            'help' => new Type\Mixed(array(
                'visible' => false
            )),
            'captcha' => new Type\Captcha(array(
                'visible' => false,
                'enable' => false
            ))
        ));
	}

    /**
    * Возвращает отладочные действия, которые можно произвести с объектом
    * 
    * @return RS\Debug\Action[]
    */
    public function getDebugActions()
    {
        return array(
            new \RS\Debug\Action\Edit(\RS\Router\Manager::obj()->getAdminPattern('edit', array(':id' => '{id}'), 'comments-ctrl')),
            new \RS\Debug\Action\Delete(\RS\Router\Manager::obj()->getAdminPattern('del', array(':chk[]' => '{id}'), 'comments-ctrl'))
        );
    }    
    
    /**
    * Получает ссылку на объект который прокомментирован.
    * 
    * @return false|string
    */
    function getTargetHref(){
        if ($this['type_obj']) {
            return $this['type_obj']->getAdminUrl($this);
        }
        return false;
    }
    
    /**
    * Получает тип объекта комментария
    * 
    * @return string
    */
    function getTypeObject(){
       return $this['type']; 
    }
    
    
    /**
    * Получает ссылку на пользователя в админ панели.
    * 
    * @return false|string
    */
    function getUserAdminHref(){
       if (intval($this['user_id'])){
          return \RS\Router\Manager::obj()->getAdminUrl('edit',array('id'=>$this['user_id']),'users-ctrl'); 
       } 
       return false; 
    }     
    
    /**
    * Возвращает пользователя оставившего комментарий
    * 
    */
    function getUser(){
       if (intval($this['user_id'])){
          if ($this->user === null){
              $this->user = new \Users\Model\Orm\User($this['user_id']);
          }  
          return $this->user;
       }
       return false; 
    }

    
    function afterObjectLoad()
    {
        // Загружаем объект типа комментария.
        $thistype = $this['type'];
        if (!isset( self::$type_instances[$thistype] )) {
            self::$type_instances[$thistype] = class_exists($thistype) ? new $thistype() : false;
        }
        //Загружаем в runtime поле - тип комментария в текстовом виде, объект типа.
        $type = self::$type_instances[$thistype];
        if ($type !== false) {
            $this['type_obj'] = $type;
            $this['type_obj_title'] = $type->getTitle();
        }
    }
    
    function getRateText()
    {
        return $this->rate_text[$this['rate']];
    }
    
    function delete()
    {
        if ($result = parent::delete()) {
            if ($this['type']) {
                $type_class = new $this['type']();
                if (method_exists($type_class, 'onDelete')) {
                    $type_class->onDelete($this);
                }
            }
        }
        return $result;
    }
}

