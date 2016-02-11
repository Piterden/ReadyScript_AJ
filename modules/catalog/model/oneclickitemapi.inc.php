<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Model;


/**
 * Класс содержит API функции для работы с объектом купить в 1 клик
 * @ingroup Catalog
 */
class OneClickItemApi extends \RS\Module\AbstractModel\EntityList
{
    function __construct()
    {
        parent::__construct(new \Catalog\Model\Orm\OneClickItem(), array(
            'multisite' => true
        ));
    }
      
}