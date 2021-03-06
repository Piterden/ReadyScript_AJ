<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Model\Orm\Property;
use \RS\Orm\Type;

/**
* Core-object - связь характеристик с товарами
* @ingroup Catalog
*/
class Link extends \RS\Orm\AbstractObject
{
    protected static
        $table = 'product_prop_link';
    
    function _init()
    {
        $this->getPropertyIterator()->append(array(
            'site_id' => new Type\CurrentSite(),
            'prop_id' => new Type\Integer(array(
                'description' => t('ID характеристики')
            )),
            'product_id' => new Type\Integer(array(
                'description' => t('ID товара'),
                'maxLength' => '11',
            )),
            'group_id' => new Type\Integer(array(
                'description' => t('ID группы товаров'),
                'maxLength' => '11',
            )),
            'val_str' => new Type\String(array(
                'description' => t('Строковое значение'),
                'maxLength' => '255',
            )),
            'val_int' => new Type\Float(array(
                'description' => t('Числовое значение')
            )),
            'public' => new Type\Integer(array(
                'description' => t('Участие в фильтрах. Для group_id>0'),
                'maxLength' => 1
            )),
            'xml_id' => new Type\String(array(
                'maxLength' => '255',
                'description' => t('Идентификатор товара в системе 1C'),
            )),       
            'extra' =>  new Type\String(array(
                'maxLength' => '255',
                'description' => t('Дополнительное поле для данных'),
                'visible' => false,
            )),

        ));
        
        $this
            ->addIndex(array('site_id', 'product_id', 'group_id'), self::INDEX_UNIQUE)
            ->addIndex(array('site_id', 'prop_id'), self::INDEX_KEY)
            ->addIndex(array('product_id', 'prop_id', 'val_str'), self::INDEX_KEY)
            ->addIndex(array('product_id', 'prop_id', 'val_int'), self::INDEX_KEY)
            ->addIndex(array('group_id', 'public'));
    }
    
    /**
    * Заполняет данными объект в зависимости от типа данных
    * 
    * @param integer $product_id   - id товара
    * @param string|integer $value - значение характеристики
    * @param array $pdata          - массив с данными свойства
    * 
    * @return void
    */
    function fillData($product_id,$value,$pdata)
    {
       $this['prop_id']    = $pdata['id']; 
       $this['public']     = $pdata['public']; 
       $this['site_id']    = $pdata['site_id']; 
       $this['xml_id']     = $pdata['xml_id']; 
       $this['product_id'] = $product_id; 
       
       if (($pdata['type']=='int')||($pdata['type']=='bool')){
          $this['val_int']  = $value; 
       }else{
          $this['val_str'] = $value; 
       }
    }
}

