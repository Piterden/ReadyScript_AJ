<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Model;

/**
 * Предоставляет возможности для импорта данных из YML файлов
 *
 */
class ImportYmlApi extends \RS\Module\AbstractModel\BaseModel
{
    const YML_ID_PREFIX = "yml_";

    public
        $storage;
    
    protected 
        $site_id,
        $yml_api,
        $timeout,
        $allow_ext = array('yml', 'xml'),
        $tmp_data_file = 'data.tmp',
        $log_name = 'yml_import.log',
        $log_file_rel,
        $log_file,
        $yml_name = 'tmp.yml',
        $yml_folder,
        $yml_folder_rel = '/storage/tmp/importyml',
        $start_time,
        $params_fields = array(
            'delivery' => 'Доставка',
            'local_deliver_cost' => 'Стоимость доставки',
            'typePrefix' => 'Тип',
            'vendorCode' => 'Код производителя',
            'model' => 'Модель',
            'manufacturer_warranty' => 'Гарантия производителя',
            'country_of_origin' => 'Страна-производитель',
            'author' => 'Автор',
            'publisher' => 'Издатель',
            'series' => 'Серия',
            'year' => 'Год',
            'ISBN' => 'ISBN',
            'volume' => 'Объем',
            'part' => 'Часть',
            'language' => 'Язык',
            'binding' => 'Переплет',
            'page_extent' => 'Количество страниц',
            'downloadable' => 'Возможность скачать',
            'performed_by' => 'Исполнитель',
            'performance_type' => 'Тип представления',
            'storage' => 'Носитель',
            'format' => 'Формат',
            'recording_length' => 'Длительность записи',
            'artist' => 'Артист',
            'media' => 'Носитель медиа',
            'starring' => 'В главных ролях',
            'country' => 'Страна',
            'director' => 'Директор',
            'originalName' => 'Оригинальное название',
            'worldRegion' => 'Регион мира',
            'region' => 'Регион',
            'days' => 'Дни',
            'title' => 'Название',
            'dataTour' => 'Дата тура',
            'hotel_stars' => 'Количество звезд',
            'room' => 'Комнаты',
            'meal' => 'Питание',
            'included' => 'Включено',
            'transport' => 'Транспорт',
            'place' => 'Место проведения',
            'hall' => 'Зал',
            'hall_part' => 'Расположение',
            'date' => 'Дата',
            'is_premiere' => 'Премьера',
            'is_kids' => 'Для детей',
            'adult' => 'Для взрослых',
            'pickup' => 'Самовывоз',
            'store' => 'Точка продаж',
            'sales_notes' => 'Ценовые предложения'
        );
    
    
    function __construct()
    {
        $this->yml_folder = \Setup::$PATH.$this->yml_folder_rel;
        $this->log_file_rel = $this->yml_folder_rel.'/'.$this->log_name;
        $this->log_file = \Setup::$PATH.$this->log_file_rel;
        
        $this->setTimeout(\RS\Config\Loader::byModule($this)->import_yml_timeout);
        $this->setSiteId(\RS\Site\Manager::getSiteId());
        $this->start_time = microtime(true);
        $this->loadLocalStorage();
    }
    
    /**
    * Устанавливает сайт, для которого будет происходить импорт данных
    * 
    * @param integer $site_id - ID сайта
    * @return void
    */
    function setSiteId($site_id)
    {
        $this->site_id = $site_id;
    }
    
    /**
    * Устанавливает время работы одного шага импорта
    * 
    * @param integer $sec - количество секунд. Если 0 - то время шага не контроллируется
    * @return void
    */
    function setTimeout($sec)
    {
        $this->timeout = $sec;
    }
    
    /**
    * Загружает временные данные текущего импорта
    * 
    * @return array
    */
    function loadLocalStorage()
    {
        if (!isset($this->storage)) {
            $filename = $this->yml_folder.'/'.$this->tmp_data_file;
            if (file_exists($filename)) {
                $this->storage = unserialize( file_get_contents($filename) );
            }
            if (!isset($this->storage)) {
                $this->storage = array();
            }
        }
        return $this->storage;
    }
    
