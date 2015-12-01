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

    function actionIndex()
    {
        $this->view->assign(array(
            'cart_info' => \Shop\Model\Cart::getCurrentInfo(),
            'cart_list' => \Shop\Model\Cart::getProductItems(),
            'currency'  => \Catalog\Model\CurrencyApi::getCurrentCurrency()
        ));
        return $this->result->setTemplate( $this->getParam('indexTemplate') );
    }
}