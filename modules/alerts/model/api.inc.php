<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Alerts\Model;


class Api extends \RS\Module\AbstractModel\EntityList
{
    private static $senders;
    
    
    function __construct()
    {
        parent::__construct(new \Alerts\Model\Orm\NoticeConfig(), 
            array(
                'multisite' => true
            ));
    }
    
    /**
    * Возвращает список объектов, согласно заданным раннее условиям
    * 
    * @param integer $page - номер страницы
    * @param integer $page_size - количество элементов на страницу
    * @param string $order - условие сортировки
    * @return array of objects
    */
    function getList($page = null, $page_size = null, $order = null)
    {   
        // Создаем все конфиги, если их нет
        foreach(self::getAllNoticeClasses() as $one){
            $obj = self::getNoticeConfig($one);
        }
        
        $list = (array)parent::getList($page, $page_size, $order);     
        
        return $list;
    } 
    
    /**
    * Возвращает список SMS-провайдеров.
    * 
    * @return array
    */
    function getSenders()
    {
        if (self::$senders === null) {
            $event_result = \RS\Event\Manager::fire('alerts.getsmssenders', array());
            $list = $event_result->getResult();
            self::$senders = array();
            foreach($list as $sender_object) {
                if (!($sender_object instanceof Sms\AbstractSender)) {
                    throw new \Exception(t('Тип отправки SMS должен быть наследником \Alerts\Model\Sms\AbstractSender'));
                }
                self::$senders[$sender_object->getShortName()] = $sender_object;
            }
        }
        
        return self::$senders;
    }
    
    public static function selectSendersList()
    {
        $_this = new self();
        $result = array();
        foreach($_this->getSenders() as $key => $object) {
            $result[$key] = $object->getTitle();
        }
        return $result;
    }
    
    /**
    * Возвращает объект провайдера по его короткому строковому идентификатору
    * 
    * @param string $name
    * @return object
    */
    public static function getSenderByShortName($name)
    {
        $_this = new self();
        $list = $_this->getSenders();
        return isset($list[$name]) ? $list[$name] : null;
    }    
    
    /**
    * Получить полный список всех уведомлений системы
    *     
    * @return array
    */
    static public function getAllNoticeClasses()
    {
        $classes = array();
        foreach(glob(\Setup::$PATH.'/modules/*/model/notice/*.inc.php') as $one){
            $class_name = self::getClassNameByPath($one);
            $classes[] = $class_name;
        }
        return $classes;
    }
    
    static public function getClassNameByPath($file_path)
    {
        $file_path = str_replace('\\', '/', $file_path);
        $file_path = str_replace(array('.'.\Setup::$CUSTOM_CLASS_EXT, '.'.\Setup::$CLASS_EXT), '', $file_path);
        $parts = explode('/', $file_path);
        $parts = array_slice($parts, count($parts) - 4, 4);
        return '\\'.join('\\', $parts);
    }
    
    /**
    * Возвращает объект NoticeConfig по имени класса уведомления
    * 
    * @param string $class_name
    */
    static public function getNoticeConfig($class_name){
        if($class_name{0} != '\\') $class_name = '\\'.$class_name;
        if(!class_exists($class_name)) throw new \Exception(t('Класс %0 не найден', array($class_name)));
        $notice_config = \Alerts\Model\Orm\NoticeConfig::loadByWhere(array(
            'site_id' => \RS\Site\Manager::getSiteId(),
            'class' => $class_name
        ));
        if(!$notice_config['id']){
            $notice_config['enable_email'] = 1;
            $notice_config['enable_sms'] = 1;
            $notice_config['class'] = $class_name;
            $notice_config->insert();
        }
        return $notice_config;
    }
    
}