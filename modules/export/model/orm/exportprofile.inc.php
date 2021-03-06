<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Export\Model\Orm;
use \RS\Orm\Type,
    \Shop\Model\Orm\UserStatus,
    \Shop\Model\UserStatusApi;


class ExportProfile extends \RS\Orm\OrmObject
{
    protected static
        $table = 'export_profile';
    
    protected
        $order;
    
    function __construct($id = null, $cache = true, Order $order = null)
    {
        parent::__construct($id, $cache);
        $this->order = $order;
    }
        
    function _init()
    {
        parent::_init()->append(array(
                'site_id' => new Type\CurrentSite(),
                'title' => new Type\String(array(
                    'maxLength' => '255',
                    'description' => t('Название'),
                )),
                '__gate_url__' => new Type\Mixed(array(
                    'visible' => true,
                    'description' => t('URL для экспорта'),
                    'template' => '%export%/form/profile/url.tpl'
                )),
                'class' => new Type\String(array(
                    'maxLength' => '255',
                    'description' => t('Класс экспорта'),
                    'list' => array(array('\Export\Model\Api', 'getTypesAssoc')),
                    'visible' => false,
                )),               
                'life_time' => new Type\Integer(array(
                    'description' => t('Период экспорта'),
                    'listfromarray' => array(array(
                            0     => t('При каждом обращении'), 
                            1     => t('1 день'), 
                            5     => t('5 дней'), 
                            10    => t('10 дней'),
                            20    => t('20 дней'),
                    ))
                
                )),
                '_serialized' => new Type\Text(array(
                    'visible' => false,
                )),
                'data' => new Type\ArrayList(array(
                    'visible' => false
                ))
        ));
    }
    
    function beforeWrite($flag)
    {
        $this['_serialized'] = serialize($this['data']);
    }
    
    function afterObjectLoad()
    {
        $this['data'] = @unserialize($this['_serialized']);
        $this->initClass();
    }
    
    /**
    * Возвращает объект профиля экспорта
    * 
    */
    function getTypeObject()
    {
        $type = \Export\Model\Api::getTypeByShortName($this['class']);
        $type->getFromArray((array)$this['data']);
        return $type;
    }
    
    function initClass()
    {
        $class_name = '\Export\Model\ExportType\\'.$this['class'].'\\'.$this['class'];
        $type = new $class_name;
        $this->getPropertyIterator()->appendPropertyIterator( $type->getPropertyIterator()
            ->arrayWrap('data')
            ->setPropertyOptions(array('runtime' => true))
        );
        foreach((array)$this['data'] as $key => $value) {
            $this[$key] = $value;
        }     
    }
    
    /**
    * Возвращает полный путь к файлу, содержащему экспортированные данные
    * 
    * @return string
    */
    public function getCacheFilePath()
    {
        return \Export\Model\Api::getInstance()->getCacheFilePath($this);
    }
    
    /**
    * Возвращатает URL-экспорта для данного профиля
    * 
    * @return string
    */
    public function getExportUrl(){
        $router = \RS\Router\Manager::obj();
        return $router->getUrl('export-front-gate', 
            array(
                'site_id' => \RS\Site\Manager::getSiteId(), 
                'export_id' => $this->id, 
                'export_type' => $this->class
            )
            , true);
    }
    
    /**
    * Возвращает HTML код для блока "список товаров"
    */
    function getProductsDialog()
    {
        return new \Catalog\Model\ProductDialog('data[products]', false, @(array) $this['data']['products']);
    }
    
    /**
    * Возвращает экспортированные данные (XML, CSV, JSON и т.п.)
    * @return string
    */
    function export()
    {
        return $this->getTypeObject()->export($this);
    }
    
    
    /**
    * Возвращает все категории свойств товаров
    * 
    * @return Orm\Property\Dir[]
    */
    function getAllPropertyGroups()
    {
        return \Catalog\Model\PropertyApi::getAllGroups();
    }
    
}
?>
