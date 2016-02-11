<?php

namespace Materials\Model;

class Api extends \RS\Module\AbstractModel\EntityList
{
	function __construct()
	{
		parent::__construct ( new \Materials\Model\Orm\Material () );
	}
}

