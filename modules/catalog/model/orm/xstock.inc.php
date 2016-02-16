<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model\Orm;
use \RS\Orm\Type;

/**
* Orm остатка на складах
*/
class Xstock extends \RS\Orm\AbstractObject
{
    protected static
        $table = "product_x_stock";
    
    function _init()
    {
        $this->getPropertyIterator()->append(array(
            'product_id' => new Type\Integer(array(
                'description' => t('ID товара')
            )),
            'offer_id' => new Type\Integer(array(
                'description' => t('ID комплектации'),
                'index' => true
            )),
            'warehouse_id' => new Type\Integer(array(
                'description' => t('ID склада'),
                'index' => true
            )),
            'stock' => new Type\Integer(array(
                'description' => t('Количество товара'),
                'default' => 0
            )),
        ));
        
        $this->addIndex(array('product_id', 'offer_id', 'warehouse_id'), self::INDEX_UNIQUE);
    }
    
}