    /**
    * Сохраняет подготовленную информацию $value под ключем $key
    * 
    * @param mixed $key
    * @param mixed $value
    * @return SiteUpdate
    */
    function saveLocalKey($key, $value = null)
    {
        if (!isset($this->storage)) $this->storage = $this->loadLocalStorage();
        
        if ($key === null) {
            $this->storage = array();
        } elseif (is_array($key)) {
            $this->storage = array_merge($this->storage, $key);
        } else {
            $this->storage[$key] = $value;
        }
        $this->flushLocalStorage();
        
        return $this;
    }    
    
    function flushLocalStorage()
    {
        file_put_contents( $this->yml_folder.'/'.$this->tmp_data_file, serialize($this->storage) );
    }
    
    /**
     * Загружает данные из YML файла в XMLReader
     * 
     * @param type $file файл в формате YML
     * @return boolean
     */
    function uploadFile($file)
    {
        \RS\File\Tools::makePath($this->yml_folder);
        \RS\File\Tools::deleteFolder($this->yml_folder, false);        
        
        $uploader = new \RS\File\Uploader($this->allow_ext, $this->yml_folder_rel);
        $uploader->setRightChecker(array($this, 'checkWriteRights'));
        
        if (!$uploader->setUploadFilename($this->yml_name)->uploadFile($file)) {
            return $this->addError($uploader->getErrorsStr());
        }
        
        return true;
    }        
    
    /**
    * Выполняет один шаг импорта
    * 
    * @param array $step_data Массив с параметрами импорта:
    * [
    *   'upload_image' => bool, //Загружать изображения или нет
    *   'step' => integer, //номер текущего шага
    *   'offset' => integer //количество обработанных раннее элементов в шаге
    * ]
    * 
    * @return array | bool Если возвращает array, то это означает что необходимо выполнить следующий шаг импорта с данными параметрами
    * Если возвращает false, значит во время импорта произошла ошибка
    * Если возвращает true, значит импорт завершен
    */
    function process($step_data)
    {
        $steps = $this->getSteps($step_data);
        
        $current_step_data = $steps[ $step_data['step'] ];
        $method = $current_step_data['method'];
        
        $result = $this->$method( $step_data );
        if (is_array($result)) {
            //Шаг просит повторного выполнения
            if (isset($current_step_data['make_callback'])) {
                $result += call_user_func($current_step_data['make_callback']);
            }
            return $result + array(
                'upload_images' => $step_data['upload_images'],
                'step'         => $step_data['step']
            );
            
        } elseif ($result) {
            //Шаг успешно выполнен, переходим к следующему или завершаем
            if ($step_data['step'] == count($steps)-1) {
                return $this->finishProcess();
            } else {
                $next_step = $step_data['step'] + 1;
                return array(
                    'upload_images' => $step_data['upload_images'],
                    'step'         => $next_step,
                    'offset'       => 0
                ) + (isset($steps[$next_step]['make_callback']) 
                        ? call_user_func($steps[$next_step]['make_callback']) 
                        : array());
            }
        }
        
        //Произошла ошибка
        return false;
    }
    
    /**
    * Возвращает список шагов, которые будут выполнены во время импорта
    * 
    * @param array $step_data - параметры импорта
    * @return array
    */
    function getSteps($step_data)
    {
        $_this = $this;
        $steps = array(
            array(
                'title' => 'Подготовка к импорту',
                'successTitle' => 'Подготовка к импорту завершена',
                'method' => 'stepStart'
            ),
            array(
                'title' => 'Импорт валют',
                'successTitle' => 'Валюты импортированы',
                'method' => 'stepCurrency'
            ),
            array(
                'title' => 'Импорт категорий',
                'successTitle' => 'Категории импортированы',
                'method' => 'stepCategory',
                'make_callback' => function() use ($_this) {
                    return array(
                        'total' => $_this->storage['yml_info']['category_count']
                    );
                }
            ),
            array(
                'title' => 'Импорт товаров',
                'successTitle' => 'Товары импортированы',
                'method' => 'stepProduct',
                'make_callback' => function() use ($_this) {
                    return array(
                        'total' => count($_this->storage['yml_info']['products_xml_ids'])
                    );
                }
            ),
            array(
                'title' => 'Импорт рекомендуемых товаров',
                'successTitle' => 'Рекомендуемые товары импортированы',
                'method' => 'stepRecommended',
                'make_callback' => function() use ($_this) {
                    return array(
                        'total' => count($_this->storage['yml_info']['products_xml_ids'])
                    );
                }
            ),
        );
        
        if (!empty($step_data['upload_images'])) {
            $steps = array_merge($steps, array(
                array(
                    'title' => 'Импорт изображений',
                    'successTitle' => 'Изображения импортированы',
                    'method' => 'stepImages',
                    'make_callback' => function() use ($_this) {
                        return array(
                            'total' => $_this->storage['yml_info']['photo_count']
                        );
                    }                    
                )
            ));                
        }   
                
        return $steps;
    }
    
