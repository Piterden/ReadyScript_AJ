<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Feedback\Model\Orm;
use \RS\Orm\Type;
 
/**
* Класс ORM-объектов "Полей формы отправки".
* Наследуется от объекта \RS\Orm\OrmObject, у которого объявлено свойство id
*/
class FormFieldItem extends \RS\Orm\OrmObject
{
    const
        SHOW_TYPE_STRING  = 'string', //Тип строка
        SHOW_TYPE_EMAIL   = 'email',  //Тип email
        SHOW_TYPE_LIST    = 'list',   //Тип список
        SHOW_TYPE_YESNO   = 'yesno',  //Тип флажок
        SHOW_TYPE_TEXT    = 'text',   //Тип текстовое поле
        SHOW_TYPE_FILE    = 'file',   //Тип файл
        SHOW_TYPE_CAPTCHA = 'captcha'; //Тип капча
    
    protected static
        $table = 'connect_form_field'; //Имя таблицы в БД
    public
        $default; //По-умолчанию
        
    /**
    * Инициализирует свойства ORM объекта
    *
    * @return void
    */
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(), //Создаем поле, которое будет содержать id текущего сайта            
            'title' => new Type\String(array(
                'maxLength' => '150',
                'description' => t('Название'),
                'checker' => array('chkEmpty',t('Необходимо заполнить поле название')),
            )),  
            'alias' => new Type\String(array(
                'checker' => array('chkEmpty', t('Необходимо указать Псевдоним')),
                'maxLength' => '150',
                'description' => t('Псевдоним(Ан.яз)')
            )),
            'hint' => new Type\String(array(
                'maxLength' => '150',
                'description' => t('Подпись поля'),
                'hint'=> t('Будет отражено поясняющей надписью в форме')
            )),
            'form_id' => new Type\Integer(array(
                'description' => t('Форма'),
                'List' => array(array('\Feedback\Model\FormApi', 'staticSelectList')),
            )),            
            'required' => new Type\Integer(array(
                'description' => t('Обязательное поле'),
                'checkboxView' => array(1,0)
            )),
            'length' => new Type\Integer(array(
                'description' => t('Длина поля'),
                'maxLength' => '11',
            )),
            'show_type' => new Type\String(array(
                'maxLength' => 10,
                'description' => t('Тип'),
                'ListFromArray' => array(array(
                    self::SHOW_TYPE_STRING => t('Строка'), 
                    self::SHOW_TYPE_EMAIL  => t('Email'), 
                    self::SHOW_TYPE_LIST   => t('Список'), 
                    self::SHOW_TYPE_YESNO  => t('Да/Нет'), 
                    self::SHOW_TYPE_TEXT   => t('Текст'),  
                    self::SHOW_TYPE_FILE   => t('Файл'),  
                )),
                'template' => '%feedback%/form/field/type.tpl'
            )),
            'anwer_list' => new Type\Text(array(
                'description' => t('Значения списка'),
                'maxLength' => 1000,
                'Attr' => array(array('rows' =>10)),
                'hint' => t('Значения можно писать с новой строки'),
            )),
            'as_radio' => new Type\Integer(array(
                'description' => t('Отображать как радио кнопки'),
                'checkboxView' => array(1,0),
            )),
            'file_size' => new Type\Integer(array(
                'description' => t('Макс. размер файлов (Кб)'),
                'maxLength' => '11',
                'default' => '8192',//По умолчанию 8 мегабайт
                'hint' => 'Ограничивает размер загружаемого файла в килобайтах.<br/> 
                           Необходимо, чтобы настройки сервера, также позволяли<br/> 
                           загрузку файлов такого объема.'
            )),
            'file_ext' => new Type\String(array(
                'maxLength' => '150',
                'description' => t('Допустимые форматы файлов'),
                'hint' => t('Указывайте через запятую необходимые форматы. Например jpg,jpeg,bmp,png,gif')
            )),
            'use_mask' => new Type\String(array(
                'maxLength' => 20,
                'description' => t('Маска проверки'),
                'listFromArray' => array(array(
                    '' => 'Не проверять',
                    'email' => 'Email',
                    'phone' => 'Телефон',
                    'other' => 'Другая маска'
                ))
            )),
            'mask' => new Type\String(array(
                'description' => t('Произвольная маска проверки'),
                'hint' => t('Регулярное выражение. Будет подставлено в php "/(ваше регулярное выражение)/". Функция preg_match'),
            )),
            'error_text' => new Type\String(array(
                'maxLength' => '255',
                'description' => t('Текст ошибки'),
                'hint' => t('Будет отображен для при возникновении ошибки')
            )), 
            'sortn' => new Type\Integer(array(
                'description' => t('Сортировочный индекс'),
                'maxLength' => '11',
                'visible' => false,
            ))
        ));
    }
    
    
    /**
    * Действия перед записью 
    * 
    * @param mixed $flag
    * @return null
    */
    function beforeWrite($flag)
    {
        if ($flag == self::INSERT_FLAG) {//Вставка записи
            //Сортировка
            $this['sortn'] = \RS\Orm\Request::make()
                ->select('MAX(sortn) as max')
                ->from($this)
                ->exec()->getOneField('max', 0) + 1;
            
        }
        
        //Расширения файла
        $this['file_ext'] = str_replace(' ','',trim($this['file_ext']));
    }
   
    /**
    * Проверяет расширения файлов загруженных в поле по его имени
    * 
    * @param string $ext - расширение файла
    * @return bool
    */
    function checkFilekExtension($file_ext)
    {
       $extensions = $this->getFileExtensions(); //Получим расширения заданые пользователем
       if (!$extensions) return true; //Если не заданы
       
       return in_array($file_ext,$extensions);
    }
    
    /**
    * Получает массив файлов для проверки из поля 
    * 
    * @return array | false
    */
    function getFileExtensions()
    {
       //разобьём на массив строку, по переносу ","
       return explode(",",$this['file_ext']); 
    }
    
    /**
    * Устанавливает значение по-умолчанию
    * 
    * @param mixed $value - значение поля
    * @return void
    */
    function setDefault($value)
    {
       $this->default = $value;  
    }
    
    /**
    * Возвращает значение по-умолчанию
    * @return mixed
    */
    function getDefault()
    {
       return $this->default; 
    }
    
    /**
    * Получает ответы в виде массива из строки с значениями находящимия на следующей строке
    * В случае если не задано значений возвращает пустой массив
    * 
    * @return array
    */
    function getArrayValuesFromString()
    { 
       if (strlen(trim($this->anwer_list))==0) return array();  
        
       //разобьём на массив строку, по переносу строки
       return explode("\r",$this->anwer_list); 
    }
    
    /**
    * Возвращает HTML формы
    * 
    * @return string
    */
    function getFieldForm()
    {
        $view = new \RS\View\Engine();
        $view->assign('field', $this);
        $view->assign('request', new \RS\Http\Request());
        return $view->fetch('%feedback%/blocks/feedback/field.tpl');
    }
    
}