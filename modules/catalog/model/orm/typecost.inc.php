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
* Класс типов цены
*/
class Typecost extends \RS\Orm\OrmObject
{
    const
        ROUND_DISABLED = 0,  //Не округлять
        ROUND_ROUND_DEC = 1,//Округлять до 1 десятой
        ROUND_ROUND_INT = 2, //Округлять в большую сторону до целых
        ROUND_CEIL_INT = 4; //Округлять в большую сторону до целых
    
    protected static
        $table = 'product_typecost';
            
    protected static $xcost;
    
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'xml_id' =>  new Type\Varchar(array(
                'maxLength' => '255',
                'description' => t('Идентификатор в системе 1C'),
                'visible' => false,
            )),
            'title' => new Type\Varchar(array(
                'maxLength' => '150',
                'description' => t('Название'),
            )),
            'type' => new Type\Enum(array('manual', 'auto'), array(
                'listfromarray' => array(array(
                    'manual' => 'manual', 
                    'auto' => 'auto'
                )),
                'default' => 'manual',
                'description' => t('Тип цены'),
                'hint' => t('Допустимо использование отрицательные и дробные значения. <br>Например: -35.5%'),
                'template' => '%catalog%/form/typecost/typecost.tpl'
            )),
            'val_znak' => new Type\Varchar(array(
                'description' => t('Знак значения'),
                'maxLength' => '1',
                'Attr' => array(array('size' => '1')),
                'ListFromArray' => array(array(
                    '+' => '+', 
                    '-' => '-'
                )),
                'visible' => false,
            )),
            'val' => new Type\Real(array(
                'maxLength' => '11',
                'description' => t('Величина увеличения стоимости'),
                'Attr' => array(array('size' => '5')),
                'visible' => false,
            )),
            'val_type' => new Type\Enum(array('sum', 'percent'), array(
                'description' => t('Тип увеличения стоимости'),
                'ListFromArray' => array(array(
                    'sum' => 'единиц',     
                    'percent' => '%'
                )),
                'visible' => false,
            )),
            'depend' => new Type\Integer(array(
                'maxLength' => '11',
                'description' => t('Цена, от которой ведется расчет'),
                'List' => array(array('\Catalog\Model\CostApi','getEditList')),
                'visible' => false,
            )),
            'round' => new Type\Integer(array(
                'description' => t('Округление'),
                'ListFromArray' => array(array(    
                    self::ROUND_DISABLED => t('Не округлять'),
                    self::ROUND_ROUND_DEC => t('Округлять до одной десятой'),
                    self::ROUND_ROUND_INT => t('Округлять до целых'),
                    self::ROUND_CEIL_INT => t('Округлять в большую сторону до целых')
                )),
            )),
            '_calcvalue' => new Type\Mixed(array(
                'description' => t('Расчитанное значение цены для товара')
            )),
        ));
    
        $this->addIndex(array('site_id', 'xml_id'), self::INDEX_UNIQUE);
        
    }
    
    /**
    * Возвращает id закупочной цены.
    */
    function getBaseCostId()
    {
        return 1;
    }
    
    /**
    * Функция срабатывает после записи объекта
    * 
    * @param string $flag - флаг текущего действия. update или insert.
    * 
    */
    function afterWrite($saveflag)
    {
        if ($saveflag == self::UPDATE_FLAG) {
            if ($this['type'] == 'auto') $this->checkDepends();
        }
        
        // Если цена по умолчанию в настройках модуля не задана 
        //или задана неверно, то устанавливаем эту ($this) цену по молчинию
        $config = \RS\Config\Loader::byModule($this);
        $is_cost_correct = $this->exists($config->default_cost);
        if(!$is_cost_correct){
            $config->default_cost = $this->id;              // Сохраняем эту ($this) цену в конфиг модуля
            $config->update();
        }
    }
    
    function delete()
    {
        $count = \RS\Orm\Request::make()->from($this)
            ->where(array('site_id' => \RS\Site\Manager::getSiteId()))->count();
        
        if ($count>1) {
            \RS\Orm\Request::make()
                ->delete()
                ->from(new Xcost())
                ->where(array('cost_id' => $this['id']))
                ->exec();
            
            //Перед удалением, переводим зависимые элементы к типу "Задается вручную"
            $this->checkDepends();
            return parent::delete();
        } else {
            return $this->addError(t('Должен присутствовать хотя бы один тип цен'));
        }
    }
    
    /**
    * Переводит зависимые элементы к типу "Задается вручную", если еобходимо
    */
    function checkDepends()
    {
        \RS\Orm\Request::make()
            ->update($this)
            ->set(array('type' => 'manual'))
            ->where(array('depend' => $this['id']))
            ->exec();
    }
    
    /**
    * Возвращает значение, округленное до параметров, заданное для данного типа цен.
    * @param float $value
    */
    public function getRounded($value)
    {
        switch($this['round']) {
            case self::ROUND_ROUND_DEC: $value = round($value,1); break;
            case self::ROUND_ROUND_INT: $value = round($value); break;
            case self::ROUND_CEIL_INT: $value = ceil($value); break;
        }
        return $value;
    }
    
    /**
    * Возвращает цену, от которой зависит текущая цена
    * @return TypeCost
    */
    function getDependCost()
    {
        return new self($this['depend']);
    }
    
    /**
    * Возвращает клонированный объект оплаты
    * @return Typecost
    */
    function cloneSelf()
    {
        /**
        * @var \Catalog\Model\Orm\Typecost
        */
        $clone = parent::cloneSelf();
        $clone['xml_id'] = null;
        return $clone;
    }
    
}


