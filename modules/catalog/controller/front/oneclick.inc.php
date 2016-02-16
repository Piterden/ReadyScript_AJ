<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Catalog\Controller\Front;

/**
* Фронт контроллер Купить в один клик
*/
class OneClick extends \RS\Controller\Front
{
    protected
        $id,
        /**
        * @var \Catalog\Model\ClickApi
        */
        $clickApi;
        
    function init()
    {
       $this->api      = new \Catalog\Model\Api(); 
    }
    
    
    /**
    * Показ формы купить в один клик и её обработка
    */
    function actionIndex()
    { 
       $api = new \Catalog\Model\OneClickApi();
       $this->id = $this->url->request('product_id', TYPE_INTEGER);
       /**
       * @var \Catalog\Model\Orm\Product
       */
       $product  = $this->api->getById($this->id); //Получим сам объект товара
       if (!$product) $this->e404(t('Такого товара не существует'));
       if (!$product['public']) $this->e404();
       
       
       //Если используются комплектации добавим их
       $offer_id    = $this->url->request('offer_id',TYPE_INTEGER,false);
       $offer       = $this->url->request('offer',TYPE_STRING);
       $multioffers = $this->url->request('multioffers',TYPE_ARRAY,array());
       
       //Ставим хлебные крошки используемые при открытии в отдельной странице
       $this->app->breadcrumbs
            ->addBreadCrumb($product['title'], $this->router->getUrl('catalog-front-product', array('id' => $product['_alias'])))
            ->addBreadCrumb(t('Купить в один клик'));
       
       //Значения полей по умолчанию
       $click           = $api->getElement();
       $click['phone']  = $this->user['phone']; 
                                        
       $errors  = false;
       $request = $this->url;
       
       //Получим дополнительные поля для формы покупки в один
       /**
       * @var \RS\Config\UserFieldsManager
       */
       $click_fields_manager = \RS\Config\Loader::byModule('catalog')->getClickFieldsManager();
       $click_fields_manager->setErrorPrefix('clickfield_');
       $click_fields_manager->setArrayWrapper('clickfields');
       $offer_fields = array(
            'offer' => null,
            'multioffer' => array()
       );
       
       //Многомерные комплектации
       if (!empty($multioffers)){
           $offer_fields['multioffer'] = $multioffers;
       }
       
       if ($this->isMyPost()){ //Если пришёл запрос
           //Добавим комплектации, если таковые имеются
            
           //Комплектации
           if ($offer){
               $offer_fields['offer'] = $offer;  
           }
           
           //Отсылаем письмо или уведомление на телефон если всё в порядке
           if ($api->send($product, $this->url, $click_fields_manager, $offer_fields)) { //OK
               $this->view->assign('success',t('Спасибо, в ближайшее время с Вами свяжется наш менеджер.'));
           } else { //Если есть ошибки
               $errors  = $api->getErrors();
           }

       }
       
       
       $this->view->assign(array(
            'click'                   => $click,    
            'offer_val'               => $offer_id,       
            'offer_fields'            => $offer_fields,    
            'oneclick_userfields'     => $click_fields_manager,
            'product'                 => $product,
            'request'                 => $request,
            'error_fields'            => $errors
       ));
       return $this->result->setTemplate('oneclick.tpl'); 
    }
}