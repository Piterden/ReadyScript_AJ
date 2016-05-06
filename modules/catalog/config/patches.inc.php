<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Config;

/**
* Патчи к модулю
*/
class Patches extends \RS\Module\AbstractPatches
{
    /**
    * Возвращает список имен существующих патчей
    */
    function init()
    {
        return array(
            '20068',
            '20082',
            '200163'
        );
    }
    
    /**
    * Патч для обновления версии 20068
    */
    function beforeUpdate20068()
    {
        $file = \Setup::$PATH."/modules/catalog/controller/front/listproducts.my.inc.php";
        if (file_exists($file)){
           $md5_file = md5_file($file);
           if ($md5_file == '0e40d45793beb97525500a4f27e71c5f' || $md5_file == '2bfe1e1ef2f3833f578f533a29ed3685') {
              @unlink($file); 
           }
        }
    }
    
    /**
    * Патч, который добавляет всё необходимое для работы со складами
    * 
    */
    function afterUpdate20082()
    {
        //Получаем сайты, которые есть в системе
        $sites = \RS\Site\Manager::getSiteList();
        foreach ($sites as $site){
          $warehouse = \Catalog\Model\Orm\WareHouse::loadByWhere(array(
                'site_id' => $site['id']
            ));

          if (!$warehouse['id']) {
          
              $module = new \RS\Module\Item('catalog');
              $installer = $module->getInstallInstance();
              $installer->importCsv(new \Catalog\Model\CsvSchema\Warehouse(), 'warehouse', $site['id']);

              $warehouse = \Catalog\Model\Orm\WareHouse::loadByWhere(array(
                  'site_id' => $site['id']
              ));
           }
           
           //Теперь создадим остатки по складам для первого основного склада
           if ($warehouse['id']) {
              $product = new \Catalog\Model\Orm\Product(); 
              $offer   = new \Catalog\Model\Orm\Offer(); 
              $x_stock = new \Catalog\Model\Orm\Xstock(); 
              //Найдём товары которые у нас без комплектаций и создаём нулевую комплектацию, для данного товара
              $sql = "
              INSERT IGNORE INTO ".$offer->_getTable()." (site_id,product_id,num,sortn) 
                  (
                      SELECT P.site_id,P.id,P.num,0 FROM ".$product->_getTable()." AS P 
                          LEFT JOIN ".$offer->_getTable()." as O ON P.id=O.product_id 
                          WHERE O.product_id IS NULL AND P.site_id = ".$site['id']."
                  )";
              \RS\Db\Adapter::sqlExec($sql); 
              
              $sql = "
              INSERT INTO ".$x_stock->_getTable()." (`product_id`,`offer_id`,`warehouse_id`,`stock`) (
                SELECT O.product_id,O.id,".$warehouse['id'].",num FROM ".$offer->_getTable()." as O 
                    WHERE O.site_id = ".$site['id']."
              )";
              \RS\Db\Adapter::sqlExec($sql); 
           } 
        } 
    }
    
    /**
    * Патч, переносит значение поля public в checkout_public у объекта warehouse
    */
    function afterUpdate200163()
    {
        \RS\Orm\Request::make()
            ->update(new \Catalog\Model\Orm\WareHouse)
            ->set('checkout_public = public')
            ->exec();
    }
}
