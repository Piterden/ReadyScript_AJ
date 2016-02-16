<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Controller\Block;
use \RS\Orm\Type;

/**
* Контроллер - фильтры в боковой колонке
* @ingroup Catalog
*/
class SideFilters extends \RS\Controller\StandartBlock
{
    protected static
        $controller_title = 'Фильтр по характеристикам',
        $controller_description = 'Отображает включенные в административной панели фильтры по характеристикам для текущей категории';
        
    protected
        $default_params = array(
            'indexTemplate' => 'blocks/sidefilters/filters.tpl', //Должен быть задан у наследника
            'use_allowed_values' => 1,
            'show_cost_filter' => 1,
            'show_brand_filter' => 1,
            'show_only_public' => 1,
            'show_is_num' => 0
        ); 
    
    function getParamObject()
    {
        return parent::getParamObject()->appendProperty(array(
            'show_cost_filter' => new Type\Integer(array(
                'description' => t('Отображать фильтр по цене?'),
                'checkboxView' => array(1,0)
            )),
            'show_brand_filter' => new Type\Integer(array(
                'description' => t('Отображать фильтр по бренду?'),
                'default' => 1,
                'checkboxView' => array(1,0)
            )),
            'use_allowed_values' => new Type\Integer(array(
                'description' => t('Отображать только существующие у товаров значения характеристик'),
                'checkboxView' => array(1,0)
            )),
            'show_only_public' => new Type\Integer(array(
                'description' => t('Отображать только видимые характеристики'),
                'checkboxView' => array(1,0)
            )),
            'show_is_num' => new Type\Integer(array(
                'description' => t('Отображать фильтр по наличию товара'),
                'checkboxView' => array(1,0)
            )),
        ));
    }
    
    function actionIndex()
    {
        $dir = urldecode($this->url->get('category', TYPE_STRING));
        $dirapi = \Catalog\Model\Dirapi::getInstance();
        $dir_id = (int)$dirapi->getIdByAlias($dir);
        
        $filters = $this->url->request('f', TYPE_ARRAY);
        $basefilters = $this->url->request('bfilter', TYPE_ARRAY);
        
        $prop_api  = new \Catalog\Model\Propertyapi();
        $prop_list = $prop_api->getGroupProperty($dir_id, true, $this->getParam('show_only_public') ?: null );
        
        $allowable_brand_values = array();
        $allowable_values = array(); 
        
        if ($this->router->getCurrentRoute()->getId() == 'catalog-front-listproducts') {
            $allowable_values = $this->router->getCurrentRoute()->allowable_values;
            //Получим бренды, если они необходимы
            if ($this->getParam('show_brand_filter')){
               $allowable_brand_values = $this->router->getCurrentRoute()->allowable_brand_values; 
            }
        } 
        
        if ($this->getParam('use_allowed_values')) {
            $new_prop_list = array();
            foreach($prop_list as $item) {
                $allowed_properties = array();
                foreach($item['properties'] as $prop_id => $prop) {
                    
                    if (isset($allowable_values[$prop_id])) {
                        if ($prop['type'] == 'int'
                                && $allowable_values[$prop_id]['interval_from'] < $allowable_values[$prop_id]['interval_to']) 
                        {
                            $prop->getFromArray($allowable_values[$prop_id]);
                            $allowed_properties[$prop_id] = $prop;
                        }
                        
                        if ($prop['type'] == 'string'
                         || ($prop['type'] == 'list' && isset($allowable_values[$prop_id]['allowed_values']) 
                            && count($allowable_values[$prop_id]['allowed_values'])>1) )
                        {
                            $prop->getFromArray($allowable_values[$prop_id]);
                            $allowed_properties[$prop_id] = $prop;
                        }
                    }
                    
                    if ($prop['type'] == 'bool') {
                        $allowed_properties[$prop_id] = $prop;
                    }                    
                }
                if ($allowed_properties) {
                    $new_prop_list[] = array('group' => $item['group'], 'properties' => $allowed_properties);
                }
            }
            $prop_list = $new_prop_list;
        }

        $this->view->assign(array(
            'prop_list' => $prop_list,
            'filters' => $prop_api->cleanNoActiveFilters($filters),
            'basefilters' => $basefilters,
            'moneyArray' => $this->router->getCurrentRoute()->money_array,
            'allowable_values' => $allowable_values,
            'brands' => $allowable_brand_values
        ));
        
        return $this->result->setTemplate( $this->getParam('indexTemplate') );
    }
}