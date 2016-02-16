<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Banners\Model;

class ZoneApi extends \RS\Module\AbstractModel\EntityList
{
    public 
        $uniq;
            
    function __construct()
    {
        parent::__construct(new \Banners\Model\Orm\Zone,
        array(
            'aliasField' => 'alias',
            'nameField' => 'title',
            'multisite' => true
        ));
    }
    
    public static function staticAdminSelectList()
    {
        return array(0 => 'Без связи с зоной') + self::staticSelectList();
    }
    
    public static function selectTreeList()
    {
        $_this = new self();
        $list = array(
            array(
                'fields' => array(
                    'id' => 0,
                    'title' => 'Все',
                    'noOtherColumns' => true,
                    'noCheckbox' => true,
                    'noDraggable' => true,
                    'noRedMarker' => true
                ), 
                'child' => null
        ));
        
        $res = $_this->getListAsResource();
        while($row = $res->fetchRow()) {
            $list[] = array('fields' => $row, 'child' => null);
        }
        return $list;
    }    
}
?>
