<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model;

class RegionApi extends \RS\Module\AbstractModel\TreeList
{
    const DEFAULT_REGION_NAME = "Россия";
    
    static protected $cache_default_region; 
    
    function __construct()
    {
        parent::__construct(new \Shop\Model\Orm\Region,
        array(
            'parentField' => 'parent_id',
            'nameField' => 'title',
            'defaultOrder' => 'parent_id, title',
            'multisite' => true
        ));
    }    
    
    static function selectList()
    {
        $_this = new self();
        $_this->setFilter('parent_id', 0);
        return array(0 => t('Верхний уровень'))+ $_this -> getAssocList($_this->id_field, $_this->name_field);
    }

    static function selectListAll()
    {
        $top_level_group_title = t('Страны');
        $_this = new self();
        $list = $_this -> getList();
        $ret = array();
        $ret[$top_level_group_title] = array();
        foreach($list as $region){
            if(!$region->parent_id){
                $ret[$top_level_group_title][$region->id] = $region->title;
            }
            else{
                 $ret[$region->getParent()->title][$region->id] = $region->title;
            }
        }
        return $ret;
    }
    
    static function countryList()
    {
        $_this = new self();
        $_this->setFilter('parent_id', 0);
        return $_this -> getAssocList($_this->id_field, $_this->name_field);
    }    
    
    /**
    * Возращает регион по умолчанию - Россию
    * Если такого региона в справочнике нет - создает его
    * @return \Shop\Model\Orm\Region 
    */
    static function getDefaultRegion()
    {
        if(self::$cache_default_region != null){
            return self::$cache_default_region;
        }
        
        $def_reg = \Shop\Model\Orm\Region::loadByWhere(array(
            'site_id'   => \RS\Site\Manager::getSiteId(),
            'title' => self::DEFAULT_REGION_NAME
        ));
        
        if(!$def_reg->id){
            $def_reg->parent_id = 0;
            $def_reg->title     = self::DEFAULT_REGION_NAME;
            $def_reg->insert();
        }
        
        self::$cache_default_region = $def_reg;
        return self::$cache_default_region;
    }
    
}
?>
