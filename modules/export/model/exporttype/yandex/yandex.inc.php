<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Export\Model\ExportType\Yandex;
use \RS\Orm\Type;

class Yandex extends \Export\Model\ExportType\AbstractType 
{
    const CHARSET = 'utf-8';

    static private $offerTypes = null;

    private $cacheSelectedProductIds = null;  
    
    function _init()
    {
        return parent::_init()->append(array(
            'products' => new Type\ArrayList(array(
                'description' => t('Список товаров'),
                'template' => '%export%/form/profile/products.tpl'
            )),
            'only_avaible' => new Type\Integer(array(
                'description' => t('Выгружать только товары, которые в наличии'),
                'checkboxView' => array(1,0)
            )),
            t('Поля данных'),
            'offer_type' => new Type\Varchar(array(
                'description' => t('Тип описания'),
                'List' => array(array('\Export\Model\ExportType\Yandex\Yandex', 'getOfferTypeNames')),     
            )),            
            'fieldmap' => new Type\Mixed(array(
                'description' => t(''),
                'visible' => true,  
                'template' => '%export%/form/profile/fieldmap.tpl'
            ))
        ));
    }
    
   /**
    * Возвращает название типа экспорта
    * 
    * @return string
    */
    function getTitle()
    {
        return t('Яндекс.Маркет');
    }
    
    /**
    * Возвращает описание типа экспорта для администратора. Возможен HTML
    * 
    * @return string
    */
    function getDescription()
    {
        return t('Экспорт в формате Яндекс.Маркет - YML');
    }
    
    /**
    * Возвращает идентификатор данного типа экспорта. (только англ. буквы)
    * 
    * @return string
    */
    function getShortName()
    {
        return 'yandex';
    }
    
    /**
    * Возвращает экспортированные данные (XML)
    * 
    * @param \Export\Model\Orm\ExportProfile $profile Профиль экспорта
    * @return string
    */
    public function export(\Export\Model\Orm\ExportProfile $profile)
    {
        $writer = new MyXMLWriter();  
        $writer->openURI($profile->getCacheFilePath());  
        $writer->startDocument('1.0', self::CHARSET);  
        $writer->setIndent(true);   
        $writer->setIndentString("    ");   
        $writer->startElement($this->getRootTag());  
            $writer->writeAttribute('date', date('Y-m-d H:i'));  
            $writer->startElement("shop");  
                $writer->writeElement('name', \RS\Helper\Tools::teaser(\RS\Site\Manager::getSite()->title, 20, false));  
                $writer->writeElement('company', \RS\Config\Loader::getSiteConfig()->firm_name);  
                $writer->writeElement('url', \RS\Site\Manager::getSite()->getRootUrl(true));  
                $this->exportCurrencies($profile, $writer);
                $this->exportCategories($profile, $writer);
                $this->exportOffers($profile, $writer);
            $writer->endElement();  
        $writer->endElement();  
        $writer->endDocument();   
        $writer->flush();
        return file_get_contents($profile->getCacheFilePath());
    }
    
    /**
    * Экспорт Валют
    * 
    * @param \Export\Model\Orm\ExportProfile $profile
    * @param \XMLWriter $writer
    */
    private function exportCurrencies(\Export\Model\Orm\ExportProfile $profile, \XMLWriter $writer)
    {
        $writer->startElement("currencies");  
            $writer->startElement("currency");
                $writer->writeAttribute('id', \Catalog\Model\CurrencyApi::getDefaultCurrency()->title);  
                $writer->writeAttribute('rate', \Catalog\Model\CurrencyApi::getDefaultCurrency()->ratio);  
            $writer->endElement();  
        $writer->endElement();  
        $writer->flush();
    }

