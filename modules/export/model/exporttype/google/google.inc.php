<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Export\Model\ExportType\Google;
use \RS\Orm\Type;

class Google extends \Export\Model\ExportType\AbstractType 
{
    const 
        CHARSET = 'utf-8';

    static private 
        $offerTypes = null;

    private 
        $shop_config, 
        $cacheSelectedProductIds = null;  
    
    function _init()
    {
        return parent::_init()->append(array(
            'products' => new Type\ArrayList(array(
                'description' => t('Список товаров'),
                'template' => '%export%/form/profile/products.tpl'
            )),
            'only_avaible' => new Type\Integer(array(
                'description' => t('Выгружать только товары, которые в наличии?'),
                'checkboxView' => array(1,0)
            )),
            'category_property_id' => new Type\Integer(array(
                'description' => t('Категория к товарам из классификатора Google(Идентификатор)'),
                'hint' => t('Категории из классификатора на сайте Google Merchants<br>(https://support.google.com/merchants/answer/1705911)'),
                'list' => array(array('\Catalog\Model\PropertyApi', 'staticSelectList'), true)
            )),
            'adult_property_id' => new Type\Integer(array(
                'description' => t('Товар принадлежит категории для взрослых?'),
                'hint' => t('Для характеристики типа Да/Нет. Необязательное.'),
                'list' => array(array('\Catalog\Model\PropertyApi', 'staticSelectList'), true)
            )),
            'weight_property_id' => new Type\Integer(array(
                'description' => t('Вес товара'),
                'hint' => t('Например "3 кг". Необязательное.'),
                'list' => array(array('\Catalog\Model\PropertyApi', 'staticSelectList'), true)
            )),
            'condition_property_id' => new Type\Integer(array(
                'description' => t('Состояние товара (condition)'),
                'hint' => t('Если не указывать, то будет указано "Новый". Необязательное.'),
                'list' => array(array('\Catalog\Model\PropertyApi', 'staticSelectList'), true)
            )),
            t('Поля данных для комплектаций'),
                'size' => new Type\Varchar(array(
                    'description' => t('Наменование значения размера в поле параметров комплектаций(size)'),
                    'hint' => t('Нужно только при наличии комплектаций у товаров. Необязательное.')
                )),
                'color' => new Type\Varchar(array(
                    'description' => t('Наменование значения цвета в поле параметров комплектаций(color)'),
                    'hint' => t('Нужно только при наличии комплектаций у товаров. Необязательное.')
                )),
                'gender' => new Type\Varchar(array(
                    'description' => t('Наменование значения пола в поле параметров комплектаций(gender)'),
                    'hint' => t('Нужно только при наличии комплектаций у товаров. Необязательное.')
                )),
                'age_group' => new Type\Varchar(array(
                    'description' => t('Наменование значения возвростной группы в поле параметров комплектаций(age_group)'),
                    'hint' => t('Нужно только при наличии комплектаций у товаров. Необязательное.')
                )),
                'pattern' => new Type\Varchar(array(
                    'description' => t('Наменование значения узора в поле параметров комплектаций(pattern)'),
                    'hint' => t('Нужно только при наличии комплектаций у товаров. Необязательное.')
                )),
                'size_type' => new Type\Varchar(array(
                    'description' => t('Наменование значения типа размера в поле параметров комплектаций(size_type)'),
                    'hint' => t('Нужно только при наличии комплектаций у товаров. Необязательное.')
                )),
                'size_system' => new Type\Varchar(array(
                    'description' => t('Наменование значения система размеров в поле параметров комплектаций(size_system)'),
                    'hint' => t('Нужно только при наличии комплектаций у товаров. Необязательное.')
                )),
        ));
    }
    
    /**
    * Возвращает название типа экспорта
    * 
    * @return string
    */
    function getTitle()
    {
        return t('Google Merchants');
    }
    
    /**
    * Возвращает описание типа экспорта для администратора. Возможен HTML
    * 
    * @return string
    */
    function getDescription()
    {
        return t('Экспорт в формате Google.Merchants - RSS 2.0');
    }
    
