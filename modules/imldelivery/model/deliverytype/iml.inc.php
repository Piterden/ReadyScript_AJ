<?php
/**
* Класс доставки компании IMLogistic
*
* @copyright Copyright (c) YellowMarkerWS. Ефремов Денис <efremov.a.denis@gmail.com> 2015.
*/
namespace Imldelivery\Model\DeliveryType;
use \RS\Orm\Type;

class Iml extends \Shop\Model\DeliveryType\AbstractType
{
    const 
        // Учетные данные
        API_LOGIN = '07308',
        API_PASS = 'TAaB4myF',

        // Основное API
        URL_API_STATUS              =   'http://api.iml.ru/json/GetStatuses',
        URL_API_ORDERSLIST          =   'http://api.iml.ru/json/GetOrders',
        URL_API_CREATEORDER         =   'http://api.iml.ru/json/CreateOrder',
        URL_API_GETPRICE            =   'http://api.iml.ru/json/GetPrice',
        
        // Справочные таблицы для API
        URL_HELP_DELIVERYSTATUS     =   'http://api.iml.ru/list/deliverystatus?type=json',
        URL_HELP_ORDERSTATUS        =   'http://api.iml.ru/list/orderstatus?type=json',
        URL_HELP_REGION             =   'http://api.iml.ru/list/region?type=json',
        URL_HELP_SD                 =   'http://api.iml.ru/list/sd?type=json',
        URL_HELP_SERVICES           =   'http://api.iml.ru/list/service?type=json',

        // Остальные справочники
        URL_LOCATION                =   'http://list.iml.ru/Location?type=json',
        URL_ZONE                    =   'http://list.iml.ru/zone?type=json',
        URL_EXCEPTIONSERVICEREGION  =   'http://list.iml.ru/ExceptionServiceRegion?type=json',
        URL_POSTDELIVERYLIMIT       =   'http://list.iml.ru/PostDeliveryLimit?type=json',
        URL_REGION                  =   'http://list.iml.ru/region?type=json',
        URL_SELFDELIVERY            =   'http://list.iml.ru/sd?type=json',
        URL_STATUS                  =   'http://list.iml.ru/status?type=json',
        URL_POSTRATEZONE            =   'http://list.iml.ru/PostRateZone?type=json',
        URL_POSTCODE                =   'http://list.iml.ru/PostCode?type=json',
        URL_CALENDAR                =   'http://list.iml.ru/calendar?type=json',
        URL_SERVICE                 =   'http://list.iml.ru/service?type=json';
    
    protected
        $service_id            = null, // Текущая услуга
        $region_id_from        = null; // Услуги доставки IML
        //$carries          = null; // Агрегаторы доставки существующие в sheepla

    private
        $cache_api_requests = array(),  // Кэш запросов к серверу рассчета 
        $cache_currencies   = null,  // Валюты
        $delivery_currency  = null;  // Текущая Валюта
        
    /**
    * Возвращает название расчетного модуля (типа доставки)
    * 
    * @return string
    */
    function getTitle()
    {
        return t('IML');
    }
    
    /**
    * Возвращает описание типа доставки
    * 
    * @return string
    */
    function getDescription()
    {
        return t('IML агрегатор доставок');
    }
    
    /**
    * Возвращает идентификатор данного типа доставки. (только англ. буквы)
    * 
    * @return string
    */
    function getShortName()
    {
        return t('iml');
    }
    
    /**
    * Функция которая возвращает надо ли, проверять возможность создание заказа на доставку
    *
    * @return bool
    */
    function getNeedCheckCreate(){
        return true;
    } 
    
    /**
    * Возвращает ORM объект для генерации формы или null
    * 
    * @return \RS\Orm\FormObject | null
    */
    function getFormObject()
    {
        $properties = new \RS\Orm\PropertyIterator(array(
            'max_weight' => new Type\String(array(
                'description' => t('Максимальный вес, грамм'),
            )),
            'region_id_from' => new Type\String(array(
                'description' => t('Регион, откуда осуществляется отправка'),
                'hint' => t('Все регионы, с которыми работает IML'),
                'list' => array(array('\Imldelivery\Model\DeliveryType\Iml','staticGetRegions')),
            )),
            'service_id' => new Type\String(array(
                'description' => t('Услуга доставки'),
                'hint' => t('Все услуги, предоставляемые IML'),
                'list' => array(array('\Imldelivery\Model\DeliveryType\Iml','staticGetServices')),
            )),
            'timeout' => new Type\Integer(array(
                'description' => t('Время ожидания ответа IML, сек'),
                'hint' => t('Иногда запросы к IML идут очень долго,<br/> чтобы не дожидатся ответа используется это значение.<br/>Рекоммендуемое значение 2 сек.'),
                'default' => 2,
            )),  
        ));
        return new \RS\Orm\FormObject($properties);
    } 

