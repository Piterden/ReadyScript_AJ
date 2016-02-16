<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\DeliveryType;

/**
* Абстрактный класс типа доставки. Тип доставки содержит в себе функции для расчета стоимости
* в зависимости от различных параметров заказа.
*/
abstract class AbstractType
{
    protected
        $opt = array();
        
    private
        $errors = array();  // Ошибки доставки 
    
    function loadOptions(array $opt = null)
    {
        $this->opt = $opt;
    }
    
    /**
    * Возвращает название расчетного модуля (типа доставки)
    * 
    * @return string
    */
    abstract function getTitle();
    
    /**
    * Возвращает описание типа доставки
    * 
    * @return string
    */
    abstract function getDescription();
    
    /**
    * Возвращает идентификатор данного типа доставки. (только англ. буквы)
    * 
    * @return string
    */
    abstract function getShortName();

    /**
    * Возвращает стоимость доставки для заданного заказа. Только число.
    * 
    * @param \Shop\Model\Orm\Order $order
    * @param \Shop\Model\Orm\Address $address - Адрес доставки
    * @return double
    */
    abstract function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true);
    
    /**
    * Возвращает дополнительную информацию о доставке (например сроки достави)
    * 
    * @param \Shop\Model\Orm\Order $order
    * @param \Shop\Model\Orm\Address $address - Адрес доставки
    * @return string
    */
    function getDeliveryExtraText(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
    {
    }

    /**
    * Возвращает текст, в случае если доставка невозможна. false - в случае если доставка возможна
    * 
    * @param \Shop\Model\Orm\Order $order
    * @param \Shop\Model\Orm\Address $address - Адрес доставки
    * @return mixed
    */
    function somethingWrong(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        return false;
    }
    
    /**
    * Функция срабатывает после создания заказа
    * 
    * @param \Shop\Model\Orm\Order $order     - объект заказа
    * @param \Shop\Model\Orm\Address $address - Объект адреса
    * @return mixed
    */
    function onOrderCreate(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {}

    /**
    * Возвращает ORM объект для генерации формы или null
    * 
    * @return \RS\Orm\FormObject | null
    */
    function getFormObject()
    {}
    
    /**
    * Возвращает дополнительный HTML для админ части в заказе
    * @return string
    */
    function getAdminHTML(\Shop\Model\Orm\Order $order)
    {
        return "";
    }
    
    /**
    * Возвращает true, если тип доставки предполагает самовывоз
    * 
    * @return bool
    */
    function isMyselfDelivery()
    {
        return false;
    }
    
    /**
    * Действие с запросами к заказу для получения дополнительной информации от доставки
    * 
    */
    function actionOrderQuery(\Shop\Model\Orm\Order $order)
    {}
    
    /**
    * Производит валидацию текущих данных в свойствах
    */
    function validate(\Shop\Model\Orm\Delivery $delivery)
    {}

    /**
    * Возвращает значение доп. поля доставки
    * 
    * @param string $key - ключ
    * @param mixed $default - значение по умолчанию
    */
    function getOption($key = null, $default = null)
    {
        if ($key == null) return $this->opt;
        return isset($this->opt[$key]) ? $this->opt[$key] : $default;
    }
    
    /**
    * Устанавливает значение доп. поля доставки
    * 
    * @param string $key_or_array
    * @param mixed $value
    */
    function setOption($key_or_array = null, $value = null)
    {
        if (is_array($key_or_array)) {
            $this->opt = $key_or_array + $this->opt;
        } else {
            $this->opt[$key_or_array] = $value;
        }
    }
    
    /**
    * Возвращает HTML форму данного типа доставки
    * 
    * @return string
    */
    function getFormHtml()
    {
        if ($params = $this->getFormObject()) {
            $params->getPropertyIterator()->arrayWrap('data');
            $params->getFromArray((array)$this->opt);
            $params->setFormTemplate(strtolower(str_replace('\\', '_', get_class($this))));
            $module = \RS\Module\Item::nameByObject($this);
            $tpl_folder = \Setup::$PATH.\Setup::$MODULE_FOLDER.'/'.$module.\Setup::$MODULE_TPL_FOLDER;
            
            return $params->getForm(null, null, false, null, '%system%/coreobject/tr_form.tpl', $tpl_folder);
        }
    }    
    
    /**
    * Возвращает дополнительный HTML для публичной части
    * 
    * @return string
    */
    function getAddittionalHtml(\Shop\Model\Orm\Delivery $delivery, \Shop\Model\Orm\Order $order = null)
    { 
        return ''; 
    }
    
    /**
    * Возвращает дополнительный HTML для административной части
    * 
    * @return string
    */
    function getAdminAddittionalHtml(\Shop\Model\Orm\Order $order = null)
    { 
        return ''; 
    }
    
    /**
    * Возвращает цену в текстовом формате, т.е. здесь может быть и цена и надпись, например "Бесплатно"
    * 
    * @param \Shop\Model\Orm\Order $order
    * @param \Shop\Model\Orm\Address $address
    * @return string
    */
    function getDeliveryCostText(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        $cost = $this->getDeliveryCost($order, $address);
        return ($cost) ? \RS\Helper\CustomView::cost($cost).' '.$order->getMyCurrency()->stitle : 'бесплатно';
    }
    
    /**
    * Переводит строку XML в форматированный XML
    * 
    * @param string $xml_string - строка XML
    */
    function toFormatedXML($xml_string)
    {
       $dom = new \DOMDocument('1.0');
       $dom->preserveWhiteSpace = false;
       $dom->formatOutput = true;
       $dom->loadXML($xml_string); 
       return $dom->saveXML();
    }
    
    /**
    * Возвращает ошибки в виде строки склеевая символами
    * 
    * @param string $glue - символы для склеивания
    * @return string
    */
    function getErrorsStr($glue = ", ")
    {
       return implode($glue,$this->errors); 
    }
    
    /**
    * Получает массив ошибок
    * 
    * @return array
    */
    function getErrors()
    {
       return $this->errors; 
    }
    
    /**
    * Возвращает есть ошибки при работе метода или нет
    * 
    * @return boolean
    */
    function hasErrors()
    {
       return count($this->errors); 
    }
    
    /**
    * Очищает ошибки доставки
    * 
    */
    function clearErrors()
    {
       $this->errors = array(); 
    }
    
    /**
    * Добавляет ошибку в массив ошибок
    *  
    * @param string $error_text - текст ошибки
    */
    function addError($error_text)
    {
        $this->errors[] = $error_text;
    }
    
}
