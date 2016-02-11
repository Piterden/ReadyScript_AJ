<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Catalog\Model;

/**
 * Api уровня многомерной комплектации
 */
class MultiOfferLevelApi extends \RS\Module\AbstractModel\EntityList
{
	const OFFERS_LIMIT = 10000; // Ограничение на количество комплектаций, которое можно создать из уровней многомерных комплектаций
	protected $property_info, // Массив с характеристиками для составления комплектаций
$base_currency; // Базовая валюта, для создания комплектаций
	function __construct()
	{
		parent::__construct ( new \Catalog\Model\Orm\MultiOfferLevel (), array('multisite' => true,'nameField' => 'title' 
		) );
	}
	
	/**
	 * Удаляет уровни многомерных комплектаций по id товара
	 * 
	 * @param integer $product_id
	 */
	function clearMultiOfferLevelsByProductId($product_id)
	{
		\RS\Orm\Request::make ()->from ( new \Catalog\Model\Orm\MultiOfferLevel () )->where ( array('product_id' => $product_id 
		) )->delete ()->exec ();
	}
	
	/**
	 * Удаляет уровни многомерных комплектаций по массиву с id товара
	 * 
	 * @param array $product_ids - массив с id товаров
	 */
	function clearMultiOfferLevelsByProductIdsArray($product_ids)
	{
		\RS\Orm\Request::make ()->from ( new \Catalog\Model\Orm\MultiOfferLevel () )->whereIn ( 'product_id', $product_ids )->delete ()->exec ();
	}
	
	/**
	 * Сохраняет многомерные комплектации
	 * 
	 * @param integer $product_id - id товара к которому относятся уровни
	 * @param array $multioffers - массив с многомерными комплектациями
	 * @return boolean
	 */
	function saveMultiOfferLevels($product_id, $levels)
	{
		// Сохраняем уровни
		if (! empty ( $levels )) {
			// Очистим уровни
			$this->clearMultiOfferLevelsByProductId ( $product_id );
			
			// Сохраним уровни
			$k = 0;
			foreach($levels as $level) {
				$mlevel = new \Catalog\Model\Orm\MultiOfferLevel ();
				
				$mlevel['product_id'] = $product_id;
				$mlevel['title'] = $level['title'];
				$mlevel['prop_id'] = $level['prop'];
				$mlevel['is_photo'] = 0;
				$mlevel['sortn'] = $k;
				// Если установлен показ как фото
				if (isset ( $level['is_photo'] )) {
					$mlevel['is_photo'] = 1;
				}
				$mlevel->insert ();
				$k ++;
			}
		}
		return true;
	}
	
	/**
	 * Получает информацию о уровнях многомерных комплектаций по id одного товара
	 * 
	 * @param integer $product_id - id товара
	 */
	function getLevelsInfoByProductId($product_id)
	{
		$levels = \RS\Orm\Request::make ()->select ( 'I.title as prop_title, I.values as prop_values, Level.*' )->from ( new \Catalog\Model\Orm\MultiOfferLevel (), 'Level' )->join ( new \Catalog\Model\Orm\Property\Item (), 'Level.prop_id = I.id', 'I' )->where ( array('Level.product_id' => $product_id,'I.type' => 'list','I.site_id' => \RS\Site\Manager::getSiteId () 
		) )->orderby ( 'Level.sortn' )->exec ()->fetchSelected ( 'prop_id' );
		
		return $levels;
	}
	
	/**
	 * Подготавливает уровни многомерных комплектаций, ищет те которые
	 * использовать нельзя т.к.
	 * значений для характеристик для товаров нет
	 * Оставляя только те уровни для которых характеристики заданы
	 * Плюс записывает в ключ items значения характеристики определённого уровня для искомого товара
	 * 
	 * @param mixed $product_id - id товара для которого будет создаваться комплектация
	 * @param mixed $levels - массив с уровнями многомерных комплектаций в массиве два ключа title и prop
	 */
	function prepareRightMOLevelsToProduct($product_id, $levels)
	{
		$levels_by_product = array();
		if (! empty ( $levels )) {
			foreach($levels as $level) {
				$props = \RS\Orm\Request::make ()->select ( 'val_str' )->from ( new \Catalog\Model\Orm\Property\Link () )->where ( array('product_id' => $product_id,'prop_id' => $level['prop'] 
				) )->where ( "val_str != ''" )->exec ()->fetchSelected ( null, 'val_str' );
				
				if (empty ( $props[0] )) { // Если первое значение пустое
					unset ( $props[0] );
				}
				if (! empty ( $props )) {
					if ($level['title'] === '') {
						// Установим название многомерной комплектации, если оно не задано
						$property = new \Catalog\Model\Orm\Property\Item ( $level['prop'] );
						if ($property['id']) {
							$level['title'] = $property['title'];
						}
					}
					
					$level['value'] = $props;
					$levels_by_product[$level['prop']] = $level;
				}
			}
		}
		
		return $levels_by_product;
	}
	
