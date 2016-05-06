<?php
namespace Colors\Model\Orm;
use \RS\Orm\Type;

class Color extends \RS\Orm\OrmObject
{
    protected static
        $table = 'colors';
        
    function _init()
    {
        parent::_init()->append(array(
            'title' => new Type\String(array(
                'description' => t('Цвет'),
                'index' => true
            )),
            'color1' => new Type\Color(array(
                'description' => t('Код первого цвета')
            )),
            'color2' => new Type\Color(array(
                'description' => t('Код второго цвета')
            ))
        ));
    }

}