<?php

namespace Wishlist\Controller\Block;

use \RS\Orm\Type;

/**
 * Класс блочного контроллера "Список желаний".
 * Предназначен для вывода формы добавления или
 * удаления желания в списках товаров.
 */
class WishActions extends \RS\Controller\StandartBlock
{
	protected static $controller_title = 'Блок действий желаний', $controller_description = 'Отображает форму добавления и удаления желаний';
	protected $default_params = array('indexTemplate' => 'form/formaddwish.tpl','context' => 'list','product_id' => null 
	);
	public function getParamObject()
	{
		return parent::getParamObject ()->appendProperty ( array('indexTemplate' => new Type\String ( array('description' => t ( 'Tpl' ) 
		) ) 
		) );
	}
	function actionIndex()
	{
		$context = $this->getParam ( 'context' );
		$product_id = $this->getParam ( 'product_id' );
		$method = $this->url->request ( 'method', TYPE_STRING, false );
		
		$api = new \Wishlist\Model\WishApi (); // Создаем экземпляр API желаний
		$user = \RS\Application\Auth::getCurrentUser (); //
		$wish_array = $api->getCurrentWish ( $product_id, $user['id'] ); // Массив! с одним объектом
		$user_wishes = $api->getWishedProductIds ( $user['id'] );
		
		// return $this->url->isAjax();
		// Если это AJAX POST авторизованного юзера и есть id продукта
		if (/*$this->url->isAjax() &&*/ $this->isMyPost () && $api->checkAuth () && $product_id !== null) {
			$product = new \Catalog\Model\Orm\Product ( $product_id );
			
			if ($method == 'del') { // Если метод del - удаляем желание
				foreach($wish_array as $wish) {
					$api->deleteWish ( $wish['id'] );
					$this->refreshPage ();
				}
			} elseif ($method == 'add') { // Если метод add - добавяем желание
				if (! in_array ( $product_id, $user_wishes )) $api->addWish ( $product, $user );
				$this->refreshPage ();
			}
		}
		
		// die();
		
		$this->view->assign ( array('product_id' => $product_id,'method' => $method,'current_wish' => $current_wish 
		) );
		
		if (in_array ( $product_id, $user_wishes )) {
			$index_template = 'form/' . $context . 'formdelwish.tpl';
		} else {
			$index_template = 'form/' . $context . 'formaddwish.tpl';
		}
		
		return $this->result->setTemplate ( $index_template );
	}
}
