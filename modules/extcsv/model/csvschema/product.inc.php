<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace ExtCsv\Model\CsvSchema;
use \RS\Csv\Preset,
    \ExtCsv\Model\CsvPreset as CustomPreset,
    \Catalog\Model\Orm;

/**
* Схема импорта/экспорта в CSV файл товаров
*/
class Product extends \RS\Csv\AbstractSchema
{
    function __construct()
    {        
        $config = \RS\Config\Loader::byModule($this);
        
        parent::__construct(
            new CustomPreset\Base(array(
                'ormObject'     => new Orm\Product(),
                'excludeFields' => array(
                    'site_id', 'processed', 'brand_id', 'unit', 'recommended', 'concomitant', 'maindir'
                ),
                'multisite'    => true,
                'nullFields'   => array('id'),
                'searchFields' => $config['csv_id_fields'],
            )),
            array(
                new Preset\LinkedTable(array(
                    'ormObject'        => new Orm\Unit(),
                    'fields'           => array('stitle'),
                    'titles'           => array('stitle' => t('Единица измерения')),
                    'idField'          => 'id',
                    'multisite'        => true,                
                    'linkForeignField' => 'unit',
                    'linkPresetId'     => 0,
                    'linkDefaultValue' => 0
                )),
                new Preset\LinkedTable(array(
                    'ormObject' => new Orm\Brand(),
                    'fields' => array('title'),
                    'titles' => array('title' => t('Бренд')),
                    'idField' => 'id',
                    'multisite' => true,                
                    'linkForeignField' => 'brand_id',
                    'linkPresetId' => 0,
                    'linkDefaultValue' => 0
                )),                                
                new CustomPreset\PhotoBlock(array(
                    'typeItem'     => Orm\Product::IMAGES_TYPE,
                    'linkPresetId' => 0,
                    'linkIdField'  => 'id'
                )),
                new CustomPreset\Catalog(array(
                    'ormObject'   => new Orm\Dir(),
                    'idField'     => 'id',
                    'delimeter'   => '/',
                    'manylinkOrm' => new Orm\Xdir(),
                    'title'       => t('Категории'),
                    
                    'manylinkIdField'        => 'dir_id',
                    'manylinkForeignIdField' => 'product_id',
                    'linkPresetId'           => 0,
                    'linkIdField'            => 'id',

                    'rootValue'       => 0,
                    'treeField'       => 'name',
                    'treeParentField' => 'parent',
                    'multisite'       => true,
                    'arrayField'      => 'xdir',
                )),
                new CustomPreset\Property(array(
                    'title'        => t('Характеристики'),
                    'linkPresetId' => 0,
                    'delimeter'    => ';',
                    'linkIdField'  => 'id',
                    'multisite'    => true
                )),
                new CustomPreset\Cost(array(
                    'linkPresetId' => 0,
                    'linkIdField' => 'id',
                    'arrayField' => 'xcost',
                )),
                new Preset\ProductsSerialized(array(
                    'title'              => t('Рекомендуемые товары'),
                    'exportPattern'      => '{products}',
                    'exportProductField' => $config['csv_recommended_id_field'],
                    'delimeter'          => ',',
                    'linkForeignField'   => 'recommended',
                    'linkIdField'        => 'id',
                    'linkPresetId'       => 0
                )),
                new Preset\ProductsSerialized(array(
                    'title'              => t('Сопутствующие товары'),
                    'exportPattern'      => '{products}',
                    'exportProductField' => $config['csv_concomitant_id_field'],
                    'delimeter'          => ',',
                    'linkForeignField'   => 'concomitant',
                    'linkIdField'        => 'id',
                    'linkPresetId'       => 0
                )),
                new \Catalog\Model\CsvPreset\MultiOffers(array(
                    'title' => t('Многомерные комплектации'),
                    'linkPresetId' => 0,
                    'linkIdField' => 'id',
                )),
            ),
            array(
                'beforeLineImport' => array(__CLASS__, 'beforeLineImport')
            )            
        );
    }
    
    /**
    * Возвращает запрос для базовой выборки элементов из БД для экспорта и импорта
    * 
    * @return \RS\Orm\Request
    */
    function getBaseQuery()
    {
       $parent_dir = $this->getParamByKey('dir'); //Папка в которой ищем товары
       
       if (!$this->query) {
            //Получим все подкатегории для поиска
            $dir_api = new \Catalog\Model\Dirapi();
            $ids = $dir_api->FindSubFolder(array(
                $parent_dir
            ));
           
            //Дописываем базовый пресет для выборки с учётом категории
            /**
            * @var \RS\Orm\Request
            */
            $query       = $this->base_preset->getSelectRequest();
            $query->asAlias('A')
                  ->join(new \Catalog\Model\Orm\Xdir(), 'X.product_id=A.id','X')
                  ->groupby('A.id')
                  ->whereIn('dir_id',$ids); 
            $this->query = $query;  
       }
       return $this->query;
    }
    
    public static function beforeLineImport($_this)
    {
        static 
            $default_warehouse_id;
        
        if ($default_warehouse_id === null) {
            $default_warehouse_id = \Catalog\Model\WareHouseApi::getDefaultWareHouse()->id;
        }        
        //Устанавливаем временный id
        $row = &$_this->getPreset(0)->row;
        
        if (isset($row['num'])) {
            //Если задан остаток товара, то он устанавливается всегда основной 
            //комплектации для склада по умолчанию
            $row['offers'] = array(
                'main' => array(
                    'stock_num' => array(
                        $default_warehouse_id => $row['num']
                    )
                )
            );
        }        
        
        if ( empty($row['id'])) {
            $time = -time();            
            $row['id']     = $time;
            $row['_tmpid'] = $time;
        }
    }    

/**
    * Возвращает возможные колонки для идентификации продукта
    * 
    * @return array
    */
    public static function getPossibleIdFields()
    {
        $product = new \Catalog\Model\Orm\Product();
        $fields = array_flip(array('id', 'title', 'barcode', 'xml_id', 'alias'));
        foreach($fields as $k => $v) {
            $fields[$k] = $product['__'.$k]->getTitle();
        }
        return $fields;
    }    
}

?>
