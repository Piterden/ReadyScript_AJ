<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Controller\Block;
use \RS\Orm\Type;

/**
* Блок-контроллер Корзина список
*/
class Cartlist extends \RS\Controller\StandartBlock
{
    protected static
        $controller_title = 'Корзина',
        $controller_description = 'Отображает количество товаров, список и общую стоимость в корзине';

    protected
        $default_params = array(
            'indexTemplate' => 'blocks/cart/cart.tpl'
        );

    public
        $order,
        $cart,
        $api;

    function actionIndex()
    {
        $this->order = $this->getParam('order');
        $this->api = new \Catalog\Model\Api;

        $cart_products = array();
        if (isset($this->order)) {
	        $this->cart = \Shop\Model\Cart::orderCart($this->order);
	        $cart_items = $this->cart->getOrderData()['items'];
	        foreach ($cart_items as $item) {
	        	$id = $item['cartitem']->getValues()['entity_id'];
	            $cart_products[$id] = $this->api->getById($id);
	        }
        } else {
	        $this->cart = \Shop\Model\Cart::currentCart();
	        $this->order = \Shop\Model\Orm\Order::currentOrder();
	        $cart_items = $this->cart->getItems();
	        foreach ($cart_items as $item) {
	            $cart_products[$item['entity_id']] = $this->api->getById($item['entity_id']);
	        }
        }

        $this->view->assign(array(
            'order' => $this->order,
            'cart' => $this->cart,
            'cart_products' => $cart_products,
            'cart_items' => $cart_items,
            'currency'  => \Catalog\Model\CurrencyApi::getCurrentCurrency()
        ));
        return $this->result->setTemplate( $this->getParam('indexTemplate') );
    }
}