    /**
    * Подготовка к импорту
    * 
    * @param array $step_data - параметры импорта
    * @return bool
    */
    private function stepStart($step_data)
    {
        //Очистка временного хранилища
        $this->saveLocalKey(null);
        $this->saveLocalKey('yml_info', $this->prepareImport());
        $this->saveLocalKey(array(
            'statistic' => array(
                'inserted_categories' => 0,
                'updated_categories' => 0,
                'inserted_offers' => 0,
                'updated_offers' => 0,            
                'inserted_photos' => 0,
                'already_exists_photos' => 0,
                'not_downloaded_photos' => 0
            )
        ));
        $this->writeLog('Import started...', false);
        
        return true;
    }
    
    /**
    * Заверщает процесс импорта
    * 
    * @return bool
    */
    private function finishProcess()
    {
        $this->writeLog('Import completed...');
        $this->cleanTemporaryDir();
        return true;
    }
    
    /**
    * Импортирует валюты
    * 
    * @param array $step_data - параметры импорта
    * @return array | bool
    */
    private function stepCurrency($step_data)
    {
        $reader = $this->loadReader('currencies');
        $currencies = new \SimpleXMLElement($reader->readOuterXML());

        foreach($currencies as $curr_xml){
            $currency_data = array(
                'site_id'   => $this->site_id,
                'title'     => (string)$curr_xml->attributes()->id,
            );
            $currency = \Catalog\Model\Orm\Currency::loadByWhere($currency_data);

            $currency_data['percent'] = $curr_xml->attributes()->plus?(string)$curr_xml->attributes()->plus:0;
            $currency_data['default'] = $curr_xml->attributes()->rate == 1?1:0;
            $currency_data['ratio'] = is_numeric(str_replace('.', ',', $curr_xml->attributes()->rate))?str_replace('.', ',', $curr_xml->attributes()->rate):'(NULL)';
            
            //если нет валюты, добавляем ее
            if(!$currency->id){
                $currency->getFromArray($currency_data)->insert(true);
            };
        }
        
        $reader->close();
        
        return true;
    }
    
    /**
    * Импортирует категории товаров
    * 
    * @param array $step_data - параметры импорта
    * @return array | bool
    */
    private function stepCategory($step_data)
    {
        //id категорий
        $exists_dir = \RS\Orm\Request::make()
                ->from(new \Catalog\Model\Orm\Dir())
                ->where(array('site_id' => $this->site_id))
                ->where('xml_id LIKE \''.self::YML_ID_PREFIX.'%\'')
                ->exec()->fetchSelected('xml_id', 'id');
                
        $reader = $this->loadReader('category');
        $offset = 0;
        do{            
            if(++$offset < $step_data['offset']) continue;

            $category = new \SimpleXMLElement( $reader->readOuterXML() );
            
            $parent_dir = (int)$category->attributes()->parentId ? $exists_dir[self::YML_ID_PREFIX.(int)$category->attributes()->parentId] : 0;
            $xml_id = self::YML_ID_PREFIX.(int)$category->attributes()->id;
            $uniq_postfix = hexdec(substr(md5((int)$category->attributes()->id), 0, 4));
            
            $dir = new \Catalog\Model\Orm\Dir();
            $dir['parent']      = $parent_dir;
            $dir['public']      = 1;
            $dir['level']       = 0;
            $dir['weight']      = 0;
            $dir['xml_id']      = $xml_id;
            $dir['site_id']     = $this->site_id;
            $dir['meta_title']  = $dir['meta_keywords'] = $dir['meta_description'] = $dir['description'] = '';
            $dir['name']        = (string) $category[0];
            $dir['alias']       = \RS\Helper\Transliteration::str2url((string) $category[0])."-".$uniq_postfix;
            $dir->insert(true, array('alias', 'parent'), array('name', 'xml_id', 'site_id'));
            $exists_dir[$xml_id] = $dir['id'];
            
            if ($dir->getLocalParameter('duplicate_updated')) {
                $this->storage['statistic']['updated_categories']++;
            } else {
                $this->storage['statistic']['inserted_categories']++;
            }
            
        } while(!($timeout = $this->checkTimeout()) && $reader->next('category'));
        $reader->close();

        $this->flushLocalStorage();
        
        if ($timeout) {
            return array(
                'offset' => $offset,
                'percent' => round($offset / $this->storage['yml_info']['category_count'] * 100)
            );
        }
        return true;
    }
    