    /**
     * Справочник услуг
     * 
     * @return array [id]|string => [name]|string
     */
    static function staticGetServices()
    {
        $iml = new Iml;
        $list = $iml->getApiRequest(self::URL_HELP_SERVICES, self::API_LOGIN, self::API_PASS);
        return $iml->getHelpIdList($list);
    }
    
    /**
     * Справочник регионов
     * 
     * @return array [id]|string => [name]|string
     */
    static function staticGetRegions()
    {
        $iml = new Iml;
        $list = $iml->getApiRequest(self::URL_HELP_REGION, self::API_LOGIN, self::API_PASS);
        return $iml->getHelpIdList($list);
    }

    /**
     * Справочник состояний заказа
     * 
     * @return array [id]|string => [name]|string
     */
    static function staticGetDeliveryStatus()
    {
        $iml = new Iml;
        $list = $iml->getApiRequest(self::URL_HELP_DELIVERYSTATUS, self::API_LOGIN, self::API_PASS);
        return $iml->getHelpIdList($list);
    }

    /**
     * Справочник статусов заказа
     * 
     * @return array [id]|string => [name]|string
     */
    static function staticGetOrderStatus()
    {
        $iml = new Iml;
        $list = $iml->getApiRequest(self::URL_HELP_ORDERSTATUS, self::API_LOGIN, self::API_PASS);
        return $iml->getHelpIdList($list);
    }

    /**
     * Справочник пунктов самовывоза
     * 
     * @return array [id]|string => [name]|string
     */
    function getSelfDeliveryList()
    {
        $iml = new Iml;
        $list = $iml->getApiRequest(self::URL_HELP_SD, self::API_LOGIN, self::API_PASS);
        return $list;
    }

    /**
     * Получает массив регионов с самовывозом
     * 
     * @return array [code] => [name]
     */
    public function getSdRegions()
    {
        $iml = new Iml;
        $arr = array();
        $regions = $iml->staticGetRegions();
        foreach ($iml->getSelfDeliveryList() as $sd_item) {
            $code = $sd_item['RegionCode'];
            $arr[$code] = $regions[$code];
        }
        return $arr;
    }

    public function getSdByRegion($params)
    {
        $iml = new Iml;
        $arr = array();
        $regions = $iml->staticGetRegions();
        $region = $params['region'];

        foreach ($iml->getSelfDeliveryList() as $sd_item) {
            if ($sd_item['RegionCode'] == $region) {
                $arr[] = $sd_item;
            }
        }
        return $arr;
    }

    public function addExtraLine(array $params)
    {
        $order = \Shop\Model\Orm\Order::currentOrder();
        
        $order->addExtraInfoLine(
            'IML Extra Data',
            $params['value'],
            array($params['key'] => $params['value']),
            'iml_'.$params['key']
        );
        //\RS\Orm\Request::make()
        //    ->update()
        //    ->from(new \Shop\Model\Orm\Order())
        //    ->set(array(
        //        '_serialized' => serialize($order['extra'])
        //    ))
        //    ->where(array(
        //        'id' => $order['id']
        //    ))->exec(); 
        return $order['extra'];
    }

    public function getExtraLine($params)
    {
        $order = \Shop\Model\Orm\Order::currentOrder();
        $key = $params['key'];
        return $order->getExtraInfo($key);
    }

    public function getDeliveryCostAjax()
    {
        $order = \Shop\Model\Orm\Order::currentOrder();
        $address = $order->getAddress();
        return $this->getDeliveryCost($order, $address);
    }

