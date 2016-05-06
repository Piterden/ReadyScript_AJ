<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\Orm;
use \RS\Orm\Type;

/**
* Пльзовательский статус заказа
*/
class UserStatus extends \RS\Orm\OrmObject
{
    const
        STATUS_NEW        = 'new',
        STATUS_WAITFORPAY = 'waitforpay',
        STATUS_INPROGRESS = 'inprogress',
        STATUS_SUCCESS    = 'success',
        STATUS_CANCELLED  = 'cancelled',
        
        STATUS_USER = 'other';
    
    protected static
        $table = 'order_userstatus',
        $sort  = array(
            self::STATUS_NEW, 
            self::STATUS_WAITFORPAY, 
            self::STATUS_INPROGRESS, 
            self::STATUS_USER, 
            self::STATUS_SUCCESS, 
            self::STATUS_CANCELLED);
    
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'title' => new Type\Varchar(array(
                'description' => t('Статус')
            )),
            'bgcolor' => new Type\Color(array(
                'maxLength' => 7,
                'description' => t('Цвет фона')
            )),
            'type' => new Type\Varchar(array(
                'maxLength' => 20,
                'description' => t('Идентификатор(Англ.яз)')
            ))
        ));
        $this->addIndex(array('site_id', 'type'), self::INDEX_UNIQUE);
        
    }
    
    /**
    * Функция срабатывает перед записью объекта
    * 
    * @param string $flag - флаг текущего действия
    * 
    */
    function beforeWrite($flag)
    {
        if ($flag == self::UPDATE_FLAG){ //Если обновление 
            //Проверим на зарезервированные alias статусов. Исключим подмену alias
            $old_status = new \Shop\Model\Orm\UserStatus($this['id']);
            
            if (in_array($old_status['type'],self::$sort)){
                unset($this['type']);
            }
        }
    }
    
    /**
    * Добавляет в базу данных стандартные статусы
    * 
    * @param integer $site_id - ID сайта, на котором небходимо добавить статусы
    * @return void
    */
    public static function insertDefaultStatuses($site_id)
    {
        $default_names = self::getDefaultStatues();
        $assoc = array();
        foreach($default_names as $type => $data) {
            $record = new self();
            $record->getFromArray($data);
            $record['site_id'] = $site_id;
            $record['type'] = $type;
            $record->insert();
            $assoc[$type] = $record['id'];
        }
        //Устанавливаем в настройки модуля статус заказа по умолчанию "Ожидает оплаты"
        $config = \RS\Config\Loader::byModule('shop', $site_id);
        $config['first_order_status'] = $assoc[self::STATUS_WAITFORPAY];
        $config->update();
    }
    
    /**
    * Возвращает статусы по умолчанию
    * 
    * @return array
    */
    public static function getDefaultStatues()
    {
        return array(
            self::STATUS_NEW => array(
                'title' => t('Новый'),
                'bgcolor' => '#83b7b3'
            ),
            self::STATUS_WAITFORPAY => array(
                'title' => t('Ожидает оплату'),
                'bgcolor' => '#687482'
            ),
            self::STATUS_INPROGRESS => array(
                'title' => t('В обработке'),
                'bgcolor' => '#f2aa17'
            ),
            self::STATUS_SUCCESS => array(
                'title' => t('Выполнен и закрыт'),
                'bgcolor' => '#5f8456'
            ),
            self::STATUS_CANCELLED => array(
                'title' => t('Отменен'),
                'bgcolor' => '#ef533a'
            )
        );        
    }
    
    /**
    * Возвращает массив с порядком статусов
    * 
    * @return array
    */
    public static function getStatusesSort()
    {
        return self::$sort;
    }  
    
    /**
    * Возвращает true, если статус является системным, иначе - false
    * 
    * @return bool
    */
    function isSystem()
    {
        static 
            $defaults;
            
        if ($defaults === null) $defaults = self::getDefaultStatues();
        return isset($defaults[$this['type']]);
    }
    
    /**
    * Возвращаетколичество заказов для текущего статуса
    * 
    * @return integer
    */
    function getOrdersCount($cache = true)
    {
        static $cache_taxonomy;
        if (!$cache || $cache_taxonomy === null) {
            $cache_taxonomy = $this->getTaxonomy();
        }
        
        return isset($cache_taxonomy[$this['id']]) ? $cache_taxonomy[$this['id']] : 0;
    }
    
    /**
    * Возвращает количество заказов для каждого статуса
    * 
    * @return array
    */
    private function getTaxonomy()
    {
        $count_by_status = \RS\Orm\Request::make()
            ->select('status, COUNT(*) as cnt')
            ->from(new \Shop\Model\Orm\Order())
            ->where(array('site_id' => \RS\Site\Manager::getSiteId()))
            ->groupby('status')
            ->exec()->fetchSelected('status', 'cnt');
        
        $count_by_status[0] = 0;
        foreach($count_by_status as $value) {
            $count_by_status[0] += $value;
        }
        return $count_by_status;
    }    
    
}