    /**
    * Возвращает идентификатор данного типа экспорта. (только англ. буквы)
    * 
    * @return string
    */
    function getShortName()
    {
        return 'google';
    }
    
    /**
    * Возвращает корневой тэг документа
    * 
    * @return string
    */
    protected function getRootTag()
    {
        return "channel";
    }   
    
    /**
    * Возвращает экспортированные данные (XML)
    * 
    * @param \Export\Model\Orm\ExportProfile $profile Профиль экспорта
    * @return string
    */
    public function export(\Export\Model\Orm\ExportProfile $profile)
    {
        
        $writer = new \Export\Model\ExportType\Yandex\MyXMLWriter();  
        $writer->openURI($profile->getCacheFilePath());  
        $writer->startDocument('1.0', self::CHARSET);  
        $writer->setIndent(true);   
        $writer->setIndentString("    ");   
        $writer->startElement('rss');
            $writer->writeAttribute('version', '2.0');
            $writer->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
            
            $writer->startElement($this->getRootTag());  
                $site = \RS\Site\Manager::getSite();
                //Запись основных сведений
                $writer->writeElement('title', t("Экспорт товаров в Google Merchants"));
                $writer->writeElement('link', $site->getRootUrl(true));
                $writer->writeElement('description', t("Экспорт товаров в Google Merchants из сайта %0", array($site['full_title'])));
                
                $this->exportOffers($profile, $writer);
                
            $writer->endElement();  
        $writer->endElement();
        $writer->endDocument();
        $writer->flush();
        return file_get_contents($profile->getCacheFilePath());
    }
    
    /**
    * Переводит строку XML в форматированный XML
    * 
    * @param string $xml_string - строка XML
    */
    function toFormatedXML($xml_string)
    {
       $dom = new \DOMDocument('1.0');
       $dom->preserveWhiteSpace = false;
       $dom->formatOutput = true;
       $dom->loadXML($xml_string); 
       return $dom->saveXML();
    }