    /**
    * Импортирует категории товаров
    * 
    * @param array $step_data - параметры импорта
    * @return array | bool
    */
    private function stepProduct($step_data)
    {
        //Загружаем справочники
        if ($step_data['offset'] == 0) {
            $this->saveLocalKey('list', $this->loadLists());
        }
        
        $reader = $this->loadReader('offer');
        $offset = 0;
        
        do {
            if(++$offset < $step_data['offset']) continue;
            
            $product = $this->importProduct($reader);
        
            if ($product->getLocalParameter('duplicate_updated')) {
                $this->storage['statistic']['updated_offers']++;
            } else {
                $this->storage['statistic']['inserted_offers']++;
            }            
                        
        } while(!($timeout = $this->checkTimeout()) && $reader->next('offer'));
        $reader->close();
        
        $this->flushLocalStorage();
        
        if ($timeout) {
            return array(
                'offset' => $offset,
                'percent' => round($offset / count($this->storage['yml_info']['products_xml_ids']) * 100)
            );
        }
        return true;
    }
    
    /**
    * Импортирует рекомендуемые товары
    * 
    * @param array $step_data - параметры импорта
    * @return array | bool
    */
    private function stepRecommended($step_data)
    {
        if ($step_data['offset'] == 0) {
            //Создаем справочник xml_id => id
            $this->storage['list']['products'] = $this->loadProductsList();
            $this->saveLocalKey('list', $this->storage['list']);
        }
        
        $reader = $this->loadReader('offer');
        $product = new Orm\Product();
        $offset = 0;
        
        do {
            if(++$offset < $step_data['offset']) continue;
            
            $offer_xml = new \SimpleXMLElement( $reader->readOuterXML() );
            
            if((string) $offer_xml->rec){
                $xml_id = self::YML_ID_PREFIX.intval($offer_xml->attributes()->id);
                
                $recommended_xml = explode(',', (string)$offer_xml->rec);
                $recommended_arr = array();
                foreach($recommended_xml as $offer_id){
                    $recommended_arr['product'][] = $this->storage['list']['products'][self::YML_ID_PREFIX.$offer_id];
                }
                
                if(!empty($recommended_arr)) {
                    \RS\Orm\Request::make()
                        ->update($product)
                        ->set(array(
                            'recommended' => serialize($recommended_arr)
                        ))
                        ->where(array(
                            'id' => $this->storage['list']['products'][$xml_id] ,
                            'site_id' => $this->site_id
                        ))->exec();
                }
            }
            
        } while(!($timeout = $this->checkTimeout()) && $reader->next('offer'));
        $reader->close();
        
        if ($timeout) {
            return array(
                'offset' => $offset,
                'percent' => round($offset / count($this->storage['yml_info']['products_xml_ids']) * 100)
            );
        }
        return true;
    }

