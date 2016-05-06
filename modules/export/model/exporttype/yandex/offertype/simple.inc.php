<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Export\Model\ExportType\Yandex\OfferType;
use \Export\Model\Orm\ExportProfile as ExportProfile;
use \Catalog\Model\Orm\Product as Product;


class Simple extends AbstractOfferType
{

    static public function getEspecialTags()
    {
        $ret = array();
        
        $field = new Field();
        $field->name        = 'market_category';
        $field->title       = t('Категория в дереве категорий Яндекс.Маркет (market_category)');
        $field->required    = false;
        $ret[$field->name]  = $field;              

        $field = new Field();
        $field->name        = 'sales_notes';
        $field->title       = t('Информация о предоплате(sales_notes)');
        $field->required    = true;
        $ret[$field->name]  = $field;
        
        $field = new Field();
        $field->name        = 'delivery';
        $field->title       = t('Возможность доставки (delivery)<br/>Характеристика Да/Нет');
        $field->type        = true;
        $field->required    = false;
        $ret[$field->name]  = $field;
        
        $field = new Field();
        $field->name        = 'local_delivery_cost';
        $field->title       = t('Стоимость доставки в Вашем регионе (local_delivery_cost)<br/>Числовая характеристика');
        $field->required    = false;
        $ret[$field->name]  = $field; 
        
        return $ret;
    }

    /**
    * Запись "Особенных" полей, для данного типа описания
    * Перегружается в потомке. По умолчанию выводит все поля в соответсвии с fieldmap
    * 
    * @param ExportProfile $profile
    * @param \XMLWriter $writer
    * @param Product $product
    * @param mixed $offer_index
    */
    function writeEspecialOfferTags(ExportProfile $profile, \XMLWriter $writer, Product $product, $offer_index)
    {
        $writer->writeElement('name', $product->title.' '.($offer_index !== false ? $product->getOfferTitle($offer_index) : '') );
        $writer->writeElement('description', $product->short_description);
        
        parent::writeEspecialOfferTags($profile, $writer, $product, $offer_index);
    }    
}