    /**
    * Экспорт Категорий
    * 
    * @param \Export\Model\Orm\ExportProfile $profile
    * @param \XMLWriter $writer
    */
    private function exportCategories(\Export\Model\Orm\ExportProfile $profile, \XMLWriter $writer)
    {
        $writer->startElement("categories");  
            $groups = $this->getSelectedProductGroups($profile);
            foreach($groups as $group){
                $writer->startElement("category");
                    $writer->writeAttribute('id', $group->id);  
                    $writer->text($group->name);
                $writer->endElement();  
            }
        $writer->endElement();  
        $writer->flush();
    }

    /**
    * Экспорт Товарных предложений
    * 
    * @param \Export\Model\Orm\ExportProfile $profile
    * @param \XMLWriter $writer
    */
    private function exportOffers(\Export\Model\Orm\ExportProfile $profile, \XMLWriter $writer)
    {
        $product_ids = $this->getSelectedProductIds($profile);
        $query = \RS\Orm\Request::make()
                    ->from(new \Catalog\Model\Orm\Product)
                    ->where(array('public' => 1));
        
        if ($profile->only_avaible) {
            $query->where('num > 0');
        }
        
        if(!empty($product_ids)){
            $query->whereIn('id', $product_ids);
        }

        $offset = 0;
        $pageSize = 200;
        $catalogApi = new \Catalog\Model\Api();

        $writer->startElement("offers");        
                
        while( $products = $query->limit($offset, $pageSize)->objects() ) {
        
        $products = $catalogApi->addProductsCost($products);
        $products = $catalogApi->addProductsOffers($products);
        $products = $catalogApi->addProductsDirs($products);
        $products = $catalogApi->addProductsProperty($products);
        $products = $catalogApi->addProductsPhotos($products);
        
            foreach($products as $product){                             
                $offers_count = count($product['offers']['items']);
                if($product['offers']['use'] && $offers_count>1){
                    foreach(range(0, $offers_count-1) as $offer_index){
                        $this->exportOneOffer($profile, $writer, $product, $offer_index);
                    }
                }
                else{
                    $this->exportOneOffer($profile, $writer, $product, false);
                }
            }
            $offset += $pageSize;
        }
        
        $writer->endElement();  
        $writer->flush();
    }

    /**
    * Экпорт одного товарного предложения
    * 
    * @param \Export\Model\Orm\ExportProfile $profile
    * @param \XMLWriter $writer
    * @param mixed $product
    * @param mixed $offer_index
    */
    private function exportOneOffer(\Export\Model\Orm\ExportProfile $profile, \XMLWriter $writer, $product, $offer_index)
    {
        $offer_type = isset($profile->data['offer_type']) ? $profile->data['offer_type'] : 'simple';
        $offer_type_class = '\Export\Model\ExportType\Yandex\OfferType\\'.str_replace('.', '', $offer_type);
        $offer_type_inst = new $offer_type_class;
        $offer_type_inst->writeOffer($profile, $writer, $product, $offer_index);
    }
    
    /**
    * Возвращает корневой тэг документа
    * 
    * @return string
    */
    protected function getRootTag()
    {
        return "yml_catalog";
    }    
    
    
    /**
    * Если для экспорта нужны какие-то специфические заголовки, то их нужно отправлять в этом методе
    */
    public function sendHeaders()
    {
        header("Content-type: text/xml; charset=".self::CHARSET);
    }
    
