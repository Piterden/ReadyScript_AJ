<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Model\Orm;
use \RS\Orm\Type;

/**
* Категория товаров
* @ingroup Catalog
*/
class Dir extends \RS\Orm\OrmObject
{
    protected static
        $table = 'product_dir',
        $deleted_folder_id, //id папки, в которую скидываются все товары из удаленных папок
        $product_xdir;
    
    function _init()
    {                
        parent::_init()->append(array(
            t('Основные'),
                    'site_id' => new Type\CurrentSite(),
                    'xml_id' =>  new Type\Varchar(array(
                        'maxLength' => '255',
                        'description' => t('Идентификатор в системе 1C'),
                        'visible' => false,
                    )),
                    'name' => new Type\Varchar(array(
                        'maxLength' => '255',
                        'description' => t('Название категории'),
                        'Checker' => array('chkEmpty', t('Название категории не может быть пустым')),
                        'attr' => array(array(
                            'data-autotranslit' => 'alias'
                        )),
                        'rootVisible' => false
                    )),
                    'alias' => new Type\Varchar(array(
                        'maxLength' => '150',
                        'Checker' => array('chkalias', null),
                        'description' => t('Псевдоним'),
                        'rootVisible' => false,
                        'meVisible' => false
                    )),
                    'parent' => new Type\Integer(array(
                        'maxLength' => '11',
                        'description' => t('Родитель'),
                        'List' => array(array('\Catalog\Model\DirApi', 'selectList')),
                        'specVisible' => false,
                        'rootVisible' => false
                    )),
                    'public' => new Type\Integer(array(
                        'maxLength' => '1',
                        'description' => t('Публичный'),
                        'CheckboxView' => array(1,0),
                        'rootVisible' => false
                    )),
                    'sortn' => new Type\Integer(array(
                        'maxLength' => '11',
                        'description' => t('Порядк. N'),
                        'visible' => false,
                        'rootVisible' => false
                    )),
                    'is_spec_dir' => new Type\Varchar(array(
                        'maxLength' => '1',
                        'description' => t('Это спец. список?'),
                        'visible' => false,
                        'rootVisible' => false,
                        'hint' => 'Например: новинки, популярные товары, горячие и т.д.',
                    )),
                    'itemcount' => new Type\Integer(array(
                        'description' => t('Количество элементов'),
                        'visible' => false,
                        'rootVisible' => false
                    )),
                    'level' => new Type\Integer(array(
                        'description' => t('Уровень вложенности'),
                        'index' => true,
                        'visible' => false,
                        'rootVisible' => false
                    )),
                    'image' => new Type\Image(array(
                        'max_file_size' => 10000000,
                        'allow_file_types' => array('image/pjpeg', 'image/jpeg', 'image/png', 'image/gif'),
                        'description' => t('Изображение'),
                        'specVisible' => true,
                        'rootVisible' => false
                    )),
                    'weight' => new Type\Integer(array(
                        'description' => t('Вес товара по умолчанию, грамм'),
                        'rootVisible' => false
                    )),
                    '_alias' => new Type\Varchar(array(
                        'maxLength' => '50',
                        'runtime' => true,
                        'visible' => false,
                        'rootVisible' => false
                    )),
                    '_class' => new Type\Varchar(array(
                        'maxLength' => '50',
                        'runtime' => true,
                        'visible' => false,
                        'rootVisible' => false
                    )),            
                    'closed' => new Type\Integer(array(
                        'maxLength' => '1',
                        'runtime' => true,
                        'visible' => false,
                        'rootVisible' => false,
                    )),
                    'prop' => new Type\Mixed(array(
                        'visible' => false,
                        'rootVisible' => false
                    )),
                    'properties' => new Type\Mixed(array(
                        'visible' => false,
                    )),
                    'processed' => new Type\Integer(array(
                        'maxLength' => '2',
                        'visible' => false,
                    )),
            
            t('Мета-тэги'),
                    'meta_title' => new Type\Varchar(array(
                        'maxLength' => '1000',
                        'description' => t('Заголовок'),
                        'rootVisible' => false
                    )),            
                    'meta_keywords' => new Type\Varchar(array(
                        'maxLength' => '1000',
                        'description' => t('Ключевые слова'),
                        'rootVisible' => false
                    )),
                    'meta_description' => new Type\Varchar(array(
                        'maxLength' => '1000',
                        'viewAsTextarea' => true,
                        'description' => t('Описание'),
                        'rootVisible' => false
                    )),
            t('Характеристики'),
                    '__property__' => new Type\UserTemplate('%catalog%/form/dir/property.tpl'),
                    
            t('Описание'),
                    'description' => new Type\Richtext(array(
                        'description' => t('Описание категории'),
                        'rootVisible' => false
                    ))
            
        ));
        
        $this->addIndex(array('site_id', 'parent'));
        $this->addIndex(array('site_id', 'xml_id'), self::INDEX_UNIQUE);
        $this->addIndex(array('site_id', 'alias'), self::INDEX_UNIQUE);
    }
    
    /**
    * Возвращает отладочные действия, которые можно произвести с объектом
    * 
    * @return RS\Debug\Action[]
    */
    function getDebugActions()
    {
        return array(
            new \RS\Debug\Action\Edit(\RS\Router\Manager::obj()->getAdminPattern('edit_dir', array(':id' => '{id}'), 'catalog-ctrl')),
            new \RS\Debug\Action\Delete(\RS\Router\Manager::obj()->getAdminPattern('del_dir', array(':chk[]' => '{id}'), 'catalog-ctrl'))        
        );
    }
    
