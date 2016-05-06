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


class ArtistTitle extends AbstractOfferType
{

    static public function getEspecialTags()
    {

        $ret = array();

        /*$field = new Field();
        $field->name        = 'title';
        $field->title       = t('Наименование');
        $field->required    = true;
        $ret[$field->name]  = $field;*/

        $field = new Field();
        $field->name        = 'artist';
        $field->title       = t('Исполнитель');
        $ret[$field->name]  = $field;
        
        $field = new Field();
        $field->name        = 'director';
        $field->title       = t('Режиссер');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'starring';
        $field->title       = t('Актеры');
        $ret[$field->name]  = $field;
        
        $field = new Field();
        $field->name        = 'originalName';
        $field->title       = t('Оригинальное наименование');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'country';
        $field->title       = t('Страна');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'year';
        $field->title       = t('Год издания');
        $ret[$field->name]  = $field;

        $field = new Field();
        $field->name        = 'media';
        $field->title       = t('Носитель');
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
        // (artist?, title, year?, media?, starring?, director?, originalName?, country?)
        $fields = self::getEspecialTags();
        // Выводим специальные теги в правильном порядке
        $this->writeElementFromFieldmap($fields['artist'], $profile, $writer, $product);
        $writer->writeElement('title', $offer_index === false ? $product->title : $product->getOfferTitle($offer_index));
        $this->writeElementFromFieldmap($fields['media'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['starring'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['director'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['originalName'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['country'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['delivery'], $profile, $writer, $product);
        $this->writeElementFromFieldmap($fields['local_delivery_cost'], $profile, $writer, $product);
    }
    
}