    /**
    * Импортирует изображения
    * 
    * @param array $step_data - параметры импорта
    * @return array | bool
    */    
    private function stepImages($step_data)
    {        
        $reader = $this->loadReader('offer');
        $photoapi = new \Photo\Model\PhotoApi();
        $offset = 0;
        
        do {            
            $offer_xml = new \SimpleXMLElement( $reader->readOuterXML() );
            if ((bool)$offer_xml->picture) {
                $offer_id = intval($offer_xml->attributes()->id);
                $xml_id = self::YML_ID_PREFIX.$offer_id;
                
                foreach($offer_xml->picture as $picture) {
                    if(++$offset < $step_data['offset']) continue;
                    
                    $product_id = $this->storage['list']['products'][$xml_id];
                    //Проверяем, присутствует ли данное фото у товара
                    $image = \RS\Orm\Request::make()
                        ->from(new \Photo\Model\Orm\Image())
                        ->where(array(
                            'site_id'   => $this->site_id,
                            'extra'     => $xml_id,
                            'filename' => basename($picture),
                            'linkid' => $product_id
                        ))
                        ->object();
                    
                    if (!$image) {
                        if ($photoapi->addFromUrl($picture, 'catalog', $product_id, true, $xml_id, false)) {
                            $this->storage['statistic']['inserted_photos']++;
                        } else {
                            $this->storage['statistic']['not_downloaded_photos']++;
                            $this->writeLog("Offer(id): $offer_id, photo(url): $picture, not uploaded");
                            $photoapi->cleanErrors();                            
                        }
                    } else {
                        $this->storage['statistic']['already_exists_photos']++;
                    }
                }
            }
            
        } while(!($timeout = $this->checkTimeout()) && $reader->next('offer'));
        $reader->close();
        
        $this->flushLocalStorage();
        
        if ($timeout) {
            return array(
                'offset' => $offset,
                'percent' => round($offset / $this->storage['yml_info']['photo_count'] * 100)
            );
        }
        
        return true;
    }
    
    
    /**
    * Возвращает справочники данных
    * @return array
    */
    private function loadLists()
    {
        $list = array();
        $common_where = array('site_id' => $this->site_id);
        //Загружаем категории        
        $list['categories'] = \RS\Orm\Request::make()
                                ->from(new Orm\Dir())
                                ->where($common_where)
                                ->exec()
                                ->fetchSelected('xml_id', 'id');
        
        //Загружаем цены
        $list['costs'] = \RS\Orm\Request::make()
                                ->from(new Orm\Typecost())
                                ->where($common_where)
                                ->exec()
                                ->fetchSelected('title', 'id');
                                
        //Загружаем бренды
        $list['brands'] = \RS\Orm\Request::make()
                                ->from(new Orm\Brand())
                                ->where($common_where)
                                ->exec()
                                ->fetchSelected('title', 'id');
                                
        //Загружаем валюты
        $list['currencies'] = \RS\Orm\Request::make()
                                ->from(new Orm\Currency())
                                ->where($common_where)
                                ->exec()
                                ->fetchSelected('title', 'id');
        
        return $list;
    }
    
    /**
    * Возвращает справочник, содержащий связь xml_id => id
    * 
    * @return array
    */
    private function loadProductsList()
    {
        $xml_ids = array();
        foreach($this->storage['yml_info']['products_xml_ids'] as $xml_id) {
            $xml_ids[] = self::YML_ID_PREFIX.$xml_id;
        }
        
        $products = \RS\Orm\Request::make()
            ->select('xml_id, id')
            ->from(new Orm\Product())
            ->whereIn('xml_id', $xml_ids)
            ->where(array(
                'site_id' => $this->site_id
            ))
            ->exec()->fetchSelected('xml_id', 'id');
        
        return $products;
    }
    
    
    /**
     * Загружает даннные из файла в reader
     * 
     * @return boolean
     */
    private function loadReader($seek_to_element = null)
    {
        $reader = new \XMLReader();
        if (!$reader->open($this->yml_folder.'/'.$this->yml_name)) {
            throw new \RS\Exception(t('Не удается открыть YML файл')." '".$this->yml_name."'", 0);
        }
        
        if ($seek_to_element) {
            while($reader->read() && ($reader->name != $seek_to_element)){}
        }
        
        return $reader;
    }
    
    /**
     * Формирует массив id товаров и категорий
     * 
     * @return void
     */
    private function prepareImport()
    {
        $product_xml_ids = array();
        $category_count = 0;
        $photo_count = 0;
        
        $reader = $this->loadReader();
        while($reader->read()){
            if ($reader->nodeType == \XMLReader::ELEMENT) {
                if ($reader->name == 'category') {
                    $category_count++;
                }    
                
                elseif ($reader->name == 'offer') {
                    $product_xml_ids[] = (int)$reader->getAttribute('id');
                }
                
                elseif ($reader->name == 'picture') {
                    $photo_count++;
                }
            }
        }    
        $reader->close();
        
        return array(
            'products_xml_ids' => $product_xml_ids,
            'category_count' => $category_count,
            'photo_count' => $photo_count
        );
    }

    
    /**
     * Проверка времени выполнения скрипта, при превышении сохраняет состояние
     * 
     * @param type $cnt
     * @return boolean
     */
    private function checkTimeout()
    {
        return ($this->timeout && time() >= $this->start_time + $this->timeout);            
    }
    
