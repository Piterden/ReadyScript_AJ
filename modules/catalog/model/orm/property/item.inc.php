<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Model\Orm\Property;
use \RS\Orm\Type;

/**
* Core-объект характеристика товара
* @ingroup Catalog
*/
class Item extends \RS\Orm\OrmObject
{
    protected static
        $table = 'product_prop';
    
    protected
        $slider_data;
        
    
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'xml_id' =>  new Type\Varchar(array(
                'maxLength' => '255',
                'description' => t('Идентификатор товара в системе 1C'),
                'visible' => false,
            )),
            'title' => new Type\Varchar(array(
                'maxLength' => '255',
                'index' => true,
                'description' => t('Название характеристики'),
                'checker' => array('chkEmpty', 'Название характеристики не должно быть пустым'),
                'meVisible' => false
            )),
            'type' => new Type\Varchar(array(
                'maxLength' => '10',
                'description' => t('Тип'),
                'Attr' => array(array('size' => 1)),
                'ListFromArray' => array(array(    
                    'int' => 'Число',    
                    'string' => 'Строка',    
                    'list' => 'Список',    
                    'bool' => 'Да/Нет'
                )),
                'meVisible' => false
            )),
            '_tpl_' => new Type\Mixed(array(
                'description' => '',
                'template' => '%catalog%/form/property/values.tpl',
                'visible' => true,
                'meVisible' => false
            )),
            'values' => new Type\Text(array(
                'maxLength' => '2000',
                'description' => t('Возможные значения'),
                'visible' => false
            )),
            'sortn' => new Type\Integer(array(
                'maxLength' => '11',
                'description' => t('Сорт. индекс'),
                'visible' => false,
            )),
            'parent_sortn' => new Type\Integer(array(
                'description' => t('Сорт. индекс группы'),
                'visible' => false,
            )),
            'unit' => new Type\Varchar(array(
                'maxLength' => '30',
                'description' => t('Единица измерения'),
            )),
            'group_id' => new Type\Integer(array(
                'maxLength' => '11',
                'runtime' => true,
                'visible' => false,
            )),
            'product_id' => new Type\Integer(array(
                'maxLength' => '11',
                'runtime' => true,
                'visible' => false,
            )),
            
            'interval_from' => new Type\Real(array(
                'description' => t('Минимальное значение'),
                'Attr' => array(array('size' => 8)),
                'visible' => false,
                'runtime' => true
            )),
            'interval_to' => new Type\Real(array(
                'description' => t('Максимальное значение'),
                'Attr' => array(array('size' => 8)),
                'visible' => false,
                'runtime' => true
            )),
            'step' => new Type\Real(array(
                'description' => t('Шаг'),
                'Attr' => array(array('size' => 3)),
                'visible' => false,
                'runtime' => true
            )),
            
