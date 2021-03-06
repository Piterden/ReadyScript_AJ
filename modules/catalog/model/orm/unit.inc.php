<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/

namespace Catalog\Model\Orm;
use \RS\Orm\Type;

/**
* Объект - единица измерения
* @ingroup Catalog
*/
class Unit extends \RS\Orm\OrmObject
{
    protected static
        $table = 'product_unit';
        
    function _init()
    {
        parent::_init()->append(array(
            'site_id' => new Type\CurrentSite(),
            'code' => new Type\Integer(array(
                'description' => t('Код ОКЕИ'),
            )),
            'icode' => new Type\String(array(
                'maxLength' => '25',
                'description' => t('Международное сокращение'),
            )),
            'title' => new Type\String(array(
                'maxLength' => '70',
                'description' => t('Полное название единицы измерения'),
            )),
            'stitle' => new Type\String(array(
                'maxLength' => '25',
                'description' => t('Короткое обозначение'),
            )),
            'sortn' => new Type\Integer(array(
                'description' => t('Сорт. номер'),
                'visible' => false,
            )),
        ));
    }
    
    function beforeWrite($flag)
    {
        if ($flag == self::INSERT_FLAG) {
            $this['sortn'] = \RS\Orm\Request::make()
                ->select('MAX(sortn) as next_sort')
                ->from($this)
                ->exec()->getOneField('next_sort', 0) + 1;
        }
    }
}

