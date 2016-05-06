<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Controller\Front;

class Reservation extends \RS\Controller\Front
{
    function actionIndex()
    {
        $api = new \Shop\Model\ReservationApi();
        
        $product_id = $this->url->request('product_id', TYPE_INTEGER);
        $product = new \Catalog\Model\Orm\Product($product_id);
        if (!$product['id']) {
            return $this->e404(t('Товар не найден'));
        }
        
        $this->app->breadcrumbs
            ->addBreadCrumb($product['title'], $this->router->getUrl('catalog-front-product', array('id' => $product['_alias'])))
            ->addBreadCrumb(t('Заказать'));
        
        /**
        * @var \Shop\Model\Orm\Reservation
        */
        $reserve = $api->getElement();
        $reserve['amount'] = 1;
        $reserve['phone'] = $this->user['phone'];
        $reserve['email'] = $this->user['e_mail'];
        
        if (!\RS\Application\Auth::isAuthorize() && \RS\Module\Manager::staticModuleEnabled('kaptcha')) {
            $reserve['__kaptcha']->setEnable(true);
        }        
        
        $template = 'reservation.tpl';
            
        if ($this->url->isPost()) {
            //Сохраним предзаказ
            if ($api->save()) {
                $template = 'reservation_success.tpl';
            }
        }   

        $this->view->assign(array(
            'reserve' => $reserve,
            'product' => $product
        ));        
                
        return $this->result->setTemplate( $template );
        
    }
}

?>
