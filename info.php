<?php 
require('setup.inc.php');

//\RS\Orm\Request::make()
//    ->select()
//    ->from(new \Shop\Model\Orm\Order())
//    ->where(array(
//        'user_id' => '1'
//    ))->exec();

//print_r($order);
//$address = $order
$order = \RS\Orm\Request::make()
    ->select()
    ->from(new \Shop\Model\Orm\Order())
    ->where(array(
        'id' => '4'
    ))->objects();
$cart = $order[0]->getCart();
//count($cart);
foreach ($cart->getProductItems() as $item) {
	$product           = $item['product'];
	$barcode           = $product->getBarCode($item['cartitem']['offer']);
	$offer_title       = $product->getOfferTitle($item['cartitem']['offer']);
	$multioffer_titles = $item['cartitem']->getMultiOfferTitles();  
	$unit              = $product->getUnit()->stitle; 
    print_r($_SESSION);
}


//Imldelivery\Model\DeliveryType\Iml::onOrderCreate();