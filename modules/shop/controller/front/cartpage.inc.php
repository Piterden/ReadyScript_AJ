<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Shop\Controller\Front;

/**
* Просмотр корзины
*/
class CartPage extends \RS\Controller\Front
{   
    protected
        $cart;
    
    function init()
    {
        $this->cart = \Shop\Model\Cart::currentCart();
    }
    
    
    /**
    * Обычный просмотр товара
    */
    function actionIndex()
    {
        $add_product_id           = $this->url->request('add', TYPE_INTEGER);       //id товара
        $add_product_amount       = $this->url->request('amount', TYPE_INTEGER);    //Количество
        $add_product_offer        = $this->url->request('offer', TYPE_STRING);      //Комплектация
        $add_product_multioffers  = $this->url->request('multioffers', TYPE_ARRAY); //Многомерные комплектации
        $add_product_concomitants = $this->url->request('concomitant', TYPE_ARRAY); //Сопутствующие товары
        $add_product_concomitants_amount = $this->url->request('concomitant_amount', TYPE_ARRAY); //Количество сопутствующих твоаров
        $checkout                 = $this->url->request('checkout', TYPE_BOOLEAN);
        
        if (!empty($add_product_id)) {
            
            $this->cart->addProduct($add_product_id, 
                                    $add_product_amount, 
                                    $add_product_offer, 
                                    $add_product_multioffers, 
                                    $add_product_concomitants,
                                    $add_product_concomitants_amount);
                                    
            if (!$this->url->isAjax()) {
                $this->redirect( $this->router->getUrl('shop-front-cartpage') );
            }
        }
        
        $cart_data = $this->cart->getCartData();
        $this->view->assign(array(
            'cart'      => $this->cart,
            'cart_data' => $cart_data 
        ));
        
        if ($checkout && !$cart_data['has_error']) {
            $this->result->setRedirect($this->router->getUrl('shop-front-checkout'));
        }
        
        return $this->result
                        ->addSection('cart', array(
                            'can_checkout'     => !$cart_data['has_error'],
                            'total_unformated' => $cart_data['total_unformatted'],
                            'total_price'      => $cart_data['total'],
                            'items_count'      => $cart_data['items_count']
                        ))
                        ->setTemplate( 'cartpage.tpl' );
    }
    
    /**
    * Обновляет информацию о товарах, их количестве в корзине. Добавляет купон на скидку, если он задан
    */
    function actionUpdate()
    {
        if ($this->url->isPost()) {                                 
            $products     = $this->url->request('products', TYPE_ARRAY);
            $coupon       = trim($this->url->request('coupon', TYPE_STRING));
            $apply_coupon = $this->cart->update($products, $coupon);
            
            if ($apply_coupon !== true) {
                $this->cart->addUserError($apply_coupon, false, 'coupon');
                
                $this->view->assign(array(
                    'coupon_code' => $coupon
                ));
            }
        }
        
        return $this->actionIndex();
    }
    
    /**
    * Удаляет товар из корзины
    */
    function actionRemoveItem()
    {
        $uniq = $this->url->request('id', TYPE_STRING);
        $success = $this->cart->removeItem($uniq);
        
        return $this->actionIndex();
    }
    
    /**
    * Очищает корзину
    */
    function actionCleanCart()
    {
        $success = $this->cart->clean();
        return $this->actionIndex();
    }
}