<?php

/**
 * ReadyScript (http://readyscript.ru)
 * 
 * @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
 * @license http://readyscript.ru/licenseAgreement/
 */
namespace Export\Model\ExportType\Yandex\OfferType;

use \Export\Model\Orm\ExportProfile as ExportProfile;
use \Catalog\Model\Orm\Product as Product;

class Simple extends AbstractOfferType
{
	static public function getEspecialTags()
	{
		$ret = array();
		
		$field = new Field ();
		$field->name = 'sales_notes';
		$field->title = t ( 'Информация о предоплате(sales_notes)' );
		$field->required = true;
		$ret[$field->name] = $field;
		
		return $ret;
	}
	public function writeEspecialOfferTags(ExportProfile $profile, \XMLWriter $writer, Product $product, $offer_index)
	{
		$writer->writeElement ( 'name', $product->title . ' ' . ($offer_index !== false ? $product->getOfferTitle ( $offer_index ) : '') );
		$writer->writeElement ( 'description', $product->short_description );
		
		parent::writeEspecialOfferTags ( $profile, $writer, $product, $offer_index );
	}
}