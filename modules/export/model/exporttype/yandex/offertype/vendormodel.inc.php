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


class VendorModel extends AbstractOfferType
{
    
    static private $boolTags = array(
        'manufacturer_warranty'
    );
    
    static public function getEspecialTags()
    {
        $ret = array();

        $field = new Field();
        $field->name        = 'vendor';
        $field->title       = t('Производитель');
        $field->required    = true;
        $ret[$field->name]  = $field;
        
        $field = new Field();
        $field->name        = 'vendorCode';
        $field->title       = t('Код производителя');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'model';
        $field->title       = t('Модель');
        $field->required    = true;
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'manufacturer_warranty';
        $field->title       = t('Гарантия производителя');
        $field->type        = TYPE_BOOLEAN;
        $ret[$field->name]  = $field;
        
        return $ret;
    }



    public function writeEspecialOfferTags(ExportProfile $profile, \XMLWriter $writer, Product $product, $offer_index)
    {
        //(typePrefix?, vendor, vendorCode?, model, (provider, tarifplan?)?)
        $fields = self::getEspecialTags();
        
        // Выводим специальные теги в правильном порядке
        
        $this->writeElementFromFieldmap($fields['vendor'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['vendorCode'], $profile, $writer, $product);

        // Для поля MODEL особенное поведение. Если не удалось получить значение из настроек fieldmap, то заполняем его наименованием продукта
        $model = $this->getElementFromFieldmap($fields['model'], $profile, $writer, $product);
        if(!$model){
            $model = $offer_index === false ? $product->title : $product->getOfferTitle($offer_index);
        }
        $writer->writeElement('model', $model);
    }    

}