    /**
     * Запрос получает статус и состояние заказа(ов)
     * 
     * @param  array|null $filters
     * @return array
     */
    function getStatuses(array $filters = null)
    {
        $content = array(
            'Test' => 'True',                       // для тестового режима, иначе не указывайте
            'CustomerOrder' => '',                 // номер заказа
            'BarCode' => '',                        // штрих код
            'DeliveryDateStart' => '',               // фильтр по дате доставки, с указанной даты и позднее
            'DeliveryDateEnd' => '',                // фильтр по дате доставки, до указанной даты
            'State' => 999,                         // из справочника
            'OrderStatus' => 0,                    // из справочника
            'Job' => '',                          // из справочника услуг
            'RegionFrom' => '',                   // фильтр по региону отправки
            'RegionTo' => '',                       // фильтр по региону получения
            'CreationDateStart' => '',              // фильтр по дате доставки, с указанной даты и позднее
            'CreationDateEnd' => ''                 // фильтр по дате доставки, до указанной даты
        );

        return $this->postApiRequest(self::URL_API_STATUS, self::API_LOGIN, self::API_PASS, array_merge($content, $filters));
    }

    /**
     * Запрос позволяет получить список заказов по параметрам
     * 
     * @param  array|null $filters
     * @return array
     */
    function getOrders(array $filters = null)
    {
        $content =array(
            'Test' => 'True', // для тестового режима, иначе не указывайте
            //'CustomerOrder' => '',                 // номер заказа
            //'BarCode' => '2624028597816',          // штрих код
            //'DeliveryDateStart' => '2014-01-15',   // с указанной даты и позднее
            //'DeliveryDateEnd' => '2014-07-15',     // до указанной даты
            //'State' => 3                           // из справочника
            //'OrderStatus' => 0,                    // из справочника
            'Job' => 'С24КО', // из справочника услуг
            //'RegionFrom' => 'МОСКВА',              // из справочника регионов
            //'RegionTo' => 'МОСКВА',                // из справочника регионов
            'CreationDateStart' => '2014-01-15' // с указанной даты и позднее
            //'CreationDateEnd' => '2014-07-15'      // до указанной даты
        );

        return $this->postApiRequest(self::URL_API_ORDERSLIST, self::API_LOGIN, self::API_PASS, array_merge($content, $filters));
    }