    /**
    * Возвращает массив идентификаторов выбранных товаров
    * 
    * @param \Export\Model\Orm\ExportProfile $profile
    * 
    * @return array
    */
    private function getSelectedProductIds(\Export\Model\Orm\ExportProfile $profile)
    {
        if($this->cacheSelectedProductIds != null){
            return $this->cacheSelectedProductIds;
        }
        
        $product_ids    = isset($profile->data['products']['product']) ? $profile->data['products']['product'] : array();       
        $group_ids      = isset($profile->data['products']['group']) ? $profile->data['products']['group'] : array();       
        
        if (!$product_ids && !$group_ids) {
            //Если не выбрана ни одна группа и ни один товар, это означает, 
            //что экспортировать нужно все товары во всех группах
            $group_ids = array(0);
        }
        
        if(!empty($group_ids)){
            // Получаем все дочерние группы
            while(true){
                $subgroups_ids = \RS\Orm\Request::make()
                    ->select('id')
                    ->from(new \Catalog\Model\Orm\Dir())
                    ->whereIn('parent', $group_ids)
                    ->exec()
                    ->fetchSelected(null, 'id');
                $old_count = count($group_ids);
                $group_ids = array_unique(array_merge($group_ids, $subgroups_ids));
                if($old_count == count($group_ids)) break;
            }
            // Получаем ID всех товаров в этих группах
            $ids = \RS\Orm\Request::make()
                ->select('product_id')
                ->from(new \Catalog\Model\Orm\Xdir())
                ->whereIn('dir_id', $group_ids)
                ->exec()
                ->fetchSelected(null, 'product_id');
                
            // Прибавляем их к "товарам выбранными по одному"
            $product_ids = array_unique(array_merge($product_ids, $ids));
        }    
        $this->cacheSelectedProductIds = $product_ids;    
        return $this->cacheSelectedProductIds;
    }
    
    /**
    * Возвращает массив идентификаторов выбранных пользователем групп товаров
    * 
    * @param \Export\Model\Orm\ExportProfile $profile
    * @return array
    */
    private function getSelectedProductGroupIds(\Export\Model\Orm\ExportProfile $profile)
    {
        //Возвращаем основные группы товаров, без спецкатегорий.
        $selected_product_ids = $this->getSelectedProductIds($profile);
        if(empty($selected_product_ids)) return array();
        $groups_ids = \RS\Orm\Request::make()
            ->select('maindir')
            ->from(new \Catalog\Model\Orm\Product())
            ->where(array('public' => 1))
            ->whereIn('id', $selected_product_ids)
            ->exec()
            ->fetchSelected(null, 'maindir');
        return array_unique($groups_ids);
    }

    /**
    * Возвращает массив выбранных пользователем групп товаров
    * 
    * @param \Export\Model\Orm\ExportProfile $profile
    * @return array
    */
    private function getSelectedProductGroups(\Export\Model\Orm\ExportProfile $profile)
    {
        $selected_product_group_ids = $this->getSelectedProductGroupIds($profile);
        if(empty($selected_product_group_ids)) return array();
        return \RS\Orm\Request::make()
            ->from(new \Catalog\Model\Orm\Dir())
            ->whereIn('id', $selected_product_group_ids)
            ->objects();
    }

    
    /**
    * Возвращает массив доступных типов описания товарных предложений
    * @return array
    */
    static public function getOfferTypeNames()
    {
        return array(
            'simple'        => t('Упрощенное'),
            'vendor.model'  => t('Произвольный товар (vendor.model)'),
            'book'          => t('Книги (book)'),
            'audiobook'     => t('Аудиокниги (audiobook)'),
            'artist.title'  => t('Музыкальная и видео продукция (artist.title)'),
        );
    }
    
    /**
    * Возвращает массив данных по всем типам описания
    * @return array
    */
    static public function getOfferTypes()
    {
        if(self::$offerTypes == null){
            self::$offerTypes = array(
                'simple'        => OfferType\Simple::getEspecialTags(),
                'vendor.model'  => OfferType\VendorModel::getEspecialTags(),
                'book'          => OfferType\Book::getEspecialTags(),
                'audiobook'     => OfferType\AudioBook::getEspecialTags(),
                'artist.title'  => OfferType\ArtistTitle::getEspecialTags(),
            ); 
        }
        return self::$offerTypes;
    }
    
    /**
    * Возвращает массив данных по всем типам описания в виде JSON
    * @return string
    */
    static public function getOfferTypesJson()
    {
        return json_encode(self::getOfferTypes());
    }
    
    
    /**
    * Возвращает массив соответсвия полей (fieldmap) в виде JSON
    * @return string
    */
    public function getFieldMapJson()
    {
        return json_encode($this['fieldmap']);
    }

}


