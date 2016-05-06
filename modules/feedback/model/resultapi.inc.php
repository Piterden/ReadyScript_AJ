<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Feedback\Model;

/**
* Api результата формы
* 
*/
class ResultApi extends \RS\Module\AbstractModel\EntityList
{    
    public 
        $uniq;
    
    function __construct()
    {
        parent::__construct(new \Feedback\Model\Orm\ResultItem(),
            array(
                //'aliasField' => 'alias',
                'nameField' => 'title', 
                'multisite' => true,
                'defaultOrder' => 'dateof'
            ));
    }
    
   
    
}

