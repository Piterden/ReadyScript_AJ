<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\Orm;
use \RS\Orm\Type;

/**
* Реквизиты компании, принимающей платежи.
*/
class Company extends \RS\Orm\AbstractObject
{
    function _init()
    {
        $this->setClassParameter('storage_class', '\RS\Orm\Storage\Stub');
        $this->getPropertyIterator()->append(array(
            'firm_name' => new Type\String(array(
                'maxLength' => '255',
                'description' => 'Наименование организации',
            )),
            'firm_inn' => new Type\String(array(
                'maxLength' => '12',
                'description' => 'ИНН организации',
                'Attr' => array(array('size' => 20)),
            )),
            'firm_kpp' => new Type\String(array(
                'maxLength' => '12',
                'description' => 'КПП организации',
                'Attr' => array(array('size' => 20)),
            )),
            'firm_bank' => new Type\String(array(
                'maxLength' => '255',
                'description' => 'Наименование банка',
            )),
            'firm_bik' => new Type\String(array(
                'maxLength' => '10',
                'description' => 'БИК',
            )),
            'firm_rs' => new Type\String(array(
                'maxLength' => '20',
                'description' => 'Расчетный счет',
                'Attr' => array(array('size' => 25)),
            )),
            'firm_ks' => new Type\String(array(
                'maxLength' => '20',
                'description' => 'Корреспондентский счет',
                'Attr' => array(array('size' => 25)),
            )),
            'firm_director' => new Type\String(array(
                'maxLength' => '70',
                'description' => 'Фамилия, инициалы руководителя',
            )),
            'firm_accountant' => new Type\String(array(
                'maxLength' => '70',
                'description' => 'Фамилия, инициалы главного бухгалтера',
            ))
        ));
    }        
    
}
?>
