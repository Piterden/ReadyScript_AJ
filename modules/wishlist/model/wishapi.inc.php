<?php

namespace Wishlist\Model;

/**
 * Класс для организации выборок ORM объекта.
 */
class WishApi extends \RS\Module\AbstractModel\EntityList
{
	function __construct()
	{
		parent::__construct ( new \Wishlist\Model\Orm\Wish (), array('multisite' => true 
		) // Сообщаем, что контент на каждом сайте для объекта Wish будет разный.
 );
	}
	
	/**
	 * Проверим авторизацию
	 * 
	 * @return bool
	 */
	public function checkAuth()
	{
		return \RS\Application\Auth::isAuthorize ();
	}
	
	/**
	 * Добавим желание
	 * 
	 * @param \Catalog\Model\Orm\Product $product
	 * @param \Users\Model\Orm\User $user
	 * @return bool
	 */
	public function addWish(\Catalog\Model\Orm\Product $product, \Users\Model\Orm\User $user)
	{
		$current_site = \RS\Site\Manager::getSite ();
		$data = array('site_id' => $current_site['id'],'user_id' => $user['id'],'user_name' => $user['name'],'product_id' => $product['id'],'product_name' => $product['title'] 
		);
		
		if (self::getElement ()->checkData ( $data )) {
			return self::save ( null, $data );
		} else {
			return false;
		}
	}
	
	/**
	 * Получим массив объектов желаемых товаров
	 * 
	 * @param array $wishes
	 * @return array of \Catalog\Model\Orm\Product
	 */
	public function getWishedProductsList(array $wishes)
	{
		$products = array();
		foreach($wishes as $wish) {
			$products[] = \Catalog\Model\Orm\Product::loadByWhere ( array('id' => $wish->product_id 
			) );
		}
		return $products;
	}
	
	/**
	 * Получим массив id желаемых товаров одного пользователя
	 * 
	 * @param int $user_id
	 * @return array
	 */
	static public function getWishedProductIds($user_id)
	{
		$ids = array();
		foreach(self::getSelfList ( $user_id ) as $wish) {
			$ids[] = $wish['product_id'];
		}
		return $ids;
	}
	
	/**
	 * Получим массив объектов желаний одного пользователя
	 * 
	 * @param int $user_id
	 * @return array of \Wishlist\Model\Orm\Wish
	 */
	static private function getSelfList($user_id)
	{
		return \RS\Orm\Request::make ()->from ( new \Wishlist\Model\Orm\Wish () )->where ( array( // Условие
'user_id' => $user_id 
		) // id пользователя
 )->objects ();
	}
	
	/**
	 * Удалим желание
	 * 
	 * @param int $wish_id
	 * @return bool
	 */
	public function deleteWish($wish_id)
	{
		return \RS\Orm\Request::make ()->from ( new \Wishlist\Model\Orm\Wish () )->where ( array('id' => $wish_id 
		) )->delete ()->exec ();
	}
	
	/**
	 * Получим желание по id пользователя и продукта
	 * 
	 * @param int $user_id
	 * @param int $product_id
	 * @return array of objects \Wishlist\Model\Orm\Wish
	 */
	public function getCurrentWish($product_id, $user_id)
	{
		return \RS\Orm\Request::make ()->from ( new \Wishlist\Model\Orm\Wish () )->where ( array('user_id' => $user_id,'product_id' => $product_id 
		) )->objects ();
	}
}