    /**
     * Запрос позволяет создать заказ в системе IML
     * 
     * @param  array|null $filters
     * @return array
     */
    function createOrder(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        if(!$address) $address = $order->getAddress();
        $catalog_config = \RS\Config\Loader::byModule('catalog');

        $currency      = \Catalog\Model\CurrencyApi::getBaseCurrency(); //Базовая валюта
        $date          = date('c',strtotime($order['dateof'])); //Дата заказа
        $delivery      = $order->getDelivery(); //объект доставки
        $delivery_cost = $delivery->getDeliveryCost($order);   
        $payment       = $order->getPayment();
        $user          = $order->getUser();

        // Подготовим данные к отправке
        $content = array(
            'Test' => 'True', // для тестового режима, иначе не указывайте
            'CustomerOrder' => $user['id'] ? $user['id'] : $order['id'],
            'Job' => $this->getOption('service_id',0), // из справочника услуг
            'Contact' => $user['name'],
            'RegionCodeFrom' => $this->getOption('region_id_from',0), // из справочника регионов
            'RegionCodeTo' => 'МОСКВА', // из справочника регионов
            'Address' => $address['address'],
            'DeliveryPoint'=> '1', // из справочника пунктов самовывоза
            'Phone'=> $user['phone'],
            'Amount' => $order['totalcost'], // разделитель '.' (точка)
            'ValuatedAmount' => '14.2', // разделитель '.' (точка)
            'Weight' => $order->getWeight(), // разделитель '.' (точка)
            'Comment' => $order['comments']
            //'GoodItems' => $lstGoodItem      //не обязательно, если есть позиции заказа
        );
        //  
        //  Test – тестовый режим, 'True' для тестового режима, иначе не указывайте
        //  Job – услуга доставки, Code из справочника услуг
        //  CustomerOrder – номер заказа
        //  DeliveryDate – дата доставки в строковом представлении, формат dd.MM.yyyy
        //  Volume – количество мест
        //  Weight – вес
        //  BarCode – штрих код заказа в формате EAN-13
        //  DeliveryPoint – пункта самовывоза, RequestCode из таблицы пунктов самовывоза
        //  Phone – телефон
        //  Contact – контактное лицо
        //  RegionCodeFrom – региона отправления, Code из таблицы регионов
        //  RegionCodeTo – код региона получения, Code из таблицы регионов
        //  Address – адрес доставки
        //  TimeFrom – начало временного периода доставки
        //  TimeTo – конец временного периода доставки
        //  Amount – сумма заказа
        //  ValuatedAmount – оценочная стоимость заказа
        //  Comment – комментарий
        //  City – город доставки, для отправки почтой России
        //  PostCode – индекс, для отправки почтой России
        //  PostRegion – регион, для отправки почтой России
        //  PostArea – район, для отправки почтой России
        //  PostContentType – тип вложения (0 - Печатная, 1 - Разное, 2 - 1 Класс), для отправки почтой России
        //  GoodItems – позиции заказа, если указывались при создании заказа, тип значения – массив элементов с параметрами:
        //
        //      ниже пример как заполнить заказ по позиционно, в примере 2 позиции, одна из них услуга доставки
        //                    
        //          productNo – номер товара (артикул)
        //          productName – наименование товара
        //          productVariant – вариант товара (размер, цвет и т.д)
        //          productBarCode – штрих код продукта
        //          couponCode – номер купона
        //          discount – скидка
        //          weightLine – вес товара
        //          amountLine – стоимость товара
        //          statisticalValueLine – оценочная стоимость
        //          itemQuantity – количество товара
        //          deliveryService – услуга доставки, если позиция описывает оплату за услугу доставки, то заполнить значением 1



        // ниже пример как заполнить заказ по позиционно, в примере 2 позиции, одна из них услуга доставки
        //-----------------------------------------------------------
        //  $GoodItem1 = array(
        //      'productNo'=> 'pr1',
        //      'productName' => 'name1',
        //      'productVariant' => 'red',
        //      'productBarCode' => '10000001',
        //      'couponCode' => '10000002',
        //      'discount' => '0',
        //      'weightLine' => '12.5',
        //      'amountLine' => '120.0',
        //      'statisticalValueLine' => '120.0',
        //      'deliveryService' => FALSE
        //      );
        //   $GoodItem2 = array(
        //       'productNo'=> 'service',
        //       'productName' => 'Доставка',
        //       'productVariant' => '',
        //       'productBarCode' => '', 
        //       'couponCode' => '', 
        //       'discount' => '', 
        //       'weightLine' => '', 
        //       'amountLine' => '', 
        //       'statisticalValueLine' => '', 
        //       'deliveryService' => TRUE
        //       );
        //   $lstGoodItem =  array( $GoodItem1,  $GoodItem2);
        //-----------------------------------------------------------
        // параметры запроса, не нужные можно не добавлять в массив или заполнить пустым текстовым полем

        return $this->postApiRequest(self::URL_API_CREATEORDER, self::API_LOGIN, self::API_PASS, $content);
    }

