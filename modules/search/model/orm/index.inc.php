<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Search\Model\Orm;
use \RS\Orm\Type;

class Index extends \RS\Orm\OrmObject
{
    protected static
        $table = 'search_index';
    
    function _init()
    {
        parent::_init()->append(array(
            'result_class' => new Type\String(array(
                'maxLength' => '100',
                'description' => t('Класс результата'),
            )),
            'entity_id' => new Type\Integer(array(
                'description' => t('id сущности'),
            )),
            'title' => new Type\String(array(
                'maxLength' => '255',
                'description' => t('Заголовок результата'),
            )),
            'indextext' => new Type\Text(array(
                'description' => t('Описание сущности (индексируемый)'),
            )),
            'dateof' => new Type\Datetime(array(
                'description' => t('Дата добавления в индекс'),
            )),
        ));
        
        $this
            ->addIndex(array('result_class', 'entity_id'), self::INDEX_UNIQUE, 'result_class-entity_id')
            ->addIndex(array('title', 'indextext'), self::INDEX_FULLTEXT);
    }
}

