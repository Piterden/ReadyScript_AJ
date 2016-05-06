<?php
namespace Optlists\Model\Orm;
use \RS\Orm\Type;

class Optlist extends \RS\Orm\OrmObject
{
    protected static
        $table = 'optlists';

    function _init()
    {
        parent::_init()->append(array(
            'title' => new Type\String(array(
                'description' => t('Опция'),
                'index' => true
            )),
            'description' => new Type\Richtext(array(
                'description' => t('Описание опции')
            )),
            'shortdescription' => new Type\String(array(
                'description' => t('Короткое описание'),
                'index' => true
            )),
            'icon' => new Type\Image(array(
                'description' => t('Иконка опции')
            ))
        ));
    }

}