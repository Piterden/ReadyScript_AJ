<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Model\CsvSchema;
use \RS\Csv\Preset;

/**
* Схема экспорта/импорта справочника цен в CSV
*/
class Offer extends \RS\Csv\AbstractSchema
{
    function __construct()
    {
        $product_search_field = \RS\Config\Loader::byModule($this)->csv_offer_product_search_field;
        $offer_search_field = \RS\Config\Loader::byModule($this)->csv_offer_search_field;
        $request = \RS\Orm\Request::make()->from(new \Catalog\Model\Orm\Offer())->where('product_id > 0');
        
        parent::__construct(new Preset\Base(array(
                'ormObject' => new \Catalog\Model\Orm\Offer(),
                'excludeFields' => array(
                    'product_id', 'pricedata', 'propsdata', 'site_id', 'id', 'num', 'photos', 'processed'
                ),
                'nullFields' => array(
                    'xml_id'
                ),
                'multisite' => true,
                'selectOrder' => 'product_id ASC, sortn ASC',
                'selectRequest' => $request,
                'searchFields' => array('product_id', $offer_search_field),
                'beforeRowImport' => function($_this) {
                    if (!$_this->row['product_id']) {
                        return false;
                    }
                },
                'afterRowImport' => array($this, 'afterBaseRowImport')
            )), array(
                new Preset\LinkedTable(array(
                    'ormObject' => new \Catalog\Model\Orm\Product(),
                    'save' => false,
                    'fields' => array($product_search_field),
                    'titles' => array($product_search_field => t('Товар')),
                    'idField' => 'id',
                    'multisite' => true,                
                    'linkForeignField' => 'product_id',
                    'linkPresetId' => 0,
                    'linkDefaultValue' => 0
                )),
                new \Catalog\Model\CsvPreset\OfferStock(array(
                    'linkPresetId' => 0,
                    'linkOfferIdField' => 'id',
                    'linkForeignField' => 'offer_id'
                )),
                new \Catalog\Model\CsvPreset\OfferPrice(array(
                    'linkPresetId' => 0,
                    'linkForeignField' => 'pricedata_arr'
                )),
                new Preset\SerializedArray(array(
                    'linkPresetId' => 0,
                    'linkForeignField' => 'propsdata',
                    'title' => 'Характеристики комплектации'
                )),
                new \Catalog\Model\CsvPreset\OfferPhotos(array(
                    'linkPresetId' => 0,
                    'link_orm_type' => 'catalog',
                    'linkForeignField' => 'photos_arr',
                    'title' => 'Фотографии',
                ))
            ), array(
                'afterImport' => array($this, 'afterImport')
            )
        );
    }
    
    /**
    * Возвращает запрос для базовой выборки
    * 
    * @return \RS\Orm\Request
    */
    function getBaseQuery()
    {
        if (!$this->query) {
            $this->query = $this->base_preset->getSelectRequest();
        }
        
        //Если есть запрос с выборкой в сессии
        if ($savedRequest = \Catalog\Model\Api::getSavedRequest('Catalog\Controller\Admin\Ctrl_list')){
            /**
            * @var \RS\ORM\Request
            */
            $q = clone $savedRequest;
            $q->select  = "OFFERS.*";
            $q->limit(null)
              ->orderby('OFFERS.product_id ASC, OFFERS.sortn ASC')
              ->leftjoin(new \Catalog\Model\Orm\Offer(), 'OFFERS.product_id = A.id', 'OFFERS')
              ->setReturnClass(new \Catalog\Model\Orm\Offer());
            
            $this->query = $q;
        }
        
        return $this->query;
    }
    
    
    
    /**
    * Возвращает возможные колонки для идентификации продукта
    * 
    * @return array
    */
    public static function getPossibleProductFields()
    {
        $product = new \Catalog\Model\Orm\Product();
        $fields = array_flip(array('title', 'barcode', 'xml_id', 'alias'));
        foreach($fields as $k => $v) {
            $fields[$k] = $product['__'.$k]->getTitle();
        }
        return $fields;
    }
    
    /**
    * Возвращает возможные колонки для идентификации комплектации
    * 
    * @return array
    */
    public static function getPossibleOffersFields()
    {
        $product = new \Catalog\Model\Orm\Offer();
        $fields = array_flip(array('title', 'barcode', 'sortn', 'xml_id'));
        foreach($fields as $k => $v) {
            $fields[$k] = $product['__'.$k]->getTitle();
        }
        return $fields;
    }
    
    /**
    * Обработчик, выполняющийся после импорта набора (которые уложились по 
    * времени в 1 шаг) данных
    * 
    * @param Catalog\Model\CsvSchema\Offer 
    */
    function afterImport()
    {
        //Производим пересчет общих остатков у товаров
        $offer = new \Catalog\Model\Orm\Offer();
        \RS\Orm\Request::make()
            ->update()
            ->from(new \Catalog\Model\Orm\Product(), 'P')
            ->set("P.num = (SELECT SUM(num) FROM {$offer->_getTable()} O WHERE O.product_id = P.id)")
            ->exec();
    }
    
    /**
    * Выполняется после импорта одной строки у пресета
    * 
    * @param mixed $_this
    */
    function afterBaseRowImport($preset)
    {
        //Обновляем сведения о ценах основной комплектации у товара
        if ($preset->row['product_id'] && empty($preset->row['sortn'])) {
            $pricedata = $preset->row['pricedata_arr'];
            if (isset($pricedata['price'])) {
                $excost = array();                
                foreach($pricedata['price'] as $cost_id => $data) {
                    $excost[$cost_id] = array(
                        'cost_original_val' => $data['original_value'],
                        'cost_original_currency' => $data['unit']
                    );
                }
                
                $product = new \Catalog\Model\Orm\Product($preset->row['product_id']);
                $product['excost'] = $excost;
                $product->update();
            }
        }
    }
}
