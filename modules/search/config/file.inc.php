<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Search\Config;
use RS\Orm\Type;

class File extends \RS\Orm\ConfigObject
{
    function _init()
    {
        parent::_init()->append(array(
            'searchtype' => new Type\String(array(
                'attr' => array(array('size' => 1)),
                'listFromArray' => array(array(
                    'like' => 'like',
                    'fulltext' => t('Полнотекстовый')
                )),
                'description' => 'Тип поиска'
            ))
        ));
        
    }
}