    /**
     * 
     * @return void
     */
    function checkWriteRights()
    {
        return \RS\AccessControl\Rights::CheckRightError($this, ACCESS_BIT_WRITE);
    }


    /**
     * Обновляет продукты
     * 
     * @return void
     */
    private function importProduct($reader)
    {
        $offer_xml = new \SimpleXMLElement( $reader->readOuterXML() );
        $xml_id = self::YML_ID_PREFIX.intval($offer_xml->attributes()->id);
        
        $product = new Orm\Product();
    
        // Импортируем цены в таблицу product_x_cost
        $excost_array = array();
        if(isset($offer_xml->price)) {
            
            $excost_array[ \Catalog\Model\CostApi::getDefaultCostId() ] = array(
                'cost_original_val'         => (string) $offer_xml->price,
                'cost_original_currency'    => $this->storage['list']['currencies'][(string)$offer_xml->currencyId]
            );
            
            if($offer_xml->oldprice && \Catalog\Model\CostApi::getOldCostId() ) {
                $excost_array[ \Catalog\Model\CostApi::getOldCostId() ] = array(
                    'cost_original_val'         => (string) $offer_xml->oldprice,
                    'cost_original_currency'    => $this->storage['list']['currencies'][(string)$offer_xml->currencyId]
                );
            }
            
            $product['excost'] = $excost_array;
        }
        
        $dir_xml_id = self::YML_ID_PREFIX.(int)$offer_xml->categoryId;
        
        //Если товар не связан с категорией, создаем для него категорию
        if ((int)$offer_xml->categoryId == 0 && !isset($this->storage['list']['categories'][$dir_xml_id])) {
            $dir = new Orm\Dir();
            $dir['name'] = 'Без категории';
            $dir['parent'] = 0;
            $dir['xml_id'] = $dir_xml_id;
            $dir->insert();
            $this->storage['list']['categories'][$dir_xml_id] = $dir['id'];
        }        
        
        $dir_id = $this->storage['list']['categories'][$dir_xml_id];
        
        //Добавление бренда
        if((string) $offer_xml->vendor) {
            //Создается бренд, если его не существует
            $vendor_title = (string) $offer_xml->vendor;
            if( !isset($this->storage['list']['brands'][$vendor_title]) ) {
                $vendor = new \Catalog\Model\Orm\Brand();
                $vendor['title'] = $vendor_title;
                $vendor['site_id'] = $this->site_id;
                $vendor->insert();
                
                $this->storage['list']['brands'][$vendor_title] = $vendor['id'];
            }
            $product['brand_id'] = $this->storage['list']['brands'][$vendor_title];
        }
        
        $product['xml_id']            = $xml_id;
        $product['public']            = (string) $offer_xml->attributes()->available == 'false' ? 0 : 1;
        $product['title']             = (string) $offer_xml->name;
        $product['description']       = 
        $product['short_description'] = (string) $offer_xml->description;
        $product['xdir']              = array($dir_id);
        $product['maindir']           = (int) $dir_id;
        $product['barcode']           = (string) $offer_xml->attributes()->vendorCode;
        
        $uniq_postfix = hexdec(substr(md5($xml_id), 0, 4));
        $product['alias'] = \RS\Helper\Transliteration::str2url($product['title'], true, 140)."-".$uniq_postfix;
        
        $on_duplicate_update_fields = array('xml_id', 'title', 'description', 'short_description', 'public', 'maindir', 'barcode');
        
        $product->insert(false, $on_duplicate_update_fields, array('site_id', 'xml_id'));    
        $this->updateProductParams($offer_xml, $product['id']);
        
        return $product;
    }


