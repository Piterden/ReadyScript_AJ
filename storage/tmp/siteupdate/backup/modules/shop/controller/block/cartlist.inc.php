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
	protected static $controller_title = 'Корзина', $controller_description = 'Отображает количество товаров, список и общую стоимость в корзине';
	protected $default_params = array('indexTemplate' => 'blocks/cart/cart.tpl' 
	);
	public $order, $cart, $api;
	function actionIndex()
	{
		$this->cart = \Shop\Model\Cart::currentCart ();
		$this->api = new \Catalog\Model\Api ();
		
		$cart_items = $this->cart->getItems ();
		
		$cart_products = array();
		foreach($cart_items as $val) {
			$cart_products[$val['entity_id']] = $this->api->getById ( $val['entity_id'] );
		}
		
		$this->view->assign ( array('cart' => $this->cart,'cart_products' => $cart_products,'cart_items' => $cart_items,'currency' => \Catalog\Model\CurrencyApi::getCurrentCurrency () 
		) );
		return $this->result->setTemplate ( $this->getParam ( 'indexTemplate' ) );
	}
}