    /**
    * Экспорт Товарных предложений
    * 
    * @param \Export\Model\Orm\ExportProfile $profile - объект профиля экспорта
    * @param \XMLWriter $writer - объект библиотеки для работы с XML
    */
    private function exportOffers(\Export\Model\Orm\ExportProfile $profile, \XMLWriter $writer)
    {
        $this->shop_config = \RS\Config\Loader::byModule('shop');
        
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
                        $this->writeOffer($profile, $writer, $product, $offer_index);
                    }
                }
                else{
                    $this->writeOffer($profile, $writer, $product, false);
                }
            }
            $offset += $pageSize;
        }
        $writer->flush();
    }
    
    /**
    * Запись товарного предложения
    * 
    * @param \Export\Model\Orm\ExportProfile $profile - объект профиля экспорта
    * @param \XMLWriter $writer - объект библиотеки для записи XML
    * @param \Catalog\Model\Orm\Product $product - объект товара
    * @param integer $offer_index - индекс комплектации для отображения
    */
    public function writeOffer(\Export\Model\Orm\ExportProfile $profile, \XMLWriter $writer, \Catalog\Model\Orm\Product $product, $offer_index)
    {
        if ($profile->only_avaible && $product->getNum($offer_index) < 1) {
            return;
        }
        
        if (!$product->hasImage()){ //Если нет фото
            return;
        }
        
        if($offer_index !== false && !count($product['offers'])){
            throw new \Exception(t('Товарные предложения отсутсвуют, но передан аргумент offer_index'));
        }
        
        /**
        * @var \Catalog\Model\Orm\Offer
        */
        $current_offer = false; //Текущая комплектация
        
        $writer->startElement("item");
        if ($offer_index!==false){
            $writer->writeAttribute('g:item_group_id', $product->id);     
        }
        
        $title = strip_tags(trim($product->title.' '.($offer_index !== false ? $product->getOfferTitle($offer_index) : '')));
        $writer->writeElement("title", $title);
        $writer->writeElement("link", $product->getUrl(true).( $offer_index ? '#'.$offer_index : '' ));
       
        //Добавим описание
        $description = $product->short_description;
        if ($offer_index!==false){
            /**
            * @var \Catalog\Model\Orm\Offer
            */
            $current_offer = $product['offers']['items'][$offer_index];//Текущее предложение
            //Если есть доп параметры модели, то добавим их в конец
            if (isset($current_offer['propsdata_arr']) && !empty($current_offer['propsdata_arr'])){
                $arr = array();
                foreach ($current_offer['propsdata_arr'] as $key=>$value){
                    $arr[] = $key.": ".$value;
                }
                $description .= " ".implode(", ", $arr);
            }
            
        }
        $writer->writeElement("description", strip_tags($description));
        $barcode = $product->getBarCode($offer_index);
        //Уникаьлный артикул
        $writer->writeElement("g:id", (!$current_offer) ? $barcode : $barcode."-".$current_offer['id']);
        //Категории
        $this->writeOfferCategory($profile, $writer, $product);
        
        //Картинка и картинки
        $this->writeOfferImages($profile, $writer, $product, $current_offer);
        
        // Берем цену по-умолчанию
        $prices = $product->getOfferCost($offer_index, $product['xcost']);
        $price  = $prices[ \Catalog\Model\CostApi::getDefaultCostId() ];
        if ($old_cost_id = \Catalog\Model\CostApi::getOldCostId()) {
            $old_price = $prices[ $old_cost_id ];
        }
        
        //Доступность
        $this->writeOfferAvaliability($writer, $product, $price, $offer_index);
        
        //Цена
        if ($old_price>0){ //Если есть старая цена продажи
            $writer->writeElement("g:price", $old_price." ".\Catalog\Model\CurrencyApi::getDefaultCurrency()->title);
            $writer->writeElement("g:sale_price", $price." ".\Catalog\Model\CurrencyApi::getDefaultCurrency()->title);
        }else{
            $writer->writeElement("g:price", $price." ".\Catalog\Model\CurrencyApi::getDefaultCurrency()->title);
        }
        
        //Бренд
        $brand = $product->getBrand();
        if (isset($brand['id']) && $brand['id']){
            $writer->writeElement("g:brand", $brand['title']);
        }

        //Состояние товара
        if ($profile['condition_property_id'] && $product->getPropertyValueById($profile['condition_property_id'])){
            $writer->writeElement("g:condition", $product->getPropertyValueById($profile['condition_property_id']));
        }else{
            $writer->writeElement("g:condition", t("новый"));
        }
        
        //Если это товары для взрослых
        if ($profile['adult_property_id'] && $product->getPropertyValueById($profile['adult_property_id'])){ //Если указана характеристка отвечающая за показ, того что это товар для взрослых
            $writer->writeElement("g:adult", "TRUE");
        }
        
        //Вес товара
        if ($profile['weight_property_id'] && $product->getPropertyValueById($profile['weight_property_id'])){
            $writer->writeElement("g:shipping_weight", $product->getPropertyValueById($profile['weight_property_id']));
        }
        
        //Дополнительные сведения если, это комплектация
        if ($current_offer){
            $this->writeOfferAdditionalParams($profile, $writer, $current_offer);
        }
            
        $writer->endElement();  
        $writer->flush();
    }
    
    /**
    * Добавляет при наличии дополнительных данных сведения по комплекатациям
    * 
    * @param \Export\Model\Orm\ExportProfile $profile - объект профиля экспорта
    * @param \XMLWriter $writer - объект библиотеки для записи XML
    * @param mixed $current_offer - текущая комплектация
    */
    private function writeOfferAdditionalParams(\Export\Model\Orm\ExportProfile $profile, \XMLWriter $writer, $current_offer)
    {
        //Параметры для поиска данных в комплектациях
        $params = array(
            'size',
            'color',
            'gender',
            'age_group',
            'pattern',
            'size_type',
            'size_system',
        );
        
        //Пройдёмся по параметрам комплектаций
        foreach ($params as $param_title){
            if (!empty($profile[$param_title]) && isset($current_offer['propsdata_arr'][$param_title])){
                $writer->writeElement("g:".$param_title, $current_offer['propsdata_arr'][$param_title]); 
            }
        }
    }
    
    /**
    * Добавляет сведения о доступности товара
    * 
    * @param \XMLWriter $writer - объект библиотеки для записи XML
    * @param \Catalog\Model\Orm\Product $product - объект товара
    * @param float $price - индекс комплектации для отображения
    * @param integer $offer_index - индекс комплектации для отображения
    */
    private function writeOfferAvaliability(\XMLWriter $writer, \Catalog\Model\Orm\Product $product, $price, $offer_index)
    {
        if ($this->shop_config && $product->shouldReserve()){ //Если есть только предзаказ
            $writer->writeElement("g:availability", "preorder"); 
        }else{
            // Определяем доступность товара
            $available = $product->getNum($offer_index) > 0 && $price > 0; 
            $writer->writeElement("g:availability", $available ? "in stock" : "out of stock");
        }
    }
    
    
    /**
    * Добавляет в XML сведения о категории в которой должен хранится товар или комплектация
    * 
    * @param \Export\Model\Orm\ExportProfile $profile - объект профиля экспорта
    * @param \XMLWriter $writer - объект библиотеки для записи XML
    * @param \Catalog\Model\Orm\Product $product - объект товара
    */
    private function writeOfferCategory(\Export\Model\Orm\ExportProfile $profile, \XMLWriter $writer, \Catalog\Model\Orm\Product $product)
    {
        $category_id = $product->getPropertyValueById($profile['category_property_id']);
        
        if ($category_id){ //Выставим категорию
            $writer->writeElement("g:google_product_category", $category_id);   
        }
        
        $dirapi = new \Catalog\Model\Dirapi();
        $xdirs   = array_diff($product['xdir'], $product['xspec']);
        foreach ($xdirs as $xdir){
            $dirapi->clearFilter();
            $dirapi->setFilter('public', 1);
            $path   = $dirapi->getPathToFirst($xdir);
        
            $arr = array();
            foreach ($path as $dir){
               $arr[] = $dir['name']; 
            }
            $writer->writeElement("g:product_type", implode(" > ", $arr));
        }
    }
    
    /**
    * Добавляет в XML сведения с фото для товара или комплектации
    * 
    * @param \Export\Model\Orm\ExportProfile $profile - объект профиля экспорта
    * @param \XMLWriter $writer - объект библиотеки для записи XML
    * @param \Catalog\Model\Orm\Product $product - объект товара
    * @param \Catalog\Model\Orm\Offer|false $current_offer - текущая комплектация, объект или false
    */
    private function writeOfferImages(\Export\Model\Orm\ExportProfile $profile, \XMLWriter $writer, \Catalog\Model\Orm\Product $product, $current_offer)
    {
        $images = $product->getImages();
        $offer_images = array();
        if ($current_offer){ //Если есть комплектации, посмотим привязани ли фото к конкретной комплектации
            $offer_images = $current_offer['photos_arr'];
            if (!empty($offer_images)){ 
                
                $first = false;
                foreach ($images as $k=>$image){
                    if (in_array($image['id'], $offer_images)){
                        if (!$first){ //Первое фото
                            $writer->writeElement("g:image_link", $image->getUrl(800, 800, 'axy', true));  
                            $first = true;
                        }else{
                            $writer->writeElement("g:additional_image_link", $image->getUrl(800, 800, 'axy', true)); 
                        }
                    }
                }
            }
        }
        //Если просто товар или фото комплектаций не привязано
        if (!$current_offer || ($current_offer && empty($offer_images))){
            foreach ($images as $k=>$image){
               if ($k==0){ //Если первое фото
                   $writer->writeElement("g:image_link", $image->getUrl(800, 800, 'axy', true));   
               }else{
                   $writer->writeElement("g:additional_image_link", $image->getUrl(800, 800, 'axy', true)); 
               } 
            }
        }  
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
    * Возвращает ссылку на файл экспорта
    * 
    */
    function getExportUrl(\Export\Model\Orm\ExportProfile $profile)
    {
        $router = \RS\Router\Manager::obj();
        return $router->getUrl('export-front-gate', 
            array(
                'site_id' => \RS\Site\Manager::getSiteId(), 
                'export_id' => $profile->id, 
                'export_type' => $profile->class
            )
            , true, 0);
    }
}


