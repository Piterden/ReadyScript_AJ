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


class Book extends AbstractOfferType
{

    static public function getEspecialTags()
    {
        $ret = array();

        $field = new Field();
        $field->name        = 'author';
        $field->title       = t('Автор');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'publisher';
        $field->title       = t('Издательство');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'series';
        $field->title       = t('Серия');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'year';
        $field->title       = t('Год издания');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'ISBN';
        $field->title       = t('Код книги (ISBN)');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'volume';
        $field->title       = t('Кол-во томов');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'part';
        $field->title       = t('Номер тома');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'language';
        $field->title       = t('Язык');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'binding';
        $field->title       = t('Переплет');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'page_extent';
        $field->title       = t('Кол-во страниц');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'table_of_contents';
        $field->title       = t('Оглавление');
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
    
    public function writeEspecialOfferTags(ExportProfile $profile, \XMLWriter $writer, Product $product, $offer_index)
    {
        //(author?, name, publisher?, series?, year?, ISBN?, volume?, part?, language?, binding?, page_extent?, table_of_contents?)
        $fields = self::getEspecialTags();

        // Выводим специальные теги в правильном порядке
        $this->writeElementFromFieldmap($fields['author'], $profile, $writer, $product);
        $writer->writeElement('name', $offer_index === false ? $product->title : $product->getOfferTitle($offer_index));
        $this->writeElementFromFieldmap($fields['publisher'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['series'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['year'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['ISBN'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['volume'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['part'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['language'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['binding'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['page_extent'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['table_of_contents'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['delivery'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['local_delivery_cost'], $profile, $writer, $product);
        
    }    
    
}