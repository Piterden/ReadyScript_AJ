<?php
namespace Optlists\Model;

class Api extends \RS\Module\AbstractModel\EntityList
{
    function __construct()
    {
        parent::__construct(new \Optlists\Model\Orm\Optlist);
    }
}

