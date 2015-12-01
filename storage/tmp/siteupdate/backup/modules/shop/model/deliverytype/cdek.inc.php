<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Shop\Model\DeliveryType;
use \RS\Orm\Type;

class Cdek extends AbstractType
{
    const 
        API_URL               = "http://gw.edostavka.ru:11443/", //Основной URL
        API_URL_ADDITIONAL    = "http://lk.cdek.ru:11443/", //Запасной URL для обращение
        API_URL_CALCULATE     = "http://api.edostavka.ru/calculator/calculate_price_by_json.php", //URL для калькуляции доставки
        REQUEST_TIMEOUT       = 10, // 10 сек. таймаут запроса к api
        API_CALCULATE_VERSION = "1.0"; //Версия API для подсчёта стоимости доставки
     
    private
       $tariffId = array(),  //Идентификатор тарифа по которому будет произведена доставка 
       $delivery_cost_info = array(), //Стоимость доставки по данному расчётному классу
       $cache_api_requests = array();  // Кэш запросов к серверу рассчета 

        
    function __construct()
    {
       
    }
        
    /**
    * Возвращает название расчетного модуля (типа доставки)
    * 
    * @return string
    */
    function getTitle()
    {
        return t('СДЕК');
    }
    
    /**
    * Возвращает описание типа доставки
    * 
    * @return string
    */
    function getDescription()
    {
        return t('Доставка СДЕК <br/><br/>
        <div class="notice-box no-padd">
            <div class="notice-bg">
                Для работы доставки необходимо указывать Вес у товара в граммах.<br/> 
                Укажите Вес по умолчанию в <b>"Веб-сайт" &rarr; "Настройка модулей" &rarr; "Каталог товаров" &rarr; "Вес одного товара по-умолчанию"</b><br/>
                <b>Минимальный вес для расчётов - 100 грамм.</b><br/> 
                У товаров должны быть обязательно указаны -  длинна, ширина и высота в характеристиках.</b>
            </div>
        </div>');
    }
    
    /**
    * Возвращает идентификатор данного типа доставки. (только англ. буквы)
    * 
    * @return string
    */
    function getShortName()
    {
        return t('cdek');
    }
    
    /**
    * Возвращает ORM объект для генерации формы или null
    * 
    * @return \RS\Orm\FormObject | null
    */
    function getFormObject()
    {
        $properties = new \RS\Orm\PropertyIterator(array(
            'channel' => new Type\String(array(
                'maxLength' => 255,
                'description' => t('Канал для обмена данными с СДЕК'),
                'default' => 0,
                'listFromArray' => array(array(
                        t('По умолчанию'),
                        t('Запасной канал'),
                )),
                'hint' => t('По умолчанию - '.self::API_URL.'<br/>Запасной - '.self::API_URL_ADDITIONAL)
            )),
            'cash_on_delivery' => new Type\Integer(array(
                'maxLength' => 1,
                'default' => 0,
                'CheckboxView' => array(1,0),
                'description' => t('Наложенный платёж?'),
                'hint' => t('Платёж при получении товаров')
            )),
            'secret_login' => new Type\String(array(
                'maxLength' => 150,
                'hint' => t('Используется дополнительно для расчёта стоимости<br/>Выдаётся СДЕК'),
                'description' => t('Логин для доступа к серверу расчётов'),
            )),
            'secret_pass' => new Type\String(array(
                'maxLength' => 150,
                'hint' => t('Используется дополнительно для расчёта стоимости<br/>Выдаётся СДЕК'),
                'description' => t('Пароль для доступа к серверу расчётов'),
            )),
            'day_apply_delivery' => new Type\Integer(array(
                'maxLength' => 11,
                'default' => 1,
                'description' => t('Количество дней, через сколько будет произведена планируемая отправка заказа'),
            )),
            'city_from_name' => new Type\String(array(
                'maxLength' => 150,
                'description' => t('Название города отправления<br/>Например: Краснодар'),
            )),
            'city_from_zipcode' => new Type\String(array(
                'maxLength' => 11,
                'description' => t('Почтовый индекс города отправителя<br/>Например: 350000'),
            )),
            'tariffTypeCode' => new Type\Integer(array(
                'description' => t('Тариф'),
                'maxLength' => 11,
                'visible' => false,
                'List' => array(array('\Shop\Model\DeliveryType\Cdek\CdekInfo','getAllTariffs'), false),
                'ChangeSizeForList' => false,
                'attr' => array(array(
                    'size' => 16
                ))
            )),
            'tariffTypeList' => new Type\ArrayList(array(
                'description' => t('Список тарифов по приоритету'),
                'maxLength' => 1000,
                'runtime' => false,
                'attr' => array(array(
                    'multiple' => true
                )),
                'template' => '%shop%/form/delivery/cdek/tariff_list.tpl',
                'hint' => t('При расчёте стоимости, если указаный тариф будет не доступен для отправления, то расчёт будет вестись по нижеследующему тарифу указанному в списке'),
                'listFromArray' => array(array())
            )),
            'additional_services' => new Type\ArrayList(array(
                'maxLength' => 1000,
                'default' => 0,
                'attr' => array(array(
                    'size' => 7,
                    'multiple' => true
                )),
                'list' => array(array('\Shop\Model\DeliveryType\Cdek','getAdditionalServices')), 
                'description' => t('Добавлять допполнительные услуги к заказу:<br/>(Необязательно)'),
                'hint' => t('Дополнительные услуги зависят от Ваших условий договора. Обратитесь к менеджеру, за дальнейшими разъяснениями по использованию доп. услуг.')
            )),
            'width' => new Type\Integer(array(
                'description' => t('Свойство со значением ширины товара(см)'),
                'default' => 0,
                'list' => array(array('\Catalog\Model\PropertyApi','staticSelectList'),true),
            )),
            'height' => new Type\Integer(array(
                'description' => t('Свойство со значением высоты товара(см)'),
                'default' => 0,
                'list' => array(array('\Catalog\Model\PropertyApi','staticSelectList'),true),
            )),
            'length' => new Type\Integer(array(
                'description' => t('Свойство со значением длинны товара(см)'),
                'default' => 0,
                'list' => array(array('\Catalog\Model\PropertyApi','staticSelectList'),true),
            )),
        ) );
        
        return new \RS\Orm\FormObject($properties);
    } 
   
    
    /**
    * Запрос к серверу рассчета стоимости доставки. Ответ сервера кешируется
    * 
    * @param string $script - скрипт
    * @param array $params  - массив параметров
    * @param string $method - POST или GET
    */
    private function apiRequest($script, $params, $method="POST")
    {
        ksort($params);                                      
        $cache_key = md5(serialize($params).$method);
        if(!isset($this->cache_api_requests[$cache_key])){
            
            $requst_array = array(
                'http' => array(
                    'ignore_errors' => true, //Игнорируем ошибки(статусы ошибок) в заголовках, т.к. может быть 500 ошибка, но контент есть
                    'method'=>$method,
                    'timeout' => self::REQUEST_TIMEOUT
                )
            );
            
            if (stripos($method,'post')!==false){
                $requst_array['http']['content'] = http_build_query($params);
                
                $ctx = stream_context_create($requst_array);
                $url = $this->getApiHost().$script;
            }else{
                $url = $this->getApiHost().$script.'?'.http_build_query($params);
                $ctx = stream_context_create($requst_array);
            }
            
            $response   = @file_get_contents($url, null, $ctx);
            $this->cache_api_requests[$cache_key] = $response;
        }
        return $this->cache_api_requests[$cache_key];
    }
    
    /**
    * Получает хост для api
    * 
    */
    private function getApiHost(){
       if (!$this->getOption('channel',0)){
           return self::API_URL;
       }else{
           return self::API_URL_ADDITIONAL;
       } 
    }
    
    /**
    * Получение кода защиты для СДЕК запросов
    * 
    * @param string $format - формат даты отправления
    */
    private function getDateExecute( $format = "Y-m-d" )
    {
       $time = time() + ($this->getOption("day_apply_delivery",0) * 60 * 60 * 24); 
       return date($format,$time);
    }
    
    /**
    * Получает секретный код основанный на MD5 и текущей дате
    * 
    * @param string $date_execute - дата для ключа
    */
    private function getSecure($date_execute)
    {
        return md5($date_execute."&".$this->getOption('secret_pass',null)); 
    }
    

    
    /**
    * Отправляет запрос на получение почтоматов для забора товара пользователем
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    * @param \Shop\Model\Orm\Address $address - объект адреса
    */
    private function requestPochtomat(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        if (!$address){
            $address = $order->getAddress();
        }
        
        $zipcode = $address['zipcode'];
        $params  = array(
            'citypostcode' => $zipcode
        );
        
        $response = $this->apiRequest('pvzlist.php',$params,'GET');
        
        $pochtomates = array();
        if ($response){
            $xml = @simplexml_load_string($response);
            if (isset($xml->Pvz)){
                $pochtomates = $xml->Pvz;
            }
        }

        return $pochtomates;
    }
    
    
    
    /**
    * Запрос на информацию по заказу
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    */
    private function requestGetInfo(\Shop\Model\Orm\Order $order)
    {
       $extra_info = $order->getExtraInfo(); 
       
       if (!isset($extra_info['cdek_order_id']['data']['orderId'])){
            throw new \RS\Exception('[Ошибка] Не удалось получить сведения о заказе СДЕК');
       }
       
       //Подготовим XML
       $sxml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><InfoRequest/>");
       
       $data_create = date('Y-m-d');
       $sxml['Date']       = $data_create;
       $sxml['Account']    = trim($this->getOption('secret_login',null)); //Id аккаунта
       $sxml['Secure']     = $this->getSecure($data_create); //Генерируемый секретный ключ
       
       $sxml->ChangePeriod['DateBeg'] = date('Y-m-d', strtotime($order['dateof'])); //Дата начала запрашиваемого периода
       
       //Номер отправления
       $sxml->Order['DispatchNumber'] = $extra_info['cdek_order_id']['data']['orderId']; 
       
       
       $xml = $sxml->asXML(); //XML заказа
    
       try{
           return @simplexml_load_string($this->apiRequest("info_report.php",array(
               'xml_request' => $xml
           ))); 
       }catch(\Exception $ex){
           throw new \RS\Exception($ex->getMessage()."<br/> Файл:".$ex->getFile()."<br/> Строка:".$ex->getLine(),$ex->getCode()); 
       }
    }
    
    /**
    * Запрос вызова курьера
    * 
    * @param array $call - массив со сведениями об отправке
    * @param \Shop\Model\Orm\Order $order - объект заказа
    */
    private function requestGetCallCourier($call, \Shop\Model\Orm\Order $order)
    {
        $extra_info = $order->getExtraInfo();
        
        if (!isset($extra_info['cdek_order_id']['data']['orderId'])){
            throw new \RS\Exception('[Ошибка] Не удалось получить сведения о заказе СДЕК');
        }
        
        $adress = $order->getAddress();
        
        //Настройки текущего сайта
        $site_config = \RS\Config\Loader::getSiteConfig();
        
        //Подготовим XML
        $sxml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><CallCourier/>");
        $data_create       = date('Y-m-d');
        $sxml['Date']      = $data_create;
        $sxml['Account']   = trim($this->getOption('secret_login',null)); //Id аккаунта
        $sxml['Secure']    = $this->getSecure($data_create); //Генерируемый секретный ключ
        $sxml['CallCount'] = 1; //Общее количество заявок для вызова курьера в документе
        
        //Сведения об узнаваемом заказе
        $sxml->Call['Date']    = date('Y-m-d',strtotime($call['Date']));
        $sxml->Call['TimeBeg'] = $call['TimeBeg'].":00";
        $sxml->Call['TimeEnd'] = $call['TimeEnd'].":00";
        
        //Если указано время обеда
        if (isset($call['use_lunch']) && $call['use_lunch']){
           $sxml->Call['LunchBeg'] = $call['LunchBeg'].":00";
           $sxml->Call['LunchEnd'] = $call['LunchEnd'].":00"; 
        }
        
        $sxml->Call['SendCityCode'] = $call['SendCityCode'];
        
        //Укажем телефон для вызова
        if (isset($call['use_admin']) && !$call['use_admin']){ //Если свой телефон
            $sxml->Call['SendPhone'] = $call['SendPhone'];
        }else{ //Если телефон администратора
            $sxml->Call['SendPhone'] = $site_config['admin_phone'];
        }
        
        
        $sxml->Call['SenderName']   = $call['SenderName'] ? $call['SenderName'] : $site_config['firm_name'];
        $sxml->Call['Weight']       = $order->getWeight() / 1000;
        $sxml->Call['Comment']      = $call['Comment'];
        $sxml->Call['Comment']      = $call['Comment'];
        
        //Адрес
        $sxml->Call->Address['Street'] = $adress['address'];
        $sxml->Call->Address['House']  = "-";
        $sxml->Call->Address['Flat']   = "-";
        
        $xml = $sxml->asXML(); //XML заказа

        try{
           return @simplexml_load_string($this->apiRequest("call_courier.php",array(
               'xml_request' => $xml
           ))); 
        }catch(\Exception $ex){
           throw new \RS\Exception($ex->getMessage()."<br/> Файл:".$ex->getFile()."<br/> Строка:".$ex->getLine(),$ex->getCode()); 
        }
    }
    
    /**
    * Запрос статусов заказа
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    */
    private function requestOrderStatus(\Shop\Model\Orm\Order $order)
    {
        $extra_info = $order->getExtraInfo();
        
        if (!isset($extra_info['cdek_order_id']['data']['orderId'])){
            throw new \RS\Exception('[Ошибка] Не удалось получить сведения о заказе СДЕК');
        }
        
        //Подготовим XML
        $sxml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><StatusReport/>");
        $data_create = date('Y-m-d');
        $sxml['Date']        = $data_create;
        $sxml['Account']     = trim($this->getOption('secret_login',null)); //Id аккаунта
        $sxml['Secure']      = $this->getSecure($data_create); //Генерируемый секретный ключ
        $sxml['ShowHistory'] = 1; //Показ истории заказа
        
        //Сведения об узнаваемом заказе
        $sxml->Order['DispatchNumber'] = $extra_info['cdek_order_id']['data']['orderId'];
        
        $xml = $sxml->asXML(); //XML заказа
        
        try{
           return @simplexml_load_string($this->apiRequest("status_report_h.php",array(
               'xml_request' => $xml
           ))); 
        }catch(\Exception $ex){
           throw new \RS\Exception($ex->getMessage()."<br/> Файл:".$ex->getFile()."<br/> Строка:".$ex->getLine(),$ex->getCode()); 
        }
    }
    
    /**
    * Отправляет запрос на создание заказа в системе СДЕК
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    * @param \Shop\Model\Orm\Address $address - объект адреса
    */
    private function requestCreateOrder(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        if (!$address){
            $address = $order->getAddress();
        }
        
        //Настройки текущего сайта
        $site_config = \RS\Config\Loader::getSiteConfig();
        
        //Доп. данные указанные в доставке
        $extra_info = $order->getExtraKeyPair('delivery_extra');
        $extra_info = json_decode(htmlspecialchars_decode($extra_info['value']),true);
        
        //Подготовим XML
        $sxml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><DeliveryRequest/>");
        $sxml['Number']     = $order['order_num']; //Номер заказа
        $data_create        = date('Y-m-d', strtotime("+".$this->getOption('day_apply_delivery',null)." days"));
        $sxml['Date']       = $data_create; //Дата создания заказа
        $sxml['Account']    = trim($this->getOption('secret_login',null)); //Id аккаунта
        $sxml['Secure']     = $this->getSecure($data_create); //Генерируемый секретный ключ
        $sxml['OrderCount'] = 1; //Сколько заказов будет отправлено
        
        $delivery = $order->getDelivery(); //Сведения о доставке заказа
        $user     = $order->getUser(); //Получим пользователя 
        
        $sxml->Order['Number']                = $order['order_num'];    //Номер заказа
        $sxml->Order['RecCityCode']           = $extra_info['cityCode'];  //код города получателя
        $sxml->Order['SendCityPostCode']      = $address['zipcode'];     //Индекс города отправителя
        $sxml->Order['RecipientName']         = !empty($address['contact_person']) ? $address['contact_person'] : $user->getFio(); //ФИО получателя
        $sxml->Order['RecipientEmail']        = $user['e_mail']; //E-mail получателя
        $sxml->Order['Phone']                 = $user['phone'];  //Телефон получателя
        $sxml->Order['TariffTypeCode']        = $extra_info['tariffId']; //Тип тарифа, по которому будет доставка
        $sxml->Order['DeliveryRecipientCost'] = $delivery->getDeliveryCost($order,null,false); //Цена за доставку
        $sxml->Order['RecipientCurrency']     = "RUB"; //Валюта доставки
        $sxml->Order['Comment']               = $order['comments']; //Комментарий заказа
        $sxml->Order['SellerName']            = $site_config['firm_name']; //Имя фирмы отправителя
        /**
        * @var \Catalog\Model\Orm\Currency
        */
        $default_currency = \Catalog\Model\CurrencyApi::getBaseCurrency();
        $sxml->Order['ItemsCurrency']    = $default_currency['title']; //Код валюты в которой был составлен заказ
        
        //Адрес куда будет доставлено
        $sxml->Order->Address['Street']  = $address['address'];
        $sxml->Order->Address['House']   = "-";
        $sxml->Order->Address['Flat']    = "-";
        $sxml->Order->Address['PvzCode'] = $extra_info['code'];
        
        //Упаковка с товарами
        $products = $order->getCart()->getProductItems();
        $cartdata = $order->getCart()->getPriceItemsData();
        $sxml->Order->Package['Number']  = $order['order_num'];
        $sxml->Order->Package['BarCode'] = $order['order_num'];
        $sxml->Order->Package['Weight']  = $order->getWeight();

        $i=0;
        foreach ($products as $n=>$item){
           /**
           * @var \Catalog\Model\Orm\Product
           */
           $product = $item['product']; 
           $barcode           = $product->getBarCode($item['cartitem']['offer']);
           $offer_title       = $product->getOfferTitle($item['cartitem']['offer']);
           
           $sxml->Order->Package->Item[$i]['WareKey'] = $barcode; //Артикул
           $sxml->Order->Package->Item[$i]['Cost']    = $cartdata['items'][$n]['single_cost']; //Цена товара
           $sxml->Order->Package->Item[$i]['Payment'] = !$this->getOption('cash_on_delivery',0) ? $cartdata['items'][$n]['single_cost'] : 0; //Оплата при получении, только есть указано в настройках иначе 0
           $sxml->Order->Package->Item[$i]['Weight']  = $product->getWeight() / 1000;
           $sxml->Order->Package->Item[$i]['Amount']  = $item['cartitem']['amount'];
           $sxml->Order->Package->Item[$i]['Comment'] = $offer_title ? $offer_title : $product['title'];
           
           $i++;
        }
        
        //Добавление дополнительных услуг
        $additional_services = $this->getOption('additional_services',null);
        if (!empty($additional_services)){
           $i=0; 
           foreach($additional_services as $service){
              $sxml->Order->AddService[$i]['ServiceCode'] = $service;
              $i++;
           } 
        }
        
        
        $xml = $sxml->asXML(); //XML заказа
        
        try{
           return @simplexml_load_string($this->apiRequest("new_orders.php",array(
               'xml_request' => $xml
           ))); 
        }catch(\Exception $ex){
           throw new \RS\Exception($ex->getMessage()."<br/> Файл:".$ex->getFile()."<br/> Строка:".$ex->getLine(),$ex->getCode()); 
        }
        
    }
    
    
    /**
    * Запрашивает информацию о доставке 
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    * @param \Shop\Model\Orm\Address $address - объект адреса доставки
    */
    private function requestDeliveryInfo(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        if (!$address){
            $address = $order->getAddress();
        }
        
        //Подготавливаем заголовки
        $request_array = array(
           'http' => array(
                'method' => "POST",
                'header' => "Content-Type: application/json\r\n",
                'timeout' => self::REQUEST_TIMEOUT
           )
        );
       
        //Параметры
        $query_info = array(
           'version'              => self::API_CALCULATE_VERSION,
           'senderCityPostCode'   => $this->getOption('city_from_zipcode'), //Zip код отправителя
           'receiverCityPostCode' => $address['zipcode'], //Zip код получателя
        );
        
        //Код тарифа
        $tariffsList = $this->getOption('tariffTypeList');
        $query_info['tariffId']   = null;
        $query_info['tariffList'] = array();
        if (!empty($tariffsList)){
            $query_info['tariffId'] = $tariffsList[0];
            foreach($tariffsList as $priority=>$tariff){
               $arr[] = array(
                 'priority' => $priority,
                 'id' => $tariff,
               );
            }
            $query_info['tariffList'] = $arr;
        }else{
            $this->addError('Не заданы тарифы для доставок.');
        }
        
        
       
        //Секретный логин и пароль, если они указаны
        $secret_login = $this->getOption('secret_login',false);
        $secret_pass  = $this->getOption('secret_pass',false);
        if ($secret_login && $secret_pass){
            $query_info['authLogin']   = $secret_login; 
            $date                      = $this->getDateExecute();
            $query_info['secure']      = $this->getSecure($date);
            $query_info['dateExecute'] = $date;
        }
   
        //Переберём товары
        $items = $order->getCart()->getProductItems();
        $product_arr = array();
        foreach ($items as $key=>$item){
           /** 
           * @var \Catalog\Model\Orm\Product
           */
           $product  = $item['product'];
           $cartitem = $item['cartitem'];
           $product->fillProperty();
           
           
           
           //Вес в килограммах
           $weight = $product->getWeight() / 1000; 
           $product_arr['weight'] = $weight * $cartitem['amount']; 
           
           //Длинна
           $length = $product->getPropertyValueById($this->getOption('length', 0));
           if ($length){
              $product_arr['length'] = $length;  
           }else{
              $this->addError('У товара с артикулом '.$product['barcode'].' не указана характеристика длинны.'); 
           }
           
           //Ширина
           $width = $product->getPropertyValueById($this->getOption('width', 0));
           if ($width){
              $product_arr['width'] = $width;  
           }else{
              $this->addError('У товара с артикулом '.$product['barcode'].' не указана характеристика ширины.'); 
           }
           
           //Высота
           $height = $product->getPropertyValueById($this->getOption('height', 0));
           if ($height){
              $product_arr['height'] = $height;  
           }else{
              $this->addError('У товара с артикулом '.$product['barcode'].' не указана характеристика высоты.'); 
           }
           
           $query_info['goods'][] = $product_arr;
        }
        
        
        
        $request_array['http']['content'] = json_encode($query_info);
       
        $ctx = stream_context_create($request_array);
        $url = self::API_URL_CALCULATE;
        
        //Кэшируем запрос
        $cache_key = md5($order['order_num'].http_build_query($query_info));
        if(!isset($this->cache_api_requests[$cache_key])){
 
            $response   = json_decode(@file_get_contents($url, null, $ctx),true); 
            if (isset($response['error'])){
               foreach ($response['error'] as $error){
                  $this->addError($error['code']." ".$error['text']); 
               } 
            }else{
               $this->setTariffId($response['result']['tariffId']); 
            }
            $this->cache_api_requests[$cache_key] = $response;
        }
        
        return $this->cache_api_requests[$cache_key];
    }
    

    /**
    * Получает валюту по имени этой волюты пришедшей из СДЕК
    * 
    * @param string $name - сокращённое название валюты из СДЕК
    */
    private function getCurrencyByName($name)
    {
        //Подгружим валюты системы
        $currencies = \RS\Orm\Request::make()
                ->from(new \Catalog\Model\Orm\Currency())
                ->where(array(
                    'public' => 1
                ))
                ->orderby('`default` DESC')
                ->objects(null,'title');
        
        if (isset($currencies[$name])){
            return $currencies[$name];
        }else{
            foreach($currencies as $currency){
                if ($currency['default']){
                    return $currency;
                }
            }
        }
    }
    
    /**
    * Добавление админского комментария в базу к заказу
    * 
    * @param integer $order_id - id заказа
    * @param string $text - текст для добавления
    */
    private function addToOrderAdminComment($order_id, $text)
    {
        \RS\Orm\Request::make()
            ->from(new \Shop\Model\Orm\Order())
            ->set(array(
                'admin_comments' => $text
            ))
            ->where(array(
                'id' => $order_id
            ))
            ->update()
            ->exec(); 
    }
    
    /**
    * Добавляет ошибки в комментарий админа в заказе через ORM запрос
    * 
    * @param string $action - действие русскими словами в родительном падеже
    * @param integer $order_id - id заказа
    * @param array $errors - массив ошибок из ответного XML
    */
    private function addErrorsToOrderAdminComment($action = "создания заказа", $order_id, $errors)
    {
       $text = ""; 
       
       foreach ($errors as $error){
          $str = "СДЕК ошибка ".$action.": ";
          if (isset($error['ErrorCode'])){
            $str .= "[".$error['ErrorCode']."]";  
          } 
          $text .= $str." ".$error['Msg']."\n"; 
       }
       $this->addToOrderAdminComment($order_id,$text);
    }
    
    /**
    * Добавляет строки в комментарий админа в заказе через ORM запрос
    * 
    * @param integer $order_id - id заказа
    * @param array $strings - массив строк из ответного XML
    */
    private function addStringsToOrderAdminComment($order_id, $strings)
    {
       $text = ""; 
       
       foreach ($strings as $string){
          $str = "СДЕК: ";
          if (isset($string['DispatchNumber'])){
            $str .= "[Код - ".$string['DispatchNumber']."]";  
          } 
          $text .= $str." ".$string['Msg']."\n"; 
       }
       $this->addToOrderAdminComment($order_id,$text);
    }
    
    /**
    * Возвращает HTML виджет временем прозвона покупателя и отправляет запрос на вызов
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    */
    private function cdekGetCallCourierHTML(\Shop\Model\Orm\Order $order)
    {  
       $view = new \RS\View\Engine();
       $request = new \RS\Http\Request();
       
       $cdekInfo = new Cdek\CDEKInfo();
       
       if ($request->isPost()){
          $call = $request->request('call',TYPE_ARRAY,false); //Массив со информациями о вызове
          $responce = $this->requestGetCallCourier($call, $order);
          if (isset($responce->CallCourier['ErrorCode'])){
              $this->addError('Не удалось отправить запрос на вызов курьера.<br/> '.$responce->CallCourier['Msg']);
          }else{
              $view->assign(array(
                'success' => 'Сделан вызов курьера на '.$call['Date'].'.<br/> 
                Ожидание курьера с '.$call['TimeBeg'].':00 по '.$call['TimeEnd'].':00'
              )); 
          }
       }
       
       $view->assign(array(
         'title' => 'Вызов курьера для забора товара СДЕКом',
         'current_date' => date('Y-m-d',time()+ 24 * 60 * 60),
         'time_range' => range(1,24),
         'time_default_start' => 10,
         'time_default_end' => 13,
         'order' => $order,
         'delivery_type' => $this,
         'current_city' => $this->getOption('city_from_name',''),
         'regions' => $cdekInfo->getAllCities(),
         'errors' => $this->getErrors()
       ));                       
       return $view->fetch('%shop%/form/delivery/cdek/cdek_call_courier.tpl'); 
    }
    
    /**
    * Возвращает HTML виджет с печатной формой
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    */
    private function cdekGetPrintDocument(\Shop\Model\Orm\Order $order)
    {
       $extra_info = $order->getExtraInfo(); 
       
       if (!isset($extra_info['cdek_order_id']['data']['orderId'])){
           return '[Ошибка] Не удалось получить сведения о заказе СДЕК';
       }
       
       
       $view = new \RS\View\Engine();
       
       //Подготовим XML
       $sxml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><OrdersPrint/>");
       
       $data_create        = date('Y-m-d');
       $sxml['Date']       = $data_create;
       $sxml['Account']    = trim($this->getOption('secret_login',null)); //Id аккаунта
       $sxml['Secure']     = $this->getSecure($data_create); //Генерируемый секретный ключ
       $sxml['OrderCount'] = 1; //Общее количество заказов в xml
       $sxml['CopyCount']  = 2; //Количество копий печатной формы в одном документе
       
       //Номер отправления
       $sxml->Order['DispatchNumber'] = $extra_info['cdek_order_id']['data']['orderId']; 
       
       $xml = $sxml->asXML(); //XML заказа
       
       $view->assign(array(
          'xml' => $xml   
       ));

       return $view->fetch('%shop%/form/delivery/cdek/cdek_print_form.tpl'); 
    }
    
    /**
    * Возвращает HTML виджет с информацией о заказе
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    */
    private function cdekGetInfo(\Shop\Model\Orm\Order $order)
    {
       try{
           $response = $this->requestGetInfo($order);
       } catch (\Exception $ex){
           return $ex->getMessage();
       }
       
       $cdekInfo = new \Shop\Model\DeliveryType\Cdek\CDEKInfo();
       $tariffs = $cdekInfo->getRusTariffs() + $cdekInfo->getInternationTariffs();
       
       $view = new \RS\View\Engine();
       //Если есть доп услуга
       
       if (isset($response->Order->AddedService) && !empty($response->Order->AddedService['ServiceCode'])){
           $addServices  = $cdekInfo->getAllAdditionalServices();
           $service_code = (integer)$response->Order->AddedService['ServiceCode']; 
           if (isset($addServices[$service_code])){
               $view->assign(array(
                  'addTariffCode' => $addServices[$service_code]
               ));
           }
           
       }
       
       
       $view->assign(array(
         'order_info' => $response->Order,
         'title' => 'Информация о заказе СДЕК',
         'tariffCode' => $tariffs[(integer)$response->Order['TariffTypeCode']],
         'tariffs' => $tariffs,
         'order' => $order,
         'delivery_type' => $this
       ));                       
       return $view->fetch('%shop%/form/delivery/cdek/cdek_order_info.tpl'); 
    }
    
    
    /**
    * Возвращает HTML виджет со статусом заказа для админки
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    */
    private function cdekGetHtmlStatus(\Shop\Model\Orm\Order $order)
    {
       try{
           $response = $this->requestOrderStatus($order);
       } catch (\Exception $ex){
           return $ex->getMessage();
       }
       
       
       $view = new \RS\View\Engine();
       $view->assign(array(
         'order_info' => $response->Order,
         'title' => 'Статус заказа',
         'order' => $order,
         'delivery_type' => $this
       ));                       
       return $view->fetch('%shop%/form/delivery/cdek/cdek_get_status.tpl'); 
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
        if (!$address){
           $address = $order->getAddress(); 
        }
        
        $this->requestDeliveryInfo($order, $address);
        
        
        if ($this->hasErrors()){ //Если есть ошибки
            return $this->getErrorsStr();
        }
        
        return false;
    }
    
    /**
    * Действие с запросами к заказу для получения дополнительной информации от доставки
    * 
    * @param \Shop\Model\Orm\Order $order - объект заказа
    */
    function actionOrderQuery(\Shop\Model\Orm\Order $order)
    {
        $url = new \RS\Http\Request();
        $method = $url->request('method',TYPE_STRING,false);
        switch ($method){
            case "getCallCourierHTML": //Получение статуса заказа
                return $this->cdekGetCallCourierHTML($order); 
                break;
            case "getInfo": //Получение статуса заказа
                return $this->cdekGetInfo($order); 
                break;
            case "getPrintDocument": //Получение статуса заказа
                return $this->cdekGetPrintDocument($order); 
                break;
            case "getStatus": //Получение статуса заказа
            default:
                return $this->cdekGetHtmlStatus($order);    
                break;
        }
    }
    
    /**
    * Возвращает дополнительный HTML для админ части в заказе
    * 
    * @param \Shop\Model\Orm\Order $order - заказ доставки
    * @return string
    */
    function getAdminHTML(\Shop\Model\Orm\Order $order)
    {
        $view = new \RS\View\Engine();
        
        $view->assign(array(
            'order' => $order,
        ));
        
        return $view->fetch("%shop%/form/delivery/cdek/cdek_additional_html.tpl");
    }
    
    /**
    * Получает выбранные тарифы для отправки доставки
    * 
    * @return array
    */
    private function getSelectedTariffs()
    {
       return $this->getOption('tariffTypeList');
    }
    
    /**
    * Возвращет первый выбранный пользователем тариф
    * 
    * @return integer
    */
    private function getSelectedFirstTariffId()
    {
       $tariffs    = $this->getSelectedTariffs();
       return !empty($tariffs) ? (int)current($tariffs) : false;  
    }
    
    /**
    * Возвращает информацию по первому выбранному тарифу пользователем
    * 
    * @return false|array
    */
    private function getSelectedFirstTariffInfo()
    {
        $tariff_id = $this->getSelectedFirstTariffId();
        
        if (!$tariff_id){
            return false;
        }                       
        
        $cdek_info = new \Shop\Model\DeliveryType\Cdek\CdekInfo();
        $tariffs = $cdek_info->getAllTariffsWithInfo();
        
        return $tariffs[$tariff_id];
    }
    
    
    /**
    * Возвращает дополнительный HTML для публичной части с выбором в заказе
    * 
    * @param \Shop\Model\Orm\Delivery $delivery - объект доставки
    * @param \Shop\Model\Orm\Order $order - заказ доставки
    * @return string
    */
    function getAddittionalHtml(\Shop\Model\Orm\Delivery $delivery, \Shop\Model\Orm\Order $order = null)
    {  
        $view = new \RS\View\Engine();
        if (!$order){
            $order = \Shop\Model\Orm\Order::currentOrder(); 
        }
        
        $tariff = $this->getSelectedFirstTariffInfo();
        
        $pochtomates     = array();
        if ($tariff && in_array($tariff['regim_id'], array(2, 4))){ //Если нужны почтоматы
            $pochtomates = $this->requestPochtomat($order); 
        }
        
        
        $this->getDeliveryCostText($order);
        
        $view->assign(array(
            'errors'      => $this->getErrors(),
            'order'       => $order,
            'extra_info'  => $order->getExtraKeyPair(),
            'delivery'    => $delivery,
            'cdek'        => $this,
            'pochtomates' => $pochtomates,
        )+ \RS\Module\Item::getResourceFolders($this));
        
        return $view->fetch("%shop%/delivery/cdek/pochtomates.tpl");
    }
    
    
    /**
    * Возвращает дополнительный HTML для административной части с выбором опций доставки в заказе
    * 
    * @param \Shop\Model\Orm\Order $order - заказ доставки
    * @return string
    */
    function getAdminAddittionalHtml(\Shop\Model\Orm\Order $order = null)
    {  
        $view        = new \RS\View\Engine();
        
        //Получим данные потоварам
        $products = $order->getCart()->getProductItems();
        if (empty($products)){
            $this->addError(t('В заказ не добавлено ни одного товара'));
        }
        
        $tariff = $this->getSelectedFirstTariffInfo();
        
        $pochtomates = array();
        if ($tariff && in_array($tariff['regim_id'], array(2, 4))){ //Если нужны почтоматы
            $pochtomates = $this->requestPochtomat($order); 
        }

        //Получим цену с параметрами по умолчанию
        $cost = $this->getDeliveryCostText($order);
        
        $view->assign(array(
            'errors'      => $this->getErrors(),
            'order'       => $order,
            'cost'        => $cost,
            'extra_info'  => $order->getExtraKeyPair(),
            'delivery'    => $delivery,
            'cdek'        => $this,
            'pochtomates' => $pochtomates,
        )+ \RS\Module\Item::getResourceFolders($this));
        
        return $view->fetch("%shop%/form/delivery/cdek/admin_pochtomates.tpl");
    }
    
    /**
    * Функция срабатывает после создания заказа
    * 
    * @param \Shop\Model\Orm\Order $order     - объект заказа
    * @param \Shop\Model\Orm\Address $address - Объект адреса
    * @return void
    */
    function onOrderCreate(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        $extra = $order->getExtraInfo(); 
        
         
        if (!isset($extra['cdek_order_id'])){ //Если заказ в СДЕК ещё не создан
            $created_order = $this->requestCreateOrder($order, $address);
            //Итак смотрим, если в ответе если у первого элемента ответа, есть ErrorCode, 
            //то добавим ошибки в админ поле заказа.
            //Иначе запишем все данные и добавим в коммент сведения об успешном создании заказа
            if ($created_order){//Если дождались ответ от СДЕК
                if (isset($created_order->Order[0]['ErrorCode'])){ //Если есть ошибки, добавим в комметарий
                    $this->addErrorsToOrderAdminComment("создания заказа",$order['id'],$created_order->Order);
                }else{//Если ошибок нет
                    $cdek_order_id = (string)$created_order->Order[0]['DispatchNumber'];
                    //$this->addStringsToOrderAdminComment($order['id'],$created_order->Order);
                    $order->addExtraInfoLine(
                        'id заказа СДЕК',
                        '<a href="http://lk.cdek.ru/" target="_blank">Перейти к заказу №'.$cdek_order_id.'</a>',
                        array(
                            'orderId' => $cdek_order_id
                        ),
                        'cdek_order_id'
                   );
                }
            }else{
                $this->addToOrderAdminComment("Не удалось связаться с сервером с СДЕК при создании заказа.");
            }
            $extra = $order->getExtraInfo(); 
        }
        //Запишем данные в таблицу, чтобы не вызывать повторного сохранения
        \RS\Orm\Request::make()
                ->update()
                ->from(new \Shop\Model\Orm\Order())
                ->set(array(
                    '_serialized' => serialize($order['extra'])
                ))
                ->where(array(
                    'id' => $order['id']
                ))->exec();
        
    }
    
    
    
    /**
    * Возвращает стоимость доставки для заданного заказа. Только число.
    * 
    * @param \Shop\Model\Orm\Order $order
    * @param \Shop\Model\Orm\Address $address - Адрес доставки
    * @return double
    */
    function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
    {
        $delivery  = $order->getDelivery(); 
        $cache_key = md5($order['order_num'].$delivery['id']);
        if (!isset($this->cache_api_requests[$cache_key])){
           $sxml = $this->requestDeliveryInfo($order, $address); 
        }else{
           $sxml = $this->cache_api_requests[$cache_key]; 
        }
        
        
        $cost = 0;
        if (isset($sxml['result'])){
           
           $this->delivery_cost_info = $sxml['result']; 
           $cost                     = $sxml['result']['price']; 
           
           if ($use_currency){
               //Получим Валюту из нашей системы
               $currency_name = $sxml['result']['currency']; 
               $currency      = $this->getCurrencyByName($currency_name);
               //Если эта валюты не полумолчанию, то переведём стоимость в валюту по умолчанию
               if (!$currency['default']){
                   $cost = $cost * $currency['ratio'];
               }
               $cost = $cost." ".$currency['stitle']; 
           }
           
           //Добавим тариф по которому будет осуществленна доставка
           $order->addExtraKeyPair('tariffId', $sxml['result']['tariffId']);
           $order->addExtraKeyPair('deliveryPeriodMin', $sxml['result']['deliveryPeriodMin']);
           $order->addExtraKeyPair('deliveryPeriodMax', $sxml['result']['deliveryPeriodMax']);
           $order->addExtraKeyPair('deliveryDateMin', $sxml['result']['deliveryDateMin']);
           $order->addExtraKeyPair('deliveryDateMax', $sxml['result']['deliveryDateMax']);
        }   
        
                      
        return $cost;
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
        $cost = $this->getDeliveryCost($order);
        if ($cost===0){
            $cost = "бесплатно";
        }
        return $cost;
    }
    
    /**
    * Получает массив доп. услуг
    * 
    * @return array
    */ 
    public static function getAdditionalServices()
    {
        $list = \Shop\Model\DeliveryType\Cdek\CDEKInfo::getAdditionalServices();
        
        $arr = array();
        foreach($list as $k=>$item){
           $arr[$k] = $item['title']; 
        }
        
        return $arr;
    }
    
    
    
    /**
    * Устанавливает тариф по которому будет произведена доставка после подсчёта стоимости 
    * 
    * @param integer $id
    */
    function setTariffId($id)
    {
       $this->tariffId = $id; 
    }
    
    /**
    * Получает id тарифа по которому будет произведена доставка после подсчёта стоимости
    * 
    */
    function getTariffId()
    {
       return $this->tariffId; 
    }
}