	/**
	 * Создаёт автоматически комплектации из уровней много мерных комплектаций
	 * 
	 * @param integer $product_id - id товара для которого будет создаваться комплектация
	 * @param array $levels - массив с уровнями многомерных комплектаций в массиве два ключа title и prop
	 */
	function createOffersFromLevels($product_id, $levels)
	{
		if (! empty ( $levels )) {
			// Очистим предыдущие комплектации
			\RS\Orm\Request::make ()->delete ()->from ( new \Catalog\Model\Orm\Offer () )->where ( array('product_id' => $product_id 
			) )->exec ();
			
			$product_barcode = \RS\Orm\Request::make ()->select ( 'barcode' )->from ( new \Catalog\Model\Orm\Product () )->where ( array('id' => $product_id 
			) )->exec ()->getOneField ( 'barcode' );
			
			return $this->createOffersFromParams ( $product_id, $product_barcode, $levels );
		}
		return 0;
	}
	
	/**
	 * Создает простые комплектации из параметров многомерных
	 * 
	 * @param integer $product_id
	 * @param array $params массив с многомерными комплектациями, в формате:
	 *        [
	 *        'ID характеристики' => [
	 *        'title' => 'Название параметра',
	 *        'value' => [значение, значение, ...]
	 *        ],
	 *        ...
	 *        ]
	 * @return int возвращает количество созданных комплектаций или false, в случае ошибки
	 */
	function createOffersFromParams($product_id, $product_barcode, $params)
	{
		if ($this->noWriteRights ()) return false;
		
		$offers = $this->createOfferTemplateRecursive ( $params );
		
		if (count ( $offers ) > self::OFFERS_LIMIT) {
			return $this->addError ( t ( 'Превышен лимит создаваемых комплектаций для товара %0', array($product_id 
			) ) );
		}
		
		// Удаляем существующие комплектации
		\RS\Orm\Request::make ()->delete ()->from ( new Orm\Offer () )->where ( array('product_id' => $product_id 
		) )->exec ();
		
		// Создаем новые комплектации
		foreach($offers as $n => $offer_data) {
			$offer = new Orm\Offer ();
			$offer->getFromArray ( $offer_data );
			$offer['product_id'] = $product_id;
			$offer['sortn'] = $n;
			$offer['barcode'] = $product_barcode . '-' . $n;
			$offer->insert ();
		}
		return count ( $offers );
	}
	
	/**
	 * Рекурсивно создает шаблон комплектаций с заполненными полями - характеристики, название
	 * 
	 * @param array $params массив с многомерными комплектациями
	 * @param array $stack технический массив со стеком текущих параметров
	 * @return array
	 */
	function createOfferTemplateRecursive($params, $stack = array())
	{
		$offers = array();
		$prop_id = key ( $params );
		$one_param = $params[$prop_id];
		unset ( $params[$prop_id] );
		$base_currency_id = \Catalog\Model\CurrencyApi::getBaseCurrency ()->id;
		
		foreach($one_param['value'] as $value) {
			$stack['titles'][$prop_id] = $one_param['title'];
			$stack['values'][$prop_id] = $value;
			
			if (count ( $params )) {
				$offers = array_merge ( $offers, $this->createOfferTemplateRecursive ( $params, $stack ) );
			} else {
				$offers[] = array('title' => implode ( ', ', array_values ( $stack['values'] ) ),'propsdata_arr' => array_combine ( $stack['titles'], $stack['values'] ),'pricedata_arr' => array('oneprice' => array('use' => 1,'znak' => '+','original_value' => '0','unit' => $base_currency_id 
				) 
				) 
				);
			}
		}
		return $offers;
	}
}

