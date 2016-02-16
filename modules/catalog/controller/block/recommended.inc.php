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
* Блок-контроллер Список категорий
*/
class Recommended extends \RS\Controller\StandartBlock
{
    protected static
        $controller_title       = 'Рекомендуемые товары',
        $controller_description = 'Отображает товары, отмеченные как рекомендуемые';

    protected
        $default_params = array(
            'indexTemplate' => 'blocks/recommended/recommended.tpl',
        );      
        
    public 
        $dirapi,
        $api;
        
    function init()
    {
        $this->api = new \Catalog\Model\Api();
        $this->dirapi = \Catalog\Model\Dirapi::getInstance();
    }                    
    
    function actionIndex()
    {
        $route = \RS\Router\Manager::obj()->getCurrentRoute();
        if ($route->getId() == 'catalog-front-product') {
            if (isset($route->product)) {
                $this->view->assign(array(
                    'current_product' => $route->product,
                    'recommended' => $route->product->getRecommended(),
                ));
            }
        }
        
        return $this->result->setTemplate( $this->getParam('indexTemplate') );
    }

}