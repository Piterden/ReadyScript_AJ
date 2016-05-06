<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model\Orm;
use \RS\Orm\Type;
 
/**
* Класс ORM-объектов "Добавить в 1 клик". Объект добавить в 1 клик
* Наследуется от объекта \RS\Orm\OrmObject, у которого объявлено свойство id
*/
class OneClickItem extends \RS\Orm\OrmObject
{
    protected static
        $table = 'one_click'; //Имя таблицы в БД
    protected
        /**
        * @var \Feedback\Model\Orm\FormItem
        */
        $form;  //Текущая форма
         
    /**
    * Инициализирует свойства ORM объекта
    *
    * @return void
    */
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(), //Создаем поле, которое будет содержать id текущего сайта
            'title' => new Type\Varchar(array(
                'maxLength' => '150',
                'description' => t('Номер сообщения')
            )), 
            'dateof' => new Type\Datetime(array(
                'maxLength' => '150',
                'description' => t('Дата отправки')
            )), 
            'status' => new Type\Enum(array(
                'new',
                'viewed',
            ), array(
                'maxLength' => '1',
                'allowEmpty' => false,
                'default'   => 'new',
                'listFromArray' => array(array(
                    'new'    => 'Новое',
                    'viewed' => 'Закрыт',
                )),
                'description' => t('Статус')
            )),            
            'ip' => new Type\Varchar(array(
                'maxLength' => '150',
                'description' => t('IP Пользователя')
            )),
            'stext' => new Type\Text(array(
                'description' => t('Содержимое результата формы'),
                'Template' => 'form/field/stext.tpl'
            )),
        ));
        
    }
    
    /**
    * Возращает масстив сохранённых данных рассериализованными
    * 
    * @param string $field - поле для десеарелизации
    */
    function tableDataUnserialized($field = 'stext')
    {
       return unserialize($this['stext']); 
    }
    
   
    /**
    * Событие срабатывает после записи объекта в БД
    * 
    * @param mixed $flag  - Флаг вставки обновления, либо удаления
    * @return null
    */
    function afterWrite($flag)
    {
       if ($flag == self::INSERT_FLAG){ //Флаг вставки
          $stext = unserialize($this['stext']);  
          $this['title'] = "Покупка №".$this['id']." - ".$stext['product']['title']; //Обновим название 
          $this->update();
       }
    }
   
}