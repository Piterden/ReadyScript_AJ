<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model;

class WareHouseApi extends \RS\Module\AbstractModel\EntityList
{
    function __construct()
    {
        parent::__construct(new \Catalog\Model\Orm\WareHouse(),
        array(
            'multisite' => true,
            'defaultOrder' => 'sortn',
            'sortField' => 'sortn'
        ));
    }
    
    
    /**
    * Получает склад используемый по умолчанию
    * 
    */
    public static function getDefaultWareHouse() 
    {
        static $default_warehouse;
        
        if ($default_warehouse === null) {
           $default_warehouse = \Catalog\Model\Orm\WareHouse::loadByWhere(array(
                'site_id' => \RS\Site\Manager::getSiteId(),
                'default_house' => 1       
           ));
        }
        return $default_warehouse;
    }
    
    /**
    * Устанавливает по id склад по умолчанию
    * 
    * @param integer $id - 
    */
    function setDefaultWareHouse($id)
    {
        $elem = $this->getById($id);
        $elem['default_house'] = 1;
        $elem->update();
        return true;
    }

    /**
    * Возвращает полный список складов, не смотря на флаг публичности
    * 
    */
    static function getWarehousesList()
    {
       $_this = new \Catalog\Model\WareHouseApi();
       return $_this->getList(); 
    }
    
}

