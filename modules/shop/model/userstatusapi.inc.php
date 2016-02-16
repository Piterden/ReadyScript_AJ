<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model;

class UserStatusApi extends \RS\Module\AbstractModel\EntityList
{
    protected static
        $id_by_type;
    
    public 
        $uniq;
        
    function __construct()
    {
        parent::__construct(new \Shop\Model\Orm\UserStatus, 
        array(
            'nameField' => 'title',
            'aliasField' => 'type',
            'defaultOrder' => 'id',
            'multisite' => true
        ));
    }
    
    function tableData()
    {
        $list = $this->getList();
        $st_list = array_keys($this->obj_instance->getDefaultStatues());
        foreach($list as $n => $status) {
            if (in_array($status['type'], $st_list)) {
                $list[$n]['checkbox_attribute'] = array('disabled' => 'disabled');
            }
        }
        return $list;
    }    
    
    /**
    * Возвращает id статуса по символьному идентификатору
    * 
    * @param mixed $type
    */
    public static function getStatusIdByType($type = null)
    {
        if (!isset(self::$id_by_type)) {
            self::$id_by_type = \RS\Orm\Request::make()
                ->select('id, type')
                ->from(new Orm\UserStatus())
                ->where(array(
                    'site_id' => \RS\Site\Manager::getSiteId()
                ))
                ->exec()->fetchSelected('type', 'id');
        }
        
        return ($type !== null) ? self::$id_by_type[$type] : self::$id_by_type;
    }
    
    /**
    * Возвращает сгруппированный список статусов для отображения в административной панели
    * 
    * @return array
    */
    function getGroupedList()
    {
        $list = $this->getAssocList('type');
        $result = array();
        foreach(Orm\UserStatus::getStatusesSort() as $type) {
            $result[$type] = isset($list[$type]) ? $list[$type] : null;
            unset($list[$type]);
        }
        $result[Orm\UserStatus::STATUS_USER] = $list;
        return $result;
    }
    
    /**
    * Возвращает список статусов для отображения в разделе для администрирования заказов
    * 
    * @return array
    */
    function getAdminTreeList()
    {
        $default_statuses = $this->getElement()->getDefaultStatues();
        $statuses = $this->getList();
        $list = array();
        $total = 0;
        
        foreach($statuses as $status) {
            if ($status->isSystem()) {
                $status['disabledCheckbox'] = true;
                $status['noRedMarker'] = true;
            }
            $total += $status->getOrdersCount();
            $list[] = array('fields' => $status, 'child' => null);
        }
        
        array_unshift($list, 
            array(
                'fields' => array(
                    'id' => 0,
                    'title' => 'Все',
                    'noOtherColumns' => true,
                    'noCheckbox' => true,
                    'noDraggable' => true,
                    'noRedMarker' => true,
                    'orders_count' => $total
                ), 
                'child' => null
            )
        );           
        return $list;
    }
    
}
