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
* ORM Объект - уровень многомерной комплектации
*/
class MultiOfferLevel extends \RS\Orm\AbstractObject
{

    protected static
            $table = 'product_multioffer';    
            


    function _init()
    {

        $this->getPropertyIterator()->append(array(
                'product_id' => new Type\Integer(array(
                    'maxLength' => 11,
                    'description' => t('id товара'),
                )),
                'prop_id' => new Type\Integer(array(
                    'maxLength' => 11,
                    'description' => t('id характеристики'),
                )),
                'title' => new Type\Varchar(array(
                    'maxLength' => '255',
                    'description' => t('Название уровня'),
                )),
                'is_photo' =>  new Type\Integer(array(
                    'maxLength' => '11',
                    'description' => t('Представление в виде фото?'),
                    'default' => 0
                )),
                'sortn' => new Type\Integer(array(
                    'maxLength' => '11',
                    'default' => 0,
                    'description' => t('Индекс сортировки'),
                )),


        ));

        $this->addIndex(array('product_id', 'prop_id'), self::INDEX_UNIQUE);
    }
    
}
