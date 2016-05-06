<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Config;
use RS\Orm\Type;

class File extends \RS\Orm\ConfigObject
{
    
    public
        //Описывает за что отвечает каждый из 8-ми бит числа, обозначающего уровень доступа
        //Каждый модуль может определить свою таблицу значимости битов
        $access_bit = array(
            'Чтение', //1-й бит - будет означать Разрешение на Чтение
            'Запись', //2-й бит будет означать разрешение на Запись
            'Оформление заказа'
        );
    
    function _init()
    {
        parent::_init()->append(array(
            'basketminlimit' => new Type\Decimal(array(
                'description' => t('Минимальная сумма заказа (в базовой валюте)'),
                'maxLength' => 20,
                'decimal' => 2
            )),
            'check_quantity' => new Type\Integer(array(
                'description' => t('Запретить оформление заказа, если товаров недостаточно на складе'),
                'checkboxView' => array(1, 0)
            )),
            'first_order_status' => new Type\Integer(array(
                'description' => t('Стартовый статус заказа (по-умолчанию)'),
                'list' => array(array('\Shop\Model\UserStatusApi', 'staticSelectList')),
                'hint' => t('Данная настройка перекрывается настройкой способа оплаты, а затем настройкой способа доставки.<br>'.
                            'Важно: система ожидает прием on-line платежей и предоставляет ссылку на оплату только в статусе - Ожидает оплату')
            )),
            'discount_code_len' => new Type\Integer(array(
                'description' => t('Длина кода купона на скидку')
            )),
            'user_orders_page_size' => new Type\Integer(array(
                'description' => t('Количество заказов в истории на одной странице')
            )),
            'use_personal_account' => new Type\Integer(array(
                'description' => t('Использовать лицевой счет'),
                'checkboxView' => array(1, 0)
            )),
            'reservation' => new Type\Integer(array(
                'description' => t('Разрешить предварительный заказ товаров с нулевым остатком'),
                'hint' => t('Актуально только при включенной опции `Запретить оформление заказа, если товаров недостаточно на складе`'),
                'listFromArray' => array(array(
                    0 => 'Нет',                
                    1 => 'Да'
                ))
            )),
            'allow_concomitant_count_edit' => new Type\Integer(array(
                'description' => t('Разрешить редактирование количества сопутствующих товаров в корзине.'),
                'checkboxView' => array(1, 0)
            )),
            'source_cost' => new Type\Integer(array(
                'description' => t('Закупочная цена товаров'),
                'hint' => t('Цена должна отражать ваши расходы на приобретение товара. Данная цена будет использована для расчета дохода, полученного при продаже товара. Расчет будет по форуме ЦЕНА ПРОДАЖИ - ЗАКУПОЧНАЯ ЦЕНА.'),
                'list' => array(array('\Catalog\Model\Costapi', 'staticSelectList'), true)
            )),
            t('Дополнительные поля'),
                '__userfields__' => new Type\UserTemplate('%shop%/form/config/userfield.tpl'),
                'userfields' => new Type\ArrayList(array(
                    'description' => t('Дополнительные поля'),
                    'runtime' => false,
                    'visible' => false
                )),
            t('Оформление заказа'),
                'require_address' => new Type\Integer(array(
                    'maxLength' => 1,
                    'description' => t('Адрес - обязательное поле?'),
                    'checkboxview' => array(1,0)
                )),
                'require_zipcode' => new Type\Integer(array(
                    'maxLength' => 1,
                    'default' => 0,
                    'checkboxview' => array(1,0),
                    'description' => t('Индекс - обязательное?')
                )),
                'require_license_agree' => new Type\Integer(array(
                    'maxLength' => 1,
                    'description' => t('Отображать условия продаж?'),
                    'checkboxView' => array(1,0)
                )),
                'use_geolocation_address' => new Type\Integer(array(
                    'maxLength' => 1,
                    'description' => t('Заполнять город, регион и страну используя геолокацию?'),
                    'checkboxView' => array(1,0)
                )),
                'license_agreement' => new Type\Richtext(array(
                    'description' => t('Условия продаж'),
                )),
                'use_generated_order_num' => new Type\Integer(array(
                    'maxLength' => 1,
                    'default' => 0,
                    'description' => t('Использовать генерируемый идентификатор заказа?'),
                    'hint' => t('Этот уникальный номер будет использоваться вместо порядкового номера заказа'),
                    'checkboxView' => array(1,0)
                )),
                'generated_ordernum_mask' => new Type\Varchar(array(
                    'maxLength' => 20,
                    'description' => t('Маска генерируемого номера'),
                    'hint' => t('Маска по которой формируется, уникальный номер заказа.<br/> {n} - обязательный тег означающий уникальный номер.'),
                    'default' => '{n}'
                )),
                'generated_ordernum_numbers' => new Type\Integer(array(
                    'maxLength' => 11,
                    'default' => 6,
                    'description' => t('Количество символов-цифр генерируемого уникального номера заказа')
                )),
                
                'hide_delivery' => new Type\Integer(array(
                    'maxLength' => 1,
                    'default' => 0,
                    'checkboxview' => array(1,0),
                    'description' => t('Не показывать шаг оформления заказа - доставка?')
                )),
                'hide_payment' => new Type\Integer(array(
                    'maxLength' => 1,
                    'default' => 0,
                    'checkboxview' => array(1,0),
                    'description' => t('Не показывать шаг оформления заказа - оплата?')
                )),
                
        ));
    }
    
    /**
    * Функция срабатывает перед записью конфига
    * 
    * @param string $flag - insert или update
    * @return void
    */
    function beforeWrite($flag)
    {
        if ($flag == self::UPDATE_FLAG) {
            //Проверим на соотвествие конструкции
            if (empty($this['generated_ordernum_mask'])||(mb_stripos($this['generated_ordernum_mask'],'{n}') === false)){
                $this['generated_ordernum_mask'] = '{n}';
            }
        }
    }
    
    
    /**
    * Возвращает объект, отвечающий за работу с пользовательскими полями.
    * 
    * @return \RS\Config\UserFieldsManager
    */
    function getUserFieldsManager()
    {
        return new \RS\Config\UserFieldsManager($this['userfields'], null, 'userfields');
    }    
    
  /**
    * Возвращает значения свойств по-умолчанию
    * 
    * @return array
    */
    public static function getDefaultValues()
    {
        return parent::getDefaultValues() + array(           
            'tools' => array(
                array(
                    'url' => \RS\Router\Manager::obj()->getAdminUrl('ajaxCalcProfit', array(), 'shop-tools'),
                    'title' => t('Пересчитать доходность заказов'),
                    'description' => t('Рассчитывает доходность заказов на основе Закупочной цены товара. Показатель доходности может использоваться другими модулями.'),
                    'confirm' => t('Вы действительно хотите пересчитать доходность заказов?')
                ),
            )
        );
    }    
    
}

