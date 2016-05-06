<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\DeliveryType;
use \RS\Orm\Type;

class Spsr extends AbstractType
{
    const 
        API_URL = "http://spsr.ru/cgi-bin/post09.pl?TARIFFCOMPUTE_2",
        DEFAULT_COUNTRY   = '209|0', // Россия
        DEFAULT_CITY_FROM = '992|0', // Москва
        DEFAULT_CITY_TO   = '893|0', // Санкт-Петербург (нужен для получения всех тарифов SPSR)
        REQUEST_TIMEOUT   = 10; // 10 сек. таймаут запроса к api
    
    private 
        $cache_api_requests = array();  // Кэш запросов к серверу рассчета 
        
    function __construct()
    {
        $this->setOption(array(
            'city_from' => self::DEFAULT_CITY_FROM
        ));
    }
        
    /**
    * Возвращает название расчетного модуля (типа доставки)
    * 
    * @return string
    */
    function getTitle()
    {
        return t('SPSR Express');
    }
    
    /**
    * Возвращает описание типа доставки
    * 
    * @return string
    */
    function getDescription()
    {
        return t('SPSR Экспресс-доставка');
    }
    
    /**
    * Возвращает идентификатор данного типа доставки. (только англ. буквы)
    * 
    * @return string
    */
    function getShortName()
    {
        return t('spsr');
    }
    
    /**
    * Возвращает ORM объект для генерации формы или null
    * 
    * @return \RS\Orm\FormObject | null
    */
    function getFormObject()
    {
        $properties = new \RS\Orm\PropertyIterator(array(
            'city_from' => new Type\Varchar(array(
                'description' => t('Город отправления'),
                'ListFromArray' => array($this->getCities()),
            )),
            'tariff' => new Type\Varchar(array(
                'description' => t('Тариф'),
                'ListFromArray' => array($this->getAllTariffs()),
            )),
        ) );
        
        return new \RS\Orm\FormObject($properties);
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
        $sxml = $this->requestDeliveryInfo($order, $address);
        
        if(!isset($sxml->Tariff)){
            return t('Не удалось соединиться с сервером SPSR');
        }
        $avalible_tariffs = array();
        foreach($sxml->Tariff as $one){
            $avalible_tariffs[] = trim($one->TariffType, '"');
        }
        if(array_search($this->getOption('tariff'), $avalible_tariffs) === false){
            return t("Доставка невозможна в Ваш регион");
        }
        return false;
    }
    
    
    /**
    * Запрос к серверу рассчета стоимости доставки. Ответ сервера кешируется
    * 
    * @param array $params
    */
    private function apiRequest($params)
    {
        ksort($params);                                      
        $cache_key = md5(serialize($params));
        if(!isset($this->cache_api_requests[$cache_key])){
            $ctx = stream_context_create(array(
                'http' => array('timeout' => self::REQUEST_TIMEOUT)
            ));
            
            $url        = self::API_URL.'&'.http_build_query($params);
            $response   = file_get_contents($url, null, $ctx);
            $this->cache_api_requests[$cache_key] = $response;
        }
        return $this->cache_api_requests[$cache_key];
    }
    
   
    /**
    * Обобщающий метод запроса информации к серверу
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    * @param \Shop\Model\Orm\Address $address - объект адреса
    * @return \SimpleXMLElement
    */
    private function requestDeliveryInfo(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        if(!$address) $address = $order->getAddress();
        $data = $this->apiRequest(array(
            'FromCountry'   => self::DEFAULT_COUNTRY,
            'FromCity'      => $this->getOption('city_from', self::DEFAULT_CITY_FROM),
            'Country'       => self::DEFAULT_COUNTRY,
            'ToRegion'      => '',
            'to_Cities_name'=> '',
            'ToCity'        => $this->getCityId($address->city),
            'Weight'        => $order->getWeight() / 1000,
        ));
        
        return new \SimpleXMLElement($data);
    }
    
    /**
    * Возвращает список всех доступных тарифов SPSR
    * 
    * @return array
    */
    public function getAllTariffs()
    {
        $data = $this->apiRequest(array(
            'FromCountry'   => self::DEFAULT_COUNTRY,
            'FromCity'      => self::DEFAULT_CITY_FROM,
            'Country'       => self::DEFAULT_COUNTRY,
            'ToRegion'      => '',
            'to_Cities_name'=> '',
            'ToCity'        => self::DEFAULT_CITY_TO,
            'Weight'        => 10,
        ));
        $sxml = new \SimpleXMLElement($data);
        $tariffs = array();
        foreach($sxml->Tariff as $one){
            $key = trim($one->TariffType, '"');
            $tariffs[$key] = $key;
        }
        return $tariffs;
    }

    /**
    * Возвращает стоимость доставки для заданного заказа. Только число.
    * 
    * @param \Shop\Model\Orm\Order $order
    * @param \Shop\Model\Orm\Address $address - Адрес доставки
    * @return double
    */
    function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, \Shop\Model\Orm\Delivery $delivery, $use_currency = true)
    {
        $sxml = $this->requestDeliveryInfo($order, $address);
        $desiredTariff = $sxml->Tariff[0];
        foreach($sxml->Tariff as $tariff){
            $tariff_name = trim($tariff->TariffType, '"');
            if($tariff_name == $this->getOption('tariff')){
                $desiredTariff = $tariff;
            }
        }
        $cost = $desiredTariff->Total_Dost;
        $cost = $this->applyExtraChangeDiscount($delivery, $cost); //Добавим наценку или скидку 
        
        if($use_currency){
            $cost = $order->applyMyCurrency($cost);
            $currency  = $order->getMyCurrency();
        }
        return $cost;
    }
    
    /**
    * Возвращает дополнительную информацию о доставке (например сроки достави)
    * 
    * @param \Shop\Model\Orm\Order $order
    * @param \Shop\Model\Orm\Address $address - Адрес доставки
    * @return string
    */
    function getDeliveryExtraText(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
    {
        $sxml = $this->requestDeliveryInfo($order, $address);
        $desiredTariff = $sxml->Tariff[0];
        foreach($sxml->Tariff as $tariff){
            $tariff_name = trim($tariff->TariffType, '"');
            if($tariff_name == $this->getOption('tariff')){
                $desiredTariff = $tariff;
            }
        }
        
        list($min, $max) = explode('-', $desiredTariff->DP);
        if($min == $max){
            return t('Срок доставки %0 [plural:%0:день|дня|дней]', array($max));
        }
        return t('Срок доставки от %0 до %1 [plural:%1:дня|дней|дней]', array($min, $max));
    }
    
    
    
    
    /**
    * Возвращает идентификатор города в данной системе доставки
    * 
    * @param string $city_title
    */
    private function getCityId($city_title)
    {
        $cities = $this->getCities();
        $cities = array_map('mb_strtolower', $cities);
        $key = array_search(mb_strtolower($city_title), $cities);
        return $key;
    }
    
    /**
    * Возвращает список городов для данного типа доставки
    * 
    * @return array
    */
    private function getCities()
    {
        $cities = SPSR\SpsrLocations::$cities;
        asort($cities);
        return $cities;
    }
}