    /**
     * Запрос базовой стоимости заказа у IML
     * 
     * @param  array|null $filters
     * @return array
     */
    public function getCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null)
    {
        $extra = $order->getExtraInfo();
        if ($this->service_id === null) {
            $this->service_id = $this->getOption('service_id');
        }
        if ($this->region_id_from === null) {
            $this->region_id_from = $this->getOption('region_id_from');
        }

        $content = array(
            'Job' => $this->getOption('service_id'),            // услуга
            'RegionFrom' => $this->getOption('region_id_from'), // регион отправки
            'RegionTo' => $extra['iml_region_to']['value'],   // регион получения
            'Volume' => '1',            // кол-во мест  
            'Weigth' => $order->getWeight() ? $order->getWeight() : '1',            // вес(кг)
            'SpecialCode' => $extra['iml_request_code']['value']      // код пункта самовывоза(см. справочник пунктов самовывоза)
        );

        return $this->postApiRequest(self::URL_API_GETPRICE, self::API_LOGIN, self::API_PASS, $content);
    }


    /**
    * Возвращает стоимость доставки для заданного заказа. Только число.
    * 
    * @param \Shop\Model\Orm\Order $order
    * @param \Shop\Model\Orm\Address $address - Адрес доставки
    * @return double
    */
    public function getDeliveryCost(\Shop\Model\Orm\Order $order, \Shop\Model\Orm\Address $address = null, $use_currency = true)
    {
        //return '';
        if(!$address) { 
            $address = $order->getAddress();
        }
        $delivery = $order->getDelivery(); 
        $cost = $this->getCost($order, $address);
        if (isset($cost['Price'])) {
            return $cost['Price'];
        } else {
            foreach ($cost as $error) {
                //$this->addError(t('Ошибка '.$error.'. '.$error));
            }
            return $cost;
        }
    }

    /**
     * Основной POST запрос к API. Вернет массив объектов
     * 
     * @param  string         $url
     * @param  string         $login
     * @param  string         $pass
     * @param  array          $content
     * @return array
     */
    private function postApiRequest($url, $login, $pass, array $content = array())
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($content));
        curl_setopt($curl, CURLOPT_USERPWD, $login.":".$pass);
        curl_setopt($curl, CURLOPT_SSLVERSION, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        return json_decode($response, true);
    }

    /**
     * Основной GET запрос к API. Вернет массив объектов
     * 
     * @param  string         $url
     * @param  string         $login
     * @param  string         $pass
     * @return array
     */
    private function getApiRequest($url, $login, $pass)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERPWD, $login.":".$pass);
        curl_setopt($curl, CURLOPT_SSLVERSION, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        return json_decode($response, true); // результат запроса  
    }

    /**
     * Переделывает структуру массива для формы в админке
     * 
     * @return array [id] => [name]
     */
    private function getHelpIdList($incomingData)
    {
        $arr = array();
        foreach ($incomingData as $data) {
            $arr[$data['Code']] = $data['Description'];
        }

        return $arr;
    }

    /**
    * Возвращает дополнительный HTML для публичной части
    * 
    * @return string
    */
    function getAddittionalHtml(\Shop\Model\Orm\Delivery $delivery, \Shop\Model\Orm\Order $order = null)
    {
        $order = \Shop\Model\Orm\Order::currentOrder();

        $view = new \RS\View\Engine();
        $view->assign(array(
            'list_regions_to'    => $this->staticGetRegions(),               //Список регионов
            'list_sd'            => $this->getSelfDeliveryList(),            //Список пунктов самовывоза 
            'list_sd_regions'    => $this->getSdRegions(),                   //Список регионов с самовывозом 
            'service_id'         => $this->getOption('service_id'),          //Услуга 
            'delivery_cost'      => $this->getDeliveryCost($order),                //Текущий объект цены доставки
            'order'              => $order,   //Текущий недоофрмленный заказ
            'delivery'           => $delivery,                               //Текущий объект доставки
            'user'               => \RS\Application\Auth::getCurrentUser(),  //Текущий объект пользователя
        ) + \RS\Module\Item::getResourceFolders($this)); 

        return $view->fetch('%imldelivery%/delivery/widget.tpl');
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
        //if (!isset($extra['sheepla_order_id'])){ //Если заказ в sheepla ещё не создан
           //Создадим заказ
        //   $created_order = $this->requestCreateOrder($order,$address); 
        //   
        //   //Если ответа дождались, то запишем номер заказа
        //   if ($created_order){
        //      //Запишем id заказа, который мы передали в сессию
        //       $sheepla_order_id = (string)$created_order->orders->order['orderId'];
        //       $order->addExtraInfoLine(
        //            'id заказа в sheepla',
        //            '<a href="http://panel.sheepla.ru/mOrder/EditOrder/'.$sheepla_order_id.'" target="_blank">'.$sheepla_order_id.'</a>',
        //            array(
        //                'orderId' => $sheepla_order_id
        //            ),
        //            'sheepla_order_id'
        //       );
        //       
        //   }else{ //Иначе
        //       $order->addExtraInfoLine(
        //            'Sheepla',
        //            '<a href="http://panel.sheepla.ru/mOrder/All" target="_blank">Ссылка в список заказов в sheepla</a>',
        //            'sheepla_order_id'
        //       );
        //   }
        //   $extra = $order->getExtraInfo();      
        //   
        //}  
        //       
        ////Запишем данные в таблицу, чтобы не вызывать повторного сохранения
        //\RS\Orm\Request::make()
        //        ->update()
        //        ->from(new \Shop\Model\Orm\Order())
        //        ->set(array(
        //            '_serialized' => serialize($order['extra'])
        //        ))
        //        ->where(array(
        //            'id' => $order['id']
        //))->exec(); 
    }

    
}
