<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Main\Model;

class LicenseApi extends \RS\Module\AbstractModel\EntityList
{
    function __construct()
    {
        parent::__construct(new \Main\Model\Orm\License);
    }
    
    /**
    * Возвращает список лицензий для отображения в таблице
    * 
    * @return array
    */
    function getTableList()
    {
        $list = __GET_LICENSE_TABLE_LIST();
        $idnaConvert = \RS\Helper\IdnaConvert::singleton();
        foreach($list as &$license) {
            $license['domain'] = $idnaConvert->decode($license['domain']);
        }
        return $list;
    }
    
    /**
    * Возвращает ссылку на страницу официального сайта, где можно купить лицензии для текущей комплектации скрипта
    * 
    * @return string
    */
    function getBuyLicenseUrl()
    {
        $script_type = strtolower(str_replace('.', '-', \Setup::$SCRIPT_TYPE));
        return str_replace('{script_type}', $script_type, \Setup::$BUY_LICENSE_URL);
    }
    
}
?>