            'parent_id' => new Type\Integer(array(
                'description' => t('Группа'),
                'allowEmpty' => false,
                'List' => array(array('\Catalog\Model\PropertyDirApi', 'selectList')),
            )),
            'hidden' => new Type\Integer(array(
                'description' => t('Не отображать в карточке товара'),
                'checkboxView' => array(1,0),
                'maxLength' => 1,
                'default' => 0
            )),
            'value' => new Type\Mixed(array(
                'runtime' => true,
                'visible' => false
            )),
            'is_my' => new Type\Mixed(array(
                'runtime' => true,
                'visible' => false
            )),            
            'public' => new Type\Integer(array(
                'runtime' => true,
                'visible' => false
            )),
            'useval' => new Type\Integer(array(
                'runtime' => true,
                'visible' => false
            )),
            'allowed_values' => new Type\ArrayList(array(
                'visible' => false
            ))
        ));

        $this->addIndex(array('site_id', 'xml_id'), self::INDEX_UNIQUE);
    }
        
    function beforeWrite($flag)
    {
        if ($flag == self::INSERT_FLAG) {
            $this['sortn'] = \RS\Orm\Request::make()
                ->select('MAX(sortn) as max')
                ->from($this)
                ->exec()->getOneField('max', 0) + 1;
        }
        
        if ($this['parent_id'] !== null) {
            if ($this['parent_id']>0) {
                $parent_sortn = \RS\Orm\Request::make()
                    ->select('sortn')
                    ->from(new Dir())
                    ->where(array(
                        'id' => $this['parent_id']
                    ))->exec()->getOneField('sortn', 0);
            } else {
                $parent_sortn = 0;
            }
            $this['parent_sortn'] = $parent_sortn; //Сохраняем родительский сорт. индекс            
        }
        
        //Провери на пустые значения
        $this['values'] = trim($this['values']);
    }
    
    /**
    * Функция срабатывает после сохранения характеристики
    * 
    * @param string $flag - update или insert
    */
    function afterWrite($flag)
    {
        if ($flag==self::UPDATE_FLAG){
            \RS\Cache\Manager::obj()->invalidateByTags(CACHE_TAG_UPDATE_CATEGORY); 
        }
    }
    
    function valuesArr($sort = false)
    {
        $arr = preg_split('/[;\n]/', htmlspecialchars_decode($this['values']));
        foreach ($arr as &$v) {
            $v = htmlspecialchars(trim($v));
        }
        if ($sort) {
            sort($arr);
        }
        
        return $arr;
    }
    
    function getAllowedValues()
    {
        return isset($this['allowed_values']) ? $this['allowed_values'] : $this->valuesArr();
    }
    
    function valView($disabled = null)
    {
        $tpl = new \RS\View\Engine();
        $tpl->assign('self', $this);
        $tpl->assign('value', is_null($this['value']) ? $this['defval'] : $this['value'] );
        $tpl->assign('disabled', ($disabled) ? 'disabled="disabled"' : '');
        return $tpl->fetch('%catalog%property_val.tpl');
    }
    
    /**
    * Возвращает читабельное значение характеристики
    */
    function textView()
    {
        $val = is_null($this['value']) ? $this['defval'] : $this['value'] ;
        switch($this['type']) {
            case 'bool': {
                return $val ? t('есть') : t('нет');
            }
            case 'list': {
                return is_array($val) ? implode(', ', $val) : '';
            }
            default:  return $val;
        }
    }
    
    function delete()
    {
        //При удалении свойства, удаляем все связи
        \RS\Orm\Request::make()
            ->delete()
            ->from(new Link())
            ->where(array(
                'prop_id' => $this['id']
            ))
            ->exec();
        return parent::delete();
    }
    
    /**
    * Если тип характеристики - да/нет (checkbox), возвращает true, если отмечен
    */
    function checked()
    {
        if ($this['type'] != 'bool') return false;
        return $this['value'] == 1;
    }
    
    /**
    * Возвращает единицы измерения для ползунка в JavaScript
    */
    function getUnit()
    {
        return '&nbsp;'.$this['unit'];
    }
    
    /**
    * Возвращает данные для потроения шкалы ползунка в JavaScript
    */
    function getScale()
    {
        $data = $this->sliderData();
        return $data['scale'];
    }
    
    /**
    * Возвращает связку процент/значение для шкалы позунка в JavaScript
    */
    function getHeterogeneity()
    {
        $data = $this->sliderData();
        return $data['heterogeneity'];
    }
    
    /**
    * Возвращает порядок округления
    */
    function getRound()
    {
        $dec = strstr($this['step'], ".");
        return ($dec === false) ? 0 : strlen($dec) - 1;
    }
    
    /**
    * Расчитывает данные для ползунка фильтра
    */
    function sliderData()
    {
        if (!isset($this->slider_data)) {
            $step = ($this['step'] != 0) ? $this['step'] : 1;
            
            $delta = ($this['interval_to'] - $this['interval_from']); //разница значений
            $points = floor($delta/$step); //количество значений.
            
            $scale = array(); //шкала
            $heterogeneity = array(); //связь процентов (0-100) со значениями.
            if ($points) {
                $max = 5;
                if ($points < $max) $max = $points;
                
                for($i=0; $i<=$max; $i++) {
                    $percent = (100/$max * $i);
                    $point = round( ($percent/100) * $delta + $this['interval_from'], $this->getRound());
                    $scale[] = $point;
                    if ($percent>0 && $percent<100) {
                        $heterogeneity[] = "\"$percent/$point\"";
                    }
                }
            }
            
            $this->slider_data = array(
                'scale' => implode(',', $scale),
                'heterogeneity' => implode(',', $heterogeneity),
                'delta' => $delta,
                'points' => $points
            );
        }
        return $this->slider_data;
    }
    
    /**
    * Возвращает Url для установки или снятия данного фильтра
    * 
    * @param string $filter_var - Массив с установленными фильтрами
    * @param array $value - значение фильтра, на которое нужна ссылка
    * @return string
    */
    function getUrl($filter_var, $value)
    {
        $url = \RS\Http\Request::commonInstance();
        $api = new \Catalog\Model\PropertyApi();        
        $filter = $url->get($filter_var, TYPE_ARRAY);
        $filter = $api->cleanNoActiveFilters($filter);
        $my_filter = isset($filter[$this['id']]) ? $filter[$this['id']] : array();
        
        if ($this['type'] == 'list') {
            $key = array_search($value, $my_filter);
            if ($key !== false) {
                //Снимаем фильтр
                unset($my_filter[$key]);
            } else {
                $my_filter[] = $value;
            }
        }
        
        return $url->replaceKey(array($filter_var => array($this['id'] => $my_filter) + $filter));
    }
    
    /**
    * Возвращает имя поля в объекте Link, в котором находится актуальное значение для текущей характеристики
    * 
    * @return string
    */
    function getValueLinkField()
    {
        switch($this['type']) {
            case 'bool':
            case 'int': {
                return 'val_int';
            }
            default: 
                return 'val_str';
        }
    }
    
    /**
    * Возвращает клонированный объект характеристики
    * @return Delivery
    */
    function cloneSelf()
    {
        /**
        * @var \Catalog\Model\Orm\Dir
        */
        $clone = parent::cloneSelf();
        unset($clone['xml_id']);
        return $clone;
    }
    
}