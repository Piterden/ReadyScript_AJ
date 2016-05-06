<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Photo\Controller\Block;
use \RS\Orm\Type;
/**
* Блок контроллер - список фотографий
*/
class Photolist extends \RS\Controller\StandartBlock
{
    protected static
        $controller_title = 'Список прикрепленных фотографий',
        $controller_description = 'Отображает полосу прикрепленных фотографий с возможностью их просмотреть в увеличенном виде';
    
    protected
        $default_params = array(
            'indexTemplate' => 'blocks/photolist/photolist.tpl',
        );
    
    function getParamObject()
    {
        parent::getParamObject()->appendProperty(array(
            'type' => new Type\Varchar(array(
                'description' => t('Тип объектов, к которым привязаны картинки')
            )),
            'route_id_param' => new Type\Varchar(array(
                'description' => t('Параметр, в котором получать id объекта, к которому привязаны фото')
            ))
        ));
    }
    
    function actionIndex()
    {
        $route = $this->router->getCurrentRoute();
        $param = $this->getParam('route_id_param');
        $link_id = isset($route->$param) ? $route->$param : 0;
        
        $api = new \Photo\Model\PhotoApi();
        $api->setFilter('type', $this->getParam('type'));
        $api->setFilter('linkid', $link_id);
        $photos = $api->getList();
        $this->view->assign('photos', $photos);
        
        return $this->result->setTemplate( $this->getParam('indexTemplate') );
    }
}

?>
