<?php
namespace Materials\Model\Orm;
use \RS\Orm\Type;

class Material extends \RS\Orm\OrmObject
{
    protected static
        $table = 'materials';

    function _init()
    {
        parent::_init()->append(array(
            'title' => new Type\String(array(
                'description' => t('Материал'),
                'index' => true
            )),
            'description' => new Type\Richtext(array(
                'description' => t('Описание материала')
            )),
            'shortdescription' => new Type\String(array(
                'description' => t('Короткое описание'),
                'index' => true
            )),
            'icon' => new Type\Image(array(
                'description' => t('Иконка материала')
            ))
        ));
    }

}