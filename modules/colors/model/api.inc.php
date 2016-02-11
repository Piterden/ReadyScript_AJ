<?php

namespace Colors\Model;

class Api extends \RS\Module\AbstractModel\EntityList
{
	function __construct()
	{
		parent::__construct ( new \Colors\Model\Orm\Color () );
	}
}