    /**
     * Обновляет характеристики продуктов
     * 
     * @param type $xml_id
     * @param type $product_id
     * @return void
     */
    private function updateProductParams(\SimpleXMLElement $offer_xml, $product_id)
    {
        //Удаляем свойства продукта
        \RS\Orm\Request::make()
            ->delete()
            ->from(new \Catalog\Model\Orm\Property\Link)
            ->where(array(
                'site_id'       => $this->site_id,
                'product_id'    => $product_id,
            ))
            ->where('xml_id LIKE \''.self::YML_ID_PREFIX.'%\'')
            ->exec();
        
        foreach($offer_xml->param as $param){
            $xml_id = $this->genXmlId((string) $param->attributes()->name);
            
            $product_property = new \Catalog\Model\Orm\Property\Item();
            
            $product_property['site_id']      = $this->site_id; 
            $product_property['type']         = 'string';
            $product_property['title']        = (string) $param->attributes()->name;
            $product_property['xml_id']       = $xml_id;
            $product_property->insert(false, array('title', 'type'), array('xml_id', 'site_id'));
            
            $product_property_link = new \Catalog\Model\Orm\Property\Link();
            
            $product_property_link['site_id']     = $this->site_id; 
            $product_property_link['prop_id']     = $product_property['id'];
            $product_property_link['product_id']  = $product_id;
            $product_property_link['val_str']     = (string) $param[0];
            $product_property_link['xml_id']      = $this->genXmlId($xml_id.(string) $param[0]);
            
            $product_property_link->insert(false, array('val_str'), array('xml_id', 'site_id', 'product_id'));
        }
        
        //Сохраняются свойства из тегов не в property
        foreach($offer_xml as $node){
            if(!($node_name = $this->getParamsField($node->getName())) || empty($node[0])) continue;
            
            $xml_id = $this->genXmlId($node_name);
            if($node->getName() == 'dataTour' && is_array($node[0])){
                $node_val = join(', ', $node[0]);
            }else{
                $node_val = ((string) $node[0] == 'true')?'да':((string) $node[0] == 'false')?'нет':(string) $node[0];
            }
            
            $product_property = new \Catalog\Model\Orm\Property\Item();
            $product_property['site_id']      = $this->site_id; 
            $product_property['type']         = 'string';
            $product_property['title']        = $node_name;
            $product_property['xml_id']       = $xml_id;
            $product_property->insert(false, array('title', 'type'), array('xml_id', 'site_id'));
            
            $product_property_link = new \Catalog\Model\Orm\Property\Link();
            $property_val = $node_val;
            $product_property_link['val_str']     = $property_val;
            $product_property_link['site_id']     = $this->site_id; 
            $product_property_link['prop_id']     = $product_property['id'];
            $product_property_link['product_id']  = $product_id;
            $product_property_link['xml_id']      = $this->genXmlId($xml_id.$property_val);
            $product_property_link->insert(false, array('val_str'), array('xml_id', 'site_id', 'product_id'));
        }
    }
    
    /**
     * Генерирует xml_id
     * 
     * @param type $str
     * @return void
     */
    public function genXmlId($str)
    {
        return self::YML_ID_PREFIX.crc32((string) $str);
    }
    
    /**
     * Очищает временную директорию
     * 
     * @return void
     */
    function cleanTemporaryDir()
    {
        @unlink($this->yml_folder.'/'.$this->yml_name);
        @unlink($this->yml_folder.'/'.$this->tmp_data_file);
    }

    /**
    * Возвращает сопоставленную тегу характеристику
    * 
    * @param string $param - наименование тега
    * @return string | false
    */
    private function getParamsField($param)
    {
        return (isset($this->params_fields[$param])) ? t($this->params_fields[$param]) : false;
    }
    
    /**
    * Сохраняет строку в log файл
    * 
    * @param string $message сообщение
    * @param bool $append - Если true, то добавит строку в конец файла, иначе пересоздаст файл
    */
    private function writeLog($message, $append = true)
    {
        file_put_contents($this->log_file, date('Y-m-d H:i:s ').$message."\n", $append ? FILE_APPEND : null);
    }
    
    /**
    * Возвращает относительную ссылку на лог файл
    * @return string
    */
    function getLogLink()
    {
        return $this->log_file_rel;
    }
    
    /**
    * Возвращает массив со статистическими данными об импорте
    * @return array
    */
    function getStatistic()
    {
        return $this->storage['statistic'];
    }
}