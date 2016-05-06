<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Templates\Model\Orm;
use \RS\Orm\Type;

/**
* Настройки темы в рамках "Контекста"
*/
class SectionContext extends \RS\Orm\AbstractObject
{
    const
        GS_NONE = 'none',
        GS_GS960 = 'gs960',
        GS_BOOTSTRAP = 'bootstrap';

    protected static
        $table = 'section_context';
    
    protected
        $before_grid_system;
        
    function _init()
    {
        $this->getPropertyIterator()->append(array(
            'site_id' => new Type\CurrentSite(),
            'context' => new Type\Varchar(array(
                'maxLength' => 50,
                'description' => t('Контекст темы оформления'),
                'visible' => false
            )),
            'grid_system' => new Type\Enum(array('none', 'gs960', 'bootstrap'), array(
                'description' => t('Тип сеточного фреймворка'),
                'listFromArray' => array(array(
                    'none' => t('Без сетки'),
                    'gs960' => t('GridSystem 960'),
                    'bootstrap' => t('Bootstrap 3')
                )),
                'allowEmpty' => false,
                'template' => '%templates%/form/sectioncontext/grid_system.tpl'
            )),
            'options' => new Type\Text(array(
                'description' => t('Настройки темы в сериализованном виде'),
                'visible' => false
            )),
            'options_arr' => new Type\ArrayList(array(
                'description' => t('Настройки темы'),
                'visible' => false
            ))
        ));
        
        $this->addIndex(array('site_id', 'context'), self::INDEX_PRIMARY);
    }
    
    /**
    * Выполняет действия перед созранением объекта
    * 
    * @param string $flag - флаг операции insert, update, replace
    * @return void
    */
    function beforeWrite($flag)
    {
        $this['options'] = serialize($this['options_arr']);
        
        $before = self::loadByWhere(array(
            'site_id' => $this['site_id'],
            'context' => $this['context']
        ));
        if ($before['context']) {
            $this->before_grid_system = $before['grid_system'];
        }
    }
    
    function afterWrite($flag)
    {
        if ($this->before_grid_system !== null && $this->before_grid_system != $this['grid_system']) {
            //Очищаем страницы, контейнеры при смене сеточного фреймворка
            $pages_id = \RS\Orm\Request::make()
                        ->select('id')
                        ->from(new SectionPage())
                        ->where(array(
                            'site_id' => $this['site_id'],
                            'context' => $this['context']
                        ))->exec()->fetchSelected(null, 'id');
            
            $page_api = new \Templates\Model\PageApi();
            $page_api->del($pages_id);
        }
    }
    
    /**
    * Выполняет действия сразу после загрузки объекта 
    * 
    * @return void
    */
    function afterObjectLoad()
    {
        $this['options_arr'] = @unserialize($this['options']);
    }
    
    /**
    * Возвращает объект, с настройками темы оформления в рамках контекста
    * 
    * @return \RS\Orm\FormObject
    */
    function getContextFormObject()
    {
        $properties = clone $this->getPropertyIterator();
        
        $theme = \RS\Theme\Item::makeByContext($this['context']);
        $theme_xml = $theme->getThemeXml();
        
        if (isset($theme_xml->options) && isset($theme_xml->options->group)) {
            foreach($theme_xml->options->group as $group) {
                if (isset($group['name'])) {
                    $properties->group((string)$group['name']);
                }
                foreach($group->option as $option) {
                    $properties[ (string)$option['name'] ] = $this->generateField($option);
                }
            }
        }

        $form_object = new \RS\Orm\FormObject($properties);
        $form_object->getFromArray( (array)$this['options_arr'] + $this->getValues() );
        return $form_object;
    }
    
    /**
    * Возвращат объект одного поля
    * @return \RS\Orm\Type\AbstractType
    */
    private function generateField($option)
    {
        switch((string)$option['type']) {
            case 'checkbox': {
                $field = new Type\Integer(array(
                    'checkboxView' => array(1,0)
                ));
                break;
            }
            case 'colorpicker': {
                $field = new Type\Color(); 
                break;
            }
            case 'select': {
                $items = array();
                foreach($option->values->value as $value) {
                    $items[ (string)$value['key'] ] = (string)$value;
                }
                $field = new Type\Varchar(array(
                    'listFromArray' => array($items)
                ));
                break;
            }
            case 'text': {
                $field = new Type\Text();
                break;
            }
            default: {
                $field = new Type\Varchar();
            }
        }
        
        $field->setDescription($option->description);
        $field->setDefault($option->default);
        $field->setArrayWrap('options_arr');
        
        return $field;
    }
    
    /**
    * Возвращает тип сеточного фреймворка, используемого в теме оформления 
    * @return string
    */
    function getGridSystem()
    {
        return $this['grid_system'] ?: self::GS_GS960;
    }
}
