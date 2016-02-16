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
* Регион доставки
*/
class Region extends \RS\Orm\OrmObject
{
    protected static
        $table = 'order_regions';
    
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'title' => new Type\String(array(
                'description' => t('Название')
            )),
            'parent_id' => new Type\Integer(array(
                'description' => t('Родитель'),
                'list' => array(array('\Shop\Model\RegionApi', 'selectList')),
                'index' => true
            ))
        ));
    }
    
    /**
    * Удаление региона
    * 
    */
    function delete()
    {
        //Удаляем вместе с вложенными элементами
        if (parent::delete()) {
            $childs_id = $this->getChildsRecursive($this['id']);
            if ($childs_id) {
                \RS\Orm\Request::make()->delete()
                ->from($this)
                ->where('id IN (#ids)', array('ids' => implode(',', $childs_id)))
                ->exec()->affectedRows();
            }
            return true;
        }
        return false;
    }  
    
    function getChildsRecursive($parent)
    {
        $ids = \RS\Orm\Request::make()->select('id')->from($this)
            ->where(array('parent_id' => $parent))
            ->exec()->fetchSelected(null, 'id');
        
        $result = $ids;
        foreach ($ids as $id) {
            $result = array_merge($result, $this->getChildsRecursive($id));
        }
        return $result;
    }
    
    /**
    * Возвращает объект родителя
    */
    function getParent()
    {
        return new self($this->parent_id);
    }  
    
    /**
    * Возвращает магистральные зоны
    */
    function getZones()
    {
        $zoneApi = new \Shop\Model\ZoneApi();
        $zone_ids = $zoneApi->getZonesByRegionId($this['id']);
        if(empty($zone_ids)){
            return array();
        }
        return \RS\Orm\Request::make()
            ->from(new \Shop\Model\Orm\Zone)
            ->whereIn('id', $zone_ids)
            ->objects();
    }
}
