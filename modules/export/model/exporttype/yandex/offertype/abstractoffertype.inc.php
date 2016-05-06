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


abstract class AbstractOfferType   
{
    
    /**
    * Получить список "особенных" полей для данного типа описания
    * Возвращает массив объектов класса Field.
    * 
    * @return Filed[]
    */
    static public function getEspecialTags()
    {
        return array();
    }
    
    /**
    * Запись товарного предложения
    * 
    * @param ExportProfile $profile
    * @param \XMLWriter $writer
    * @param mixed $product
    * @param mixed $offer_index
    */
    public function writeOffer(ExportProfile $profile, \XMLWriter $writer, Product $product, $offer_index)
    {
        if ($profile->only_avaible && $product->getNum($offer_index) < 1) {
            return;
        }
        
        $this->getEspecialTags();
        if($offer_index !== false && !count($product['offers'])){
            throw new \Exception(t('Товарные предложения отсутсвуют, но передан аргумент offer_index'));
        }
        
        $writer->startElement("offer");
        $writer->writeAttribute('id', $product->id.'x'.$offer_index);  
        
        // Добавляем group_id, если у товара есть комплектации
        if ($product->isOffersUse()) {
            $writer->writeAttribute('group_id', $product->id);  
        }
        
        // Если указан тип описания
        if(isset($profile->data['offer_type'])){
            // Если это не Simple описание, то у тега offer добавляем аттрибут type
            if($profile->data['offer_type'] != 'simple'){
                $writer->writeAttribute('type', $profile->data['offer_type']);  
            }
        }
        
        // Берем цену по-умолчанию
        $prices = $product->getOfferCost($offer_index, $product['xcost']);
        $price = $prices[ \Catalog\Model\CostApi::getDefaultCostId() ];
        if ($old_cost_id = \Catalog\Model\CostApi::getOldCostId()) {
            $old_price = $prices[ $old_cost_id ];
        }

        // Определяем доступность товара
        $available = $product->getNum($offer_index) > 0 && $price > 0;
        
        $writer->writeAttribute('available', $available ? 'true' : 'false');  
        $writer->writeElement('url', $product->getUrl(true).( $offer_index ? '#'.$offer_index : '' ));  
        $writer->writeElement('price', $price);  
        if (!empty($old_price) && $old_price > $price) {
            $writer->writeElement('oldprice', $old_price);  
        }
        $writer->writeElement('currencyId', \Catalog\Model\CurrencyApi::getDefaultCurrency()->title);  
        $writer->writeElement('categoryId', $product->maindir);  
        $n = 0;
        foreach($product->images as $image){
            if($image instanceof \Photo\Model\Orm\Image && $n<10) {
                //Yandex допускает не более 10 фото на одно предложение
                $rootUrl = \RS\Site\Manager::getSite()->getRootUrl(true);
                $writer->writeElement('picture', rtrim($rootUrl,'/').$image->getUrl(800, 600));
                $n++;
            }
        }
        

        // Запись "особенных" элементов для каждого конкретного типа описания
        $this->writeEspecialOfferTags($profile, $writer, $product, $offer_index);
        
        
        // Записываем свойства товара в теги <param>
        $prop_list = $product->getVisiblePropertyList();
        
        foreach($prop_list as $group){
            if(!isset($group['properties'])) continue;
            foreach($group['properties'] as $prop){
                $value = $prop->textView();
                if (trim($value) !== '') {
                    $writer->startElement('param');  
                    $writer->writeAttribute('name', $prop->title);  
                    // Если у свойства товара указана единица измерения
                    if($prop->unit){
                        $writer->writeAttribute('unit', $prop->unit);  
                    }
                    $writer->text($value);  
                    $writer->endElement();  
                }
            }
        }
        
        //Записываем свойства комплектации в теги <param>
        if (isset($product['offers']['items'][$offer_index])) {
            $offer = $product['offers']['items'][$offer_index];
            foreach((array)$offer['propsdata_arr'] as $key => $value) {
                $writer->startElement('param');  
                $writer->writeAttribute('name', $key);  
                $writer->text($value);  
                $writer->endElement();  
            }
        }
            
        $writer->endElement();  
        $writer->flush();
    }
    
    /**
    * Запись элемента в соответсвии с настройками сопоставления полей экспорта свойствам товара
    * 
    * @param Field $field
    * @param ExportProfile $profile
    * @param \XMLWriter $writer
    * @param Product $product
    */
    protected function writeElementFromFieldmap(Field $field, ExportProfile $profile, \XMLWriter $writer, Product $product)
    {
        $value = $this->getElementFromFieldmap($field, $profile, $writer, $product);
        if ($value!==null){
            $writer->writeElement($field->name, $value);      
        }
    }
    
    /**
    * Получить элемент в соответсвии с настройками сопоставления полей экспорта свойствам товара
    * 
    * @param Field $field
    * @param ExportProfile $profile
    * @param \XMLWriter $writer
    * @param Product $product
    * @return string
    */    
    protected function getElementFromFieldmap(Field $field, ExportProfile $profile, \XMLWriter $writer, Product $product)
    {
        // Получаем объект типа экспорта (в нем хранятся соотвествия полей - fieldmap)
        $export_type_object = $profile->getTypeObject();
        
        if(!empty($export_type_object['fieldmap'][$field->name]['prop_id'])){
            // Идентификатор свойстава товара
            $property_id = (int) $export_type_object['fieldmap'][$field->name]['prop_id'];
            // Значение по умолчанию
            $default_value = $export_type_object['fieldmap'][$field->name]['value'];
            // Получаем значение свойства товара
            $value = $product->getPropertyValueById($property_id);
            // Если яндекс ожидает строку (true|false)
            if($field->type == TYPE_BOOLEAN){
                // Если значение свойства 1 или непустая строка - выводим 'true', в противном случае 'false'
                if (!$value && !$default_value){
                    return "false";
                }
                return "true";
            }
            else{
                // Выводим значение свойства, либо значение по умолчанию
                return $value === null ? $default_value : $value;  
            }
        }
        return null;
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
    protected function writeEspecialOfferTags(ExportProfile $profile, \XMLWriter $writer, Product $product, $offer_index)
    {
        foreach(static::getEspecialTags() as $field)
        {
            $this->writeElementFromFieldmap($field, $profile, $writer, $product);
        }
    }

}