    function _initDefaults()
    {
        $this['is_spec_dir'] = 'N'; //Если свойство не задано, то его значение будет N = Нет
    }
    
    /**
    * При создании записи sortn - ставим максимальный, т.е. добавляем фото в конец.
    */
    function beforeWrite($save_flag)
    {
        if ($save_flag == self::INSERT_FLAG)
        {
            if (!$this->isModified('sortn')) {
                $this['sortn'] = \RS\Orm\Request::make()
                    ->select('MAX(sortn) maxid')
                    ->from($this)
                    ->exec()->getOneField('maxid', 0) + 1;
            }
        }
        
        if (empty($this['itemcount'])) $this['itemcount'] = 0;
        
        //$api = new \Catalog\Model\Dirapi();
        //$this['level'] = ($this['parent']>0) ? count($api->queryParents($this['parent'])) : 0;
        
        if ($this['xml_id'] === '') {
            unset($this['xml_id']);
        }
        
        if (empty($this['alias'])) {
            $this['alias'] = null;
        }

        return true;
    }    
    
    /**
    * Функция срабатывает после сохранения.
    * 
    * @param string $flag - update или insert
    */
    function afterWrite($flag)
    {
        if ($this->isModified('parent')) {
            \Catalog\Model\Dirapi::updateLevels();
        }
        if ($flag==self::UPDATE_FLAG){
           \RS\Cache\Manager::obj()->invalidateByTags(CACHE_TAG_UPDATE_CATEGORY); 
        }
    }
    
    /**
    * Возвращает клонированный объект доставки
    * @return Delivery
    */
    function cloneSelf()
    {
        $this->fillProperty();
        /**
        * @var \Catalog\Model\Orm\Dir
        */
        $clone = parent::cloneSelf();
        //Клонируем фото, если нужно
        if ($clone['image']){
           /**
           * @var \RS\Orm\Type\Image
           */
           $clone['image'] = $clone->__image->addFromUrl($clone->__image->getFullPath());
        }
        unset($clone['alias']);
        unset($clone['xml_id']);
        unset($clone['sortn']);
        $clone['itemcount'] = 0;
        return $clone;
    }
    
    /**
    * Действия после загрузки самого объекта
    */
    function afterObjectLoad()
    {
        $this['_alias'] = empty($this['alias']) ? (string)$this['id'] : $this['alias'];
        $this['_class'] = $this['is_spec_dir'] == 'Y' ? 'specdir' : '';
    }
    
    /**
    * Возвращает список характеристик из поля prop в виде списка объектов
    */
    function getPropObjects()
    {
        return $this['properties'];
    }    
    
    function fillProperty()
    {
        if ($this['properties'] === null) {
            $propapi = new \Catalog\Model\PropertyApi();
            $this['properties'] = $propapi->getGroupProperty($this['id']);
        }
        return $this['properties'];
    }
    
    function alias()
    {
        return urlencode($this['_alias']);
    }
    
    /**
    * Удаление категории товара.
    */
    function delete()
    {        
        $ids = \RS\Orm\Request::make()
            ->select('product_id')
            ->from(new Xdir())
            ->where(array('dir_id' => $this['id']))
            ->exec()
            ->fetchSelected(null, 'product_id');

        if (!empty($ids)) {
            $api = new \Catalog\Model\Api();
            $api->multiDelete($ids, $this['id']); //Удаляем товары
        }        
		
    	return parent::delete(); //Удаляем текущий объект из базы.
	}
    
    
    /**
    * Перемещает товары из удаляемой папки в папку для удаленных товаров.
    */
    protected function _moveProducts()
    {
        if (!isset(self::$deleted_folder_id)) {
            $api = new \Catalog\Model\Dirapi();
            $dir = $api->getByAlias('deleted');
            if ($dir) {
                self::$deleted_folder_id = $dir['id'];
            } else {//Создаем эту папку при необходимости
                $newdir = $api->getElement();
                $newdir['id'] = '1';
                $newdir['name'] = 'Товары из удаленных папок';
                $newdir['parent'] = 0;
                $newdir['public'] = 0;
                $newdir['alias'] = 'deleted';
                $newdir->insert();
                self::$deleted_folder_id = $newdir['id'];
            }
        }
        
        //Переносим товары в спец. папку. Помним, что товар уже может присутствовать там
        \RS\Orm\Request::make()
            ->update(new Xdir(), true)
            ->set(array('dir_id' => self::$deleted_folder_id))
            ->where(array('dir_id' => $this['id']))
            ->exec();

        //Если в предыдущем запросе возникла ошибка Duplicate, то чистим связи с этой директорией.            
        \RS\Orm\Request::make()
            ->delete()
            ->from(new Xdir())
            ->where(array('dir_id' => $this['id']))
            ->exec();
    }
    
    /**
    * Возвращает объект фото-заглушку
    */
    function getImageStub()
    {
        return new \Photo\Model\Stub();
    }
    
    /**
    * Возвращает главную фотографию
    * @return \Photo\Model\Orm\Image
    */
    function getMainImage($width = null, $height = null, $type = 'xy')
    {
        $img = $this['image'] ? $this->__image : $this->getImageStub();
        
        return ($width === null) ? $img : $img->getUrl($width, $height, $type);
    }
    
    /**
    * Возвращает путь к странице со списком товаров
    * @return string
    */
    function getUrl($absolute = false)
    {
        return \RS\Router\Manager::obj()->getUrl('catalog-front-listproducts', array('category' => $this['_alias']), $absolute);
    }
    
}

