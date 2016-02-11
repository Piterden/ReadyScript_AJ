<?php

namespace SeoControl\Model;

class Api extends \RS\Module\AbstractModel\EntityList
{
	private static $cache_uri;
	function __construct()
	{
		parent::__construct ( new \SeoControl\Model\Orm\SeoRule (), array('multisite' => true 
		) );
	}
	
	/**
	 * Возвращает объект SeoRule для заданного URI, если нет совпадений по маске ни с одним правилом, то возвращается false
	 * 
	 * @param string $uri - URI для которого нужно вернуть объект
	 * @return Orm\SeoRule | false
	 */
	function getRuleForUri($uri)
	{
		$uri = urldecode ( $uri );
		if (! isset ( self::$cache_uri[$uri] )) {
			$all_rules = $this->getList ();
			
			self::$cache_uri[$uri] = null;
			foreach($all_rules as $rule) {
				if ($rule->match ( $uri )) {
					self::$cache_uri[$uri] = $rule;
					break;
				}
			}
		}
		return self::$cache_uri[$uri];
	}
}
?>
